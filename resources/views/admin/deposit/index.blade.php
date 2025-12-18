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

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Total Deposits</h6>
                            <h3 class="mb-0">{{ $totalDeposits }}</h3>
                        </div>
                        <div class="icon">
                            <i class="fas fa-exchange-alt fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Total Amount</h6>
                            <h3 class="mb-0">৳{{ number_format($totalAmount, 2) }}</h3>
                        </div>
                        <div class="icon">
                            <i class="fas fa-wallet fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Pending Deposits</h6>
                            <h3 class="mb-0">{{ $pendingDeposits }}</h3>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clock fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Pending Amount</h6>
                            <h3 class="mb-0">৳{{ number_format($pendingAmount, 2) }}</h3>
                        </div>
                        <div class="icon">
                            <i class="fas fa-money-bill-wave fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow border-0 mb-4">
        <div class="card-body">
            <form action="{{ route('admin.deposit.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Payment Method</label>
                    <select name="payment_method" class="form-select">
                        <option value="">All Methods</option>
                        <option value="bKash" {{ request('payment_method') == 'bKash' ? 'selected' : '' }}>bKash</option>
                        <option value="Nagad" {{ request('payment_method') == 'Nagad' ? 'selected' : '' }}>Nagad</option>
                        <option value="Bank" {{ request('payment_method') == 'Bank' ? 'selected' : '' }}>Bank</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date From</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date To</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Search</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" 
                               placeholder="Search by TXN ID, Name, Email..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <a href="{{ route('admin.deposit.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-redo me-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Deposits Table -->
    <div class="card shadow border-0">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">All Deposits</h5>
            <div class="d-flex">
                <form method="POST" action="{{ route('admin.deposit.bulk.update') }}" class="d-inline me-2">
                    @csrf
                    <input type="hidden" name="status" id="bulk_status" value="">
                    <input type="hidden" name="admin_note" id="bulk_admin_note" value="">
                    <input type="hidden" name="ids" id="bulk_ids" value="">
                    <button type="button" class="btn btn-outline-success btn-sm me-2" onclick="bulkApprove()">
                        <i class="fas fa-check-circle me-1"></i> Bulk Approve
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="bulkReject()">
                        <i class="fas fa-times-circle me-1"></i> Bulk Reject
                    </button>
                </form>
                <div class="dropdown">
                    <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" 
                            data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-download me-1"></i> Export
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-file-excel me-2"></i> Excel</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-file-pdf me-2"></i> PDF</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-light">
                        <tr>
                            <th width="50">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAll">
                                </div>
                            </th>
                            <th>Sl</th>
                            <th>Date & Time</th>
                            <th>Investor</th>
                            <th>TXN ID</th>
                            <th>Method</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Payment Slip</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deposits as $deposit)
                        <tr>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input deposit-checkbox" type="checkbox" 
                                           value="{{ $deposit->id }}" data-status="{{ $deposit->status }}">
                                </div>
                            </td>
                            <td>{{ $loop->iteration + ($deposits->currentPage() - 1) * $deposits->perPage() }}</td>
                            <td>
                                <div>{{ $deposit->created_at->format('d M Y') }}</div>
                                <small class="text-muted">{{ $deposit->created_at->format('h:i A') }}</small>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="bg-primary bg-opacity-10 rounded-circle p-2">
                                            <i class="fas fa-user text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <h6 class="mb-0">{{ $deposit->user->name ?? 'N/A' }}</h6>
                                        <small class="text-muted">{{ $deposit->user->email ?? '' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <code>{{ $deposit->transaction_id }}</code>
                            </td>
                            <td>
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
                            </td>
                            <td>
                                <strong>৳{{ number_format($deposit->amount, 2) }}</strong>
                            </td>
                            <td>
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
                            </td>
                            <td>
                                @if($deposit->payment_slip)
                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#slipModal{{ $deposit->id }}">
                                        <i class="fas fa-eye me-1"></i> View
                                    </button>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#updateModal{{ $deposit->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="{{ route('admin.deposit.show', $deposit->id) }}" 
                                       class="btn btn-outline-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>

                        <!-- Payment Slip Modal -->
                        @if($deposit->payment_slip)
                        <div class="modal fade" id="slipModal{{ $deposit->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Payment Slip - {{ $deposit->transaction_id }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        @if(pathinfo($deposit->payment_slip, PATHINFO_EXTENSION) == 'pdf')
                                            <iframe src="{{ asset('storage/' . $deposit->payment_slip) }}" 
                                                    width="100%" height="500px"></iframe>
                                        @else
                                            <img src="{{ asset('storage/' . $deposit->payment_slip) }}" 
                                                 alt="Payment Slip" 
                                                 class="img-fluid rounded">
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <a href="{{ asset('storage/' . $deposit->payment_slip) }}" 
                                           download 
                                           class="btn btn-primary">
                                            <i class="fas fa-download me-2"></i>Download
                                        </a>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Update Status Modal -->
                        <div class="modal fade" id="updateModal{{ $deposit->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.deposit.update', $deposit->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Update Deposit Status</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Deposit Details</label>
                                                <div class="card bg-light border-0 p-3">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <small class="text-muted">Investor</small>
                                                            <p class="mb-1 fw-bold">{{ $deposit->user->name ?? 'N/A' }}</p>
                                                        </div>
                                                        <div class="col-6">
                                                            <small class="text-muted">Amount</small>
                                                            <p class="mb-1 fw-bold text-primary">৳{{ number_format($deposit->amount, 2) }}</p>
                                                        </div>
                                                        <div class="col-6">
                                                            <small class="text-muted">TXN ID</small>
                                                            <p class="mb-1"><code>{{ $deposit->transaction_id }}</code></p>
                                                        </div>
                                                        <div class="col-6">
                                                            <small class="text-muted">Method</small>
                                                            <p class="mb-1">{{ $deposit->payment_method }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="form-label">Current Status</label>
                                                <div>
                                                    @if($deposit->status == 'pending')
                                                        <span class="badge bg-warning">Pending</span>
                                                    @elseif($deposit->status == 'approved')
                                                        <span class="badge bg-success">Approved</span>
                                                    @else
                                                        <span class="badge bg-danger">Rejected</span>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="form-label">Update Status *</label>
                                                <select name="status" class="form-select" required>
                                                    <option value="pending" {{ $deposit->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="approved" {{ $deposit->status == 'approved' ? 'selected' : '' }}>Approve</option>
                                                    <option value="rejected" {{ $deposit->status == 'rejected' ? 'selected' : '' }}>Reject</option>
                                                </select>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="form-label">Admin Note (Optional)</label>
                                                <textarea name="admin_note" class="form-control" rows="3" 
                                                          placeholder="Add note for the user...">{{ $deposit->admin_note }}</textarea>
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
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">
                                <div class="empty-state">
                                    <i class="fas fa-exchange-alt fa-3x text-muted mb-3"></i>
                                    <h5>No Deposits Found</h5>
                                    <p class="text-muted">No deposit records available.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($deposits->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Showing {{ $deposits->firstItem() }} to {{ $deposits->lastItem() }} of {{ $deposits->total() }} entries
                </div>
                <nav aria-label="Page navigation">
                    {{ $deposits->withQueryString()->links() }}
                </nav>
            </div>
            @endif
        </div>
    </div>
@endsection

@push('js')
<script>
    // Select All Checkbox
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.deposit-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Bulk Actions
    function bulkApprove() {
        const selectedIds = getSelectedIds();
        if (selectedIds.length === 0) {
            alert('Please select at least one deposit.');
            return;
        }
        
        if (confirm(`Approve ${selectedIds.length} deposit(s)?`)) {
            document.getElementById('bulk_status').value = 'approved';
            document.getElementById('bulk_admin_note').value = 'Bulk approved by admin';
            document.getElementById('bulk_ids').value = JSON.stringify(selectedIds);
            document.getElementById('bulk_status').closest('form').submit();
        }
    }

    function bulkReject() {
        const selectedIds = getSelectedIds();
        if (selectedIds.length === 0) {
            alert('Please select at least one deposit.');
            return;
        }
        
        if (confirm(`Reject ${selectedIds.length} deposit(s)?`)) {
            document.getElementById('bulk_status').value = 'rejected';
            document.getElementById('bulk_admin_note').value = 'Bulk rejected by admin';
            document.getElementById('bulk_ids').value = JSON.stringify(selectedIds);
            document.getElementById('bulk_status').closest('form').submit();
        }
    }

    function getSelectedIds() {
        const checkboxes = document.querySelectorAll('.deposit-checkbox:checked');
        const ids = [];
        checkboxes.forEach(checkbox => {
            // Only allow selecting pending deposits
            if (checkbox.dataset.status === 'pending') {
                ids.push(checkbox.value);
            }
        });
        return ids;
    }

    // Update checkbox when clicking row
    document.addEventListener('DOMContentLoaded', function() {
        const rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => {
            row.addEventListener('click', function(e) {
                // Don't trigger if clicking on button or link
                if (e.target.tagName === 'BUTTON' || 
                    e.target.tagName === 'A' || 
                    e.target.tagName === 'INPUT' ||
                    e.target.closest('button') || 
                    e.target.closest('a')) {
                    return;
                }
                
                const checkbox = this.querySelector('.deposit-checkbox');
                if (checkbox) {
                    checkbox.checked = !checkbox.checked;
                }
            });
        });
    });
</script>

<style>
.empty-state {
    padding: 3rem 1rem;
    text-align: center;
}

.empty-state i {
    opacity: 0.5;
}

.table th {
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    color: #6c757d;
    border-bottom: 2px solid #dee2e6;
}

.table td {
    vertical-align: middle;
    cursor: pointer;
}

.badge {
    font-weight: 500;
}

.badge.bg-warning {
    color: #000;
}

.card {
    border-radius: 10px;
}

.modal-content {
    border-radius: 10px;
}

.btn-group-sm > .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.02);
}
</style>
@endpush