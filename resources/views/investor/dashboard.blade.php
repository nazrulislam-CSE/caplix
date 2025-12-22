@extends('layouts.investor')

@section('content')
    <!-- Breadcrumb -->
    <div class="breadcrumb-header d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">{{ $pageTitle ?? 'Investor Dashboard' }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
        </nav>
    </div>
    <div class="row align-items-center">
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <div class="col-md-8">
                <h5 class="mb-1">Welcome back, {{ $user->name }}! 👋</h5>
                <p class="text-muted mb-0">
                    @if ($user->hasVerifiedInvestorKyc())
                        Your account is fully verified. Ready to explore investment opportunities?
                    @else
                        Complete your KYC verification to unlock full access to investment opportunities.
                    @endif
                </p>
            </div>
            <div class="col-md-4">
                @if ($user->hasVerifiedInvestorKyc())
                    <span class="badge bg-success p-2">
                        <i class="fas fa-check-circle me-1"></i> Verified Investor
                    </span>
                @else
                    <span class="badge bg-warning p-2">
                        <i class="fas fa-clock me-1"></i> Verification Pending
                    </span>
                @endif
            </div>
        </div>
    </div>
    <!-- KYC Status Alert -->
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="alert alert-{{ $kycStatus['badge'] }} border-0 shadow-sm">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="{{ $kycStatus['icon'] }} fa-2x me-3"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="alert-heading mb-1">
                            @if ($kycStatus['status'] === 'not_submitted')
                                KYC Verification Required
                            @elseif($kycStatus['status'] === 'draft')
                                KYC Draft Saved
                            @elseif($kycStatus['status'] === 'under_review')
                                KYC Under Review
                            @elseif($kycStatus['status'] === 'verified')
                                KYC Verified Successfully
                            @elseif($kycStatus['status'] === 'rejected')
                                KYC Rejected
                            @endif
                        </h6>
                        <p class="mb-2">{{ $kycStatus['message'] }}</p>
                        @if ($kycStatus['action'])
                            <a href="{{ $kycStatus['route'] }}" class="btn btn-{{ $kycStatus['badge'] }} btn-sm">
                                <i class="fas fa-arrow-right me-1"></i> {{ $kycStatus['action'] }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard Stats -->
    <div class="row g-3 mb-4">
        <!-- Total Invested -->
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-card">
                <h5 class="text-primary">৳{{ number_format($totalInvested) }}</h5>
                <small>Total Invested</small>
                <div class="mt-2 text-primary">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
            </div>
        </div>

        <!-- Active Projects -->
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-card">
                <h5 class="text-success">{{ $activeProjects }}</h5>
                <small>Active Projects</small>
                <div class="mt-2 text-success">
                    <i class="fas fa-rocket"></i>
                </div>
            </div>
        </div>

        <!-- Monthly Profit -->
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-card">
                <h5 class="text-info {{ $monthlyProfit >= 0 ? 'text-success' : 'text-danger' }}">
                    ৳{{ number_format(abs($monthlyProfit)) }}
                </h5>
                <small>30-Day {{ $monthlyProfit >= 0 ? 'Profit' : 'Loss' }}</small>
                <div class="mt-2 {{ $monthlyProfit >= 0 ? 'text-success' : 'text-danger' }}">
                    <i class="fas {{ $monthlyProfit >= 0 ? 'fa-chart-line' : 'fa-chart-line-down' }}"></i>
                </div>
            </div>
        </div>

        <!-- Pending Withdrawals -->
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-card">
                <h5 class="text-warning">0</h5>
                <small>Pending Withdrawals</small>
                <div class="mt-2 text-warning">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>

        <!-- Verified Entrepreneurs -->
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-card border-0 shadow-sm">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h5 class="text-purple mb-0">{{ $adminStats['verified_entrepreneurs'] ?? 0 }}</h5>
                        <small class="text-muted">Verified Entrepreneurs</small>
                    </div>
                    <div class="icon-box bg-purple-soft rounded-circle d-flex align-items-center justify-content-center"
                        style="width: 40px; height: 40px;">
                        <i class="fas fa-badge-check text-purple"></i>
                    </div>
                </div>
                @if (isset($adminStats['entrepreneurs_trend']) && $adminStats['entrepreneurs_trend'])
                    <div class="mt-2">
                        <small class="text-{{ $adminStats['entrepreneurs_trend']['color'] }}">
                            <i class="fas fa-arrow-{{ $adminStats['entrepreneurs_trend']['direction'] }} me-1"></i>
                            {{ $adminStats['entrepreneurs_trend']['value'] }}%
                        </small>
                        <small class="text-muted ms-1">from last month</small>
                    </div>
                @endif
            </div>
        </div>

        <!-- KYC Pending -->
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-card border-0 shadow-sm">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h5 class="text-danger mb-0">{{ $adminStats['kyc_pending'] ?? 0 }}</h5>
                        <small class="text-muted">KYC Pending</small>
                    </div>
                    <div class="icon-box bg-danger-soft rounded-circle d-flex align-items-center justify-content-center"
                        style="width: 40px; height: 40px;">
                        <i class="fas fa-user-clock text-danger"></i>
                    </div>
                </div>
                @if (isset($adminStats['pending_trend']) && $adminStats['pending_trend'])
                    <div class="mt-2">
                        <small class="text-{{ $adminStats['pending_trend']['color'] }}">
                            <i class="fas fa-arrow-{{ $adminStats['pending_trend']['direction'] }} me-1"></i>
                            {{ $adminStats['pending_trend']['value'] }}%
                        </small>
                        <small class="text-muted ms-1">from last week</small>
                    </div>
                @endif
            </div>
        </div>

        <!-- Financial data with icons -->
        <div class="row g-3 mb-4">

            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body d-flex align-items-center">
                        <i class="fa-solid fa-wallet fa-2x text-primary me-3"></i>
                        <div>
                            <h5 class="fw-bold mb-0">৳ {{ number_format($financials['balance'], 2) }}</h5>
                            <p class="text-muted mb-0">Wallet Balance</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body d-flex align-items-center">
                        <i class="fa-solid fa-coins fa-2x text-success me-3"></i>
                        <div>
                            <h5 class="fw-bold mb-0">৳ {{ number_format($financials['total_earnings'], 2) }}</h5>
                            <p class="text-muted mb-0">Total Earnings</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body d-flex align-items-center">
                        <i class="fa-solid fa-user-plus fa-2x text-warning me-3"></i>
                        <div>
                            <h5 class="fw-bold mb-0">৳ {{ number_format($financials['referral_earnings'], 2) }}</h5>
                            <p class="text-muted mb-0">Referral Earnings</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body d-flex align-items-center">
                        <i class="fa-solid fa-gift fa-2x text-info me-3"></i>
                        <div>
                            <h5 class="fw-bold mb-0">৳ {{ number_format($financials['deposit_bonus_earned'], 2) }}</h5>
                            <p class="text-muted mb-0">Deposit Bonuses</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>


    <!-- Additional Investor Stats -->
    <div class="row g-3 mb-4">
        <!-- Portfolio Value -->
        <div class="col-6 col-md-4 col-lg-3">
            <div class="stat-card bg-light">
                <h5 class="text-dark">৳{{ number_format($currentValue) }}</h5>
                <small>Portfolio Value</small>
                <div class="mt-2 text-dark">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>
        </div>

        <!-- Total Profit/Loss -->
        <div class="col-6 col-md-4 col-lg-3">
            <div class="stat-card bg-light">
                <h5 class="{{ $totalProfitLoss >= 0 ? 'text-success' : 'text-danger' }}">
                    ৳{{ number_format(abs($totalProfitLoss)) }}
                </h5>
                <small>Total {{ $totalProfitLoss >= 0 ? 'Profit' : 'Loss' }}</small>
                <div class="mt-2 {{ $totalProfitLoss >= 0 ? 'text-success' : 'text-danger' }}">
                    <i class="fas {{ $totalProfitLoss >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }}"></i>
                </div>
            </div>
        </div>

        <!-- Active Investments -->
        <div class="col-6 col-md-4 col-lg-3">
            <div class="stat-card bg-light">
                <h5 class="text-info">{{ $activeInvestments }}</h5>
                <small>My Active Investments</small>
                <div class="mt-2 text-info">
                    <i class="fas fa-briefcase"></i>
                </div>
            </div>
        </div>

        <!-- Total Investors -->
        <div class="col-6 col-md-4 col-lg-3">
            <div class="stat-card bg-light">
                <h5 class="text-secondary">{{ $totalInvestors }}</h5>
                <small>Platform Investors</small>
                <div class="mt-2 text-secondary">
                    <i class="fas fa-user-group"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Investments & Available Projects -->
    <div class="row g-4">
        <!-- Recent Investments -->
        <div class="col-lg-6">
            <div class="card shadow border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Recent Investments</h5>
                </div>
                <div class="card-body">
                    @if ($recentInvestments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>Project</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentInvestments as $investment)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <div class="avatar-xs bg-light rounded">
                                                            <i class="fas fa-building text-primary"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 ms-2">
                                                        <h6 class="mb-0 fs-13">
                                                            {{ Str::limit($investment->project->name, 20) }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>৳{{ number_format($investment->investment_amount) }}</td>
                                            <td>{{ $investment->investment_date->format('M d') }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $investment->status == 'active' ? 'success' : 'info' }}">
                                                    {{ ucfirst($investment->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">No investments yet</p>
                            @if ($user->hasVerifiedInvestorKyc())
                                <a href="{{ route('investor.investment.create') }}" class="btn btn-primary btn-sm mt-2">
                                    Start Investing
                                </a>
                            @else
                                <!-- Not verified হলে disabled button বা অন্য action -->
                                <a href="{{ route('investor.kyc.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-coins me-1"></i> Start Investing
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Available Projects -->
        <div class="col-lg-6">
            <div class="card shadow border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>Available Projects</h5>
                </div>
                <div class="card-body">
                    @if ($projects->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>Project</th>
                                        <th>ROI</th>
                                        <th>Funding</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($projects as $project)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <div class="avatar-xs bg-light rounded">
                                                            <i class="fas fa-project-diagram text-success"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 ms-2">
                                                        <h6 class="mb-0 fs-13">{{ Str::limit($project->name, 20) }}</h6>
                                                        <small
                                                            class="text-muted">{{ $project->investment_type_label }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">{{ $project->roi }}%</span>
                                            </td>
                                            <td>
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar bg-primary"
                                                        style="width: {{ $project->funding_percentage }}%">
                                                    </div>
                                                </div>
                                                <small
                                                    class="text-muted">{{ number_format($project->funding_percentage, 1) }}%</small>
                                            </td>
                                            <td>
                                                @if ($user->hasVerifiedInvestorKyc())
                                                    <!-- Verified হলে Invest Button দেখাবে -->
                                                    <a href="{{ route('investor.investment.index') }}"
                                                        class="btn btn-primary btn-sm">
                                                        <i class="fas fa-coins me-1"></i> Invest Now
                                                    </a>
                                                @else
                                                    <!-- Not verified হলে disabled button বা অন্য action -->
                                                    <a href="{{ route('investor.kyc.create') }}"
                                                        class="btn btn-primary btn-sm">
                                                        <i class="fas fa-coins me-1"></i> Invest Now
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $projects->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">No available projects at the moment</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>


    </div>
    <!-- Charts Section -->
    <div class="row mb-4 mt-5">
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

@push('styles')
    <style>
        .stat-card {
            background: white;
            padding: 1.5rem 1rem;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border: 1px solid #e9ecef;
            text-align: center;
            transition: transform 0.2s;
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .stat-card h5 {
            font-weight: 700;
            margin-bottom: 0.5rem;
            font-size: 1.25rem;
        }

        .stat-card small {
            color: #6c757d;
            font-weight: 500;
        }

        .stat-card .fas {
            font-size: 1.5rem;
        }

        .text-purple {
            color: #6f42c1 !important;
        }

        .avatar-xs {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .fs-13 {
            font-size: 13px;
        }

        .progress {
            background-color: #e9ecef;
            border-radius: 10px;
        }
    </style>
@endpush
