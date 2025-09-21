<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

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
        return view('admin.dashboard',compact('pageTitle'));
    }

    public function AdminDestroy(Request $request){

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login')->with('success','Admin logout Successfully');
    }
}
