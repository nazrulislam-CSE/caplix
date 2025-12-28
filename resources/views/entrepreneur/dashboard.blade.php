@extends('layouts.entrepreneur')

@section('content')
    <h1>{{ $pageTitle }}</h1>
    <!-- Breadcrumb -->
    <div class="breadcrumb-header d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Dashboard</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
        </nav>
    </div>

    <!-- Dashboard Stats -->
    <div class="row g-3 mb-4">
        <!-- KYC Status Alert -->
        @if (!$isKycVerified)
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle me-3 fa-2x"></i>
                    <div>
                        <h5 class="alert-heading mb-1">KYC Verification Required</h5>
                        <p class="mb-0">
                            Please complete your KYC verification to access all features including project creation.
                            @if ($kyc)
                                Current Status: <strong>{{ ucfirst($kyc->status) }}</strong>
                            @endif
                        </p>
                    </div>
                </div>
                <div class="mt-3">
                    @if (!$kyc)
                        <a href="{{ route('entrepreneur.kyc.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-user-check me-1"></i> Start KYC Verification
                        </a>
                    @else
                        <a href="{{ route('entrepreneur.kyc.status') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-info-circle me-1"></i> Check KYC Status
                        </a>
                    @endif
                </div>
            </div>
        @endif
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-card">
                <h5 class="text-primary">{{ $totalInvestors ?? 0 }}</h5>
                <small>Total Investors</small>
                <div class="mt-2 text-primary">
                    <i class="fas fa-user-group"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-card">
                <h5 class="text-primary">৳ {{ number_format($totalInvestment ?? 0, 2) }} </h5>
                <small>Total Investment</small>
                <div class="mt-2 text-primary">
                    <i class="fas fa-user-group"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-card">
                <h5 class="text-success">32</h5>
                <small>Active Projects</small>
                <div class="mt-2 text-success">
                    <i class="fas fa-rocket"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-card">
                <h5 class="text-info">৳45,200</h5>
                <small>Monthly Profit</small>
                <div class="mt-2 text-info">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-card">
                <h5 class="text-warning">8</h5>
                <small>Pending Withdrawals</small>
                <div class="mt-2 text-warning">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-card">
                <h5 class="text-purple">58</h5>
                <small>Verified Entrepreneurs</small>
                <div class="mt-2 text-purple">
                    <i class="fas fa-badge-check"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-card">
                <small>
                    @if ($isKycVerified)
                        <span class="badge bg-success">Verified</span>
                    @elseif($kyc)
                        <span class="badge bg-warning">{{ ucfirst($kyc->status) }}</span>
                    @else
                        <span class="badge bg-danger">Pending</span>
                    @endif
                </small>
                <div class="mt-2 text-danger">
                    <i class="fas fa-user-clock"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tables Section -->
    <div class="row">
        <div class="col-12 col-lg-6 mb-4">
            <div class="table-container">
                <h5>Latest Withdrawal Requests</h5>
                <div class="table-responsive">
                    <table class="table table-hover mt-3">
                        <thead>
                            <tr>
                                <th>Investor</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Rahim Ahmed</td>
                                <td>৳5,000</td>
                                <td>bKash</td>
                                <td><span class="badge bg-warning text-dark">PENDING</span></td>
                                <td>
                                    <button class="btn btn-success btn-sm">Approve</button>
                                    <button class="btn btn-outline-danger btn-sm">Reject</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Salma Khatun</td>
                                <td>৳2,500</td>
                                <td>Nagad</td>
                                <td><span class="badge bg-warning text-dark">PENDING</span></td>
                                <td>
                                    <button class="btn btn-success btn-sm">Approve</button>
                                    <button class="btn btn-outline-danger btn-sm">Reject</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6 mb-4">
            <div class="table-container">
                <h5>Project Approval Queue</h5>
                <div class="table-responsive">
                    <table class="table table-hover mt-3">
                        <thead>
                            <tr>
                                <th>Project</th>
                                <th>Capital</th>
                                <th>Investors</th>
                                <th>Invest Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($projects as $project)
                                @php
                                    $totalInvestors = $project->investments->unique('user_id')->count();
                                    $totalInvestment = $project->investments->sum('investment_amount');
                                @endphp
                                <tr>
                                    <td>{{ $project->name }}</td>
                                    <td>৳{{ number_format($project->capital_required, 2) }}</td>
                                    <td>{{ $totalInvestors }}</td>
                                    <td>৳{{ number_format($totalInvestment, 2) }}</td>
                                    <td>
                                        @if ($project->status === 'Pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @elseif($project->status === 'Approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($project->status === 'Issued')
                                            <span class="badge bg-primary">Issued</span>
                                        @elseif($project->status === 'At Risk')
                                            <span class="badge bg-danger">At Risk</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $project->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('entrepreneur.project.show', $project->id) }}"
                                            class="btn btn-primary btn-sm">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No projects found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- Pagination --}}
                    <div class="mt-3">
                        {{ $projects->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row mb-4">
        <div class="col-12 col-lg-6 mb-4">
            <div class="table-container">
                <h5>Monthly Investment Trend</h5>
                <canvas id="investmentChart" height="200"></canvas>
            </div>
        </div>
        <div class="col-12 col-lg-6 mb-4">
            <div class="table-container">
                <h5>Project Performance</h5>
                <canvas id="projectChart" height="200"></canvas>
            </div>
        </div>
    </div>
@endsection
