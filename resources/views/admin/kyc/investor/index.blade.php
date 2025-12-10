@extends('layouts.admin', ['pageTitle' => $pageTitle])
@section('content')
    <!-- Breadcrumb -->
    <div class="breadcrumb-header d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">{{ $pageTitle }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
            </ol>
        </nav>
    </div>

    <!-- Filters -->
    <div class="card shadow border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.investor-kyc.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="all">All Status</option>
                            <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="under_review" {{ $status == 'under_review' ? 'selected' : '' }}>Under Review
                            </option>
                            <option value="verified" {{ $status == 'verified' ? 'selected' : '' }}>Verified</option>
                            <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control"
                            placeholder="Search by name, phone, email, NID..." value="{{ $search }}">
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-1"></i> Search
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <!-- Total Investors -->
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-card border-0 shadow-sm">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h5 class="text-primary mb-0">{{ $totalInvestors }}</h5>
                        <small class="text-muted">Total Investors</small>
                    </div>
                    <div class="icon-box bg-primary-soft rounded-circle d-flex align-items-center justify-content-center"
                        style="width: 40px; height: 40px;">
                        <i class="fas fa-users text-primary"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending -->
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-card border-0 shadow-sm">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h5 class="text-warning mb-0">{{ $pendingCount }}</h5>
                        <small class="text-muted">Pending</small>
                    </div>
                    <div class="icon-box bg-warning-soft rounded-circle d-flex align-items-center justify-content-center"
                        style="width: 40px; height: 40px;">
                        <i class="fas fa-clock text-warning"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Verified -->
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-card border-0 shadow-sm">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h5 class="text-success mb-0">{{ $verifiedCount }}</h5>
                        <small class="text-muted">Verified</small>
                    </div>
                    <div class="icon-box bg-success-soft rounded-circle d-flex align-items-center justify-content-center"
                        style="width: 40px; height: 40px;">
                        <i class="fas fa-check-circle text-success"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rejected -->
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-card border-0 shadow-sm">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h5 class="text-danger mb-0">{{ $rejectedCount }}</h5>
                        <small class="text-muted">Rejected</small>
                    </div>
                    <div class="icon-box bg-danger-soft rounded-circle d-flex align-items-center justify-content-center"
                        style="width: 40px; height: 40px;">
                        <i class="fas fa-times-circle text-danger"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Verified Investors -->
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-card border-0 shadow-sm">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h5 class="text-purple mb-0">{{ $verifiedCount }}</h5>
                        <small class="text-muted">Verified Investors</small>
                    </div>
                    <div class="icon-box bg-purple-soft rounded-circle d-flex align-items-center justify-content-center"
                        style="width: 40px; height: 40px;">
                        <i class="fas fa-badge-check text-purple"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- KYC Pending -->
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-card border-0 shadow-sm">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h5 class="text-info mb-0">{{ $pendingCount }}</h5>
                        <small class="text-muted">KYC Pending</small>
                    </div>
                    <div class="icon-box bg-info-soft rounded-circle d-flex align-items-center justify-content-center"
                        style="width: 40px; height: 40px;">
                        <i class="fas fa-user-clock text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Investor KYC List Table -->
    <div class="card shadow border-0">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Investor KYC List</h6>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-primary" onclick="printTable()">
                    <i class="fas fa-print me-1"></i> Print
                </button>
                <button class="btn btn-sm btn-outline-success" onclick="exportToExcel()">
                    <i class="fas fa-file-excel me-1"></i> Export
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="investorKycTable">
                    <thead class="table-light">
                        <tr>
                            <th width="50">#</th>
                            <th>Investor</th>
                            <th>Investor Details</th>
                            <th>Documents</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($investorKycs as $kyc)
                            <tr>
                                <td>{{ $loop->iteration + ($investorKycs->currentPage() - 1) * $investorKycs->perPage() }}
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2">
                                            <div class="avatar-title bg-light rounded-circle">
                                                <i class="fas fa-user text-primary"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <strong>{{ $kyc->full_name_en ?? 'N/A' }}</strong>
                                            @if ($kyc->full_name_bn)
                                                <br><small class="text-muted">{{ $kyc->full_name_bn }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>NID: </strong>{{ $kyc->nid ?? 'N/A' }}
                                        <br><strong>Phone: </strong>{{ $kyc->phone ?? 'N/A' }}
                                        <br><strong>Email: </strong>{{ $kyc->email ?? 'N/A' }}
                                        @if ($kyc->occupation)
                                            <br><strong>Occupation: </strong>{{ $kyc->occupation }}
                                        @endif
                                        @if ($kyc->investment_range)
                                            <br><strong>Investment Range: </strong>
                                            @php
                                                $ranges = [
                                                    '<100000' => 'Below 100,000 BDT',
                                                    '100000-500000' => '100,000 - 500,000 BDT',
                                                    '500000-2000000' => '500,000 - 2,000,000 BDT',
                                                    '>2000000' => 'Above 2,000,000 BDT',
                                                ];
                                            @endphp
                                            {{ $ranges[$kyc->investment_range] ?? $kyc->investment_range }}
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        @if ($kyc->nid_front)
                                            <a href="{{ route('admin.investor-kyc.download', ['id' => $kyc->id, 'field' => 'nid_front']) }}"
                                                class="btn btn-sm btn-primary" data-bs-toggle="tooltip"
                                                title="NID Front">
                                                <i class="fas fa-id-card"></i>
                                            </a>
                                        @else
                                            <span class="badge bg-warning">N/A</span>
                                        @endif

                                        @if ($kyc->nid_back)
                                            <a href="{{ route('admin.investor-kyc.download', ['id' => $kyc->id, 'field' => 'nid_back']) }}"
                                                class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="NID Back">
                                                <i class="fas fa-id-card"></i>
                                            </a>
                                        @else
                                            <span class="badge bg-warning">N/A</span>
                                        @endif

                                        @if ($kyc->passport)
                                            <a href="{{ route('admin.investor-kyc.download', ['id' => $kyc->id, 'field' => 'passport']) }}"
                                                class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Passport">
                                                <i class="fas fa-passport"></i>
                                            </a>
                                        @else
                                            <span class="badge bg-secondary">Optional</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span
                                        class="badge bg-{{ $kyc->status == 'verified' ? 'success' : ($kyc->status == 'rejected' ? 'danger' : ($kyc->status == 'pending' ? 'warning' : 'info')) }}">
                                        {{ ucfirst(str_replace('_', ' ', $kyc->status)) }}
                                    </span>
                                    @if ($kyc->status == 'rejected' && $kyc->rejection_reason)
                                        <br><small class="text-danger" data-bs-toggle="tooltip"
                                            title="{{ $kyc->rejection_reason }}">
                                            <i class="fas fa-info-circle"></i> Rejected
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    {{ $kyc->created_at->format('d M, Y') }}
                                    <br><small class="text-muted">{{ $kyc->created_at->format('h:i A') }}</small>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <!-- View Details Button -->
                                        <a href="{{ route('admin.investor-kyc.show', $kyc->id) }}"
                                            class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <!-- Edit Status Button -->
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#editStatusModal{{ $kyc->id }}" data-bs-toggle="tooltip"
                                            title="Edit Status">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <!-- Full Edit Button -->
                                        <a href="{{ route('admin.investor-kyc.edit', $kyc->id) }}"
                                            class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>

                                        <!-- Quick Verify Button -->
                                        @if ($kyc->status != 'verified')
                                            <button class="btn btn-sm btn-success"
                                                onclick="updateStatus({{ $kyc->id }}, 'verified')"
                                                data-bs-toggle="tooltip" title="Mark as Verified">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @endif

                                        <!-- Quick Reject Button -->
                                        @if ($kyc->status != 'rejected')
                                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#rejectModal{{ $kyc->id }}" data-bs-toggle="tooltip"
                                                title="Reject KYC">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif

                                        <!-- Restore from Rejected -->
                                        @if ($kyc->status == 'rejected')
                                            <button class="btn btn-sm btn-warning"
                                                onclick="updateStatus({{ $kyc->id }}, 'pending')"
                                                data-bs-toggle="tooltip" title="Move to Pending">
                                                <i class="fas fa-redo"></i>
                                            </button>
                                        @endif
                                    </div>

                                    <!-- Edit Status Modal -->
                                    <div class="modal fade" id="editStatusModal{{ $kyc->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit KYC Status</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST"
                                                    action="{{ route('admin.investor-kyc.update-status', $kyc->id) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Current Status</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ ucfirst($kyc->status) }}" readonly>
                                                            <small class="text-muted">Submitted:
                                                                {{ $kyc->created_at->format('d M Y, h:i A') }}</small>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Select New Status *</label>
                                                            <select name="status" class="form-select" required
                                                                onchange="toggleRejectionField{{ $kyc->id }}(this.value)">
                                                                <option value="draft"
                                                                    {{ $kyc->status == 'draft' ? 'selected' : '' }}>Draft
                                                                </option>
                                                                <option value="pending"
                                                                    {{ $kyc->status == 'pending' ? 'selected' : '' }}>
                                                                    Pending</option>
                                                                <option value="under_review"
                                                                    {{ $kyc->status == 'under_review' ? 'selected' : '' }}>
                                                                    Under Review</option>
                                                                <option value="verified"
                                                                    {{ $kyc->status == 'verified' ? 'selected' : '' }}>
                                                                    Verified</option>
                                                                <option value="rejected"
                                                                    {{ $kyc->status == 'rejected' ? 'selected' : '' }}>
                                                                    Rejected</option>
                                                            </select>
                                                        </div>

                                                        <div id="rejectionField{{ $kyc->id }}"
                                                            style="display: {{ $kyc->status == 'rejected' ? 'block' : 'none' }};">
                                                            <div class="mb-3">
                                                                <label class="form-label">Reason for Rejection *</label>
                                                                <textarea name="rejection_reason" class="form-control" rows="3"
                                                                    placeholder="Please specify the reason for rejection..." {{ $kyc->status == 'rejected' ? 'required' : '' }}>{{ old('rejection_reason', $kyc->rejection_reason) }}</textarea>
                                                            </div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Admin Notes (Optional)</label>
                                                            <textarea name="admin_notes" class="form-control" rows="3"
                                                                placeholder="Add any internal notes or comments...">{{ old('admin_notes', $kyc->admin_notes) }}</textarea>
                                                        </div>

                                                        <div class="alert alert-info">
                                                            <i class="fas fa-info-circle me-2"></i>
                                                            <small>Changing status will notify the investor via
                                                                email.</small>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Update
                                                            Status</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Reject Modal (Existing) -->
                                    <div class="modal fade" id="rejectModal{{ $kyc->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Reject KYC</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST"
                                                    action="{{ route('admin.investor-kyc.update-status', $kyc->id) }}">
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
                                                            <textarea name="admin_notes" class="form-control" rows="3" placeholder="Add any additional notes..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger">Reject KYC</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Add Notes Modal -->
                                    <div class="modal fade" id="notesModal{{ $kyc->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Add Admin Notes</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST"
                                                    action="{{ route('admin.investor-kyc.update-notes', $kyc->id) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Admin Notes</label>
                                                            <textarea name="admin_notes" class="form-control" rows="5"
                                                                placeholder="Add internal notes or comments about this KYC...">{{ old('admin_notes', $kyc->admin_notes) }}</textarea>
                                                            <small class="text-muted">These notes are only visible to admin
                                                                users.</small>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Save Notes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>


                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="icon-box bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                        style="width: 80px; height: 80px;">
                                        <i class="fas fa-user-slash fa-2x text-muted"></i>
                                    </div>
                                    <h5 class="text-muted">No Investor KYC submissions found</h5>
                                    <p class="text-muted mb-0">When investors submit KYC, they will appear here.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($investorKycs->hasPages())
                <div class="card-footer border-0">
                    {{ $investorKycs->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .avatar-sm {
            width: 36px;
            height: 36px;
        }

        .avatar-title {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .stat-card {
            transition: transform 0.2s;
            background: #fff;
        }

        .stat-card:hover {
            transform: translateY(-3px);
        }

        .icon-box {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .bg-primary-soft {
            background-color: rgba(13, 110, 253, 0.1) !important;
        }

        .bg-success-soft {
            background-color: rgba(25, 135, 84, 0.1) !important;
        }

        .bg-warning-soft {
            background-color: rgba(255, 193, 7, 0.1) !important;
        }

        .bg-danger-soft {
            background-color: rgba(220, 53, 69, 0.1) !important;
        }

        .bg-info-soft {
            background-color: rgba(13, 202, 240, 0.1) !important;
        }

        .bg-purple-soft {
            background-color: rgba(111, 66, 193, 0.1) !important;
        }

        .text-purple {
            color: #6f42c1 !important;
        }
    </style>
@endpush

@push('js')
    <script>
        // Toggle rejection reason field in edit modal
        function toggleRejectionField{{ $kyc->id }}(status) {
            const field = document.getElementById('rejectionField{{ $kyc->id }}');
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

        // Update Status function
        function updateStatus(id, status) {
            if (!confirm(`Are you sure you want to mark this KYC as ${status.toUpperCase()}?`)) {
                return;
            }

            let form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/investor-kyc/${id}/status`;

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

        // Download all KYC documents
        function downloadKycDocs(id) {
            // Create a zip download or individual file downloads
            // For now, show a message
            alert('Document download feature will be implemented');

            // Alternatively, redirect to a download route
            // window.location.href = `/admin/investor-kyc/${id}/download-all`;
        }

        // Delete KYC confirmation
        function deleteKyc(id) {
            if (confirm('Are you sure you want to delete this KYC? This action cannot be undone.')) {
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/investor-kyc/${id}`;

                let csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';

                let methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';

                form.appendChild(csrfToken);
                form.appendChild(methodInput);

                document.body.appendChild(form);
                form.submit();
            }
        }

        function printTable() {
            let printWindow = window.open('', '_blank');
            printWindow.document.write(`
            <html>
            <head>
                <title>Investor KYC List - {{ date('Y-m-d') }}</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    th { background-color: #f5f5f5; }
                    .header { text-align: center; margin-bottom: 30px; }
                    .header h2 { margin: 0; }
                    .header p { margin: 5px 0; color: #666; }
                </style>
            </head>
            <body>
                <div class="header">
                    <h2>Investor KYC Verification List</h2>
                    <p>Generated on: {{ date('d M Y h:i A') }}</p>
                    <p>Total Investors: {{ $totalInvestors }} | 
                       Pending: {{ $pendingCount }} | 
                       Verified: {{ $verifiedCount }} | 
                       Rejected: {{ $rejectedCount }}</p>
                </div>
        `);

            printWindow.document.write(document.getElementById('investorKycTable').outerHTML);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }

        function exportToExcel() {
            // You can implement Excel export using a library like SheetJS
            alert('Excel export functionality will be implemented');
            // For now, redirect to an export route

        }

        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endpush
