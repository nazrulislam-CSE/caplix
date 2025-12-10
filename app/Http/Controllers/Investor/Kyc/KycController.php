<?php

namespace App\Http\Controllers\Investor\Kyc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\InvestorKyc;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class KycController extends Controller
{
    // Show KYC form
    public function create()
    {
        $user = Auth::user();
        
        // Check if already has KYC
        if ($user->hasInvestorKyc()) {
            $kyc = $user->investorKyc;
            
            if ($kyc->isVerified()) {
                return redirect()->route('investor.dashboard')
                    ->with('success', 'Your KYC is already verified.');
            }
            
            if ($kyc->isPending()) {
                return redirect()->route('investor.kyc.status')
                    ->with('info', 'Your KYC submission is under review.');
            }
            
            // If draft or rejected, show form with existing data
            return view('investor.kyc.create', [
                'pageTitle' => 'Update KYC Information',
                'kyc' => $kyc
            ]);
        }
        
        return view('investor.kyc.create', [
            'pageTitle' => 'KYC Verification - Investor'
        ]);
    }
    
    // Store KYC data
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'full_name_bn' => 'required|string|max:255',
            'full_name_en' => 'required|string|max:255',
            'nid' => 'required|string|unique:investor_kycs,nid,' . ($request->kyc_id ?? ''),
            'date_of_birth' => 'required|date|before:today',
            'phone' => 'required|string|regex:/^01[3-9]\d{8}$/',
            'email' => 'required|email',
            'permanent_address' => 'required|string|max:500',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_no' => 'nullable|string|max:50',
            'investment_range' => 'nullable|in:<100000,100000-500000,500000-2000000,>2000000',
            'occupation' => 'nullable|string|max:255',
            'nid_front' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'nid_back' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'passport' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'nominees' => 'nullable|array|max:3',
            'nominees.*.name' => 'required_with:nominees|string|max:255',
            'nominees.*.relation' => 'required_with:nominees|string|max:100',
            'nominees.*.nid' => 'required_with:nominees|string|max:50',
            'nominees.*.share_percentage' => 'required_with:nominees|numeric|min:1|max:100',
            'nominees.*.phone' => 'nullable|string|regex:/^01[3-9]\d{8}$/',
            'agree' => 'required|accepted'
        ], [
            'phone.regex' => 'Please enter a valid Bangladeshi mobile number (01XXXXXXXXX)',
            'nid.unique' => 'This NID is already registered with another account',
            'nominees.*.share_percentage.required_with' => 'Share percentage is required for each nominee',
            'nominees.*.share_percentage.numeric' => 'Share percentage must be a number',
            'nominees.*.share_percentage.min' => 'Minimum share percentage is 1%',
            'nominees.*.share_percentage.max' => 'Maximum share percentage is 100%',
            'agree.accepted' => 'You must agree to the terms and conditions'
        ]);
        
        // Validate total share percentage
        if ($request->has('nominees') && is_array($request->nominees)) {
            $totalShare = array_sum(array_column($request->nominees, 'share_percentage'));
            if ($totalShare !== 100) {
                $validator->errors()->add('nominees', 'Total nominee share must be exactly 100%');
            }
        }
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the errors below.');
        }
        
        // Handle file uploads
        $data = $request->except(['nid_front', 'nid_back', 'passport', 'agree', '_token']);
        
        // Upload documents
        $uploadFields = ['nid_front', 'nid_back', 'passport'];
        foreach ($uploadFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $filename = 'kyc_' . $user->id . '_' . $field . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('kyc_documents/investor', $filename, 'public');
                $data[$field] = $path;
            }
        }
        
        // Prepare nominees data
        if ($request->has('nominees')) {
            $data['nominees'] = json_encode($request->nominees);
        }
        
        // Set KYC data
        $data['status'] = 'pending';
        $data['user_id'] = $user->id;
        
        // Start database transaction
        DB::beginTransaction();
        
        try {
            // Update User table information
            $userUpdateData = [];
            
            // Update name in users table (use English name or both)
            if ($request->filled('full_name_en')) {
                $userUpdateData['name'] = $request->full_name_en;
            }
            
            // Update email if different
            if ($request->filled('email') && $request->email !== $user->email) {
                // Check if email already exists for another user
                $existingUser = User::where('email', $request->email)
                    ->where('id', '!=', $user->id)
                    ->first();
                    
                if ($existingUser) {
                    throw ValidationException::withMessages([
                        'email' => 'This email is already registered with another account.'
                    ]);
                }
                $userUpdateData['email'] = $request->email;
            }
            
            // Update phone if different
            if ($request->filled('phone') && $request->phone !== $user->phone) {
                // Check if phone already exists for another user
                $existingUser = User::where('phone', $request->phone)
                    ->where('id', '!=', $user->id)
                    ->first();
                    
                if ($existingUser) {
                    throw ValidationException::withMessages([
                        'phone' => 'This phone number is already registered with another account.'
                    ]);
                }
                $userUpdateData['phone'] = $request->phone;
            }
            
            // Update address (assuming you have an address field in users table)
            // If you don't have, you can add it or store in profile table
            if ($request->filled('permanent_address')) {
                // You can either store in users table or create a separate profile
                // Let's assume you have 'address' field in users table
                if (Schema::hasColumn('users', 'address')) {
                    $userUpdateData['address'] = $request->permanent_address;
                }
            }
            
            // Set user type to investor if not already
            if ($user->role !== 'investor') {
                $userUpdateData['role'] = 'investor';
            }
            
            // Update user if there are changes
            if (!empty($userUpdateData)) {
                $user->update($userUpdateData);
            }
            
            // Check if updating existing KYC
            if ($request->kyc_id) {
                $kyc = InvestorKyc::where('id', $request->kyc_id)
                    ->where('user_id', $user->id)
                    ->firstOrFail();
                    
                // Delete old files if new ones uploaded
                foreach ($uploadFields as $field) {
                    if ($request->hasFile($field) && $kyc->$field) {
                        Storage::disk('public')->delete($kyc->$field);
                    }
                }
                
                $kyc->update($data);
                
                $message = 'KYC information updated successfully. Under review.';
            } else {
                // Create new KYC
                InvestorKyc::create($data);
                $message = 'KYC submitted successfully. We will review your information soon.';
            }
            
            
            DB::commit();
            
            return redirect()->route('investor.kyc.status')
                ->with('success', $message);
                
        } catch (ValidationException $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', $e->getMessage());
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('KYC Submission Error: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while submitting KYC. Please try again.');
        }
    }
    
    // Show KYC status
    public function status()
    {
        $user = Auth::user();
        
        if (!$user->hasInvestorKyc()) {
            return redirect()->route('investor.kyc.create')
                ->with('info', 'Please submit your KYC information first.');
        }
        
        $kyc = $user->investorKyc;
        
        // Get timeline/audit trail if available
        $timeline = $this->getKycTimeline($kyc);
        
        return view('investor.kyc.status', [
            'pageTitle' => 'KYC Verification Status',
            'kyc' => $kyc,
            'timeline' => $timeline,
            'user' => $user
        ]);
    }

    // Get KYC timeline/audit trail
    private function getKycTimeline($kyc)
    {
        $timeline = [];
        
        // Created
        $timeline[] = [
            'date' => $kyc->created_at,
            'title' => 'KYC Submitted',
            'description' => 'Your KYC application was submitted successfully.',
            'status' => 'completed',
            'icon' => 'fas fa-paper-plane'
        ];
        
        // If pending review
        if ($kyc->status === 'pending' || $kyc->status === 'under_review') {
            $timeline[] = [
                'date' => $kyc->updated_at,
                'title' => 'Under Review',
                'description' => 'Your application is being reviewed by our verification team.',
                'status' => 'current',
                'icon' => 'fas fa-search'
            ];
            
            $timeline[] = [
                'date' => null,
                'title' => 'Verification',
                'description' => 'Your documents will be verified.',
                'status' => 'pending',
                'icon' => 'fas fa-user-check'
            ];
        }
        
        // If verified
        if ($kyc->status === 'verified') {
            $timeline[] = [
                'date' => $kyc->updated_at,
                'title' => 'Under Review',
                'description' => 'Your application was reviewed.',
                'status' => 'completed',
                'icon' => 'fas fa-search'
            ];
            
            $timeline[] = [
                'date' => $kyc->verified_at,
                'title' => 'Verified Successfully',
                'description' => 'Your KYC has been verified and approved.',
                'status' => 'completed',
                'icon' => 'fas fa-check-circle'
            ];
        }
        
        // If rejected
        if ($kyc->status === 'rejected') {
            $timeline[] = [
                'date' => $kyc->updated_at,
                'title' => 'Review Completed',
                'description' => 'Your application was reviewed.',
                'status' => 'completed',
                'icon' => 'fas fa-search'
            ];
            
            $timeline[] = [
                'date' => $kyc->updated_at,
                'title' => 'Rejected',
                'description' => 'Your KYC application was rejected.',
                'status' => 'rejected',
                'icon' => 'fas fa-times-circle'
            ];
        }
        
        // If draft
        if ($kyc->status === 'draft') {
            $timeline[] = [
                'date' => $kyc->updated_at,
                'title' => 'Draft Saved',
                'description' => 'Your KYC information is saved as draft.',
                'status' => 'current',
                'icon' => 'fas fa-save'
            ];
            
            $timeline[] = [
                'date' => null,
                'title' => 'Submit for Review',
                'description' => 'Submit your KYC for verification.',
                'status' => 'pending',
                'icon' => 'fas fa-paper-plane'
            ];
        }
        
        return $timeline;
    }
    
    // Send OTP
    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|regex:/^01[3-9]\d{8}$/'
        ]);
        
        // Generate OTP
        $otp = rand(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(10);
        
        // In real application, send OTP via SMS gateway
        // SMS::send($request->phone, "Your OTP is: $otp");
        
        // For now, just store in session
        session(['kyc_otp' => $otp, 'kyc_otp_expires' => $expiresAt, 'kyc_phone' => $request->phone]);
        
        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully',
            'otp' => $otp // Remove in production
        ]);
    }
    
    // Verify OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:6',
            'phone' => 'required'
        ]);
        
        $storedOtp = session('kyc_otp');
        $expiresAt = session('kyc_otp_expires');
        $storedPhone = session('kyc_phone');
        
        if (!$storedOtp || Carbon::now()->gt($expiresAt)) {
            return response()->json([
                'success' => false,
                'message' => 'OTP expired. Please request a new one.'
            ]);
        }
        
        if ($storedPhone !== $request->phone) {
            return response()->json([
                'success' => false,
                'message' => 'Phone number mismatch.'
            ]);
        }
        
        if ($storedOtp != $request->otp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP. Please try again.'
            ]);
        }
        
        // Clear OTP session
        session()->forget(['kyc_otp', 'kyc_otp_expires', 'kyc_phone']);
        
        // Update phone verification status if KYC exists
        $user = Auth::user();
        if ($user->hasInvestorKyc()) {
            $user->investorKyc->update([
                'owner_verified' => true,
                'otp' => null,
                'otp_expires_at' => null
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Phone number verified successfully!'
        ]);
    }
    
    // Download document
    public function downloadDocument($field, $id)
    {
        $kyc = InvestorKyc::findOrFail($id);
        
        if (!$kyc->$field || !Storage::disk('public')->exists($kyc->$field)) {
            abort(404);
        }
        
        return Storage::disk('public')->download($kyc->$field);
    }
    
    // Preview KYC
    public function preview()
    {
        $user = Auth::user();
        
        if (!$user->hasInvestorKyc()) {
            return redirect()->route('investor.kyc.create');
        }
        
        $kyc = $user->investorKyc;
        
        return view('investor.kyc.preview', [
            'pageTitle' => 'Preview KYC',
            'kyc' => $kyc
        ]);
    }
}