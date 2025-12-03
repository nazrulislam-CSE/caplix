<?php

namespace App\Http\Controllers\Investor\Investment;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\Project;
use App\Models\InvestmentTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvestmentController extends Controller
{
    /**
     * Display a listing of the investments.
     */
    public function index()
    {
        $pageTitle = 'My Investment Portfolio';
        
        // Get current investor's investments with project details
        $investments = Investment::with('project')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();
          

        // Get available projects for new investment
        // $availableProjects = Project::approved()
        //     ->whereDoesntHave('investments', function ($query) {
        //         $query->where('user_id', Auth::id())
        //               ->whereIn('status', ['active', 'managed']);
        //     })
        //     ->whereRaw('capital_raised < capital_required') // Only projects that need funding
        //     ->get();

        $availableProjects = Project::approved()
            ->whereDoesntHave('investments', function ($query) {
                $query->whereIn('status', ['active', 'managed']); // Auth::id() remove
            })
            ->whereRaw('capital_raised < capital_required') // Only projects that need funding
            ->get();


        return view('investor.investment.index', compact(
            'pageTitle', 
            'investments', 
            'availableProjects'
        ));
    }

    /**
     * Show the form for creating a new investment.
     */
    public function create(Request $request)
    {
        $pageTitle = 'Make a New Investment';
        
        $projectId = $request->query('project_id');
        $project = null;

        if ($projectId) {
            $project = Project::approved()->findOrFail($projectId);
            
            // Check if user already has an active investment in this project
            $existingInvestment = Investment::where('user_id', Auth::id())
                ->where('project_id', $project->id)
                ->whereIn('status', ['active', 'managed'])
                ->first();

            if ($existingInvestment) {
                return redirect()->route('investor.investment.index')
                    ->with('info', 'You already have an active investment in this project.');
            }

            // Check if project is fully funded
            if ($project->isFullyFunded()) {
                return redirect()->route('investor.investment.index')
                    ->with('error', 'This project is already fully funded.');
            }
        }

        $availableProjects = Project::approved()
            ->whereRaw('capital_raised < capital_required')
            ->get();

        return view('investor.investment.index', compact(
            'pageTitle', 
            'project', 
            'availableProjects'
        ));
    }

    /**
     * Store a newly created investment in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'investment_amount' => 'required|numeric|min:1000',
            'type' => 'required|in:short-term,regular,fixed-deposit,long-term',
        ]);

        try {
            DB::beginTransaction();

            $project = Project::approved()->findOrFail($request->project_id);

            // Check minimum investment (using capital_required as reference)
            $minInvestment = 1000; // Default minimum
            if ($request->investment_amount < $minInvestment) {
                return back()->withErrors([
                    'investment_amount' => "Minimum investment is $" . number_format($minInvestment)
                ])->withInput();
            }

            // Check if project can accept this investment
            $remainingCapital = $project->remaining_capital;
            if ($request->investment_amount > $remainingCapital) {
                return back()->withErrors([
                    'investment_amount' => "Maximum investment for this project is $" . number_format($remainingCapital)
                ])->withInput();
            }

            // Create investment
            $investment = Investment::create([
                'user_id' => Auth::id(),
                'project_id' => $project->id,
                'investment_amount' => $request->investment_amount,
                'current_value' => $request->investment_amount,
                'profit_loss' => 0,
                'profit_loss_percentage' => 0,
                'status' => 'active',
                'type' => $request->type,
                'risk_level' => $project->risk_level,
                'investment_date' => now(),
            ]);

            // Update project capital raised
            $project->increment('capital_raised', $request->investment_amount);

            // Create transaction record
            InvestmentTransaction::create([
                'investment_id' => $investment->id,
                'user_id' => Auth::id(),
                'transaction_type' => 'initial_investment',
                'amount' => $request->investment_amount,
                'balance_after' => $request->investment_amount,
                'reference_id' => (new InvestmentTransaction())->generateReferenceId(),
                'status' => 'completed',
                'processed_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('investor.investment.index')
                ->with('success', 'Investment created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create investment: ' . $e->getMessage());
        }
    }

    /**
     * Add more investment to existing investment.
     */
    public function addMoreInvestment(Request $request, Investment $investment)
    {
        // Authorization check
        if ($investment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'additional_amount' => 'required|numeric|min:1000',
        ]);

        try {
            DB::beginTransaction();

            $additionalAmount = $request->additional_amount;
            
            // Check if project can accept additional investment
            $project = $investment->project;
            $remainingCapital = $project->remaining_capital;
            
            if ($additionalAmount > $remainingCapital) {
                return back()->withErrors([
                    'additional_amount' => "Maximum additional investment for this project is $" . number_format($remainingCapital)
                ])->withInput();
            }

            $newTotal = $investment->investment_amount + $additionalAmount;

            // Update investment
            $investment->update([
                'investment_amount' => $newTotal,
                'current_value' => $investment->current_value + $additionalAmount,
            ]);

            // Update project capital raised
            $project->increment('capital_raised', $additionalAmount);

            // Recalculate profit/loss
            $investment->calculateProfitLoss();

            // Create transaction record
            InvestmentTransaction::create([
                'investment_id' => $investment->id,
                'user_id' => Auth::id(),
                'transaction_type' => 'additional_investment',
                'amount' => $additionalAmount,
                'balance_after' => $newTotal,
                'reference_id' => (new InvestmentTransaction())->generateReferenceId(),
                'status' => 'completed',
                'notes' => 'Additional investment added',
                'processed_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('investor.investment.index')
                ->with('success', 'Additional investment added successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to add additional investment: ' . $e->getMessage());
        }
    }
}