<?php

namespace App\Http\Controllers\Admin\Deposit;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Deposit;
use App\Models\User;
use App\Models\Income;

class DepositController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pageTitle = 'Investor Deposit List';
        
        // Start query
        $query = Deposit::with('user')->latest();
        
        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('transaction_id', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($userQuery) use ($request) {
                      $userQuery->where('name', 'like', '%' . $request->search . '%')
                               ->orWhere('email', 'like', '%' . $request->search . '%')
                               ->orWhere('username', 'like', '%' . $request->search . '%');
                  });
            });
        }
        
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('created_at', [
                $request->date_from . ' 00:00:00',
                $request->date_to . ' 23:59:59'
            ]);
        }
        
        // Get paginated results
        $deposits = $query->paginate(20)->withQueryString();
        
        // Statistics
        $totalDeposits = Deposit::count();
        $totalAmount = Deposit::where('status', 'approved')->sum('amount');
        $pendingDeposits = Deposit::where('status', 'pending')->count();
        $pendingAmount = Deposit::where('status', 'pending')->sum('amount');
        
        return view('admin.deposit.index', compact(
            'pageTitle', 
            'deposits',
            'totalDeposits',
            'totalAmount',
            'pendingDeposits',
            'pendingAmount'
        ));
    }

    /**
     * Show deposit details
     */
    public function show($id)
    {
        $deposit = Deposit::with('user')->findOrFail($id);
        return view('admin.deposit.show', compact('deposit'));
    }

    /**
     * Update deposit status
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'admin_note' => 'nullable|string|max:500',
        ]);
        
        $deposit = Deposit::with('user')->findOrFail($id);
        
        $previousStatus = $deposit->status;

        // Update deposit
        $deposit->update([
            'status' => $request->status,
            'admin_note' => $request->admin_note,
        ]);
        
        // If approved, update user balance (if you have balance system)
        if ($request->status === 'approved' && $previousStatus !== 'approved') {
            // dd('approved logic here');
            // Update user balance
            $user = $deposit->user;
            if ($user) {
                // If you have a balance column in users table
                if (isset($user->balance)) {
                    $user->increment('balance', $deposit->amount);
                }

                // 2️⃣ Add deposit bonus to user (10)
                Income::create([
                    'user_id' => $user->id,
                    'amount' => 10,
                    'type' => 'deposit_bonus',
                    'description' => 'Deposit bonus for transaction '.$deposit->transaction_id,
                ]);

                // 3️⃣ Give referrer 0.5% of deposit
                if ($user->refer_by) {
                    $referrer = User::find($user->refer_by);
                    if ($referrer) {
                        $referralAmount = $deposit->amount * 0.005; // 0.5%
                        $referrer->increment('balance', $referralAmount); // update referrer wallet
                        $referrer->increment('referral_earnings', $referralAmount); // optional summary

                        Income::create([
                            'user_id' => $referrer->id,
                            'amount' => $referralAmount,
                            'type' => 'referral_bonus',
                            'description' => '0.5% referral bonus from '.$user->username.' deposit',
                        ]);
                    }
                }
                // You can send notification here (email, SMS, etc.)
            }
        }
        
        // If rejected and was previously approved, deduct balance
        if ($request->status == 'rejected' && $previousStatus == 'approved') {
            $user = $deposit->user;
            if ($user && isset($user->balance)) {
                $user->decrement('balance', $deposit->amount);
            }

            // 2️⃣ Remove deposit bonus from incomes
            Income::where('user_id', $user->id)
                ->where('type', 'deposit_bonus')
                ->where('description', 'like', '%'.$deposit->transaction_id.'%')
                ->delete();

            // 3️⃣ Remove referral bonus from incomes & wallet
            if ($user->refer_by) {
                $referrer = User::find($user->refer_by);
                if ($referrer) {
                    $referralAmount = $deposit->amount * 0.005;

                    if (isset($referrer->balance)) {
                        $referrer->decrement('balance', $referralAmount);
                        $referrer->decrement('referral_earnings', $referralAmount); // optional summary
                    }

                    Income::where('user_id', $referrer->id)
                        ->where('type', 'referral_bonus')
                        ->where('description', 'like', '%'.$user->username.'%')
                        ->delete();
                }
            }
        }
        
        // dd('here');
        return redirect()->back()->with('success', 'Deposit status updated successfully!');
    }

    /**
     * Bulk update status
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:deposits,id',
            'status' => 'required|in:pending,approved,rejected',
            'admin_note' => 'nullable|string|max:500',
        ]);

        foreach ($request->ids as $id) {
            $deposit = Deposit::with('user')->findOrFail($id);
            $user = $deposit->user;

            $previousStatus = $deposit->status;

            // Update deposit status
            $deposit->update([
                'status' => $request->status,
                'admin_note' => $request->admin_note,
            ]);

            // ----------------------
            // APPROVED LOGIC
            // ----------------------
            if ($request->status === 'approved' && $previousStatus !== 'approved') {
                // Add deposit to user balance
                if ($user && isset($user->balance)) {
                    $user->increment('balance', $deposit->amount);
                }

                // Deposit bonus
                Income::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'type' => 'deposit_bonus',
                        'description' => 'Deposit bonus for transaction '.$deposit->transaction_id,
                    ],
                    ['amount' => 10]
                );

                // Referrer 0.5%
                if ($user->refer_by) {
                    $referrer = User::find($user->refer_by);
                    if ($referrer) {
                        $referralAmount = $deposit->amount * 0.005;

                        // Update referrer balance & summary
                        $referrer->increment('balance', $referralAmount);
                        $referrer->increment('referral_earnings', $referralAmount);

                        Income::firstOrCreate(
                            [
                                'user_id' => $referrer->id,
                                'type' => 'referral_bonus',
                                'description' => '0.5% referral bonus from '.$user->username.' deposit',
                            ],
                            ['amount' => $referralAmount]
                        );
                    }
                }
            }

            // ----------------------
            // REJECTED LOGIC
            // ----------------------
            if ($request->status === 'rejected' && $previousStatus === 'approved') {
                // Deduct deposit from user
                if ($user && isset($user->balance)) {
                    $user->decrement('balance', $deposit->amount);
                }

                // Remove deposit bonus income
                Income::where('user_id', $user->id)
                    ->where('type', 'deposit_bonus')
                    ->where('description', 'like', '%'.$deposit->transaction_id.'%')
                    ->delete();

                // Remove referrer bonus & adjust balance
                if ($user->refer_by) {
                    $referrer = User::find($user->refer_by);
                    if ($referrer) {
                        $referralAmount = $deposit->amount * 0.005;

                        $referrer->decrement('balance', $referralAmount);
                        $referrer->decrement('referral_earnings', $referralAmount);

                        Income::where('user_id', $referrer->id)
                            ->where('type', 'referral_bonus')
                            ->where('description', 'like', '%'.$user->username.'%')
                            ->delete();
                    }
                }
            }
        }

        return redirect()->back()->with('success', count($request->ids) . ' deposits updated successfully!');
    }

}