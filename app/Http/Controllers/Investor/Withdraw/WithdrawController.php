<?php

namespace App\Http\Controllers\Investor\Withdraw;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class WithdrawController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Withdraw Request';
        $user = Auth::user();
        $balance = $user->balance-$user->investment_balance;
        return view('investor.withdraw.request', compact('user', 'pageTitle','balance'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'wallet_type' => 'required|in:capital_wallet,point_wallet,salary_wallet,earning_wallet',
            'amount' => 'required|numeric|min:1',
        ]);

        $wallet = $request->wallet_type;
        $amount = $request->amount;

        // Check if user has enough balance
        if ($amount > $user->{$wallet}) {
            return back()->withErrors(['amount' => 'Insufficient balance in selected wallet.']);
        }

        // Create withdraw request
        $withdraw = Withdraw::create([
            'user_id' => $user->id,
            'wallet_type' => $wallet,
            'amount' => $amount,
            'status' => 'pending',
        ]);

        // Deduct from user's wallet immediately (or you can wait until approval)
        $user->{$wallet} -= $amount;
        $user->withdrawable_balance -= $amount; // optional: update total withdrawable balance
        $user->locked_balance += $amount; // optional: lock this amount until approved
        $user->save();

        return redirect()->route('investor.withdraw.request')
                        ->with('success', 'Withdraw request submitted successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
