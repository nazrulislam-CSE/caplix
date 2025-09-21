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
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-card">
                <h5 class="text-primary">125</h5>
                <small>Total Investors</small>
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
                <h5 class="text-danger">12</h5>
                <small>KYC Pending</small>
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
                                <th>Entrepreneur</th>
                                <th>Project</th>
                                <th>Capital</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Fatima Rahman</td>
                                <td>Green Energy Co.</td>
                                <td>৳8,00,000</td>
                                <td><button class="btn btn-primary btn-sm">Review</button></td>
                            </tr>
                            <tr>
                                <td>Kamal Hossain</td>
                                <td>Rajshahi AgroTech</td>
                                <td>৳3,50,000</td>
                                <td><button class="btn btn-primary btn-sm">Review</button></td>
                            </tr>
                        </tbody>
                    </table>
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
