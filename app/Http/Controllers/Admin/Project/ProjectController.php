<?php

namespace App\Http\Controllers\Admin\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pageTitle = 'Project List';
        $projects = Project::latest()->get();

        return view('admin.project.index', compact('pageTitle', 'projects'));
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
            'investment_type' => 'nullable|string|max:100',
            'roi' => 'nullable|numeric|min:0|max:100',
            'description' => 'nullable|string',
            'capital_required' => 'nullable|numeric|min:0',
            'pitch_deck' => 'nullable|mimes:pdf|max:5120', // Max 5MB
        ]);

        // Handle file upload
        $filePath = null;
        if ($request->hasFile('pitch_deck')) {
            $filePath = $request->file('pitch_deck')->store('projects/pitch_decks', 'public');
        }

        // Create new project
        Project::create([
            'name' => $request->name,
            'investment_type' => $request->investment_type,
            'roi' => $request->roi,
            'description' => $request->description,
            'capital_required' => $request->capital_required ?? 0,
            'capital_raised' => 0,
            'status' => 'Pending',
            'pitch_deck' => $filePath,
            'score' => 100,
            'has_complaint' => false,
            'entrepreneur_id' => Auth::id(),
        ]);

        return redirect()->route('admin.project.index')->with('success', '✅ Project submitted successfully for review!');
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
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'investment_type' => 'nullable|string|max:100',
            'roi' => 'nullable|numeric|min:0|max:100',
            'description' => 'nullable|string',
            'capital_required' => 'nullable|numeric|min:0',
            'capital_raised' => 'nullable|numeric|min:0',
            'status' => 'required|string|in:Pending,Approved,Issued,At Risk',
            'pitch_deck' => 'nullable|mimes:pdf|max:5120',
            'has_complaint' => 'boolean',
        ]);

        $project = Project::findOrFail($id);

        // If complaint status changed
        if ($request->has_complaint && !$project->has_complaint) {
            $project->score = max(0, $project->score - 20);
            $project->status = 'At Risk';
        }

        // Handle file re-upload
        $filePath = $project->pitch_deck;
        if ($request->hasFile('pitch_deck')) {
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            $filePath = $request->file('pitch_deck')->store('projects/pitch_decks', 'public');
        }

        $project->update([
            'name' => $request->name,
            'investment_type' => $request->investment_type,
            'roi' => $request->roi,
            'description' => $request->description,
            'capital_required' => $request->capital_required,
            'capital_raised' => $request->capital_raised ?? 0,
            'status' => $project->status ?? $request->status,
            'pitch_deck' => $filePath,
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

        // Delete pitch deck file if exists
        if ($project->pitch_deck && Storage::disk('public')->exists($project->pitch_deck)) {
            Storage::disk('public')->delete($project->pitch_deck);
        }

        $project->delete();

        return redirect()->route('admin.project.index')->with('success', '🗑️ Project deleted successfully!');
    }
}
