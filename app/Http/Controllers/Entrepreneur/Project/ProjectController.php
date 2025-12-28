<?php

namespace App\Http\Controllers\Entrepreneur\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BusinessKyc;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    /**
     * Display a listing of projects.
     */
    public function index()
    {
        $user = Auth::user();
        $kyc = BusinessKyc::where('user_id', $user->id)->first();
        
        if (!$kyc || $kyc->status !== 'verified') {
            return redirect()->route('entrepreneur.kyc.create')
                ->with('error', 'Please complete KYC verification to create projects.');
        }
        

        $pageTitle = 'Project List';
        $projects = Project::where('entrepreneur_id', Auth::id())->latest()->paginate(10); 
        return view('entrepreneur.project.index', compact('pageTitle', 'projects'));
    }

    /**
     * Show the form for creating a new project.
     */
    public function create()
    {
        $user = Auth::user();
        $kyc = BusinessKyc::where('user_id', $user->id)->first();
        
        if (!$kyc || $kyc->status !== 'verified') {
            return redirect()->route('entrepreneur.kyc.create')
                ->with('error', 'Please complete KYC verification to create projects.');
        }
        

        $pageTitle = 'Add New Project';
        return view('entrepreneur.project.create', compact('pageTitle'));
    }

    /**
     * Store a newly created project.
     */
    public function store(Request $request)
    {
        try {
            // Validate request
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'investment_type' => 'nullable|in:short,regular,fdi',
                'short_duration' => 'nullable|integer|min:1|max:8',
                'regular_duration' => 'nullable|integer|min:1|max:20',
                'roi' => 'nullable|numeric|min:0|max:100',
                'description' => 'nullable|string',
                'capital_required' => 'nullable|numeric|min:0',
                'pitch_deck' => 'nullable|mimes:pdf|max:5120',
            ]);

            // Handle file upload
            if ($request->hasFile('pitch_deck')) {
                $validated['pitch_deck'] = $request->file('pitch_deck')->store('projects/pitch_decks', 'public');
            }

            // Assign additional default fields
            $validated['capital_raised'] = 0;
            $validated['status'] = 'Pending';
            $validated['score'] = 100;
            $validated['has_complaint'] = false;
            $validated['entrepreneur_id'] = Auth::id();

            $validated['created_by'] = Auth::id();

            // Create project
            Project::create($validated);

            return redirect()->route('entrepreneur.project.index')
                ->with('success', '✅ Project submitted successfully for review!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation failed
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();

        } catch (\Exception $e) {
            // Any other error (DB, file upload, etc.)
            return redirect()->back()
                ->with('error', '❌ Something went wrong: ' . $e->getMessage())
                ->withInput();
        }
    }


    /**
     * Display a single project.
     */
    public function show(Project $project)
    {
        $pageTitle = 'Project Details';

        // Project investments with investor info
        $project->load(['investments.user']);

        // Total investment amount
        $totalInvestment = $project->investments->sum('investment_amount');

        // Total investors count
        $totalInvestors = $project->investments->unique('user_id')->count();

        return view('entrepreneur.project.show', compact(
            'project',
            'pageTitle',
            'totalInvestment',
            'totalInvestors'
        ));
    }


    /**
     * Show the form for editing a project.
     */
    public function edit(Project $project)
    {
        $pageTitle = 'Edit Project';
        return view('entrepreneur.project.edit', compact('project', 'pageTitle'));
    }

    /**
     * Update a project.
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'investment_type' => 'nullable|in:short,regular,fdi',
            'short_duration' => 'nullable|integer|min:1|max:8',
            'regular_duration' => 'nullable|integer|min:1|max:20',
            'roi' => 'nullable|numeric|min:0|max:100',
            'description' => 'nullable|string',
            'capital_required' => 'nullable|numeric|min:0',
            'capital_raised' => 'nullable|numeric|min:0',
            'status' => 'required|in:Pending,Approved,Issued,At Risk',
            'pitch_deck' => 'nullable|mimes:pdf|max:5120',
            'has_complaint' => 'nullable|boolean',
        ]);

        // Handle complaint adjustment
        if (!empty($validated['has_complaint']) && !$project->has_complaint) {
            $project->score = max(0, $project->score - 20);
            $validated['status'] = 'At Risk';
        }

        // Handle file re-upload
        if ($request->hasFile('pitch_deck')) {
            if ($project->pitch_deck && Storage::disk('public')->exists($project->pitch_deck)) {
                Storage::disk('public')->delete($project->pitch_deck);
            }
            $validated['pitch_deck'] = $request->file('pitch_deck')->store('projects/pitch_decks', 'public');
        } else {
            $validated['pitch_deck'] = $project->pitch_deck;
        }

        $validated['score'] = $project->score; // Preserve updated score if complaint
        $validated['updated_by'] = Auth::id();
        $project->update($validated);

        return redirect()->route('entrepreneur.project.index')
            ->with('success', '✅ Project updated successfully!');
    }

    /**
     * Delete a project.
     */
    public function destroy(Project $project)
    {
        if ($project->pitch_deck && Storage::disk('public')->exists($project->pitch_deck)) {
            Storage::disk('public')->delete($project->pitch_deck);
        }

        $project->delete();

        return redirect()->route('entrepreneur.project.index')
            ->with('success', '🗑️ Project deleted successfully!');
    }
}
