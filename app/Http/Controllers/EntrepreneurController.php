<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EntrepreneurController extends Controller
{
    public function EntrepreneurDashboard(){

        $pageTitle = 'Entrepreneur Dashboard';
        return view('entrepreneur.dashboard',compact('pageTitle'));
    }

    public function EntrepreneurDestroy(Request $request){

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success','Entrepreneur logout Successfully');
    }
}
