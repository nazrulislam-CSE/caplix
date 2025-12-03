<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BusinessKyc;

class CheckKycVerified
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        
        // শুধুমাত্র entrepreneur ইউজারের জন্য চেক করব
        if ($user && $user->role === 'entrepreneur') {
            // KYC verified আছে কিনা চেক
            $kyc = BusinessKyc::where('user_id', $user->id)->first();
            
            if (!$kyc || $kyc->status !== 'verified') {
                // KYC verified না হলে redirect
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Please complete KYC verification first.'
                    ], 403);
                }
                
                return redirect()->route('entrepreneur.kyc.status')
                    ->with('error', 'Please complete KYC verification to access this feature.');
            }
        }
        
        return $next($request);
    }
}