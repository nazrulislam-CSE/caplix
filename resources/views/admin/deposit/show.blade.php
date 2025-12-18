@extends('layouts.admin')
@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb-header d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Deposit Details</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.deposit.index') }}">Deposit List</a></li>
            <li class="breadcrumb-item active" aria-current="page">Deposit Details</li>
        </ol>
    </nav>
</div>

<div class="row">
    <!-- Deposit Details -->
    <div class="col-lg-8">
        <div class="card shadow border-0 mb-4">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="fw-bold mb-0">Deposit Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Transaction ID</label>
                        <div class="fw-bold"><code>{{ $deposit->transaction_id }}</code></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Date & Time</label>
                        <div class="fw-bold">
                            {{ $deposit->created_at->format('d M Y, h:i A') }}
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Investor</label>
                        <div class="fw-bold">{{ $deposit->user->name ?? 'N/A' }}</div>
                        <small class="text-muted">{{ $deposit->user->email ?? '' }}</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Amount</label>
                        <div class="fw-bold h4 text-primary">৳{{ number_format($deposit->amount, 2) }}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Payment Method</label>
                        <div class="fw-bold">
                            @if($deposit->payment_method == 'bKash')
                                <span class="badge bg-primary">
                                    <i class="fas fa-mobile-alt me-1"></i> bKash
                                </span>
                            @elseif($deposit->payment_method == 'Nagad')
                                <span class="badge bg-success">
                                    <i class="fas fa-wallet me-1"></i> Nagad
                                </span>
                            @else
                                <span class="badge bg-info">
                                    <i class="fas fa-university me-1"></i> Bank
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Status</label>
                        <div>
                            @if($deposit->status == 'pending')
                                <span class="badge bg-warning rounded-pill">
                                    <i class="fas fa-clock me-1"></i> Pending
                                </span>
                            @elseif($deposit->status == 'approved')
                                <span class="badge bg-success rounded-pill">
                                    <i class="fas fa-check-circle me-1"></i> Approved
                                </span>
                            @else
                                <span class="badge bg-danger rounded-pill">
                                    <i class="fas fa-times-circle me-1"></i> Rejected
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Payment Slip</label>
                        <div>
                            @if($deposit->payment_slip)
                                <a href="{{ asset('storage/' . $deposit->payment_slip) }}" 
                                   target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-external-link-alt me-1"></i> View Slip
                                </a>
                            @else
                                <span class="text-muted">Not Available</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Last Updated</label>
                        <div class="fw-bold">
                            {{ $deposit->updated_at->format('d M Y, h:i A') }}
                        </div>
                    </div>
                </div>
                
                @if($deposit->admin_note)
                <div class="mt-4">
                    <label class="form-label text-muted">Admin Note</label>
                    <div class="card bg-light border-0 p-3">
                        <i class="fas fa-sticky-note text-info me-2"></i>
                        {{ $deposit->admin_note }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Update Status Form -->
    <div class="col-lg-4">
        <div class="card shadow border-0">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="fw-bold mb-0">Update Status</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.deposit.update', $deposit->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Current Status</label>
                        <div class="alert alert-light border">
                            @if($deposit->status == 'pending')
                                <span class="text-warning fw-bold">Pending</span>
                            @elseif($deposit->status == 'approved')
                                <span class="text-success fw-bold">Approved</span>
                            @else
                                <span class="text-danger fw-bold">Rejected</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Change Status *</label>
                        <select name="status" class="form-select" required>
                            <option value="pending" {{ $deposit->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ $deposit->status == 'approved' ? 'selected' : '' }}>Approve</option>
                            <option value="rejected" {{ $deposit->status == 'rejected' ? 'selected' : '' }}>Reject</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Admin Note (Optional)</label>
                        <textarea name="admin_note" class="form-control" rows="4" 
                                  placeholder="Add note for the user...">{{ $deposit->admin_note }}</textarea>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-2"></i>Update Status
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="card shadow border-0 mt-4">
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.deposit.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection