<?php

namespace App\Http\Controllers\Investor\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
   
    public function show(Project $project)
    {
        $user = Auth::user();
        if (!$user->hasVerifiedInvestorKyc()) {
            return redirect()->route('investor.kyc.status')
                ->with('error', 'KYC verification required to view project details.');
        }
        $pageTitle = 'Project Details';
        
        return view('investor.project.show', compact('project', 'pageTitle'));
    }

    public function analysis()
    {
        $user = Auth::user();
        if (!$user->hasVerifiedInvestorKyc()) {
            return redirect()->route('investor.kyc.status')
                ->with('error', 'KYC verification required to access project analysis.');
        }

        $pageTitle = 'Project Analysis';
        
        $projects = Project::where('status', 'Approved')->latest()->get();
        return view('investor.project.analysis', compact('pageTitle','projects'));
    }
   
}
