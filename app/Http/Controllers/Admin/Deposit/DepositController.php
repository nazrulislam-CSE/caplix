<?php

namespace App\Http\Controllers\Admin\Deposit;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Deposit;
use App\Models\User;

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
        
        // Update deposit
        $deposit->update([
            'status' => $request->status,
            'admin_note' => $request->admin_note,
        ]);
        
        // If approved, update user balance (if you have balance system)
        if ($request->status == 'approved' && $deposit->status != 'approved') {
            // Update user balance
            $user = $deposit->user;
            if ($user) {
                // If you have a balance column in users table
                if (isset($user->balance)) {
                    $user->increment('balance', $deposit->amount);
                }
                
                // You can send notification here (email, SMS, etc.)
            }
        }
        
        // If rejected and was previously approved, deduct balance
        if ($request->status == 'rejected' && $deposit->status == 'approved') {
            $user = $deposit->user;
            if ($user && isset($user->balance)) {
                $user->decrement('balance', $deposit->amount);
            }
        }
        
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
            $deposit = Deposit::find($id);
            $deposit->update([
                'status' => $request->status,
                'admin_note' => $request->admin_note,
            ]);
        }
        
        return redirect()->back()->with('success', count($request->ids) . ' deposits updated successfully!');
    }
}