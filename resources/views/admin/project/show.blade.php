@extends('layouts.admin')

@section('content')
    <!-- Breadcrumb -->
    <div class="breadcrumb-header d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">{{ $pageTitle ?? 'Project Details' }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.project.index') }}">Projects</a></li>
                <li class="breadcrumb-item active" aria-current="page">View Project</li>
            </ol>
        </nav>
    </div>

    <!-- Project Details -->
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow border-0">
                    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ $project->name }}</h5>
                        <a href="{{ route('admin.project.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>Project Name</th>
                                    <td>{{ $project->name }}</td>
                                </tr>
                                <tr>
                                    <th>Investment Type</th>
                                    <td>{{ ucfirst($project->investment_type ?? '-') }}</td>
                                </tr>
                                <tr>
                                    <th>Duration (Months)</th>
                                    <td>{{ $project->short_duration ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Duration (Years)</th>
                                    <td>{{ $project->regular_duration ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Approx. ROI (%)</th>
                                    <td>{{ $project->roi ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Capital Required (৳)</th>
                                    <td>{{ number_format($project->capital_required, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if ($project->status === 'Pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @elseif ($project->status === 'Approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif ($project->status === 'Issued')
                                            <span class="badge bg-primary">Issued</span>
                                        @elseif ($project->status === 'At Risk')
                                            <span class="badge bg-danger">At Risk</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $project->status }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Score</th>
                                    <td>{{ $project->score ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Description</th>
                                    <td>{{ $project->description ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Entrepreneur</th>
                                    <td>{{ $project->entrepreneur->name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th>Project Url</th>
                                    <td>
                                        <a href="{{ $project->url }}" target="_blank" class="btn btn-success"> 
                                            {{ $project->url }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created By</th>
                                    <td>{{ $project->user->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Pitch Deck</th>
                                    <td>
                                        @if($project->pitch_deck)
                                            <a href="{{ asset('storage/' . $project->pitch_deck) }}" target="_blank" class="btn btn-sm btn-primary">
                                                <i class="fas fa-file-pdf"></i> View PDF
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="row mb-4">

                            <!-- Total Investors Card -->
                            <div class="col-md-4 col-sm-6 mb-3  mt-3">
                                <div class="card shadow border-0">
                                    <div class="card-body d-flex align-items-center">
                                        <div class="me-3">
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                                style="width:50px; height:50px;">
                                                <i class="fas fa-users"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <h6 class="text-muted mb-1">Total Investors</h6>
                                            <h4 class="mb-0">{{ $totalInvestors }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Investment Amount Card -->
                            <div class="col-md-4 col-sm-6 mb-3  mt-3">
                                <div class="card shadow border-0">
                                    <div class="card-body d-flex align-items-center">
                                        <div class="me-3">
                                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center"
                                                style="width:50px; height:50px;">
                                                <i class="fas fa-coins"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <h6 class="text-muted mb-1">Total Investment</h6>
                                            <h4 class="mb-0">
                                                ৳ {{ number_format($totalInvestment, 2) }} 
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>


                        <h4 class="mt-4">Investors List</h4>

                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Investor Name</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Investment Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($project->investments as $investment)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $investment->user->name }}</td>
                                        <td>৳ {{ number_format($investment->investment_amount, 2) }}</td>
                                        <td>
                                            <span class="badge bg-warning text-dark">{{ $investment->status }}</span>
                                        </td>
                                        <td>{{ $investment->investment_date->format('d M Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No investments yet</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="text-end mt-3">
                            <a href="{{ route('admin.project.edit', $project->id) }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Edit Project
                            </a>
                            <a href="{{ route('admin.project.index') }}" class="btn btn-secondary">Back to List</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
