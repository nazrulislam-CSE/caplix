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
                <li class="breadcrumb-item active">Refer, Rank & Rewards</li>
            </ol>
        </nav>
    </div>

    <!-- Top Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h3 class="fw-bold">৳ {{ number_format($rewardBalance, 2) }}</h3>
                    <p class="text-muted mb-0">Reward Balance</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h3 class="fw-bold">৳ {{ number_format($user->referral_earnings, 2) }}</h3>
                    <p class="text-muted mb-0">Referral Income</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h3 class="fw-bold">
                        <i class="fa-solid {{ auth()->user()->rankIcon() }} me-2" style="font-size:24px;"></i> {{ $currentRank }}
                    </h3>
                    <p class="text-muted mb-0">Current Rank</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Referral Hub & Bonus -->
    <div class="row g-4">
        <!-- Referral Hub -->
        <div class="col-md-7">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Your Referral Hub</h5>
                    <p class="text-muted">
                        Share your code and earn when your friends invest.
                    </p>

                    <!-- Referral Code -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Your Referral Code</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fa-solid fa-user-plus"></i>
                            </span>
                            <input type="text" id="refCode" class="form-control" readonly
                                value="{{ $user->username ?? 'CAPLIX-RA25' }}">
                            <button class="btn btn-outline-primary" onclick="copyToClipboard('refCode')">
                                <i class="fa-regular fa-copy"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Referral Link -->
                    <div>
                        <label class="form-label fw-semibold">Your Referral Link</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fa-solid fa-link"></i>
                            </span>
                            <input type="text" id="refLink" class="form-control" readonly
                                value="{{ url('register') }}?refer_id={{ $user->username }}">
                            <button class="btn btn-outline-success" onclick="copyToClipboard('refLink')">
                                <i class="fa-regular fa-copy"></i>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <!-- Challenges & Bonuses -->
        <div class="col-md-5">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="fw-bold mb-3">Challenges & Bonuses</h5>

                        <div class="mb-3">
                            <strong>Daily Check-in Bonus</strong>
                            <button class="btn btn-sm btn-primary float-end">Claim</button>
                        </div>

                        <p class="mb-2">
                            <strong>This Month's Rank Achiever</strong><br>
                            <span class="text-muted">Silver Tier</span>
                        </p>

                        <ul class="mb-0">
                            <li>
                                <strong>Challenge:</strong>
                                Invest in 3 projects this month to earn
                                <strong>৳500 bonus</strong>.
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Referral List  -->
    <div class="row g-4 mt-1">
        <div class="col-md-12">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Your Referrals ({{ $referrals->count() }})</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Joined At</th>
                                    <th>Referral Income</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($referrals as $key => $referral)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $referral->name }}</td>
                                        <td>{{ $referral->email }}</td>
                                        <td>{{ $referral->created_at->format('d M Y') }}</td>
                                        <td>৳ {{ $referral->incomes->where('type','referral_bonus')->sum('amount') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No referrals yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $referrals->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        function copyToClipboard(id) {
            let input = document.getElementById(id);
            input.select();
            input.setSelectionRange(0, 99999);
            document.execCommand("copy");

            // Simple alert (toast থাকলে এটাকে replace করতে পারবেন)
            toastr.success('Copied to clipboard!');
        }
    </script>
@endpush
