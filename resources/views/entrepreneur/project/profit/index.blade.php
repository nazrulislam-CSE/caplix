@extends('layouts.entrepreneur')

@section('content')
    <!-- Breadcrumb -->
    <div class="breadcrumb-header d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">{{ $pageTitle ?? 'Submit New Project for Funding' }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('entrepreneur.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#">My Profit Reports</a></li>
            </ol>
        </nav>
    </div>

    <!-- Project Form -->
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow border-0">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">My Profit Reports</h5>
                         <a href="{{ route('entrepreneur.project.profit.report.create') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-plus"></i> Reports & Audit
                        </a>
                    </div>
                    

                    <div class="card-body">
                        @if ($reports->count())
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead class="table-dark text-center">
                                        <tr>
                                            <th>Sl</th>
                                            <th>Project</th>
                                            <th>Month</th>
                                            <th>Year</th>
                                            <th>Total Profit</th>
                                            <th>Admin Share</th>
                                            <th>Investor Share</th>
                                            <th>Referral Share</th>
                                            <th>Status</th>
                                            <th>Submitted At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($reports as $report)
                                            <tr class="text-center">
                                                <td>{{ $loop->iteration + ($reports->currentPage() - 1) * $reports->perPage() }}
                                                </td>
                                                <td>{{ $report->project->name ?? '-' }}</td>
                                                <td>{{ $report->month }}</td>
                                                <td>{{ $report->year }}</td>
                                                <td>{{ number_format($report->total_profit, 2) }}</td>
                                                <td>{{ number_format($report->admin_share, 2) }}</td>
                                                <td>{{ number_format($report->investor_share, 2) }}</td>
                                                <td>{{ number_format($report->referral_share, 2) }}</td>
                                                <td>
                                                    @if ($report->status == 'submitted')
                                                        <span class="badge bg-warning text-dark">Submitted</span>
                                                    @elseif($report->status == 'approved')
                                                        <span class="badge bg-success">Approved</span>
                                                    @elseif($report->status == 'rejected')
                                                        <span class="badge bg-danger">Rejected</span>
                                                    @endif
                                                </td>
                                                <td>{{ $report->created_at->format('d M Y') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center">
                                {{ $reports->links() }}
                            </div>
                        @else
                            <div class="alert alert-info text-center">No profit reports found.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
