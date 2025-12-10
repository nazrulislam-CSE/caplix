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
                    <li class="breadcrumb-item active" aria-current="page">Details</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.investor-kyc.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>

    <!-- Status Update Card -->
    <div class="card shadow border-0 mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <div class="avatar-lg me-3">
                            <div class="avatar-title bg-light rounded-circle">
                                <i class="fas fa-user fa-2x text-primary"></i>
                            </div>
                        </div>
                        <div>
                            <h5 class="mb-1">{{ $kyc->full_name_en }}</h5>
                            <p class="text-muted mb-1">{{ $kyc->full_name_bn }}</p>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-{{ $kyc->status == 'verified' ? 'success' : ($kyc->status == 'rejected' ? 'danger' : ($kyc->status == 'pending' ? 'warning' : 'info')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $kyc->status)) }}
                                </span>
                                @if($kyc->verified_at)
                                    <span class="ms-2 text-muted">
                                        <i class="fas fa-calendar-check me-1"></i>
                                        Verified on {{ $kyc->verified_at->format('d M Y') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-cog me-1"></i> Update Status
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="#" onclick="updateStatus('verified')">
                                    <i class="fas fa-check-circle text-success me-2"></i> Mark as Verified
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                    <i class="fas fa-times-circle text-danger me-2"></i> Reject KYC
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" onclick="updateStatus('pending')">
                                    <i class="fas fa-clock text-warning me-2"></i> Move to Pending
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" onclick="updateStatus('under_review')">
                                    <i class="fas fa-search text-info me-2"></i> Under Review
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Investor Details -->
    <div class="row g-3 mb-4">
        <!-- Personal Information -->
        <div class="col-md-6">
            <div class="card shadow border-0 h-100">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Personal Information</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th width="40%">Full Name (English)</th>
                            <td>{{ $kyc->full_name_en }}</td>
                        </tr>
                        <tr>
                            <th>Full Name (Bangla)</th>
                            <td>{{ $kyc->full_name_bn }}</td>
                        </tr>
                        <tr>
                            <th>NID Number</th>
                            <td>{{ $kyc->nid }}</td>
                        </tr>
                        <tr>
                            <th>Date of Birth</th>
                            <td>{{ $kyc->date_of_birth->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <th>Phone Number</th>
                            <td>{{ $kyc->phone }}</td>
                        </tr>
                        <tr>
                            <th>Email Address</th>
                            <td>{{ $kyc->email }}</td>
                        </tr>
                        <tr>
                            <th>Occupation</th>
                            <td>{{ $kyc->occupation ?? 'Not specified' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Address & Investment -->
        <div class="col-md-6">
            <div class="card shadow border-0 h-100">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Address & Investment Details</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th width="40%">Permanent Address</th>
                            <td>{{ $kyc->permanent_address }}</td>
                        </tr>
                        <tr>
                            <th>Bank Name</th>
                            <td>{{ $kyc->bank_name ?? 'Not specified' }}</td>
                        </tr>
                        <tr>
                            <th>Bank Account No.</th>
                            <td>{{ $kyc->bank_account_no ?? 'Not specified' }}</td>
                        </tr>
                        <tr>
                            <th>Investment Range</th>
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
                                    Not specified
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Phone Verified</th>
                            <td>
                                @if($kyc->owner_verified)
                                    <span class="badge bg-success">Verified</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Submitted On</th>
                            <td>{{ $kyc->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                        <tr>
                            <th>Last Updated</th>
                            <td>{{ $kyc->updated_at->format('d M Y, h:i A') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Documents & Nominees -->
    <div class="row g-3">
        <!-- Documents -->
        <div class="col-md-6">
            <div class="card shadow border-0 h-100">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Uploaded Documents</h6>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="document-card text-center border rounded p-3">
                                @if($kyc->nid_front)
                                    <i class="fas fa-id-card fa-2x text-primary mb-2"></i>
                                    <h6>NID Front</h6>
                                    <a href="{{ route('admin.investor-kyc.download', ['id' => $kyc->id, 'field' => 'nid_front']) }}" 
                                       class="btn btn-sm btn-outline-primary mt-2">
                                        <i class="fas fa-download me-1"></i> Download
                                    </a>
                                @else
                                    <i class="fas fa-times-circle fa-2x text-danger mb-2"></i>
                                    <h6>NID Front</h6>
                                    <span class="badge bg-danger">Not Uploaded</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="document-card text-center border rounded p-3">
                                @if($kyc->nid_back)
                                    <i class="fas fa-id-card fa-2x text-primary mb-2"></i>
                                    <h6>NID Back</h6>
                                    <a href="{{ route('admin.investor-kyc.download', ['id' => $kyc->id, 'field' => 'nid_back']) }}" 
                                       class="btn btn-sm btn-outline-primary mt-2">
                                        <i class="fas fa-download me-1"></i> Download
                                    </a>
                                @else
                                    <i class="fas fa-times-circle fa-2x text-danger mb-2"></i>
                                    <h6>NID Back</h6>
                                    <span class="badge bg-danger">Not Uploaded</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="document-card text-center border rounded p-3">
                                @if($kyc->passport)
                                    <i class="fas fa-passport fa-2x text-info mb-2"></i>
                                    <h6>Passport</h6>
                                    <a href="{{ route('admin.investor-kyc.download', ['id' => $kyc->id, 'field' => 'passport']) }}" 
                                       class="btn btn-sm btn-outline-info mt-2">
                                        <i class="fas fa-download me-1"></i> Download
                                    </a>
                                @else
                                    <i class="fas fa-passport fa-2x text-secondary mb-2"></i>
                                    <h6>Passport</h6>
                                    <span class="badge bg-secondary">Optional</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Nominees -->
        <div class="col-md-6">
            <div class="card shadow border-0 h-100">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Nominees</h6>
                </div>
                <div class="card-body">
                    @if(count($nominees) > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Relation</th>
                                        <th>NID</th>
                                        <th>Share %</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($nominees as $nominee)
                                    <tr>
                                        <td>{{ $nominee['name'] }}</td>
                                        <td>{{ $nominee['relation'] }}</td>
                                        <td>{{ $nominee['nid'] }}</td>
                                        <td>{{ $nominee['share_percentage'] }}%</td>
                                    </tr>
                                    @endforeach
                                    <tr class="table-light">
                                        <td colspan="3" class="text-end"><strong>Total Share:</strong></td>
                                        <td><strong>{{ collect($nominees)->sum('share_percentage') }}%</strong></td>
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

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reject Investor KYC</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('admin.investor-kyc.update-status', $kyc->id) }}" id="rejectForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="rejected">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Reason for Rejection *</label>
                            <textarea name="rejection_reason" class="form-control" rows="4" 
                                      placeholder="Please specify the reason for rejection..." required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Admin Notes (Optional)</label>
                            <textarea name="admin_notes" class="form-control" rows="3" 
                                      placeholder="Add any additional notes..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Reject KYC</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function updateStatus(status) {
        if (!confirm(`Are you sure you want to mark this KYC as ${status.toUpperCase()}?`)) {
            return;
        }
        
        let form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.investor-kyc.update-status", $kyc->id) }}';
        
        let csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        let methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PUT';
        
        let statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = status;
        
        form.appendChild(csrfToken);
        form.appendChild(methodInput);
        form.appendChild(statusInput);
        
        document.body.appendChild(form);
        form.submit();
    }
</script>
@endpush