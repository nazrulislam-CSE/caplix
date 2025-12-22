<?php

namespace App\Http\Controllers\Investor\Refer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Income;

class ReferController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user(); 
        $referrals = Auth::user()->referrals()->latest()->paginate(10);
        $rewardBalance = Income::where('user_id', $user->id)->whereIn('type', ['reward_bonus', 'daily_bonus', 'performance_bonus', 'cashback_income'])->sum('amount');
        $currentRank = auth()->user()->rank();

        $pageTitle = 'Refer, Rank & Rewards';
        return view('investor.refer.index', compact('user', 'pageTitle', 'referrals', 'currentRank','rewardBalance'));
    }
}
