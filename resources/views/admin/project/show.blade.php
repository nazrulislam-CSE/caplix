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
