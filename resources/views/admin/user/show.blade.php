@extends('layouts.admin')

@section('content')
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .bg-gradient-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }
        .card {
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-2px);
        }
        .badge {
            font-size: 0.8em;
        }
    </style>
    <!-- Breadcrumb -->
    <div class="breadcrumb-header d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">{{ $pageTitle }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.user.index') }}">Users</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $user->name }}</li>
            </ol>
        </nav>
    </div>

    <!-- User Details Container -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <!-- Back Button -->
                <div class="mb-3">
                    <a href="{{ route('admin.user.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Users
                    </a>
                </div>

                <div class="row">
                    <!-- Left Column: Profile Info -->
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-lg border-0">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Profile Information</h5>
                            </div>
                            <div class="card-body text-center">
                                @php
                                    $photoPath = null;

                                    if ($user->photo) {
                                        if ($user->role === 'admin') {
                                            $photoPath = asset('upload/admin/' . $user->photo);
                                        } elseif ($user->role === 'investor') {
                                            $photoPath = asset('upload/investor/' . $user->photo);
                                        } elseif ($user->role === 'entrepreneur') {
                                            $photoPath = asset('upload/entrepreneur/' . $user->photo);
                                        }
                                    }
                                @endphp
                                <!-- Profile Photo -->
                                @if($photoPath)
                                    <img src="{{ $photoPath }}" alt="{{ $user->name }}" class="rounded-circle mb-3" width="120" height="120">
                                @else
                                    <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png"
                                        class="rounded-circle"
                                        width="120"
                                        height="120"
                                        alt="Default Avatar">
                                @endif
                                
                                <h4 class="mb-2">{{ $user->name }}</h4>
                                
                                <div class="mb-3">
                                    @php
                                        $roleColors = [
                                            'admin' => 'danger',
                                            'investor' => 'success',
                                            'entrepreneur' => 'primary'
                                        ];
                                        $color = $roleColors[$user->role] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $color }} fs-6">{{ ucfirst($user->role) }}</span>
                                    
                                    @if($user->rank_level)
                                        <span class="badge bg-info fs-6">{{ $user->rank_level }}</span>
                                    @endif
                                    
                                    @if($user->status)
                                        <span class="badge bg-success fs-6">Active</span>
                                    @else
                                        <span class="badge bg-danger fs-6">Inactive</span>
                                    @endif
                                </div>
                                
                                <div class="text-start">
                                    <p><strong><i class="fas fa-user-circle me-2"></i>Username:</strong> {{ $user->username }}</p>
                                    <p><strong><i class="fas fa-envelope me-2"></i>Email:</strong> {{ $user->email }}</p>
                                    <p><strong><i class="fas fa-phone me-2"></i>Phone:</strong> {{ $user->phone ?? 'N/A' }}</p>
                                    <p><strong><i class="fas fa-map-marker-alt me-2"></i>Address:</strong> {{ $user->address ?? 'N/A' }}</p>
                                    <p><strong><i class="fas fa-calendar me-2"></i>Joined:</strong> {{ $user->created_at->format('d M, Y h:i A') }}</p>
                                    @if($user->refer_by)
                                        <p><strong><i class="fas fa-user-friends me-2"></i>Referred By:</strong> 
                                            @php
                                                $referrer = \App\Models\User::find($user->refer_by);
                                            @endphp
                                            @if($referrer)
                                                {{ $referrer->name }} ({{ $referrer->username }})
                                            @else
                                                User not found
                                            @endif
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Activity Stats -->
                        <div class="card shadow-lg border-0 mt-4">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0">Activity Stats</h5>
                            </div>
                            <div class="card-body">
                                <p><strong>Total Referrals:</strong> {{ $user->total_referral_count }}</p>
                                <p><strong>Last Deposit:</strong> {{ $user->last_deposit_at ? $user->last_deposit_at->format('d M, Y') : 'Never' }}</p>
                                <p><strong>Last Withdrawal:</strong> {{ $user->last_withdraw_at ? $user->last_withdraw_at->format('d M, Y') : 'Never' }}</p>
                                <p><strong>Email Verified:</strong> {{ $user->email_verified_at ? 'Yes' : 'No' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Balance Information -->
                    <div class="col-md-8">
                        <!-- Main Balance Cards -->
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="card border-0 shadow-lg bg-gradient-primary text-white">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="card-subtitle mb-2">Main Balance</h6>
                                                <h2 class="card-title mb-0">৳{{ number_format($user->balance, 2) }}</h2>
                                                <small>Available for all transactions</small>
                                            </div>
                                            <i class="fas fa-wallet fa-3x opacity-50"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <div class="card border-0 shadow-lg bg-gradient-success text-white">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="card-subtitle mb-2">Withdrawable Balance</h6>
                                                <h2 class="card-title mb-0">৳{{ number_format($user->withdrawable_balance, 2) }}</h2>
                                                <small>Available for withdrawal</small>
                                            </div>
                                            <i class="fas fa-money-bill-wave fa-3x opacity-50"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Investment & Earnings Section -->
                        <div class="card shadow-lg border-0 mb-4">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0">Investment & Earnings</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="card border">
                                            <div class="card-body">
                                                <h6 class="card-subtitle text-muted">Investment Balance</h6>
                                                <h4 class="card-title">৳{{ number_format($user->investment_balance, 2) }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="card border">
                                            <div class="card-body">
                                                <h6 class="card-subtitle text-muted">Total Earnings</h6>
                                                <h4 class="card-title">৳{{ number_format($user->total_earnings, 2) }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="card border">
                                            <div class="card-body">
                                                <h6 class="card-subtitle text-muted">Interest Earned</h6>
                                                <h4 class="card-title">৳{{ number_format($user->total_interest_earned, 2) }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="card border">
                                            <div class="card-body">
                                                <h6 class="card-subtitle text-muted">Referral Earnings</h6>
                                                <h4 class="card-title">৳{{ number_format($user->referral_earnings, 2) }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bonuses & Commissions -->
                        <div class="card shadow-lg border-0 mb-4">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="mb-0">Bonuses & Commissions</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <div class="card border">
                                            <div class="card-body">
                                                <h6 class="card-subtitle text-muted">Deposit Bonus</h6>
                                                <h4 class="card-title">৳{{ number_format($user->deposit_bonus_earned, 2) }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="card border">
                                            <div class="card-body">
                                                <h6 class="card-subtitle text-muted">Total Cashback</h6>
                                                <h4 class="card-title">৳{{ number_format($user->total_cashback, 2) }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="card border">
                                            <div class="card-body">
                                                <h6 class="card-subtitle text-muted">Total Commission</h6>
                                                <h4 class="card-title">৳{{ number_format($user->total_commission, 2) }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Other Balances -->
                        <div class="card shadow-lg border-0">
                            <div class="card-header bg-secondary text-white">
                                <h5 class="mb-0">Other Balances</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <div class="card border">
                                            <div class="card-body">
                                                <h6 class="card-subtitle text-muted">Pending Balance</h6>
                                                <h4 class="card-title">৳{{ number_format($user->pending_balance, 2) }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="card border">
                                            <div class="card-body">
                                                <h6 class="card-subtitle text-muted">Locked Balance</h6>
                                                <h4 class="card-title">৳{{ number_format($user->locked_balance, 2) }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="card border">
                                            <div class="card-body">
                                                <h6 class="card-subtitle text-muted">Total Withdrawn</h6>
                                                <h4 class="card-title">৳{{ number_format($user->total_withdrawn, 2) }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="card border">
                                            <div class="card-body">
                                                <h6 class="card-subtitle text-muted">Total Penalties</h6>
                                                <h4 class="card-title text-danger">৳{{ number_format($user->total_penalties, 2) }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="card border">
                                            <div class="card-body">
                                                <h6 class="card-subtitle text-muted">About/Bio</h6>
                                                <p class="card-text">{{ $user->text ?? 'No bio available' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')

@endpush