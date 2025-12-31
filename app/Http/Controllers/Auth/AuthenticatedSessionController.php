<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->role === 'investor') {
                return redirect()->route('investor.dashboard');
            }

            if ($user->role === 'entrepreneur') {
                return redirect()->route('entrepreneur.dashboard');
            }
        }

        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();

        // Check if user exists
        if (!$user) {
            Auth::logout();
            return back()->withErrors([
                $request->filled('email') ? 'email' : 'phone' => 'The provided credentials do not match our records.',
            ]);
        }

        if ($user->role === 'admin') {
            Auth::logout(); 
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', 'Admins are not allowed to access this section.');
        }

        // Normal user (investor / entrepreneur) case
        if ($user->role === 'investor') {
            return redirect()->intended(route('investor.dashboard', absolute: false));
        }

        if ($user->role === 'entrepreneur') {
            return redirect()->intended(route('entrepreneur.dashboard', absolute: false));
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}