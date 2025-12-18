<?php

namespace App\Http\Controllers\Investor\Refer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReferController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user(); 
        $pageTitle = 'Refer, Rank & Rewards';

        return view('investor.refer.index', compact('user', 'pageTitle'));
    }
}
