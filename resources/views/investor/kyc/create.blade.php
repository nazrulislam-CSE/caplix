@extends('layouts.investor', ['pageTitle' => $pageTitle])
@section('content')
    <!-- Breadcrumb -->
    <div class="breadcrumb-header d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">{{ $pageTitle }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('investor.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
            </ol>
        </nav>
    </div>

    <!-- KYC Form -->
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow border-0 p-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <div class="icon-box bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <div>
                                <h5 class="mb-1">CapliX — KYC যাচাই (Investor)</h5>
                                <p class="text-muted mb-0">নিম্নলিখিত তথ্য দিন — এটি আপনার নিরাপত্তা, আইডেন্টিটি ভেরিফিকেশন এবং দ্রুত অনবোর্ডিং নিশ্চিত করবে।</p>
                            </div>
                        </div>

                        <!-- Progress Steps -->
                        <div class="kyc-steps mb-4">
                            <div class="step active">
                                <div class="step-circle">১</div>
                                <div class="step-label">প্রোফাইল</div>
                            </div>
                            <div class="step">
                                <div class="step-circle">২</div>
                                <div class="step-label">ডক/নমিনি</div>
                            </div>
                            <div class="step">
                                <div class="step-circle">৩</div>
                                <div class="step-label">যাচাই</div>
                            </div>
                        </div>

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form id="kycForm" action="{{ route('investor.kyc.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @if(isset($kyc))
                                <input type="hidden" name="kyc_id" value="{{ $kyc->id }}">
                            @endif

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="full_name_bn" class="form-label">পূর্ণ নাম (বাংলায়) *</label>
                                    <input type="text" class="form-control" id="full_name_bn" name="full_name_bn" 
                                           value="{{ old('full_name_bn', $kyc->full_name_bn ?? '') }}" 
                                           placeholder="উদাহরণ: মোঃ মনির হোসেন" required>
                                    <div class="form-text">বাংলায় আপনার সম্পূর্ণ নাম লিখুন</div>
                                </div>

                                <div class="col-md-6">
                                    <label for="full_name_en" class="form-label">Full Name (English) *</label>
                                    <input type="text" class="form-control" id="full_name_en" name="full_name_en" 
                                           value="{{ old('full_name_en', Auth::user()->name) }}" 
                                           placeholder="Like: Monir Hossain" required>
                                    <div class="form-text">ইংরেজিতে আপনার সম্পূর্ণ নাম</div>
                                </div>

                                <div class="col-md-6">
                                    <label for="nid" class="form-label">জাতীয় পরিচয়পত্র (NID) নম্বর *</label>
                                    <input type="text" class="form-control" id="nid" name="nid" 
                                           value="{{ old('nid', $kyc->nid ?? '') }}" 
                                           placeholder="NID / Birth Reg. No" required>
                                    <div class="form-text">বাংলাদেশ NID/জন্মনিবন্ধন নম্বর লিখুন।</div>
                                </div>

                                <div class="col-md-6">
                                    <label for="date_of_birth" class="form-label">জন্মতারিখ *</label>
                                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" 
                                           value="{{ old('date_of_birth', isset($kyc->date_of_birth) ? $kyc->date_of_birth->format('Y-m-d') : '') }}" 
                                           required max="{{ date('Y-m-d') }}">
                                </div>

                                <div class="col-md-6">
                                    <label for="phone" class="form-label">মোবাইল নম্বর *</label>
                                    <div class="input-group">
                                        <span class="input-group-text">+88</span>
                                        <input type="tel" class="form-control" id="phone" name="phone" 
                                               value="{{ old('phone', Auth::user()->phone) }}" 
                                               placeholder="01XXXXXXXXX" required pattern="01[3-9]\d{8}">
                                    </div>
                                    <div class="form-text">বাংলাদেশি মোবাইল নম্বর (01XXXXXXXXX)</div>
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label">ইমেইল *</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="{{ old('email', Auth::user()->email) }}" 
                                           placeholder="you@example.com" required>
                                </div>

                                <div class="col-12">
                                    <label for="permanent_address" class="form-label">স্থায়ী ঠিকানা *</label>
                                    <textarea class="form-control" id="permanent_address" name="permanent_address" 
                                              rows="3" placeholder="গ্রাম/বাড়ি, পোস্ট অফিস, উপজেলা/থানা, জেলা" required>{{ old('permanent_address', Auth::user()->address) }}</textarea>
                                </div>

                                <div class="col-md-6">
                                    <label for="bank_name" class="form-label">ব্যাংক নাম</label>
                                    <input type="text" class="form-control" id="bank_name" name="bank_name" 
                                           value="{{ old('bank_name', $kyc->bank_name ?? '') }}" 
                                           placeholder="যেমন: ব্র্যাক ব্যাংক">
                                </div>

                                <div class="col-md-6">
                                    <label for="bank_account_no" class="form-label">ব্যাংক অ্যাকাউন্ট নম্বর</label>
                                    <input type="text" class="form-control" id="bank_account_no" name="bank_account_no" 
                                           value="{{ old('bank_account_no', $kyc->bank_account_no ?? '') }}" 
                                           placeholder="Account Number">
                                </div>

                                <div class="col-md-6">
                                    <label for="investment_range" class="form-label">আনুমানিক বিনিয়োগ রেঞ্জ (BDT)</label>
                                    <select class="form-select" id="investment_range" name="investment_range">
                                        <option value="">Select...</option>
                                        <option value="<100000" {{ old('investment_range', $kyc->investment_range ?? '') == '<100000' ? 'selected' : '' }}>Below 100,000</option>
                                        <option value="100000-500000" {{ old('investment_range', $kyc->investment_range ?? '') == '100000-500000' ? 'selected' : '' }}>100,000 - 500,000</option>
                                        <option value="500000-2000000" {{ old('investment_range', $kyc->investment_range ?? '') == '500000-2000000' ? 'selected' : '' }}>500,000 - 2,000,000</option>
                                        <option value=">2000000" {{ old('investment_range', $kyc->investment_range ?? '') == '>2000000' ? 'selected' : '' }}>Above 2,000,000</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="occupation" class="form-label">পেশা</label>
                                    <input type="text" class="form-control" id="occupation" name="occupation" 
                                           value="{{ old('occupation', $kyc->occupation ?? '') }}" 
                                           placeholder="উদাহরণ: ব্যবসায়ী / উদ্যোক্তা / চাকরি (Company)">
                                </div>
                            </div>

                            <!-- Documents Section -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">ডকুমেন্ট আপলোড (NID / Passport ইত্যাদি)</h6>
                                    <small class="text-muted">Max each 5MB • JPG/PNG/PDF</small>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label for="nid_front" class="form-label">NID Front *</label>
                                            <input type="file" class="form-control" id="nid_front" name="nid_front" accept="image/*,.pdf">
                                            <div class="form-text">ফ্রন্ট সাইডের ছবি</div>
                                            @if(isset($kyc) && $kyc->nid_front)
                                                <div class="mt-2">
                                                    <a href="{{ route('investor.kyc.download', ['field' => 'nid_front', 'id' => $kyc->id]) }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye me-1"></i> View Current
                                                    </a>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="col-md-4">
                                            <label for="nid_back" class="form-label">NID Back *</label>
                                            <input type="file" class="form-control" id="nid_back" name="nid_back" accept="image/*,.pdf">
                                            <div class="form-text">ব্যাক সাইডের ছবি</div>
                                            @if(isset($kyc) && $kyc->nid_back)
                                                <div class="mt-2">
                                                    <a href="{{ route('investor.kyc.download', ['field' => 'nid_back', 'id' => $kyc->id]) }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye me-1"></i> View Current
                                                    </a>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="col-md-4">
                                            <label for="passport" class="form-label">Passport (Optional)</label>
                                            <input type="file" class="form-control" id="passport" name="passport" accept="image/*,.pdf">
                                            <div class="form-text">যদি থাকে আপলোড করুন</div>
                                            @if(isset($kyc) && $kyc->passport)
                                                <div class="mt-2">
                                                    <a href="{{ route('investor.kyc.download', ['field' => 'passport', 'id' => $kyc->id]) }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye me-1"></i> View Current
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Nominees Section -->
                            <div class="card mb-4">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Nominee / নমিনি</h6>
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addNominee()">
                                        <i class="fas fa-plus me-1"></i> Add Nominee
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div id="nominees-container">
                                        <!-- Dynamic nominees will be added here -->
                                        @php
                                            $nominees = isset($kyc) ? json_decode($kyc->nominees, true) : [];
                                        @endphp
                                        
                                        @if(!empty($nominees))
                                            @foreach($nominees as $index => $nominee)
                                                <div class="nominee-card mb-3 p-3 border rounded">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <h6 class="mb-0">Nominee #{{ $index + 1 }}</h6>
                                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeNominee(this)">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                    <div class="row g-2">
                                                        <div class="col-md-4">
                                                            <label class="form-label">Name *</label>
                                                            <input type="text" class="form-control" name="nominees[{{ $index }}][name]" 
                                                                   value="{{ $nominee['name'] }}" required>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label">Relation *</label>
                                                            <input type="text" class="form-control" name="nominees[{{ $index }}][relation]" 
                                                                   value="{{ $nominee['relation'] }}" placeholder="Brother/Son/Wife" required>
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
                                                                   value="{{ $nominee['share_percentage'] }}" min="1" max="100" step="1" required>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label">Phone (Optional)</label>
                                                            <input type="tel" class="form-control" name="nominees[{{ $index }}][phone]" 
                                                                   value="{{ $nominee['phone'] ?? '' }}" pattern="01[3-9]\d{8}">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div class="text-muted mt-2">
                                        <small><i class="fas fa-info-circle me-1"></i> কীভাবে বণ্টন করবেন: সকল নমিনিদের শেয়ার যোগফল অবশ্যই ১০০% হতে হবে। (Max 3 nominees)</small>
                                    </div>
                                    <div id="share-error" class="alert alert-danger mt-2 d-none">
                                        Nominee shares must total 100%.
                                    </div>
                                    <div class="mt-2">
                                        <strong>Total Share: <span id="total-share">0</span>%</strong>
                                    </div>
                                </div>
                            </div>

                            <!-- OTP Verification -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Phone Verification</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-4">
                                            <label for="verification_phone" class="form-label">Mobile Number for OTP</label>
                                            <input type="tel" class="form-control" id="verification_phone" 
                                                   value="{{ old('phone', $kyc->phone ?? '') }}" 
                                                   placeholder="01XXXXXXXXX" pattern="01[3-9]\d{8}">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="otp_input" class="form-label">OTP</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="otp_input" placeholder="Enter OTP">
                                                <button type="button" class="btn btn-outline-primary" onclick="verifyOTP()">Verify</button>
                                            </div>
                                        </div>
                                        <div class="col-md-4 d-flex align-items-end">
                                            <button type="button" class="btn btn-primary w-100" onclick="sendOTP()" id="send-otp-btn">
                                                <i class="fas fa-paper-plane me-1"></i> Send OTP
                                            </button>
                                        </div>
                                    </div>
                                    <div id="otp-status" class="mt-2"></div>
                                </div>
                            </div>

                            <!-- Declaration -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Declaration & Consent</h6>
                                </div>
                                <div class="card-body">
                                    <div class="border rounded p-3 bg-light mb-3">
                                        <p class="mb-0">আমি ঘোষণা করছি যে প্রদত্ত তথ্য সঠিক এবং সম্পূর্ণ। CapliX এই তথ্য যাচাই করতে পারে এবং আমি যাচাই প্রক্রিয়ার জন্য সম্মতি দিচ্ছি।</p>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="agree" name="agree" required>
                                        <label class="form-check-label" for="agree">
                                            আমি এই শর্তে সম্মত (I agree to the terms)
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('investor.dashboard') }}" class="btn btn-outline-secondary me-md-2">Cancel</a>
                                <button type="submit" class="btn btn-primary" id="submit-btn">
                                    <i class="fas fa-paper-plane me-1"></i> Submit & Request Verification
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .kyc-steps {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin-bottom: 2rem;
    }
    .kyc-steps::before {
        content: '';
        position: absolute;
        top: 24px;
        left: 10%;
        right: 10%;
        height: 2px;
        background-color: #dee2e6;
        z-index: 1;
    }
    .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 2;
        flex: 1;
    }
    .step-circle {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background-color: #dee2e6;
        color: #6c757d;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
        border: 3px solid white;
    }
    .step.active .step-circle {
        background-color: #0d6efd;
        color: white;
    }
    .step-label {
        font-size: 0.9rem;
        text-align: center;
        color: #6c757d;
        font-weight: 500;
    }
    .step.active .step-label {
        color: #0d6efd;
        font-weight: 600;
    }
    .icon-box {
        width: 48px;
        height: 48px;
        font-size: 1.5rem;
    }
    .nominee-card {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
    }
</style>
@endpush

@push('js')
<script>
    let nomineeCount = {{ !empty($nominees) ? count($nominees) : 0 }};
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
                    <div class="col-md-4">
                        <label class="form-label">Name *</label>
                        <input type="text" class="form-control" name="nominees[${index}][name]" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Relation *</label>
                        <input type="text" class="form-control" name="nominees[${index}][relation]" placeholder="Brother/Son/Wife" required>
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
                    <div class="col-md-4">
                        <label class="form-label">Phone (Optional)</label>
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
    
    function sendOTP() {
        const phone = document.getElementById('verification_phone').value;
        const phoneRegex = /^01[3-9]\d{8}$/;
        
        if (!phoneRegex.test(phone)) {
            showOtpStatus('Please enter a valid Bangladeshi mobile number', 'error');
            return;
        }
        
        const btn = document.getElementById('send-otp-btn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Sending...';
        
        fetch('{{ route("investor.kyc.send-otp") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ phone: phone })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showOtpStatus('OTP sent successfully! Check your phone.', 'success');
                // In production, remove the OTP display
                console.log('OTP:', data.otp); // Remove this in production
            } else {
                showOtpStatus(data.message || 'Failed to send OTP', 'error');
            }
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-paper-plane me-1"></i> Send OTP';
        })
        .catch(error => {
            showOtpStatus('Network error. Please try again.', 'error');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-paper-plane me-1"></i> Send OTP';
        });
    }
    
    function verifyOTP() {
        const phone = document.getElementById('verification_phone').value;
        const otp = document.getElementById('otp_input').value;
        
        if (!otp || otp.length !== 6) {
            showOtpStatus('Please enter a 6-digit OTP', 'error');
            return;
        }
        
        fetch('{{ route("investor.kyc.verify-otp") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ 
                phone: phone,
                otp: otp 
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showOtpStatus('Phone verified successfully!', 'success');
            } else {
                showOtpStatus(data.message || 'OTP verification failed', 'error');
            }
        })
        .catch(error => {
            showOtpStatus('Network error. Please try again.', 'error');
        });
    }
    
    function showOtpStatus(message, type) {
        const statusDiv = document.getElementById('otp-status');
        statusDiv.innerHTML = `
            <div class="alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        
        setTimeout(() => {
            const alert = statusDiv.querySelector('.alert');
            if (alert) {
                alert.remove();
            }
        }, 5000);
    }
    
    // Form validation before submit
    document.getElementById('kycForm').addEventListener('submit', function(e) {
        // Check total share percentage
        if (!updateTotalShare()) {
            e.preventDefault();
            alert('Nominee shares must total exactly 100%. Please adjust the percentages.');
            return false;
        }
        
        // Check file sizes
        const files = ['nid_front', 'nid_back', 'passport'];
        const maxSize = 5 * 1024 * 1024; // 5MB
        
        for (const fileId of files) {
            const fileInput = document.getElementById(fileId);
            if (fileInput.files.length > 0) {
                const file = fileInput.files[0];
                if (file.size > maxSize) {
                    e.preventDefault();
                    alert(`File ${file.name} is too large. Maximum size is 5MB.`);
                    fileInput.value = '';
                    return false;
                }
            }
        }
        
        return true;
    });
    
    // Initialize total share on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateTotalShare();
        
        // Add change event to existing share inputs
        document.querySelectorAll('.share-percentage').forEach(input => {
            input.addEventListener('input', updateTotalShare);
        });
    });
</script>
@endpush