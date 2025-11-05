<?php

namespace App\Http\Controllers\Admin\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pageTitle = 'Project List';

        $user = Auth::user(); 
        $projects = Project::latest()->get();
        return view('admin.project.index', compact('pageTitle','projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Add New Project';
        return view('admin.project.create', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'capital_raised' => 'nullable|numeric',
            'goal' => 'nullable|numeric',
            'status' => 'required|string',
        ]);

        Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'capital_raised' => $request->capital_raised ?? 0,
            'goal' => $request->goal ?? 0,
            'status' => $request->status,
            'score' => 100,
            'has_complaint' => false,
        ]);

        return redirect()->route('admin.project.index')->with('success', '✅ Project added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $project = Project::findOrFail($id);
        $pageTitle = 'Project Details';
        return view('admin.project.show', compact('project', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $project = Project::findOrFail($id);
        $pageTitle = 'Edit Project';
        return view('admin.project.edit', compact('project', 'pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'capital_raised' => 'nullable|numeric',
            'goal' => 'nullable|numeric',
            'status' => 'required|string',
            'has_complaint' => 'boolean',
        ]);

        $project = Project::findOrFail($id);

        if ($request->has_complaint && !$project->has_complaint) {
            $project->score = max(0, $project->score - 20);
            $project->status = 'At Risk';
        }

        $project->update([
            'name' => $request->name,
            'description' => $request->description,
            'capital_raised' => $request->capital_raised,
            'goal' => $request->goal,
            'status' => $request->status,
            'has_complaint' => $request->has_complaint ?? false,
            'score' => $project->score,
        ]);

        return redirect()->route('admin.project.index')->with('success', '✅ Project updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return redirect()->route('admin.project.index')->with('success', '🗑️ Project deleted successfully!');
    }
}
