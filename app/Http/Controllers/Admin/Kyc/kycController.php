<?php

namespace App\Http\Controllers\Admin\Kyc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BusinessKyc;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class KycController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pageTitle = 'KYC / Business Verification List';
        
        // Search and filter
        $query = BusinessKyc::with('user')
            ->when($request->status, function($q) use ($request) {
                return $q->where('status', $request->status);
            })
            ->when($request->search, function($q) use ($request) {
                return $q->where(function($query) use ($request) {
                    $query->where('company_name', 'like', '%' . $request->search . '%')
                          ->orWhere('owner_name', 'like', '%' . $request->search . '%')
                          ->orWhere('owner_phone', 'like', '%' . $request->search . '%')
                          ->orWhere('owner_email', 'like', '%' . $request->search . '%')
                          ->orWhereHas('user', function($userQuery) use ($request) {
                              $userQuery->where('name', 'like', '%' . $request->search . '%')
                                        ->orWhere('email', 'like', '%' . $request->search . '%');
                          });
                });
            })
            ->latest();
        
        $kycs = $query->paginate(20);

        // Counts for stats
        $totalCount = BusinessKyc::count();
        $pendingCount = BusinessKyc::where('status', 'pending')->count();
        $verifiedCount = BusinessKyc::where('status', 'verified')->count();
        $rejectedCount = BusinessKyc::where('status', 'rejected')->count();
        
        $statuses = [
            'pending' => 'Pending',
            'under_review' => 'Under Review',
            'verified' => 'Verified',
            'rejected' => 'Rejected'
        ];
        
        return view('admin.kyc.index', compact('pageTitle', 'kycs', 'statuses', 'totalCount','pendingCount','verifiedCount','rejectedCount'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $kyc = BusinessKyc::with('user')->findOrFail($id);
        
        return view('admin.kyc.show', compact('kyc'));
    }

    /**
     * Show KYC details in modal
     */
    public function details($id)
    {
        $kyc = BusinessKyc::with('user')->findOrFail($id);
        
        return view('admin.kyc.partials.details-modal', compact('kyc'))->render();
    }

    /**
     * Show form for editing KYC
     */
    public function edit(string $id)
    {
        $kyc = BusinessKyc::with('user')->findOrFail($id);
        $statuses = [
            'pending' => 'Pending',
            'under_review' => 'Under Review',
            'verified' => 'Verified',
            'rejected' => 'Rejected'
        ];
        
        return view('admin.kyc.edit', compact('kyc', 'statuses'));
    }

    /**
     * Update KYC status and information
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:pending,under_review,verified,rejected',
            'rejection_reason' => 'required_if:status,rejected|nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);
        
        $kyc = BusinessKyc::findOrFail($id);
        
        $oldStatus = $kyc->status;
        $newStatus = $request->status;
        
        $updateData = [
            'status' => $newStatus,
            'rejection_reason' => $request->rejection_reason,
            'notes' => $request->notes,
        ];
        
        // If verified, set verified_at timestamp
        if ($newStatus == 'verified' && $oldStatus != 'verified') {
            $updateData['verified_at'] = now();
            
            // Update user's KYC verified status if needed
            $user = User::find($kyc->user_id);
            if ($user) {
                $user->update(['status' => true]);
            }
        }
        
        // If moving from verified to other status
        if ($oldStatus == 'verified' && $newStatus != 'verified') {
            $updateData['verified_at'] = null;
            
            // Update user's KYC verified status
            $user = User::find($kyc->user_id);
            if ($user) {
                $user->update(['status' => false]);
            }
        }
        
        $kyc->update($updateData);
        
        // Log the status change
        // activity()
        //     ->performedOn($kyc)
        //     ->causedBy(auth()->user())
        //     ->withProperties([
        //         'old_status' => $oldStatus,
        //         'new_status' => $newStatus,
        //         'reason' => $request->rejection_reason
        //     ])
        //     ->log('KYC status updated');
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'KYC status updated successfully!',
                'status' => $newStatus,
                'status_text' => ucfirst(str_replace('_', ' ', $newStatus))
            ]);
        }
        
        return redirect()->route('admin.kyc.index')
            ->with('success', 'KYC status updated successfully!');
    }

    /**
     * Quick status update via modal
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,under_review,verified,rejected',
            'rejection_reason' => 'required_if:status,rejected|nullable|string|max:500',
        ]);
        
        $kyc = BusinessKyc::findOrFail($id);
        $oldStatus = $kyc->status;
        
        $kyc->update([
            'status' => $request->status,
            'rejection_reason' => $request->rejection_reason,
            'verified_at' => $request->status == 'verified' ? now() : null,
        ]);
        
        // Update user's KYC status
        $user = User::find($kyc->user_id);
        if ($user) {
            $user->update(['status' => $request->status == 'verified']);
        }
        
        // Log activity
        // activity()
        //     ->performedOn($kyc)
        //     ->causedBy(auth()->user())
        //     ->log('KYC status changed from ' . $oldStatus . ' to ' . $request->status);
        
        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully!',
            'status' => $request->status,
            'status_badge' => $this->getStatusBadge($request->status)
        ]);
    }

    /**
     * Delete KYC (Soft delete or permanent)
     */
    public function destroy(string $id)
    {
        $kyc = BusinessKyc::findOrFail($id);
        
        // Optional: Delete uploaded files
        // $this->deleteKycFiles($kyc);
        
        $kyc->delete();
        
        return redirect()->route('admin.kyc.index')
            ->with('success', 'KYC record deleted successfully!');
    }

    /**
     * Download KYC document
     */
    public function downloadDocument($id, $documentType)
    {
        $kyc = BusinessKyc::findOrFail($id);
        
        $documentField = 'doc_' . $documentType;
        
        if (!isset($kyc->$documentField) || empty($kyc->$documentField)) {
            abort(404, 'Document not found');
        }
        
        $path = storage_path('app/public/' . $kyc->$documentField);
        
        if (!file_exists($path)) {
            abort(404, 'File not found');
        }
        
        $fileName = 'kyc_' . $documentType . '_' . $kyc->company_name . '.' . pathinfo($path, PATHINFO_EXTENSION);
        
        return response()->download($path, $fileName);
    }

    /**
     * Get status badge HTML
     */
    private function getStatusBadge($status)
    {
        $badges = [
            'pending' => '<span class="badge bg-warning">Pending</span>',
            'under_review' => '<span class="badge bg-info">Under Review</span>',
            'verified' => '<span class="badge bg-success">Verified</span>',
            'rejected' => '<span class="badge bg-danger">Rejected</span>',
        ];
        
        return $badges[$status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }

    /**
     * Delete KYC files from storage
     */
    private function deleteKycFiles($kyc)
    {
        $documents = [
            'doc_registration',
            'doc_trade_license',
            'doc_tin',
            'doc_bank_statement',
            'doc_financials',
        ];
        
        foreach ($documents as $document) {
            if ($kyc->$document && Storage::disk('public')->exists($kyc->$document)) {
                Storage::disk('public')->delete($kyc->$document);
            }
        }
    }
}