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
                <li class="breadcrumb-item">
                    <a href="{{ route('investor.deposit.index') }}">Deposit Funds</a>
                </li>
                <li class="breadcrumb-item active">Deposit History</li>
            </ol>
        </nav>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-primary bg-gradient text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Total Deposited</h6>
                            <h3 class="mb-0">৳ {{ number_format($user->getTotalDepositedAttribute(), 2) }}</h3>
                        </div>
                        <div class="icon">
                            <i class="fas fa-wallet fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-warning bg-gradient text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Pending Deposits</h6>
                            <h3 class="mb-0">৳ {{ number_format($user->getTotalPendingDepositAttribute(), 2) }}</h3>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clock fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-info bg-gradient text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Total Transactions</h6>
                            <h3 class="mb-0">{{ $deposits->total() }}</h3>
                        </div>
                        <div class="icon">
                            <i class="fas fa-exchange-alt fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Deposit History Table -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0 fw-bold">Deposit History</h5>
        </div>
        <div class="card-body">
            <!-- Filters -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <form action="{{ route('investor.deposit.index') }}" method="GET" class="row g-2">
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved
                                </option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="payment_method" class="form-select">
                                <option value="">All Methods</option>
                                <option value="bKash" {{ request('payment_method') == 'bKash' ? 'selected' : '' }}>bKash
                                </option>
                                <option value="Nagad" {{ request('payment_method') == 'Nagad' ? 'selected' : '' }}>Nagad
                                </option>
                                <option value="Bank" {{ request('payment_method') == 'Bank' ? 'selected' : '' }}>Bank
                                </option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" placeholder="Search by TXN ID"
                                    value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                        </div>
                    </form>
                </div>
                <div class="col-md-4 text-end">
                    <a href="{{ route('investor.deposit.create') }}" class="btn btn-success">
                        <i class="fas fa-plus-circle me-2"></i>New Deposit
                    </a>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>Sl</th>
                            <th>Date</th>
                            <th>TXN ID</th>
                            <th>Payment Method</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Payment Slip</th>
                            <th>Admin Note</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deposits as $deposit)
                            <tr>
                                <td>{{ $loop->iteration + ($deposits->currentPage() - 1) * $deposits->perPage() }}</td>
                                <td>
                                    <div>{{ $deposit->created_at->format('d M Y') }}</div>
                                    <small class="text-muted">{{ $deposit->created_at->format('h:i A') }}</small>
                                </td>
                                <td>
                                    <code>{{ $deposit->transaction_id }}</code>
                                </td>
                                <td>
                                    @if ($deposit->payment_method == 'bKash')
                                        <span class="badge bg-primary-light text-primary">
                                            <i class="fas fa-mobile-alt me-1"></i> bKash
                                        </span>
                                    @elseif($deposit->payment_method == 'Nagad')
                                        <span class="badge bg-success-light text-success">
                                            <i class="fas fa-wallet me-1"></i> Nagad
                                        </span>
                                    @else
                                        <span class="badge bg-info-light text-info">
                                            <i class="fas fa-university me-1"></i> Bank
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <strong>৳ {{ number_format($deposit->amount, 2) }}</strong>
                                </td>
                                <td>
                                    @if ($deposit->status == 'pending')
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
                                    @if ($deposit->payment_slip)
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                            data-bs-target="#slipModal{{ $deposit->id }}">
                                            <i class="fas fa-eye me-1"></i> View
                                        </button>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($deposit->admin_note)
                                        <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip"
                                            title="{{ $deposit->admin_note }}">
                                            <i class="fas fa-sticky-note me-1"></i> Note
                                        </button>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        @if ($deposit->status == 'pending')
                                            <button class="btn btn-outline-warning" disabled>
                                                <i class="fas fa-hourglass-half"></i>
                                            </button>
                                        @endif
                                        <a href="#" class="btn btn-outline-secondary" data-bs-toggle="modal"
                                            data-bs-target="#depositDetailsModal{{ $deposit->id }}">
                                            <i class="fas fa-info-circle"></i>
                                        </a>

                                        <div class="modal fade" id="depositDetailsModal{{ $deposit->id }}"
                                            tabindex="-1">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Deposit Details</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <table class="table table-bordered">
                                                            <tr>
                                                                <th>Date</th>
                                                                <td>{{ $deposit->created_at->format('d M Y h:i A') }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Transaction ID</th>
                                                                <td>{{ $deposit->transaction_id }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Payment Method</th>
                                                                <td>{{ $deposit->payment_method }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Amount</th>
                                                                <td>৳ {{ number_format($deposit->amount, 2) }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Status</th>
                                                                <td>{{ ucfirst($deposit->status) }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Admin Note</th>
                                                                <td>{{ $deposit->admin_note ?? '-' }}</td>
                                                            </tr>

                                                            @if ($deposit->payment_slip)
                                                                <tr>
                                                                    <th>Payment Slip</th>
                                                                    <td>
                                                                        <a href="{{ asset('storage/' . $deposit->payment_slip) }}"
                                                                            target="_blank">
                                                                            View Slip
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </td>
                            </tr>

                            <!-- Payment Slip Modal -->
                            @if ($deposit->payment_slip)
                                <div class="modal fade" id="slipModal{{ $deposit->id }}" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Payment Slip - {{ $deposit->transaction_id }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                @if (pathinfo($deposit->payment_slip, PATHINFO_EXTENSION) == 'pdf')
                                                    <iframe src="{{ asset('storage/' . $deposit->payment_slip) }}"
                                                        width="100%" height="500px"></iframe>
                                                @else
                                                    <img src="{{ asset('storage/' . $deposit->payment_slip) }}"
                                                        alt="Payment Slip" class="img-fluid rounded">
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <a href="{{ asset('storage/' . $deposit->payment_slip) }}" download
                                                    class="btn btn-primary">
                                                    <i class="fas fa-download me-2"></i>Download
                                                </a>
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="empty-state">
                                        <i class="fas fa-exchange-alt fa-3x text-muted mb-3"></i>
                                        <h5>No Deposit History Found</h5>
                                        <p class="text-muted">You haven't made any deposits yet.</p>
                                        <a href="{{ route('investor.deposit.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus-circle me-2"></i>Make Your First Deposit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($deposits->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Showing {{ $deposits->firstItem() }} to {{ $deposits->lastItem() }} of {{ $deposits->total() }}
                        entries
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
        // Tooltip initialization
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
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

        .bg-primary-light {
            background-color: rgba(13, 110, 253, 0.1) !important;
        }

        .bg-success-light {
            background-color: rgba(25, 135, 84, 0.1) !important;
        }

        .bg-info-light {
            background-color: rgba(13, 202, 240, 0.1) !important;
        }

        .bg-warning-light {
            background-color: rgba(255, 193, 7, 0.1) !important;
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
        }

        .badge {
            font-weight: 500;
        }

        .badge.bg-warning {
            color: #000;
        }
    </style>
@endpush
