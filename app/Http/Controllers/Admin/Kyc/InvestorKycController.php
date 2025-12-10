<?php

namespace App\Http\Controllers\Admin\kyc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InvestorKyc;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class InvestorKycController extends Controller
{
    public function index(Request $request)
    {
        $pageTitle = 'Investor KYC Verification';
        
        // Get filter parameters
        $status = $request->input('status', 'all');
        $search = $request->input('search');
        
        // Start query
        $query = InvestorKyc::with('user');
        
        // Apply status filter
        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }
        
        // Apply search
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('full_name_en', 'LIKE', "%{$search}%")
                  ->orWhere('full_name_bn', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('nid', 'LIKE', "%{$search}%")
                  ->orWhere('occupation', 'LIKE', "%{$search}%");
            });
        }
        
        // Get counts
        $totalInvestors = InvestorKyc::count();
        $pendingCount = InvestorKyc::where('status', 'pending')->count();
        $verifiedCount = InvestorKyc::where('status', 'verified')->count();
        $rejectedCount = InvestorKyc::where('status', 'rejected')->count();
        
        // Get data
        $investorKycs = $query->latest()->paginate(20);
        
        return view('admin.kyc.investor.index', compact(
            'pageTitle',
            'investorKycs',
            'totalInvestors',
            'pendingCount',
            'verifiedCount',
            'rejectedCount',
            'status',
            'search'
        ));
    }
    
    public function show($id)
    {
        $kyc = InvestorKyc::with('user')->findOrFail($id);
        $pageTitle = 'Investor KYC Details';
        
        // Parse nominees
        $nominees = $kyc->nominees ? json_decode($kyc->nominees, true) : [];
        
        return view('admin.kyc.investor.show', compact(
            'pageTitle',
            'kyc',
            'nominees'
        ));
    }
    
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,under_review,verified,rejected',
            'rejection_reason' => 'required_if:status,rejected|nullable|string|max:1000',
            'admin_notes' => 'nullable|string|max:2000'
        ]);
        
        $kyc = InvestorKyc::findOrFail($id);
        
        DB::beginTransaction();
        
        try {
            $oldStatus = $kyc->status;
            $newStatus = $request->status;
            
            $kyc->status = $newStatus;
            
            if ($newStatus === 'rejected') {
                $kyc->rejection_reason = $request->rejection_reason;
                $kyc->verified_at = null;
                
            } elseif ($newStatus === 'verified') {
                $kyc->verified_at = now();
                $kyc->rejection_reason = null;
            
    
            } else {
                $kyc->rejection_reason = null;
                $kyc->verified_at = null;
            
            }
            
            // Update admin notes if provided
            if ($request->has('admin_notes')) {
                $kyc->admin_notes = $request->admin_notes;
            }
            
            $kyc->save();
            
            // Log the status change
            \Log::info('Investor KYC Status Updated', [
                'investor_kyc_id' => $kyc->id,
                'user_id' => $kyc->user_id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'admin_id' => auth()->id(),
                'rejection_reason' => $request->rejection_reason
            ]);
            
            DB::commit();
            
            return redirect()->back()
                ->with('success', 'Investor KYC status updated successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Failed to update status: ' . $e->getMessage());
        }
    }
    
    public function downloadDocument($id, $field)
    {
        $allowedFields = ['nid_front', 'nid_back', 'passport'];
        
        if (!in_array($field, $allowedFields)) {
            abort(404);
        }
        
        $kyc = InvestorKyc::findOrFail($id);
        
        if (!$kyc->$field || !Storage::disk('public')->exists($kyc->$field)) {
            abort(404);
        }
        
        return Storage::disk('public')->download($kyc->$field);
    }
    
    public function destroy($id)
    {
        $kyc = InvestorKyc::findOrFail($id);
        
        // Delete uploaded files
        $files = ['nid_front', 'nid_back', 'passport'];
        foreach ($files as $file) {
            if ($kyc->$file && Storage::disk('public')->exists($kyc->$file)) {
                Storage::disk('public')->delete($kyc->$file);
            }
        }
        
        $kyc->delete();
        
        return redirect()->route('admin.investor-kyc.index')
            ->with('success', 'Investor KYC deleted successfully.');
    }

    // Admin/InvestorKycController.php তে

/**
 * Show the form for editing investor KYC
 */
    public function edit($id)
    {
        $kyc = InvestorKyc::with('user')->findOrFail($id);
        
        // Parse nominees if exists
        $nominees = $kyc->nominees ? json_decode($kyc->nominees, true) : [];
        
        $pageTitle = 'Edit Investor KYC';
        
        return view('admin.kyc.investor.edit', compact(
            'pageTitle',
            'kyc',
            'nominees'
        ));
    }

    /**
     * Update investor KYC information
     */
    public function update(Request $request, $id)
    {
        $kyc = InvestorKyc::with('user')->findOrFail($id);
        
        // Validation rules
        $validator = Validator::make($request->all(), [
            'full_name_bn' => 'required|string|max:255',
            'full_name_en' => 'required|string|max:255',
            'nid' => 'required|string|unique:investor_kycs,nid,' . $kyc->id,
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
            'status' => 'required|in:draft,pending,under_review,verified,rejected',
            'rejection_reason' => 'required_if:status,rejected|nullable|string|max:1000',
            'admin_notes' => 'nullable|string|max:2000',
            'owner_verified' => 'boolean'
        ], [
            'phone.regex' => 'Please enter a valid Bangladeshi mobile number (01XXXXXXXXX)',
            'nid.unique' => 'This NID is already registered with another account',
            'nominees.*.share_percentage.required_with' => 'Share percentage is required for each nominee',
            'nominees.*.share_percentage.numeric' => 'Share percentage must be a number',
            'nominees.*.share_percentage.min' => 'Minimum share percentage is 1%',
            'nominees.*.share_percentage.max' => 'Maximum share percentage is 100%'
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
                ->with('error', 'Please fix the validation errors.');
        }
        
        DB::beginTransaction();
        
        try {
            $oldStatus = $kyc->status;
            $newStatus = $request->status;
            
            // Prepare update data
            $updateData = $request->only([
                'full_name_bn',
                'full_name_en', 
                'nid',
                'date_of_birth',
                'phone',
                'email',
                'permanent_address',
                'bank_name',
                'bank_account_no',
                'investment_range',
                'occupation',
                'status',
                'rejection_reason',
                'admin_notes',
                'owner_verified'
            ]);
            
            // Handle file uploads
            $uploadFields = ['nid_front', 'nid_back', 'passport'];
            foreach ($uploadFields as $field) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $filename = 'kyc_' . $kyc->user_id . '_' . $field . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('kyc_documents/investor', $filename, 'public');
                    
                    // Delete old file if exists
                    if ($kyc->$field && Storage::disk('public')->exists($kyc->$field)) {
                        Storage::disk('public')->delete($kyc->$field);
                    }
                    
                    $updateData[$field] = $path;
                }
            }
            
            // Handle nominees
            if ($request->has('nominees')) {
                $updateData['nominees'] = json_encode($request->nominees);
            }
            
            // Handle verified_at timestamp
            if ($newStatus === 'verified' && $oldStatus !== 'verified') {
                $updateData['verified_at'] = now();
                
                // Update user's kyc status
                if ($kyc->user) {
                    $kyc->user->update([
                        'kyc_verified' => true,
                        'kyc_verified_at' => now(),
                        'kyc_type' => 'personal'
                    ]);
                }
            } elseif ($newStatus !== 'verified' && $oldStatus === 'verified') {
                $updateData['verified_at'] = null;
                
                // Update user's kyc status
                if ($kyc->user) {
                    $kyc->user->update([
                        'kyc_verified' => false,
                        'kyc_verified_at' => null
                    ]);
                }
            }
            
            // Update KYC
            $kyc->update($updateData);
            
            // Log the update
            \Log::info('Investor KYC Updated', [
                'investor_kyc_id' => $kyc->id,
                'user_id' => $kyc->user_id,
                'admin_id' => auth()->id(),
                'updated_fields' => array_keys($updateData),
                'old_status' => $oldStatus,
                'new_status' => $newStatus
            ]);
            
            // Send notification if status changed
            if ($oldStatus !== $newStatus) {
                $this->sendStatusNotification($kyc, $oldStatus, $newStatus);
            }
            
            DB::commit();
            
            return redirect()->route('admin.investor-kyc.show', $kyc->id)
                ->with('success', 'Investor KYC updated successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Failed to update Investor KYC: ' . $e->getMessage(), [
                'investor_kyc_id' => $id,
                'admin_id' => auth()->id()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update KYC: ' . $e->getMessage());
        }
    }
}