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
                <li class="breadcrumb-item active">{{ $pageTitle }}</li>
            </ol>
        </nav>
    </div>

    <!-- Wallet & Withdraw Form -->
    <div class="row g-3">
        <!-- Balance Card -->
        <div class="col-md-6 col-lg-6">
            <div class="card shadow">
                <div class="card-body d-flex align-items-center">
                    <i class="fa-solid fa-coins fa-2x text-warning me-3"></i>
                    <div>
                        <h5 class="mb-1">Balance</h5>
                        <div class="mt-2">
                            <strong>Total Available:</strong> ৳{{ number_format($balance ?? 0, 2) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

         <!-- Balance Card -->
        <div class="col-md-6 col-lg-6">
            <div class="card shadow">
                <div class="card-body d-flex align-items-center">
                    <i class="fa-solid fa-coins fa-2x text-warning me-3"></i>
                    <div>
                        <h5 class="mb-1">Withdraw Balance</h5>
                        <div class="mt-2">
                            <strong>Total Withdraw:</strong> ৳{{ number_format($user->withdrawable_balance ?? 0, 2) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Withdraw Form -->
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title mb-3">Withdraw Amount</h5>
                    <form action="{{ route('investor.withdraw.store') }}" method="POST">
                        @csrf
                        <!-- Select Wallet -->
                        <div class="mb-3">
                            <label for="wallet" class="form-label">Select Wallet</label>
                            <select class="form-select" name="wallet_type" id="wallet" required>
                                <option value="" disabled selected>-- Choose Wallet --</option>
                                <option value="capital_wallet">Capital Wallet (Some Charge Before 1 Year)</option>
                                <option value="point_wallet">Point Wallet</option>
                                <option value="salary_wallet">Salary Wallet</option>
                                <option value="earning_wallet">Earning Wallet</option>
                            </select>
                        </div>

                        <!-- Amount -->
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="number" class="form-control" id="amount" name="amount"
                                placeholder="Enter amount" max="{{ $balance }}" required>
                        </div>

                        <!-- Submit -->
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                Withdraw
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
