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
        $pageTitle = 'Project Details';
        return view('investor.project.show', compact('project', 'pageTitle'));
    }

    public function analysis()
    {
        $pageTitle = 'Project Analysis';
        $projects = Project::where('status', 'Approved')->latest()->get();
        return view('investor.project.analysis', compact('pageTitle','projects'));
    }
   
}
