<?php

namespace App\Http\Controllers\Investor\Deposit;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Deposit;

class DepositController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $pageTitle = 'Deposit History';
        
        // Start query
        $query = $user->deposits()->latest();
        
        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        
        if ($request->filled('search')) {
            $query->where('transaction_id', 'like', '%' . $request->search . '%');
        }
        
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('created_at', [
                $request->date_from . ' 00:00:00',
                $request->date_to . ' 23:59:59'
            ]);
        }
        
        // Get paginated results
        $deposits = $query->paginate(20)->withQueryString();
        
        // Calculate totals
        $totalDeposited = $user->getTotalDepositedAttribute();
        $totalPending = $user->getTotalPendingDepositAttribute();
        
        return view('investor.deposit.index', compact(
            'user', 
            'pageTitle', 
            'deposits',
            'totalDeposited',
            'totalPending'
        ));
    }

    public function create()
    { 
        $pageTitle = 'Deposit Funds';
        return view('investor.deposit.create', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:bKash,Nagad,Bank',
            'amount' => 'required|numeric|min:100',
            'transaction_id' => 'required|string|max:100|unique:deposits,transaction_id',
            'payment_slip' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Check if user has pending deposits (optional)
        $hasPending = Auth::user()->deposits()->where('status', 'pending')->exists();
        if ($hasPending) {
            return redirect()->back()->with('warning', 'You already have a pending deposit. Please wait for approval.');
        }

        // File upload
        $slipPath = null;
        if ($request->hasFile('payment_slip')) {
            $slipPath = $request->file('payment_slip')->store('payment-slips', 'public');
        }

        // Create deposit record
        $deposit = Deposit::create([
            'user_id' => Auth::id(),
            'payment_method' => $request->payment_method,
            'amount' => $request->amount,
            'transaction_id' => $request->transaction_id,
            'payment_slip' => $slipPath,
            'status' => 'pending',
        ]);

        // You can add notification system here (email, SMS, etc.)

        return redirect()->route('investor.deposit.index')
            ->with('success', 'Deposit request submitted successfully! Please wait for admin approval.');
    }

    /**
     * Display the deposit history.
     */
}