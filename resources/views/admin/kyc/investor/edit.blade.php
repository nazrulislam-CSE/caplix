@extends('layouts.admin', ['pageTitle' => $pageTitle])
@section('content')
    <!-- Breadcrumb -->
    <div class="breadcrumb-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">{{ $pageTitle }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.investor-kyc.index') }}">Investor KYC</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.investor-kyc.show', $kyc->id) }}">View</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.investor-kyc.show', $kyc->id) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Details
            </a>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="card shadow border-0">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Edit Investor KYC Information</h6>
            <span class="badge bg-{{ $kyc->status == 'verified' ? 'success' : ($kyc->status == 'rejected' ? 'danger' : ($kyc->status == 'pending' ? 'warning' : 'info')) }}">
                {{ ucfirst($kyc->status) }}
            </span>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.investor-kyc.update', $kyc->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row g-3 mb-4">
                    <!-- Personal Information -->
                    <div class="col-md-6">
                        <h6 class="border-bottom pb-2 mb-3">Personal Information</h6>
                        
                        <div class="mb-3">
                            <label class="form-label">Full Name (Bangla) *</label>
                            <input type="text" class="form-control" name="full_name_bn" 
                                   value="{{ old('full_name_bn', $kyc->full_name_bn) }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Full Name (English) *</label>
                            <input type="text" class="form-control" name="full_name_en" 
                                   value="{{ old('full_name_en', $kyc->full_name_en) }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">NID Number *</label>
                            <input type="text" class="form-control" name="nid" 
                                   value="{{ old('nid', $kyc->nid) }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Date of Birth *</label>
                            <input type="date" class="form-control" name="date_of_birth" 
                                   value="{{ old('date_of_birth', $kyc->date_of_birth->format('Y-m-d')) }}" required max="{{ date('Y-m-d') }}">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Phone Number *</label>
                            <input type="tel" class="form-control" name="phone" 
                                   value="{{ old('phone', $kyc->phone) }}" required pattern="01[3-9]\d{8}">
                            <small class="text-muted">Format: 01XXXXXXXXX</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Email Address *</label>
                            <input type="email" class="form-control" name="email" 
                                   value="{{ old('email', $kyc->email) }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Occupation</label>
                            <input type="text" class="form-control" name="occupation" 
                                   value="{{ old('occupation', $kyc->occupation) }}">
                        </div>
                    </div>
                    
                    <!-- Address & Bank Details -->
                    <div class="col-md-6">
                        <h6 class="border-bottom pb-2 mb-3">Address & Bank Details</h6>
                        
                        <div class="mb-3">
                            <label class="form-label">Permanent Address *</label>
                            <textarea class="form-control" name="permanent_address" rows="4" required>{{ old('permanent_address', $kyc->permanent_address) }}</textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Bank Name</label>
                            <input type="text" class="form-control" name="bank_name" 
                                   value="{{ old('bank_name', $kyc->bank_name) }}">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Bank Account Number</label>
                            <input type="text" class="form-control" name="bank_account_no" 
                                   value="{{ old('bank_account_no', $kyc->bank_account_no) }}">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Investment Range</label>
                            <select class="form-select" name="investment_range">
                                <option value="">Select Range</option>
                                <option value="<100000" {{ old('investment_range', $kyc->investment_range) == '<100000' ? 'selected' : '' }}>Below 100,000 BDT</option>
                                <option value="100000-500000" {{ old('investment_range', $kyc->investment_range) == '100000-500000' ? 'selected' : '' }}>100,000 - 500,000 BDT</option>
                                <option value="500000-2000000" {{ old('investment_range', $kyc->investment_range) == '500000-2000000' ? 'selected' : '' }}>500,000 - 2,000,000 BDT</option>
                                <option value=">2000000" {{ old('investment_range', $kyc->investment_range) == '>2000000' ? 'selected' : '' }}>Above 2,000,000 BDT</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Phone Verification Status</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="owner_verified" value="1" 
                                       id="owner_verified" {{ old('owner_verified', $kyc->owner_verified) ? 'checked' : '' }}>
                                <label class="form-check-label" for="owner_verified">
                                    Phone Number Verified
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Documents Section -->
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <h6 class="border-bottom pb-2 mb-3">Documents</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="card border">
                                    <div class="card-body">
                                        <h6 class="card-title">NID Front</h6>
                                        @if($kyc->nid_front)
                                            <div class="mb-2">
                                                <a href="{{ route('admin.investor-kyc.download', ['id' => $kyc->id, 'field' => 'nid_front']) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-download me-1"></i> Current File
                                                </a>
                                            </div>
                                        @endif
                                        <input type="file" class="form-control" name="nid_front" accept="image/*,.pdf">
                                        <small class="text-muted">Max 5MB, JPG/PNG/PDF</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="card border">
                                    <div class="card-body">
                                        <h6 class="card-title">NID Back</h6>
                                        @if($kyc->nid_back)
                                            <div class="mb-2">
                                                <a href="{{ route('admin.investor-kyc.download', ['id' => $kyc->id, 'field' => 'nid_back']) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-download me-1"></i> Current File
                                                </a>
                                            </div>
                                        @endif
                                        <input type="file" class="form-control" name="nid_back" accept="image/*,.pdf">
                                        <small class="text-muted">Max 5MB, JPG/PNG/PDF</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="card border">
                                    <div class="card-body">
                                        <h6 class="card-title">Passport (Optional)</h6>
                                        @if($kyc->passport)
                                            <div class="mb-2">
                                                <a href="{{ route('admin.investor-kyc.download', ['id' => $kyc->id, 'field' => 'passport']) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-download me-1"></i> Current File
                                                </a>
                                            </div>
                                        @endif
                                        <input type="file" class="form-control" name="passport" accept="image/*,.pdf">
                                        <small class="text-muted">Max 5MB, JPG/PNG/PDF</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Nominees Section -->
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0">Nominees</h6>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="addNominee()">
                                <i class="fas fa-plus me-1"></i> Add Nominee
                            </button>
                        </div>
                        
                        <div id="nominees-container">
                            @php
                                $nomineeCount = count($nominees);
                            @endphp
                            
                            @if($nomineeCount > 0)
                                @foreach($nominees as $index => $nominee)
                                    <div class="nominee-card mb-3 p-3 border rounded">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="mb-0">Nominee #{{ $index + 1 }}</h6>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeNominee(this)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-md-3">
                                                <label class="form-label">Name *</label>
                                                <input type="text" class="form-control" name="nominees[{{ $index }}][name]" 
                                                       value="{{ $nominee['name'] }}" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Relation *</label>
                                                <input type="text" class="form-control" name="nominees[{{ $index }}][relation]" 
                                                       value="{{ $nominee['relation'] }}" placeholder="Brother/Son" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">NID *</label>
                                                <input type="text" class="form-control" name="nominees[{{ $index }}][nid]" 
                                                       value="{{ $nominee['nid'] }}" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Share % *</label>
                                                <input type="number" class="form-control share-percentage" 
                                                       name="nominees[{{ $index }}][share_percentage]" 
                                                       value="{{ $nominee['share_percentage'] }}" min="1" max="100" step="1" required oninput="updateTotalShare()">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Phone</label>
                                                <input type="tel" class="form-control" name="nominees[{{ $index }}][phone]" 
                                                       value="{{ $nominee['phone'] ?? '' }}" pattern="01[3-9]\d{8}">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        
                        <div class="mt-2">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Total nominee shares must equal 100%. Max 3 nominees allowed.
                            </small>
                        </div>
                        <div class="mt-2">
                            <strong>Total Share: <span id="total-share">{{ collect($nominees)->sum('share_percentage') }}</span>%</strong>
                        </div>
                        <div id="share-error" class="alert alert-danger mt-2 d-none">
                            Total nominee share must be exactly 100%.
                        </div>
                    </div>
                </div>
                
                <!-- Admin Section -->
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <h6 class="border-bottom pb-2 mb-3">Admin Controls</h6>
                        
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">KYC Status *</label>
                                <select name="status" class="form-select" required onchange="toggleRejectionReason(this.value)">
                                    <option value="draft" {{ old('status', $kyc->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="pending" {{ old('status', $kyc->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="under_review" {{ old('status', $kyc->status) == 'under_review' ? 'selected' : '' }}>Under Review</option>
                                    <option value="verified" {{ old('status', $kyc->status) == 'verified' ? 'selected' : '' }}>Verified</option>
                                    <option value="rejected" {{ old('status', $kyc->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>
                            
                            <div class="col-md-8" id="rejection-reason-field" style="{{ old('status', $kyc->status) == 'rejected' ? '' : 'display: none;' }}">
                                <label class="form-label">Reason for Rejection *</label>
                                <textarea name="rejection_reason" class="form-control" rows="2" 
                                          placeholder="Required when status is Rejected">{{ old('rejection_reason', $kyc->rejection_reason) }}</textarea>
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Admin Notes</label>
                                <textarea name="admin_notes" class="form-control" rows="3" 
                                          placeholder="Internal notes for admin only">{{ old('admin_notes', $kyc->admin_notes) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Form Actions -->
                <div class="row g-3">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center border-top pt-3">
                            <div>
                                <a href="{{ route('admin.investor-kyc.show', $kyc->id) }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i> Cancel
                                </a>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="reset" class="btn btn-outline-warning">
                                    <i class="fas fa-redo me-1"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-primary" onclick="return validateForm()">
                                    <i class="fas fa-save me-1"></i> Update KYC
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .nominee-card {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
    }
    
    .border-bottom {
        border-bottom: 2px solid #dee2e6 !important;
    }
</style>
@endpush

@push('js')
<script>
    let nomineeCount = {{ $nomineeCount }};
    const maxNominees = 3;
    
    function addNominee() {
        if (nomineeCount >= maxNominees) {
            alert('Maximum 3 nominees allowed');
            return;
        }
        
        const container = document.getElementById('nominees-container');
        const index = nomineeCount;
        
        const nomineeHtml = `
            <div class="nominee-card mb-3 p-3 border rounded">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="mb-0">Nominee #${index + 1}</h6>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeNominee(this)">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="row g-2">
                    <div class="col-md-3">
                        <label class="form-label">Name *</label>
                        <input type="text" class="form-control" name="nominees[${index}][name]" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Relation *</label>
                        <input type="text" class="form-control" name="nominees[${index}][relation]" placeholder="Brother/Son" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">NID *</label>
                        <input type="text" class="form-control" name="nominees[${index}][nid]" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Share % *</label>
                        <input type="number" class="form-control share-percentage" 
                               name="nominees[${index}][share_percentage]" 
                               min="1" max="100" step="1" required oninput="updateTotalShare()">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Phone</label>
                        <input type="tel" class="form-control" name="nominees[${index}][phone]" pattern="01[3-9]\\d{8}">
                    </div>
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', nomineeHtml);
        nomineeCount++;
        updateTotalShare();
    }
    
    function removeNominee(button) {
        if (confirm('Are you sure you want to remove this nominee?')) {
            const card = button.closest('.nominee-card');
            card.remove();
            nomineeCount--;
            updateTotalShare();
            renumberNominees();
        }
    }
    
    function renumberNominees() {
        const containers = document.querySelectorAll('.nominee-card');
        containers.forEach((container, index) => {
            const title = container.querySelector('h6');
            if (title) {
                title.textContent = `Nominee #${index + 1}`;
            }
        });
    }
    
    function updateTotalShare() {
        const shares = document.querySelectorAll('.share-percentage');
        let total = 0;
        
        shares.forEach(share => {
            const value = parseFloat(share.value) || 0;
            total += value;
        });
        
        document.getElementById('total-share').textContent = total;
        
        const errorDiv = document.getElementById('share-error');
        if (shares.length > 0 && total !== 100) {
            errorDiv.classList.remove('d-none');
            return false;
        } else {
            errorDiv.classList.add('d-none');
            return true;
        }
    }
    
    function toggleRejectionReason(status) {
        const field = document.getElementById('rejection-reason-field');
        const textarea = field.querySelector('textarea[name="rejection_reason"]');
        
        if (status === 'rejected') {
            field.style.display = 'block';
            textarea.required = true;
        } else {
            field.style.display = 'none';
            textarea.required = false;
            textarea.value = '';
        }
    }
    
    function validateForm() {
        // Check nominee shares
        if (!updateTotalShare()) {
            alert('Total nominee shares must equal exactly 100%. Please adjust the percentages.');
            return false;
        }
        
        // Check file sizes
        const fileInputs = document.querySelectorAll('input[type="file"]');
        const maxSize = 5 * 1024 * 1024; // 5MB
        
        for (const input of fileInputs) {
            if (input.files.length > 0) {
                const file = input.files[0];
                if (file.size > maxSize) {
                    alert(`File ${file.name} is too large. Maximum size is 5MB.`);
                    input.value = '';
                    return false;
                }
            }
        }
        
        return true;
    }
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateTotalShare();
        
        // Add event listeners to existing share inputs
        document.querySelectorAll('.share-percentage').forEach(input => {
            input.addEventListener('input', updateTotalShare);
        });
    });
</script>
@endpush