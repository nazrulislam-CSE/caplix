<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BusinessKyc;
use App\Models\Project;

class EntrepreneurController extends Controller
{
    public function EntrepreneurDashboard(){
        // KYC status check
        $user = Auth::user();
        $kyc = BusinessKyc::where('user_id', $user->id)->first();
        $isKycVerified = $kyc && $kyc->status === 'verified';
        

        $pageTitle = 'Entrepreneur Dashboard';
        $projects = Project::where('entrepreneur_id', Auth::id())->latest()->paginate(10); 
        
        return view('entrepreneur.dashboard',compact('pageTitle','projects','isKycVerified','kyc',));
    }

    public function EntrepreneurDestroy(Request $request){

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success','Entrepreneur logout Successfully');
    }
}
