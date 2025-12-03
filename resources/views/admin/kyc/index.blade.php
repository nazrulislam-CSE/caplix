{{-- resources/views/admin/kyc/index.blade.php --}}
@extends('layouts.admin')

@section('content')
    <!-- Breadcrumb -->
    <div class="breadcrumb-header d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">{{ $pageTitle ?? '' }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle ?? '' }}</li>
            </ol>
        </nav>
    </div>

    <!-- KYC List Container -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-lg border-0">
                    <div class="card-body">
                        <!-- Filters and Search -->
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <form method="GET" action="{{ route('admin.kyc.index') }}">
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <select name="status" class="form-select" onchange="this.form.submit()">
                                                <option value="">All Status</option>
                                                @foreach ($statuses as $key => $label)
                                                    <option value="{{ $key }}"
                                                        {{ request('status') == $key ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <input type="text" name="search" class="form-control"
                                                    placeholder="Search by company, owner, phone, email..."
                                                    value="{{ request('search') }}">
                                                <button class="btn btn-primary" type="submit">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <a href="{{ route('admin.kyc.index') }}"
                                                class="btn btn-outline-secondary w-100">
                                                <i class="fas fa-redo"></i> Reset
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                        data-bs-target="#exportModal">
                                        <i class="fas fa-file-export"></i> Export
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Stats -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-0">Total KYC</h6>
                                                <h3 class="mb-0">{{ $totalCount }}</h3>
                                            </div>
                                            <i class="fas fa-file-alt fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-0">Pending</h6>
                                                <h3 class="mb-0">{{ $pendingCount }}</h3>
                                            </div>
                                            <i class="fas fa-clock fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-0">Verified</h6>
                                                <h3 class="mb-0">{{ $verifiedCount }}</h3>
                                            </div>
                                            <i class="fas fa-check-circle fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-danger text-white">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-0">Rejected</h6>
                                                <h3 class="mb-0">{{ $rejectedCount }}</h3>
                                            </div>
                                            <i class="fas fa-times-circle fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- KYC List Table -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th width="50">Sl</th>
                                        <th>Company / Business</th>
                                        <th>Entrepreneur</th>
                                        <th>Owner Details</th>
                                        <th>Documents</th>
                                        <th>Status</th>
                                        <th>Submitted</th>
                                        <th width="150">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($kycs as $kyc)
                                        <tr>
                                            <td>{{ $loop->iteration + $kycs->perPage() * ($kycs->currentPage() - 1) }}
                                            </td>
                                            <td>
                                                <strong>{{ $kyc->company_name }}</strong><br>
                                                <small class="text-muted">
                                                    {{ $kyc->business_type ?? 'N/A' }} |
                                                    Reg: {{ $kyc->registration_no ?? 'N/A' }}
                                                </small>
                                            </td>
                                            <td>
                                                @if ($kyc->user)
                                                    <strong>{{ $kyc->user->name }}</strong><br>
                                                    <small class="text-muted">{{ $kyc->user->email }}</small><br>
                                                    <small class="text-muted">{{ $kyc->user->phone ?? 'N/A' }}</small>
                                                @else
                                                    <span class="text-danger">User not found</span>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ $kyc->owner_name }}</strong><br>
                                                <small class="text-muted">{{ $kyc->owner_phone }}</small><br>
                                                <small class="text-muted">{{ $kyc->owner_email }}</small>
                                            </td>
                                            <td>
                                                @php
                                                    $docCount = 0;
                                                    $documents = [
                                                        'doc_registration',
                                                        'doc_trade_license',
                                                        'doc_tin',
                                                        'doc_bank_statement',
                                                        'doc_financials',
                                                    ];
                                                    foreach ($documents as $doc) {
                                                        if (!empty($kyc->$doc)) {
                                                            $docCount++;
                                                        }
                                                    }
                                                @endphp
                                                <span class="badge bg-info">{{ $docCount }} files</span>
                                            </td>
                                            <td>
                                                @if ($kyc->status == 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif($kyc->status == 'under_review')
                                                    <span class="badge bg-info">Under Review</span>
                                                @elseif($kyc->status == 'verified')
                                                    <span class="badge bg-success">Verified</span>
                                                    <br><small>{{ $kyc->verified_at->format('d M, Y') ?? '' }}</small>
                                                @elseif($kyc->status == 'rejected')
                                                    <span class="badge bg-danger">Rejected</span>
                                                    @if ($kyc->rejection_reason)
                                                        <br><small class="text-danger"
                                                            title="{{ $kyc->rejection_reason }}">
                                                            <i class="fas fa-info-circle"></i> Reason
                                                        </small>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                {{ $kyc->created_at->format('d M, Y') }}<br>
                                                <small class="text-muted">{{ $kyc->created_at->format('h:i A') }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <!-- View Button -->
                                                    <button type="button" class="btn btn-info" data-bs-toggle="modal"
                                                        data-bs-target="#viewKycModal"
                                                        onclick="loadKycDetails({{ $kyc->id }})"
                                                        title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </button>

                                                    <!-- Quick Status Update Button -->
                                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                                        data-bs-target="#statusModal"
                                                        onclick="setKycId({{ $kyc->id }}, '{{ $kyc->status }}')"
                                                        title="Update Status">
                                                        <i class="fas fa-edit"></i>
                                                    </button>

                                                    <!-- Full Edit Button -->
                                                    <a href="{{ route('admin.kyc.edit', $kyc->id) }}"
                                                        class="btn btn-primary" title="Edit">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>

                                                    <!-- Delete Button -->
                                                    <button type="button" class="btn btn-danger"
                                                        onclick="confirmDelete({{ $kyc->id }})" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4">
                                                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                                <h5>No KYC Applications Found</h5>
                                                <p class="text-muted">No business KYC verification requests available.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div class="text-muted">
                                Showing {{ $kycs->firstItem() ?? 0 }} to {{ $kycs->lastItem() ?? 0 }} of
                                {{ $kycs->total() }} entries
                            </div>
                            <div>
                                {{ $kycs->links() }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View KYC Details Modal -->
    <div class="modal fade" id="viewKycModal" tabindex="-1" aria-labelledby="viewKycModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewKycModalLabel">KYC Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="kycDetailsContent">
                    <!-- Content will be loaded via AJAX -->
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading details...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Update Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">Update KYC Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="statusForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" name="kyc_id" id="modalKycId">

                        <div class="mb-3">
                            <label for="statusSelect" class="form-label">Select Status</label>
                            <select class="form-select" id="statusSelect" name="status" required>
                                <option value="pending">Pending</option>
                                <option value="under_review">Under Review</option>
                                <option value="verified">Verified</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>

                        <div class="mb-3" id="rejectionReasonDiv" style="display: none;">
                            <label for="rejectionReason" class="form-label">Rejection Reason</label>
                            <textarea class="form-control" id="rejectionReason" name="rejection_reason" rows="3"
                                placeholder="Please provide reason for rejection..."></textarea>
                            <small class="text-muted">Required when status is set to Rejected</small>
                        </div>

                        <div class="mb-3">
                            <label for="adminNotes" class="form-label">Admin Notes (Optional)</label>
                            <textarea class="form-control" id="adminNotes" name="notes" rows="2"
                                placeholder="Add any additional notes..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this KYC application?</p>
                    <p class="text-danger"><strong>This action cannot be undone.</strong></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Modal -->
    <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exportModalLabel">Export KYC Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="GET" action="{{ route('admin.kyc.export') }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="exportFormat" class="form-label">Format</label>
                            <select class="form-select" id="exportFormat" name="format">
                                <option value="excel">Excel (.xlsx)</option>
                                <option value="csv">CSV (.csv)</option>
                                <option value="pdf">PDF (.pdf)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="exportStatus" class="form-label">Filter by Status</label>
                            <select class="form-select" id="exportStatus" name="status">
                                <option value="">All Status</option>
                                @foreach ($statuses as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="exportDate" class="form-label">Date Range</label>
                            <select class="form-select" id="exportDate" name="date_range">
                                <option value="all">All Time</option>
                                <option value="today">Today</option>
                                <option value="week">This Week</option>
                                <option value="month">This Month</option>
                                <option value="year">This Year</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Export</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        // Load KYC details in modal
        function loadKycDetails(kycId) {
            $('#kycDetailsContent').html(`
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading details...</p>
        </div>
    `);

            $.ajax({
                url: '{{ url('admin/kyc') }}/' + kycId + '/details',
                method: 'GET',
                success: function(response) {
                    $('#kycDetailsContent').html(response);
                },
                error: function() {
                    $('#kycDetailsContent').html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    Failed to load KYC details. Please try again.
                </div>
            `);
                }
            });
        }

        // Set KYC ID for status update modal
        function setKycId(kycId, currentStatus) {
            $('#modalKycId').val(kycId);
            $('#statusSelect').val(currentStatus);

            // Set form action
            $('#statusForm').attr('action', '{{ url('admin/kyc') }}/' + kycId + '/status');

            // Show/hide rejection reason field
            toggleRejectionReason(currentStatus);
        }

        // Toggle rejection reason field
        $('#statusSelect').change(function() {
            toggleRejectionReason($(this).val());
        });

        function toggleRejectionReason(status) {
            if (status === 'rejected') {
                $('#rejectionReasonDiv').show();
                $('#rejectionReason').prop('required', true);
            } else {
                $('#rejectionReasonDiv').hide();
                $('#rejectionReason').prop('required', false);
            }
        }

        // Submit status update form via AJAX
        $('#statusForm').submit(function(e) {
            e.preventDefault();

            const form = $(this);
            const formData = form.serialize();
            const url = form.attr('action');

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        showToast('Status updated successfully!', 'success');

                        // Close modal
                        $('#statusModal').modal('hide');

                        // Reload page after 1 second
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    }
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    let errorMsg = 'Failed to update status.';

                    if (errors) {
                        errorMsg = Object.values(errors).join('<br>');
                    }

                    showToast(errorMsg, 'error');
                }
            });
        });

        // Delete confirmation
        function confirmDelete(kycId) {
            $('#deleteForm').attr('action', '{{ url('admin/kyc') }}/' + kycId);
            $('#deleteModal').modal('show');
        }

        // Toast notification
        function showToast(message, type = 'info') {
            const toast = `
        <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0" 
             role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;

            $('.toast-container').append(toast);
            $('.toast:last').toast('show');

            // Remove toast after 5 seconds
            setTimeout(() => {
                $('.toast:last').remove();
            }, 5000);
        }

        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>

    <!-- Toast container -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;"></div>
@endpush

@push('styles')
    <style>
        .kyc-doc-list {
            list-style: none;
            padding: 0;
        }

        .kyc-doc-list li {
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }

        .kyc-doc-list li:last-child {
            border-bottom: none;
        }

        .doc-badge {
            cursor: pointer;
            transition: all 0.3s;
        }

        .doc-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush
