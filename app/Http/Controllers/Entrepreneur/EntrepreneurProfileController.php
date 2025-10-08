<?php

namespace App\Http\Controllers\Entrepreneur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EntrepreneurProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user(); 
        $pageTitle = 'প্রোফাইল সম্পাদনা';

        return view('entrepreneur.profile.edit', compact('user', 'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'username' => 'required|string|unique:users,username,' . $user->id,
            'phone'    => 'nullable|numeric',
            'address'  => 'nullable|string|max:255',
            'photo'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Update basic info
        $user->update($request->only('name', 'email', 'username', 'phone', 'address'));

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/entrepreneur'), $filename);

            // Delete old photo (optional)
            if ($user->photo && file_exists(public_path('upload/entrepreneur/' . $user->photo))) {
                unlink(public_path('upload/entrepreneur/' . $user->photo));
            }

            $user->photo = $filename;
            $user->save();
        }

        return redirect()->back()->with('success', 'প্রোফাইল সফলভাবে আপডেট হয়েছে!');
    }

    public function changePasswordForm()
    {
        $pageTitle = 'পাসওয়ার্ড পরিবর্তন';
        return view('entrepreneur.profile.change_password', compact('pageTitle'));
    }

    public function updatePassword(Request $request)
    {
        // Validate inputs
        $request->validate([
            'current_password'      => 'required',
            'new_password'          => 'required|string|min:8|confirmed',
            // 'new_password_confirmation' is required by “confirmed”
        ]);

        $user = Auth::user();

        // Check that the current password matches
        if (! Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Current password is incorrect.');
        }

        // Optional: Prevent using the same password
        if (Hash::check($request->new_password, $user->password)) {
            return back()->with('error', 'New password cannot be same as the current password.');
        }

        // Update with hashed new password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password updated successfully!');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
