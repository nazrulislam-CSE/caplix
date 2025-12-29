<?php

namespace App\Http\Controllers\Entrepreneur\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use App\Models\Income;
use App\Models\ProjectProfitReport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfitReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 1️⃣ Fetch reports of logged-in user only
        $reports = ProjectProfitReport::with('project')
            ->where('entrepreneur_id', Auth::id())   // only for logged-in user
            ->orderBy('year', 'desc')
            ->orderByRaw("FIELD(month, 'January','February','March','April','May','June','July','August','September','October','November','December')")
            ->paginate(15);
        $pageTitle = 'My Profit Reports';

        // 2️⃣ Return view
        return view('entrepreneur.project.profit.index', compact('reports','pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $entrepreneurId = Auth::id();
        $projects = Project::where('entrepreneur_id', $entrepreneurId)->get();
        $pageTitle = 'Reports & Audit';
        return view('entrepreneur.project.profit.create', compact('pageTitle','projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1️⃣ Validate input
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'month' => 'required|string',
            'year' => 'required|integer',
            'total_profit' => 'required|numeric|min:0',
        ]);

        // 2️⃣ Check for duplicate
        $exists = ProjectProfitReport::where('project_id', $request->project_id)
            ->where('month', $request->month)
            ->where('year', $request->year)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', 'This project profit report already exists for the selected month & year.');
        }

        // 3️⃣ Start transaction
        DB::transaction(function () use ($request) {

            $totalProfit = $request->total_profit;

            // 🔢 Calculate shares (0.5% for Admin & Referral)
            $adminShare    = round($totalProfit * 0.005, 2); // 0.5%
            $referralShare = round($totalProfit * 0.005, 2); // 0.5%
            $investorShare = round($totalProfit - ($adminShare + $referralShare), 2);

            // 4️⃣ Create Profit Report
            $report = ProjectProfitReport::create([
                'project_id' => $request->project_id,
                'entrepreneur_id' => auth()->id(),
                'year' => $request->year,
                'month' => $request->month,
                'total_profit' => $totalProfit,
                'admin_share' => $adminShare,
                'investor_share' => $investorShare,
                'referral_share' => $referralShare,
                'status' => 'submitted',
            ]);

            // 5️⃣ Admin Income (0.5%)
            $admin = User::find(1); // Admin user id
            Income::create([
                'user_id' => $admin->id,
                'amount' => $adminShare,
                'type' => 'commission_income',
                'description' => "0.5% admin commission from project profit",
            ]);
            $admin->increment('balance', $adminShare);
            $admin->increment('total_earnings', $adminShare);

            // 6️⃣ Investor Income (remaining 99%)
            $investor = User::find($report->entrepreneur_id);
            Income::create([
                'user_id' => $investor->id,
                'amount' => $investorShare,
                'type' => 'investment_profit',
                'description' => 'Investor profit after admin & referral deduction',
            ]);
            $investor->increment('balance', $investorShare);
            $investor->increment('total_earnings', $investorShare);
            $investor->increment('investment_balance', $investorShare);
            $investor->increment('withdrawable_balance', $investorShare);

            // 7️⃣ Referral Income (0.5%) – Optional
            if ($investor->referrer_id) {
                $referral = User::find($investor->referrer_id);
                Income::create([
                    'user_id' => $referral->id,
                    'amount' => $referralShare,
                    'type' => 'referral_bonus',
                    'description' => '0.5% referral bonus from project profit',
                ]);
                $referral->increment('balance', $referralShare);
                $referral->increment('total_earnings', $referralShare);
                $referral->increment('referral_earnings', $referralShare);
            }
        });

        // 8️⃣ Return response
         return redirect()->route('entrepreneur.project.profit.report.index')->with('success', 'Profit report submitted & distributed successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
