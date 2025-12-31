<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\Project;
use App\Models\Deposit;
use App\Models\BusinessKyc;
use App\Models\InvestorKyc;

class AdminController extends Controller
{
    public function showLoginForm()
    {
        // Check if user is logged in with 'web' guard and has admin role
        if (auth()->guard('web')->check() && auth()->guard('web')->user()->role === 'admin') {
            return redirect()->route('admin.dashboard'); // Redirect to admin dashboard
        }
        return view('auth.admin_login'); // Show login form if not logged in as admin
    }


    public function login(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();
        $currentPath = $request->path();

        // ✅ Allow only admin
        if ($user->role === 'admin') {
            // ❌ Admin trying to login from user login page
            if ($currentPath === 'login') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Admin must login from /admin/login only.'
                ]);
            }

            // ✅ Admin logged in from correct path
            return redirect()->intended(route('admin.dashboard', absolute: false));
        }

        // ❌ Not admin — logout and block
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return back()->withErrors([
            'email' => 'You are not authorized to access this section.'
        ]);
    }


    public function AdminDashboard(){

        $pageTitle = 'Admin Dashboard';

        // Check if the public/storage symlink exists
        if (!File::exists(public_path('storage'))) {
            // Create the symlink
            app('files')->link(storage_path('app/public'), public_path('storage'));
        }

        $projects = Project::latest()->paginate(10); 
        $activeProjectCount = Project::where('status', 'approved')->count();
        $totalDeposit = Deposit::where('status', 'approved')->sum('amount');
        $verifiedEntrepreneurs = BusinessKyc::where('status', 'verified')->count();
        $kycPending = BusinessKyc::where('status', 'pending')->count();
        $verifiedInvestor = InvestorKyc::where('status', 'verified')->count();
        $kycPendingInvestor = InvestorKyc::where('status', 'pending')->count();

        // Dashboard cards data
        $totalInvestors = 0;
        $totalInvestment = 0;
        $activeInvestments = 0;
        $completedInvestments = 0;

        foreach ($projects as $project) {
            $totalInvestors += $project->investments->count();
            $totalInvestment += $project->investments->sum('investment_amount');
            $activeInvestments += $project->investments->where('status', 'active')->count();
            $completedInvestments += $project->investments->where('status', 'completed')->count();
        }

        return view('admin.dashboard',compact('pageTitle','projects',  'totalInvestors','totalInvestment','activeInvestments','completedInvestments','activeProjectCount','totalDeposit','verifiedEntrepreneurs','kycPending','verifiedInvestor','kycPendingInvestor'));
    }

    public function AdminDestroy(Request $request){

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login')->with('success','Admin logout Successfully');
    }
}
