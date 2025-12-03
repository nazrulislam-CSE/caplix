@extends('layouts.entrepreneur', [$pageTitle => $pageTitle])
@section('content')
    <!-- Breadcrumb -->
    <div class="breadcrumb-header d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">{{ $pageTitle ?? '' }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('entrepreneur.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
            </ol>
        </nav>
    </div>

    <!-- KYC Form -->
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow border-0 p-4">
                    <!-- Display Success/Error Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @if(session('otp_sent'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            {{ session('otp_message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div style="display:flex;gap:14px;align-items:center;margin-bottom:24px">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="12" r="10" fill="#0b5ed7"/>
                            <path d="M12 7v6l4 2" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <div>
                            <h1 class="h4 mb-1">CapliX — Entrepreneur KYC / Business Verification</h1>
                            <p class="text-muted mb-0">ব্যবসা-বহির্ভূত প্রোফাইল ও ডকুমেন্ট আপলোড করুন — দ্রুত এবং নিরাপদ যাচাই নিশ্চিত করতে।</p>
                        </div>
                    </div>

                    <div class="steps mb-4" style="display:flex;gap:20px;margin-bottom:30px">
                        <div class="step active" style="padding:10px 20px;background:#0b5ed7;color:white;border-radius:20px;font-weight:bold">১. ব্যবসা তথ্য</div>
                        <div class="step" style="padding:10px 20px;background:#e9ecef;color:#6c757d;border-radius:20px">২. মালিক/দায়িত্বপ্রাপ্ত</div>
                        <div class="step" style="padding:10px 20px;background:#e9ecef;color:#6c757d;border-radius:20px">৩. ডকুমেন্ট</div>
                    </div>

                    <form method="POST" action="{{ route('entrepreneur.kyc.store') }}" enctype="multipart/form-data" id="bizKyc">
                        @csrf
                        
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">Business Details</h5>
                            </div>

                            <div class="col-md-6">
                                <label for="compName" class="form-label">Business / Company Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('compName') is-invalid @enderror" 
                                       id="compName" name="compName" value="{{ old('compName') }}" 
                                       placeholder="e.g. Moni Handicrafts Ltd" required>
                                @error('compName')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="regNo" class="form-label">Registration No. (e.g. ROC/Trade Reg.)</label>
                                <input type="text" class="form-control @error('regNo') is-invalid @enderror" 
                                       id="regNo" name="regNo" value="{{ old('regNo') }}" 
                                       placeholder="Registration / ROC No">
                                @error('regNo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="tradeLicense" class="form-label">Trade License No.</label>
                                <input type="text" class="form-control @error('tradeLicense') is-invalid @enderror" 
                                       id="tradeLicense" name="tradeLicense" value="{{ old('tradeLicense') }}" 
                                       placeholder="Trade License Number">
                                @error('tradeLicense')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="businessType" class="form-label">Business Type</label>
                                <select class="form-select @error('businessType') is-invalid @enderror" id="businessType" name="businessType">
                                    <option value="">Select...</option>
                                    <option value="Proprietorship" {{ old('businessType') == 'Proprietorship' ? 'selected' : '' }}>Proprietorship</option>
                                    <option value="Partnership" {{ old('businessType') == 'Partnership' ? 'selected' : '' }}>Partnership</option>
                                    <option value="Private Limited" {{ old('businessType') == 'Private Limited' ? 'selected' : '' }}>Private Limited</option>
                                    <option value="Limited Company" {{ old('businessType') == 'Limited Company' ? 'selected' : '' }}>Limited Company</option>
                                    <option value="NGO/NPO" {{ old('businessType') == 'NGO/NPO' ? 'selected' : '' }}>NGO / NPO</option>
                                </select>
                                @error('businessType')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="tin" class="form-label">TIN / BIN</label>
                                <input type="text" class="form-control @error('tin') is-invalid @enderror" 
                                       id="tin" name="tin" value="{{ old('tin') }}" 
                                       placeholder="Tax Identification Number">
                                @error('tin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="estYear" class="form-label">Year of Establishment</label>
                                <input type="number" class="form-control @error('estYear') is-invalid @enderror" 
                                       id="estYear" name="estYear" value="{{ old('estYear') }}" 
                                       placeholder="e.g. 2019" min="1900" max="{{ date('Y') }}">
                                @error('estYear')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="employees" class="form-label">Number of Employees</label>
                                <input type="number" class="form-control @error('employees') is-invalid @enderror" 
                                       id="employees" name="employees" value="{{ old('employees') }}" 
                                       placeholder="e.g. 12" min="0">
                                @error('employees')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="turnover" class="form-label">Last Fiscal Year Turnover (BDT)</label>
                                <input type="number" step="0.01" class="form-control @error('turnover') is-invalid @enderror" 
                                       id="turnover" name="turnover" value="{{ old('turnover') }}" 
                                       placeholder="e.g. 1200000" min="0">
                                @error('turnover')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="businessAddress" class="form-label">Registered Business Address</label>
                                <textarea class="form-control @error('businessAddress') is-invalid @enderror" 
                                          id="businessAddress" name="businessAddress" rows="2" 
                                          placeholder="Address, Post Office, Upazila/Thana, District">{{ old('businessAddress') }}</textarea>
                                @error('businessAddress')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="website" class="form-label">Website (Optional)</label>
                                <input type="url" class="form-control @error('website') is-invalid @enderror" 
                                       id="website" name="website" value="{{ old('website') }}" 
                                       placeholder="https://">
                                @error('website')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">Owner / Responsible Person</h5>
                            </div>

                            <div class="col-md-6">
                                <label for="ownerName" class="form-label">Owner / CEO Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('ownerName') is-invalid @enderror" 
                                       id="ownerName" name="ownerName" value="{{ old('ownerName') }}" 
                                       placeholder="Full name" required>
                                @error('ownerName')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="ownerPhone" class="form-label">Mobile Number <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control @error('ownerPhone') is-invalid @enderror" 
                                       id="ownerPhone" name="ownerPhone" value="{{ old('ownerPhone') }}" 
                                       placeholder="01XXXXXXXXX" required>
                                @error('ownerPhone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="ownerEmail" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('ownerEmail') is-invalid @enderror" 
                                       id="ownerEmail" name="ownerEmail" value="{{ old('ownerEmail') }}" 
                                       placeholder="owner@example.com" required>
                                @error('ownerEmail')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="ownerNid" class="form-label">Owner NID / Passport <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('ownerNid') is-invalid @enderror" 
                                       id="ownerNid" name="ownerNid" value="{{ old('ownerNid') }}" 
                                       placeholder="NID/Passport No" required>
                                @error('ownerNid')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="ownerRole" class="form-label">Role in Company</label>
                                <input type="text" class="form-control @error('ownerRole') is-invalid @enderror" 
                                       id="ownerRole" name="ownerRole" value="{{ old('ownerRole') }}" 
                                       placeholder="e.g. Managing Director">
                                @error('ownerRole')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">Shareholders / Directors</h5>
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addShareholder()">
                                        <i class="fas fa-plus"></i> Add Shareholder
                                    </button>
                                </div>
                                
                                @error('shareholders_total')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                
                                <div id="shareholdersContainer">
                                    <!-- Existing shareholders from old input -->
                                    @php
                                        $oldShareholders = old('shareholder_name', []);
                                        $oldNids = old('shareholder_nid', []);
                                        $oldShares = old('shareholder_share', []);
                                    @endphp
                                    
                                    @if(count($oldShareholders) > 0)
                                        @foreach($oldShareholders as $index => $name)
                                            @if(!empty($name))
                                                <div class="row g-2 mb-2 shareholder-row">
                                                    <div class="col-md-4">
                                                        <input type="text" class="form-control @error('shareholder_name.' . $index) is-invalid @enderror" 
                                                               name="shareholder_name[]" value="{{ $name }}" 
                                                               placeholder="Full Name">
                                                        @error('shareholder_name.' . $index)
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="text" class="form-control @error('shareholder_nid.' . $index) is-invalid @enderror" 
                                                               name="shareholder_nid[]" value="{{ $oldNids[$index] ?? '' }}" 
                                                               placeholder="NID/Passport">
                                                        @error('shareholder_nid.' . $index)
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="number" step="0.01" class="form-control @error('shareholder_share.' . $index) is-invalid @enderror" 
                                                               name="shareholder_share[]" value="{{ $oldShares[$index] ?? '' }}" 
                                                               placeholder="Share %">
                                                        @error('shareholder_share.' . $index)
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button type="button" class="btn btn-sm btn-danger w-100" onclick="removeShareholder(this)">
                                                            <i class="fas fa-trash"></i> Remove
                                                        </button>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                                
                                <div id="shareErr" class="text-danger mt-2" style="display: none;">
                                    Shares must total 100%
                                </div>
                                
                                <div class="text-muted mt-1">Total share percentage must equal 100%</div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">Document Uploads</h5>
                                <p class="text-muted mb-3">Upload key business documents for verification. Each file max 10MB. JPG/PNG/PDF preferred.</p>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Certificate of Incorporation / Reg. Cert <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control @error('docReg') is-invalid @enderror" 
                                               id="docReg" name="docReg" accept="image/*,.pdf" required>
                                        @error('docReg')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label">Trade License <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control @error('docTrade') is-invalid @enderror" 
                                               id="docTrade" name="docTrade" accept="image/*,.pdf" required>
                                        @error('docTrade')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label">TIN / BIN Certificate</label>
                                        <input type="file" class="form-control @error('docTin') is-invalid @enderror" 
                                               id="docTin" name="docTin" accept="image/*,.pdf">
                                        @error('docTin')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label">Latest Bank Statement (3 months)</label>
                                        <input type="file" class="form-control @error('docBank') is-invalid @enderror" 
                                               id="docBank" name="docBank" accept="image/*,.pdf">
                                        @error('docBank')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label">Audited Financials / Management Accounts</label>
                                        <input type="file" class="form-control @error('docFin') is-invalid @enderror" 
                                               id="docFin" name="docFin" accept="image/*,.pdf">
                                        @error('docFin')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">Nominee (Optional)</h5>
                                <p class="text-muted mb-2">Nominee for business-related proceeds (if applicable)</p>
                                
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <input type="text" class="form-control @error('nomNameBiz') is-invalid @enderror" 
                                               id="nomNameBiz" name="nomNameBiz" value="{{ old('nomNameBiz') }}" 
                                               placeholder="Nominee Name">
                                        @error('nomNameBiz')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control @error('nomRelationBiz') is-invalid @enderror" 
                                               id="nomRelationBiz" name="nomRelationBiz" value="{{ old('nomRelationBiz') }}" 
                                               placeholder="Relation">
                                        @error('nomRelationBiz')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control @error('nomNidBiz') is-invalid @enderror" 
                                               id="nomNidBiz" name="nomNidBiz" value="{{ old('nomNidBiz') }}" 
                                               placeholder="NID">
                                        @error('nomNidBiz')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">OTP Verification</h5>
                                
                                <div class="row g-3 align-items-center">
                                    <div class="col-auto">
                                        <button type="button" class="btn btn-primary" onclick="sendOwnerOTP()">
                                            Send OTP to Owner
                                        </button>
                                    </div>
                                    <div class="col-auto">
                                        <input type="text" class="form-control @error('ownerOtp') is-invalid @enderror" 
                                               id="ownerOtp" name="ownerOtp" value="{{ old('ownerOtp') }}" 
                                               placeholder="Enter OTP" maxlength="6">
                                        @error('ownerOtp')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-auto">
                                        <span id="ownerOtpStatus" class="text-muted"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="border rounded p-3 bg-light">
                                    <p class="mb-2">
                                        <strong>Declaration & Consent</strong>
                                    </p>
                                    <p class="text-muted mb-3">
                                        আমি ঘোষণা করছি যে সব তথ্য সঠিক এবং CapliX আমার ব্যবসা যাচাই করতে প্রয়োজনীয় ডকুমেন্ট যাচাই করবে।
                                    </p>
                                    <div class="form-check">
                                        <input class="form-check-input @error('agreeBiz') is-invalid @enderror" 
                                               type="checkbox" id="agreeBiz" name="agreeBiz" value="1" 
                                               {{ old('agreeBiz') ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="agreeBiz">
                                            আমি উপরের শর্তে সম্মত
                                        </label>
                                        @error('agreeBiz')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('entrepreneur.dashboard') }}" class="btn btn-secondary me-md-2">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i> Submit & Request Business Verification
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script>
let shareholderIndex = {{ count($oldShareholders) }};

function addShareholder() {
    const container = document.getElementById('shareholdersContainer');
    
    const row = document.createElement('div');
    row.className = 'row g-2 mb-2 shareholder-row';
    row.innerHTML = `
        <div class="col-md-4">
            <input type="text" class="form-control" name="shareholder_name[]" placeholder="Full Name">
        </div>
        <div class="col-md-3">
            <input type="text" class="form-control" name="shareholder_nid[]" placeholder="NID/Passport">
        </div>
        <div class="col-md-3">
            <input type="number" step="0.01" class="form-control" name="shareholder_share[]" placeholder="Share %" oninput="calculateTotalShares()">
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-sm btn-danger w-100" onclick="removeShareholder(this)">
                <i class="fas fa-trash"></i> Remove
            </button>
        </div>
    `;
    
    container.appendChild(row);
    shareholderIndex++;
}

function removeShareholder(button) {
    const row = button.closest('.shareholder-row');
    row.remove();
    calculateTotalShares();
}

function calculateTotalShares() {
    const shareInputs = document.querySelectorAll('input[name="shareholder_share[]"]');
    let total = 0;
    
    shareInputs.forEach(input => {
        total += parseFloat(input.value) || 0;
    });
    
    const shareErr = document.getElementById('shareErr');
    if (Math.abs(total - 100) > 0.01) {
        shareErr.style.display = 'block';
    } else {
        shareErr.style.display = 'none';
    }
    
    return total;
}

// OTP functions (for better UX, still using fetch but form submission is normal)
async function sendOwnerOTP() {
    const phone = document.getElementById('ownerPhone').value;
    
    if (!phone) {
        alert('Please enter phone number');
        return;
    }
    
    try {
        const response = await fetch('{{ route("entrepreneur.kyc.send-otp") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ phone: phone })
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('OTP sent successfully');
            // For testing - prefill OTP
            document.getElementById('ownerOtp').value = data.otp;
        } else {
            alert('Failed to send OTP');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Network error');
    }
}

// Form validation before submission
document.getElementById('bizKyc').addEventListener('submit', function(event) {
    // Check shareholders total
    const totalShares = calculateTotalShares();
    const hasShareholders = document.querySelectorAll('input[name="shareholder_name[]"]').length > 0;
    
    if (hasShareholders && Math.abs(totalShares - 100) > 0.01) {
        event.preventDefault();
        alert('Total share percentage must equal 100%');
        return false;
    }
    
    // Basic OTP check
    const otp = document.getElementById('ownerOtp').value;
    if (!otp || otp.length !== 6) {
        event.preventDefault();
        alert('Please enter a valid 6-digit OTP');
        return false;
    }
    
    return true;
});
</script>
@endpush