@extends('layouts.investor')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb-header d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">{{ $pageTitle }}</h4>
    <nav>
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">
                <a href="{{ route('investor.dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Deposit Funds</li>
        </ol>
    </nav>
</div>

<!-- Deposit Content -->
<div class="card shadow-sm border-0">
    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Deposit Funds</h5>
        <a href="{{ route('investor.deposit.index') }}" class="btn btn-light btn-sm">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('investor.deposit.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Payment Method Selection -->
            <div class="mb-4">
                <label class="form-label fw-semibold mb-3">Select Payment Method</label>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="payment-method-card text-center p-3 border rounded cursor-pointer" data-method="bKash">
                            <div class="mb-2">
                                <i class="fas fa-mobile-alt fa-2x text-primary"></i>
                            </div>
                            <h6 class="fw-bold mb-1">bKash</h6>
                            <p class="text-muted small mb-0">Send money via bKash</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="payment-method-card text-center p-3 border rounded cursor-pointer" data-method="Nagad">
                            <div class="mb-2">
                                <i class="fas fa-wallet fa-2x text-success"></i>
                            </div>
                            <h6 class="fw-bold mb-1">Nagad</h6>
                            <p class="text-muted small mb-0">Send money via Nagad</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="payment-method-card text-center p-3 border rounded cursor-pointer" data-method="Bank">
                            <div class="mb-2">
                                <i class="fas fa-university fa-2x text-info"></i>
                            </div>
                            <h6 class="fw-bold mb-1">Bank Transfer</h6>
                            <p class="text-muted small mb-0">Direct bank transfer</p>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="payment_method" id="payment_method" value="">
                @error('payment_method')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Payment Details (Dynamic) -->
            <div class="mb-4" id="payment_details" style="display: none;">
                <div class="border rounded p-3 bg-light">
                    <h6 class="fw-bold mb-2" id="method_title"></h6>
                    <div id="bKash_details" class="method-details" style="display: none;">
                        <p class="mb-1"><strong>bKash Number:</strong> 01XXXXXXXXX</p>
                        <p class="mb-1"><strong>Type:</strong> Send Money</p>
                        <p class="mb-0"><strong>Reference:</strong> Your Username</p>
                    </div>
                    <div id="Nagad_details" class="method-details" style="display: none;">
                        <p class="mb-1"><strong>Nagad Number:</strong> 01XXXXXXXXX</p>
                        <p class="mb-1"><strong>Type:</strong> Send Money</p>
                        <p class="mb-0"><strong>Reference:</strong> Your Username</p>
                    </div>
                    <div id="Bank_details" class="method-details" style="display: none;">
                        <p class="mb-1"><strong>Bank Name:</strong> Example Bank</p>
                        <p class="mb-1"><strong>A/C Number:</strong> 0123456789012</p>
                        <p class="mb-1"><strong>Branch:</strong> Gulshan</p>
                        <p class="mb-0"><strong>Account Name:</strong> Your Company Name</p>
                    </div>
                </div>
            </div>

            <!-- Amount Selection -->
            <div class="mb-4">
                <label class="form-label fw-semibold mb-3">Select Amount (BDT)</label>
                <div class="row g-2 mb-3">
                    <div class="col-3">
                        <button type="button" class="btn btn-outline-primary w-100 amount-btn" data-amount="1000">৳1,000</button>
                    </div>
                    <div class="col-3">
                        <button type="button" class="btn btn-outline-primary w-100 amount-btn" data-amount="5000">৳5,000</button>
                    </div>
                    <div class="col-3">
                        <button type="button" class="btn btn-outline-primary w-100 amount-btn" data-amount="10000">৳10,000</button>
                    </div>
                    <div class="col-3">
                        <button type="button" class="btn btn-outline-primary w-100 amount-btn" data-amount="50000">৳50,000</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Custom Amount</label>
                        <div class="input-group">
                            <span class="input-group-text">৳</span>
                            <input type="number" class="form-control" name="amount" id="amount" min="100" step="100" placeholder="Enter amount" required>
                        </div>
                        <small class="text-muted">Minimum amount: ৳100</small>
                        @error('amount')
                            <span class="text-danger d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Transaction Details -->
            <div class="mb-4">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Transaction ID *</label>
                        <input type="text" class="form-control" name="transaction_id" placeholder="Example: TRX20250915001" required>
                        @error('transaction_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Payment Slip (Optional)</label>
                        <input type="file" class="form-control" name="payment_slip" accept=".jpg,.jpeg,.png,.pdf">
                        <small class="text-muted">Upload screenshot or PDF of payment receipt</small>
                        @error('payment_slip')
                            <span class="text-danger d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-4">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fas fa-paper-plane me-2"></i>Submit Deposit Request
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Payment method selection
        const methodCards = document.querySelectorAll('.payment-method-card');
        const paymentMethodInput = document.getElementById('payment_method');
        const paymentDetails = document.getElementById('payment_details');
        const methodTitle = document.getElementById('method_title');
        const methodDetails = document.querySelectorAll('.method-details');
        
        methodCards.forEach(card => {
            card.addEventListener('click', function() {
                // Remove active class from all cards
                methodCards.forEach(c => c.classList.remove('border-primary', 'bg-primary-light'));
                
                // Add active class to clicked card
                this.classList.add('border-primary', 'bg-primary-light');
                
                // Set payment method value
                const method = this.getAttribute('data-method');
                paymentMethodInput.value = method;
                
                // Show payment details
                paymentDetails.style.display = 'block';
                methodTitle.textContent = `${method} Payment Details`;
                
                // Show selected method details
                methodDetails.forEach(detail => detail.style.display = 'none');
                document.getElementById(`${method}_details`).style.display = 'block';
            });
        });

        // Amount buttons
        const amountButtons = document.querySelectorAll('.amount-btn');
        const amountInput = document.getElementById('amount');
        
        amountButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                amountButtons.forEach(btn => btn.classList.remove('active', 'btn-primary'));
                
                // Add active class to clicked button
                this.classList.add('active', 'btn-primary');
                this.classList.remove('btn-outline-primary');
                
                // Set amount value
                const amount = this.getAttribute('data-amount');
                amountInput.value = amount;
            });
        });

        // Manual amount input resets button selection
        amountInput.addEventListener('input', function() {
            amountButtons.forEach(btn => {
                btn.classList.remove('active', 'btn-primary');
                btn.classList.add('btn-outline-primary');
            });
        });
    });
</script>

<style>
.payment-method-card {
    transition: all 0.3s ease;
    cursor: pointer;
}
.payment-method-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.payment-method-card.border-primary {
    border-width: 2px !important;
}
.bg-primary-light {
    background-color: rgba(13, 110, 253, 0.05) !important;
}
.amount-btn.active {
    border-width: 2px;
}
</style>
@endpush