<?php

namespace App\Http\Controllers\Investor\Claim;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Income;
use Illuminate\Support\Facades\Auth;

class BonusController extends Controller
{
    public function claimReferralBonus()
    {
        $user = Auth::user();

        // Check if already claimed
        $alreadyClaimed = Income::where('user_id', $user->id)
            ->where('type', 'referral_bonus')
            ->exists();

        if ($alreadyClaimed) {
            return back()->with('error', 'Referral bonus already claimed.');
        }

        // Add referral bonus
        Income::create([
            'user_id' => $user->id,
            'amount' => 50,
            'type' => 'referral_bonus',
            'description' => 'Referral bonus for ' . $user->username,
        ]);

        // Update user balance
        $user->increment('balance', 50);
        $user->increment('referral_earnings', 50);

        return back()->with('success', 'Referral bonus claimed successfully!');
    }
}
