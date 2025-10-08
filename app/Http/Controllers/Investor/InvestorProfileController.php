<?php

namespace App\Http\Controllers\Investor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class InvestorProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user(); 
        $pageTitle = 'প্রোফাইল সম্পাদনা';

        return view('investor.profile.edit', compact('user', 'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
            $file->move(public_path('upload/investor'), $filename);

            // Delete old photo (optional)
            if ($user->photo && file_exists(public_path('upload/investor/' . $user->photo))) {
                unlink(public_path('upload/investor/' . $user->photo));
            }

            $user->photo = $filename;
            $user->save();
        }

        return redirect()->back()->with('success', 'প্রোফাইল সফলভাবে আপডেট হয়েছে!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
