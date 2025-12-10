@extends('layouts.investor', ['pageTitle' => $pageTitle])
@section('content')
    <!-- Breadcrumb -->
    <div class="breadcrumb-header d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">{{ $pageTitle }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('investor.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('investor.kyc.create') }}">KYC</a></li>
                <li class="breadcrumb-item active" aria-current="page">Status</li>
            </ol>
        </nav>
    </div>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <!-- Status Card -->
                <div class="card shadow border-0 mb-4">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="d-flex align-items-center">
                                    <div class="icon-box bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                        <i class="fas fa-user-check fa-lg"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-1">KYC Verification Status</h5>
                                        <p class="text-muted mb-0">Track your KYC verification process</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <div class="status-badge badge bg-{{ $kyc->status === 'verified' ? 'success' : ($kyc->status === 'rejected' ? 'danger' : ($kyc->status === 'pending' || $kyc->status === 'under_review' ? 'warning' : 'secondary')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $kyc->status)) }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <!-- Timeline -->
                            <div class="timeline">
                                @foreach($timeline as $item)
                                <div class="timeline-item {{ $item['status'] }}">
                                    <div class="timeline-marker">
                                        <i class="{{ $item['icon'] }}"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <div class="d-flex justify-content-between">
                                            <h6 class="mb-1">{{ $item['title'] }}</h6>
                                            @if($item['date'])
                                            <small class="text-muted">{{ $item['date']->format('d M Y, h:i A') }}</small>
                                            @endif
                                        </div>
                                        <p class="text-muted mb-0">{{ $item['description'] }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Current Status Details -->
                <div class="card shadow border-0 mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Current Status Details</h6>
                    </div>
                    <div class="card-body">
                        @if($kyc->status === 'draft')
                        <div class="alert alert-info">
                            <div class="d-flex">
                                <i class="fas fa-info-circle fa-lg me-3 mt-1"></i>
                                <div>
                                    <h6 class="alert-heading">KYC Draft Saved</h6>
                                    <p class="mb-0">Your KYC information is saved as draft. Please complete and submit it for verification.</p>
                                    <div class="mt-3">
                                        <a href="{{ route('investor.kyc.create') }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit me-1"></i> Continue Editing
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($kyc->status === 'pending' || $kyc->status === 'under_review')
                        <div class="alert alert-warning">
                            <div class="d-flex">
                                <i class="fas fa-clock fa-lg me-3 mt-1"></i>
                                <div>
                                    <h6 class="alert-heading">Under Review</h6>
                                    <p class="mb-0">Your KYC application is currently being reviewed by our verification team. This process usually takes 24-48 hours.</p>
                                    <ul class="mt-2 mb-0">
                                        <li>We are verifying your documents and information</li>
                                        <li>You will be notified once the review is complete</li>
                                        <li>Please ensure your contact information is up to date</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($kyc->status === 'verified')
                        <div class="alert alert-success">
                            <div class="d-flex">
                                <i class="fas fa-check-circle fa-lg me-3 mt-1"></i>
                                <div>
                                    <h6 class="alert-heading">KYC Verified Successfully!</h6>
                                    <p class="mb-0">Congratulations! Your KYC has been verified and approved on <strong>{{ $kyc->verified_at->format('d M Y, h:i A') }}</strong>.</p>
                                    <div class="mt-3">
                                        <a href="{{ route('investor.dashboard') }}" class="btn btn-success btn-sm">
                                            <i class="fas fa-rocket me-1"></i> Start Investing
                                        </a>
                                        <a href="{{ route('investor.kyc.preview') }}" class="btn btn-outline-primary btn-sm ms-2">
                                            <i class="fas fa-eye me-1"></i> View KYC Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($kyc->status === 'rejected')
                        <div class="alert alert-danger">
                            <div class="d-flex">
                                <i class="fas fa-times-circle fa-lg me-3 mt-1"></i>
                                <div>
                                    <h6 class="alert-heading">KYC Application Rejected</h6>
                                    <p class="mb-2">Your KYC application was rejected on <strong>{{ $kyc->updated_at->format('d M Y, h:i A') }}</strong>.</p>
                                    @if($kyc->rejection_reason)
                                    <div class="rejection-reason p-3 mt-2 mb-3 bg-light rounded">
                                        <strong>Reason for rejection:</strong>
                                        <p class="mb-0 mt-1">{{ $kyc->rejection_reason }}</p>
                                    </div>
                                    @endif
                                    <p>Please review the reason above and resubmit your KYC with corrected information.</p>
                                    <div class="mt-3">
                                        <a href="{{ route('investor.kyc.create') }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-redo me-1"></i> Resubmit KYC
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- KYC Summary -->
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card shadow border-0 h-100">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Personal Information</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <tr>
                                        <th width="40%">Name (Bangla):</th>
                                        <td>{{ $kyc->full_name_bn }}</td>
                                    </tr>
                                    <tr>
                                        <th>Name (English):</th>
                                        <td>{{ $kyc->full_name_en }}</td>
                                    </tr>
                                    <tr>
                                        <th>NID:</th>
                                        <td>{{ $kyc->nid }}</td>
                                    </tr>
                                    <tr>
                                        <th>Date of Birth:</th>
                                        <td>{{ $kyc->date_of_birth->format('d M Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Phone:</th>
                                        <td>{{ $kyc->phone }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td>{{ $kyc->email }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <div class="card shadow border-0 h-100">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Additional Information</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <tr>
                                        <th width="40%">Investment Range:</th>
                                        <td>
                                            @if($kyc->investment_range)
                                                @php
                                                    $ranges = [
                                                        '<100000' => 'Below 100,000 BDT',
                                                        '100000-500000' => '100,000 - 500,000 BDT',
                                                        '500000-2000000' => '500,000 - 2,000,000 BDT',
                                                        '>2000000' => 'Above 2,000,000 BDT'
                                                    ];
                                                @endphp
                                                {{ $ranges[$kyc->investment_range] ?? $kyc->investment_range }}
                                            @else
                                                <span class="text-muted">Not specified</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Occupation:</th>
                                        <td>{{ $kyc->occupation ?? 'Not specified' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Bank Name:</th>
                                        <td>{{ $kyc->bank_name ?? 'Not specified' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Account No:</th>
                                        <td>{{ $kyc->bank_account_no ?? 'Not specified' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Submitted On:</th>
                                        <td>{{ $kyc->created_at->format('d M Y, h:i A') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Last Updated:</th>
                                        <td>{{ $kyc->updated_at->format('d M Y, h:i A') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Documents & Nominees -->
                <div class="row">
                    <!-- Documents -->
                    <div class="col-md-6 mb-4">
                        <div class="card shadow border-0 h-100">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Uploaded Documents</h6>
                            </div>
                            <div class="card-body">
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-id-card text-primary me-2"></i>
                                            <span>NID Front</span>
                                        </div>
                                        <div>
                                            @if($kyc->nid_front)
                                                <a href="{{ route('investor.kyc.download', ['field' => 'nid_front', 'id' => $kyc->id]) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-download me-1"></i> Download
                                                </a>
                                            @else
                                                <span class="badge bg-warning">Pending</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-id-card text-primary me-2"></i>
                                            <span>NID Back</span>
                                        </div>
                                        <div>
                                            @if($kyc->nid_back)
                                                <a href="{{ route('investor.kyc.download', ['field' => 'nid_back', 'id' => $kyc->id]) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-download me-1"></i> Download
                                                </a>
                                            @else
                                                <span class="badge bg-warning">Pending</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-passport text-primary me-2"></i>
                                            <span>Passport</span>
                                        </div>
                                        <div>
                                            @if($kyc->passport)
                                                <a href="{{ route('investor.kyc.download', ['field' => 'passport', 'id' => $kyc->id]) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-download me-1"></i> Download
                                                </a>
                                            @else
                                                <span class="text-muted">Optional</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Nominees -->
                    <div class="col-md-6 mb-4">
                        <div class="card shadow border-0 h-100">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Nominees</h6>
                            </div>
                            <div class="card-body">
                                @if($kyc->nominees && count(json_decode($kyc->nominees, true)) > 0)
                                    @php
                                        $nominees = json_decode($kyc->nominees, true);
                                        $totalShare = collect($nominees)->sum('share_percentage');
                                    @endphp
                                    
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Relation</th>
                                                    <th>Share</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($nominees as $nominee)
                                                <tr>
                                                    <td>{{ $nominee['name'] }}</td>
                                                    <td>{{ $nominee['relation'] }}</td>
                                                    <td>{{ $nominee['share_percentage'] }}%</td>
                                                </tr>
                                                @endforeach
                                                <tr class="table-light">
                                                    <td colspan="2" class="text-end"><strong>Total:</strong></td>
                                                    <td><strong>{{ $totalShare }}%</strong></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-users fa-2x text-muted mb-3"></i>
                                        <p class="text-muted mb-0">No nominees added</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card shadow border-0">
                    <div class="card-body text-center">
                        @if($kyc->status === 'draft')
                            <a href="{{ route('investor.kyc.create') }}" class="btn btn-primary me-2">
                                <i class="fas fa-edit me-1"></i> Continue Editing
                            </a>
                            <a href="{{ route('investor.dashboard') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                            </a>
                        @elseif($kyc->status === 'rejected')
                            <a href="{{ route('investor.kyc.create') }}" class="btn btn-primary me-2">
                                <i class="fas fa-redo me-1"></i> Resubmit KYC
                            </a>
                            <a href="{{ route('investor.dashboard') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                            </a>
                        @else
                            <a href="{{ route('investor.dashboard') }}" class="btn btn-primary me-2">
                                <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                            </a>
                            {{-- <a href="{{ route('investor.kyc.preview') }}" class="btn btn-outline-primary me-2">
                                <i class="fas fa-eye me-1"></i> View Full Details
                            </a> --}}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .status-badge {
        font-size: 1rem;
        padding: 8px 16px;
        border-radius: 50px;
    }
    
    .bg-warning {
        background-color: #ffc107 !important;
    }
    
    .timeline {
        position: relative;
        padding-left: 40px;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: 20px;
        top: 0;
        bottom: 0;
        width: 2px;
        background-color: #e9ecef;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 30px;
    }
    
    .timeline-item:last-child {
        margin-bottom: 0;
    }
    
    .timeline-marker {
        position: absolute;
        left: -40px;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #fff;
        border: 3px solid #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
    }
    
    .timeline-item.completed .timeline-marker {
        border-color: #198754;
        color: #198754;
    }
    
    .timeline-item.current .timeline-marker {
        border-color: #0d6efd;
        color: #0d6efd;
        animation: pulse 2s infinite;
    }
    
    .timeline-item.rejected .timeline-marker {
        border-color: #dc3545;
        color: #dc3545;
    }
    
    .timeline-item.pending .timeline-marker {
        border-color: #6c757d;
        color: #6c757d;
        opacity: 0.6;
    }
    
    .timeline-content {
        padding: 12px 16px;
        background-color: #f8f9fa;
        border-radius: 8px;
        border-left: 4px solid #dee2e6;
    }
    
    .timeline-item.completed .timeline-content {
        border-left-color: #198754;
    }
    
    .timeline-item.current .timeline-content {
        border-left-color: #0d6efd;
    }
    
    .timeline-item.rejected .timeline-content {
        border-left-color: #dc3545;
    }
    
    .timeline-item.pending .timeline-content {
        border-left-color: #6c757d;
        opacity: 0.6;
    }
    
    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(13, 110, 253, 0.4);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(13, 110, 253, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(13, 110, 253, 0);
        }
    }
    
    .icon-box {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }
    
    .rejection-reason {
        border-left: 4px solid #dc3545;
    }
    
    .table-sm th {
        font-weight: 600;
        color: #495057;
    }
    
    .list-group-item {
        border: none;
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .list-group-item:last-child {
        border-bottom: none;
    }
</style>
@endpush

@push('js')
<script>
    // Auto refresh if under review (every 30 seconds)
    @if($kyc->status === 'pending' || $kyc->status === 'under_review')
    setTimeout(function() {
        location.reload();
    }, 30000);
    @endif
    
    // Status update notification
    @if(session('success'))
    Toastify({
        text: "{{ session('success') }}",
        duration: 3000,
        close: true,
        gravity: "top",
        position: "right",
        backgroundColor: "#198754",
        stopOnFocus: true,
    }).showToast();
    @endif
</script>
@endpush