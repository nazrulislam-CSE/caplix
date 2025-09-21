<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvestorController extends Controller
{
    public function InvestorDashboard(){

        $pageTitle = 'Investor Dashboard';
        return view('investor.dashboard',compact('pageTitle'));
    }

    public function InvestorDestroy(Request $request){

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success','Investor logout Successfully');
    }

}
