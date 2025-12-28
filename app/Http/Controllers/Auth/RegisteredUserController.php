<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Income;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Exception;
use Illuminate\Validation\Rules\Password;


class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        try {

            // if (empty($request->refer_by)) {
            //     return redirect()->back()->withInput()->with('error', 'Please provide a Refer Username!');
            // }

            // Default refer username
            $defaultReferUsername = 'caplix';

            // If refer_by is provided, use it; otherwise use default
            $referUsername = $request->refer_by ?: $defaultReferUsername;

            $referUser = User::where('username', $referUsername)->first();

            if (!$referUser) {
              return redirect()->back()->withInput()->with('error', 'Please provide a Refer Username!');
            }

            $refer_id = $referUser->id;

            // Validate input
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'username' => ['required', 'string', 'max:255', 'unique:users,username'],
                'password' => ['required', 'confirmed', Password::defaults()],
                'role' => ['required', 'in:investor,entrepreneur'],
                'phone' => ['required', 'digits:11', 'unique:users,phone'],
            ]);

            // Create user
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'role' => $request->role,
                'password' => Hash::make($request->password),
                'status' => 1,
                'phone' => $request->phone,
                'refer_by' => $refer_id,
            ]);

            // ✅ Referral bonus add (50 taka)
            // Income::create([
            //     'user_id' => $referUser->id,
            //     'amount' => 50,
            //     'type' => 'referral_bonus',
            //     'description' => 'Referral bonus for '.$referUser->username,
            // ]);

            // Update referrer balance
            // if (isset($referUser->balance)) {
            //     $referUser->increment('balance', 50);
            // }

            event(new Registered($user));

            Auth::login($user);

            // Redirect based on account type
            if ($user->role === 'investor') {
                return redirect()->route('investor.dashboard')->with('success', 'Welcome Investor!');
            } else {
                return redirect()->route('entrepreneur.dashboard')->with('success', 'Welcome Entrepreneur!');
            }

        } catch (Exception $e) {
            // Log the error
            \Log::error('Registration failed: '.$e->getMessage());

            // Redirect back with error message
            return redirect()->back()->withInput()->with('error', 'Something went wrong! Please try again.');
        }
    }

    public function checkRefer($username){
        $user = User::where('username', $username)->first();

        if($user){
            return response()->json([
                'status' => true,
                'message' => 'Valid Refer Username ✅'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Refer Username ❌'
            ]);
        }
    }
}
