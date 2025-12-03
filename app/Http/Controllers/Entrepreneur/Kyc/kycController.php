<?php

namespace App\Http\Controllers\Entrepreneur\Kyc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BusinessKyc;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

class KycController extends Controller
{
    // Show KYC form
    public function create()
    {
        $pageTitle = 'KYC / Business Verification';
        
        // Check if user already has a KYC submission
        $existingKyc = BusinessKyc::where('user_id', Auth::id())->first();
        
        if ($existingKyc) {
            return redirect()->route('entrepreneur.kyc.status')
                ->with('info', 'You have already submitted your KYC application.');
        }
        
        return view('entrepreneur.kyc.create', compact('pageTitle'));
    }
    
    // Store KYC data
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'compName' => 'required|string|max:255',
            'regNo' => 'nullable|string|max:100',
            'tradeLicense' => 'nullable|string|max:100',
            'businessType' => 'nullable|string|max:50',
            'tin' => 'nullable|string|max:50',
            'estYear' => 'nullable|integer|min:1900|max:' . date('Y'),
            'employees' => 'nullable|integer|min:0',
            'turnover' => 'nullable|numeric|min:0',
            'businessAddress' => 'nullable|string|max:500',
            'website' => 'nullable|url|max:255',
            
            'ownerName' => 'required|string|max:255',
            'ownerPhone' => 'required|string|max:20',
            'ownerEmail' => 'required|email|max:255',
            'ownerNid' => 'required|string|max:50',
            'ownerRole' => 'nullable|string|max:100',
            
            // Nominee
            'nomNameBiz' => 'nullable|string|max:255',
            'nomRelationBiz' => 'nullable|string|max:100',
            'nomNidBiz' => 'nullable|string|max:50',
            
            // Documents
            'docReg' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240',
            'docTrade' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240',
            'docTin' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
            'docBank' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
            'docFin' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
            
            // OTP Verification
            'ownerOtp' => 'required|string|size:6',
            
            // Agreement
            'agreeBiz' => 'required|accepted',
        ]);
        
        // Custom validation for shareholders
        $validator->after(function ($validator) use ($request) {
            $shareholders = $request->input('shareholder_name', []);
            $totalShare = 0;
            
            foreach ($shareholders as $index => $name) {
                $nid = $request->input('shareholder_nid.' . $index, '');
                $share = $request->input('shareholder_share.' . $index, 0);
                
                if (!empty($name) || !empty($nid) || $share > 0) {
                    if (empty($name)) {
                        $validator->errors()->add('shareholder_name.' . $index, 'Shareholder name is required');
                    }
                    if (empty($nid)) {
                        $validator->errors()->add('shareholder_nid.' . $index, 'Shareholder NID is required');
                    }
                    if ($share <= 0) {
                        $validator->errors()->add('shareholder_share.' . $index, 'Share percentage must be greater than 0');
                    }
                    
                    $totalShare += floatval($share);
                }
            }
            
            if ($totalShare > 0 && abs($totalShare - 100) > 0.01) {
                $validator->errors()->add('shareholders_total', 'Total share percentage must equal 100%');
            }
        });
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Check OTP verification
        // if (!$this->verifyOtp($request->ownerPhone, $request->ownerOtp)) {
        //     return redirect()->back()
        //         ->with('error', 'Invalid or expired OTP')
        //         ->withInput();
        // }
        
        try {
            // Upload documents
            $docPaths = $this->uploadDocuments($request);
            
            // Prepare shareholders data
            $shareholders = [];
            $shareholderNames = $request->input('shareholder_name', []);
            
            foreach ($shareholderNames as $index => $name) {
                if (!empty($name)) {
                    $shareholders[] = [
                        'name' => $name,
                        'nid' => $request->input('shareholder_nid.' . $index, ''),
                        'share' => floatval($request->input('shareholder_share.' . $index, 0))
                    ];
                }
            }
            
            // Create KYC record
            $kyc = BusinessKyc::create([
                'user_id' => Auth::id(),
                'company_name' => $request->compName,
                'registration_no' => $request->regNo,
                'trade_license_no' => $request->tradeLicense,
                'business_type' => $request->businessType,
                'tin_bin' => $request->tin,
                'establishment_year' => $request->estYear,
                'number_of_employees' => $request->employees,
                'last_turnover' => $request->turnover,
                'business_address' => $request->businessAddress,
                'website' => $request->website,
                
                'owner_name' => $request->ownerName,
                'owner_phone' => $request->ownerPhone,
                'owner_email' => $request->ownerEmail,
                'owner_nid_passport' => $request->ownerNid,
                'owner_role' => $request->ownerRole,
                
                'shareholders' => $shareholders,
                
                // Document paths
                'doc_registration' => $docPaths['docReg'],
                'doc_trade_license' => $docPaths['docTrade'],
                'doc_tin' => $docPaths['docTin'] ?? null,
                'doc_bank_statement' => $docPaths['docBank'] ?? null,
                'doc_financials' => $docPaths['docFin'] ?? null,
                
                'nominee_name' => $request->nomNameBiz,
                'nominee_relation' => $request->nomRelationBiz,
                'nominee_nid' => $request->nomNidBiz,
                
                'owner_verified' => true,
                'status' => 'pending',
            ]);
            
            return redirect()->route('entrepreneur.kyc.status')
                ->with('success', 'KYC submitted successfully!');
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error submitting KYC: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    // Send OTP to owner (separate method for AJAX-like behavior)
    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:20'
        ]);
        
        $otp = rand(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(10);
        
        // Store in session
        session([
            'kyc_owner_otp' => $otp,
            'kyc_owner_phone' => $request->phone,
            'kyc_otp_expires' => $expiresAt
        ]);
        
        // Log OTP for testing
        \Log::info("KYC OTP for {$request->phone}: {$otp}");
        
        // If it's an AJAX request (from JavaScript)
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'OTP sent successfully',
                'otp' => $otp // For testing only
            ]);
        }
        
        // For regular form submission
        return back()->with('otp_sent', true)->with('otp_message', 'OTP sent successfully');
    }
    
    // Verify OTP method (internal)
    private function verifyOtp($phone, $otp)
    {
        $storedOtp = session('kyc_owner_otp');
        $storedPhone = session('kyc_owner_phone');
        $expiresAt = session('kyc_otp_expires');
        
        if (!$storedOtp || !$storedPhone || !$expiresAt) {
            return false;
        }
        
        if (Carbon::now()->gt($expiresAt)) {
            session()->forget(['kyc_owner_otp', 'kyc_owner_phone', 'kyc_otp_expires']);
            return false;
        }
        
        return $storedPhone === $phone && $storedOtp == $otp;
    }
    
    // Upload documents
    private function uploadDocuments($request)
    {
        $paths = [];
        $userId = Auth::id();
        
        $documents = [
            'docReg' => 'doc_registration',
            'docTrade' => 'doc_trade_license',
            'docTin' => 'doc_tin',
            'docBank' => 'doc_bank_statement',
            'docFin' => 'doc_financials',
        ];
        
        foreach ($documents as $field => $folder) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $extension = $file->getClientOriginalExtension();
                $fileName = $folder . '_' . time() . '_' . Str::random(10) . '.' . $extension;
                
                $path = $file->storeAs("kyc/{$userId}/{$folder}", $fileName, 'public');
                $paths[$field] = $path;
            }
        }
        
        return $paths;
    }
    
    // Show KYC status
    public function status()
    {
        $pageTitle = 'KYC Status';
        $kyc = BusinessKyc::where('user_id', Auth::id())->firstOrFail();
        
        return view('entrepreneur.kyc.status', compact('pageTitle', 'kyc'));
    }
}