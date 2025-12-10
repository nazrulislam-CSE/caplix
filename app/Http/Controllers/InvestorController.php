<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Investment;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\InvestorKyc;

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
            
        $user = Auth::user();
        // Get investor stats
        $kycStatus = $this->getKycStatus($user);
        $stats = $this->getDashboardStats($user);

        $hasKyc = $user->hasInvestorKyc();
        
        // Get recent activities
        $recentActivities = $this->getRecentActivities($user);

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
            'recentInvestments',
            'user',
            'kycStatus',
            'stats',
            'recentActivities',
            'hasKyc',
        ));
    }

    // ... other methods
    public function InvestorDestroy(Request $request){

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success','Investor logout Successfully');
    }

      private function getKycStatus($user)
    {
        if (!$user->hasInvestorKyc()) {
            return [
                'status' => 'not_submitted',
                'message' => 'Please complete your KYC verification to start investing.',
                'badge' => 'secondary',
                'icon' => 'fas fa-exclamation-circle',
                'action' => 'Submit KYC',
                'route' => route('investor.kyc.create')
            ];
        }
        
        $kyc = $user->investorKyc;
        
        switch ($kyc->status) {
            case 'draft':
                return [
                    'status' => 'draft',
                    'message' => 'Your KYC is saved as draft. Complete and submit for verification.',
                    'badge' => 'warning',
                    'icon' => 'fas fa-save',
                    'action' => 'Complete KYC',
                    'route' => route('investor.kyc.create')
                ];
                
            case 'pending':
            case 'under_review':
                return [
                    'status' => 'under_review',
                    'message' => 'Your KYC is under review. We\'ll notify you once verified.',
                    'badge' => 'info',
                    'icon' => 'fas fa-search',
                    'action' => 'View Status',
                    'route' => route('investor.kyc.status')
                ];
                
            case 'verified':
                return [
                    'status' => 'verified',
                    'message' => 'Your KYC is verified. You can start investing now.',
                    'badge' => 'success',
                    'icon' => 'fas fa-check-circle',
                    'action' => 'Start Investing',
                    'route' => route('investor.project.analysis')
                ];
                
            case 'rejected':
                return [
                    'status' => 'rejected',
                    'message' => 'Your KYC was rejected. Please resubmit with corrections.',
                    'badge' => 'danger',
                    'icon' => 'fas fa-times-circle',
                    'action' => 'Resubmit KYC',
                    'route' => route('investor.kyc.create')
                ];
                
            default:
                return [
                    'status' => 'unknown',
                    'message' => 'KYC status unknown.',
                    'badge' => 'secondary',
                    'icon' => 'fas fa-question-circle',
                    'action' => 'Check Status',
                    'route' => route('investor.kyc.status')
                ];
        }
    }
    
    private function getDashboardStats($user)
    {
        return [
            'total_investment' => [
                'title' => 'Total Investment',
                'value' => '৳0.00',
                'icon' => 'fas fa-money-bill-wave',
                'color' => 'primary',
                'trend' => null
            ],
            'active_investments' => [
                'title' => 'Active Investments',
                'value' => '0',
                'icon' => 'fas fa-chart-line',
                'color' => 'success',
                'trend' => null
            ],
            'total_returns' => [
                'title' => 'Total Returns',
                'value' => '৳0.00',
                'icon' => 'fas fa-coins',
                'color' => 'warning',
                'trend' => null
            ],
            'kyc_status' => [
                'title' => 'KYC Status',
                'value' => $user->hasVerifiedInvestorKyc() ? 'Verified' : 'Pending',
                'icon' => 'fas fa-user-check',
                'color' => $user->hasVerifiedInvestorKyc() ? 'success' : 'warning',
                'trend' => null
            ]
        ];
    }
    
    private function getRecentActivities($user)
    {
        $activities = [];
        
        if ($user->hasInvestorKyc()) {
            $kyc = $user->investorKyc;
            $activities[] = [
                'time' => $kyc->updated_at,
                'title' => 'KYC Status Updated',
                'description' => 'Your KYC status is now ' . ucfirst($kyc->status),
                'icon' => 'fas fa-user-check',
                'color' => $kyc->status === 'verified' ? 'success' : 'info'
            ];
        }
        
        // Add more activities as needed
        $activities[] = [
            'time' => $user->created_at,
            'title' => 'Account Created',
            'description' => 'Welcome to CapliX Investor Platform',
            'icon' => 'fas fa-user-plus',
            'color' => 'primary'
        ];
        
        // Sort by time desc
        usort($activities, function($a, $b) {
            return $b['time'] <=> $a['time'];
        });
        
        return array_slice($activities, 0, 5);
    }
}