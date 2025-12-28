<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BusinessKyc;
use App\Models\Project;

class EntrepreneurController extends Controller
{
   public function EntrepreneurDashboard()
    {
        $user = Auth::user();

        // KYC status
        $kyc = BusinessKyc::where('user_id', $user->id)->first();
        $isKycVerified = $kyc && $kyc->status === 'verified';

        $pageTitle = 'Entrepreneur Dashboard';

        // Projects paginate
        $projects = Project::where('entrepreneur_id', $user->id)->latest()->paginate(10);

        // Dashboard cards data
        $totalInvestors = 0;
        $totalInvestment = 0;
        $activeInvestments = 0;
        $completedInvestments = 0;

        foreach ($projects as $project) {
            $totalInvestors += $project->investments->unique('user_id')->count();
            $totalInvestment += $project->investments->sum('investment_amount');
            $activeInvestments += $project->investments->where('status', 'active')->count();
            $completedInvestments += $project->investments->where('status', 'completed')->count();
        }

        return view('entrepreneur.dashboard', compact(
            'pageTitle',
            'projects',
            'isKycVerified',
            'kyc',
            'totalInvestors',
            'totalInvestment',
            'activeInvestments',
            'completedInvestments'
        ));
    }


    public function EntrepreneurDestroy(Request $request){

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success','Entrepreneur logout Successfully');
    }
}
