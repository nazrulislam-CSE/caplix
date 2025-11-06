<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Investment;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvestorController extends Controller
{
    public function InvestorDashboard()
    {
        $pageTitle = 'Investor Dashboard';
        
        // Get investor-specific statistics
        $investorId = Auth::id();
        
        // Investor's total investments
        $totalInvested = Investment::where('user_id', $investorId)
            ->whereIn('status', ['active', 'managed'])
            ->sum('investment_amount');

        // Investor's current portfolio value
        $currentValue = Investment::where('user_id', $investorId)
            ->whereIn('status', ['active', 'managed'])
            ->sum('current_value');

        // Investor's total profit/loss
        $totalProfitLoss = Investment::where('user_id', $investorId)
            ->whereIn('status', ['active', 'managed'])
            ->sum('profit_loss');

        // Investor's active investments count
        $activeInvestments = Investment::where('user_id', $investorId)
            ->where('status', 'active')
            ->count();

        // Monthly profit (last 30 days)
        $monthlyProfit = Investment::where('user_id', $investorId)
            ->where('status', 'active')
            ->where('investment_date', '>=', now()->subDays(30))
            ->sum('profit_loss');

        // Pending withdrawals for this investor
        // $pendingWithdrawals = Withdrawal::where('user_id', $investorId)
        //     ->where('status', 'pending')
        //     ->count();

        // Platform-wide statistics
        $totalInvestors = User::where('role', 'investor')->count();
        $activeProjects = Project::where('status', 'Approved')
            ->whereRaw('capital_raised < capital_required')
            ->count();
        // $verifiedEntrepreneurs = User::where('role', 'entrepreneur')
        //     ->where('email_verified_at', '!=', null)
        //     ->count();
        // $kycPending = User::where('role', 'investor')
        //     ->where('kyc_verified', false)
        //     ->count();

        // Get approved projects for display
        $projects = Project::where('status', 'Approved')
            ->whereRaw('capital_raised < capital_required')
            ->latest()
            ->paginate(10);

        // Recent investments by this investor
        $recentInvestments = Investment::with('project')
            ->where('user_id', $investorId)
            ->latest()
            ->take(5)
            ->get();

        return view('investor.dashboard', compact(
            'pageTitle',
            'projects',
            'totalInvested',
            'currentValue',
            'totalProfitLoss',
            'activeInvestments',
            'monthlyProfit',
            // 'pendingWithdrawals',
            'totalInvestors',
            'activeProjects',
            // 'verifiedEntrepreneurs',
            // 'kycPending',
            'recentInvestments'
        ));
    }

    // ... other methods
    public function InvestorDestroy(Request $request){

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success','Investor logout Successfully');
    }
}