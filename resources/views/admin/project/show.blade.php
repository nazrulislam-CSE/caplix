@extends('layouts.admin')

@section('content')
    <!-- Breadcrumb -->
    <div class="breadcrumb-header d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">{{ $pageTitle ?? 'Project Details' }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.project.index') }}">Projects</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $project->name }}</li>
            </ol>
        </nav>
    </div>

    <!-- Project Details -->
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-folder-open me-2"></i> {{ $project->name }}
                        </h5>
                        <a href="{{ route('admin.project.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Back to List
                        </a>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered align-middle mb-0">
                                <tbody>
                                    <tr>
                                        <th style="width: 30%">Project Name</th>
                                        <td>{{ $project->name }}</td>
                                    </tr>

                                    <tr>
                                        <th>Description</th>
                                        <td>{{ $project->description ? $project->description : 'No description provided.' }}</td>
                                    </tr>

                                    <tr>
                                        <th>Capital Raised</th>
                                        <td>
                                            <strong class="text-success">
                                                ${{ number_format($project->capital_raised, 2) }}
                                            </strong>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Goal</th>
                                        <td>
                                            <strong>${{ number_format($project->goal, 2) }}</strong>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Progress</th>
                                        <td>
                                            @php
                                                $progress = ($project->capital_raised / $project->goal) * 100;
                                            @endphp
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar 
                                                    {{ $progress >= 100 ? 'bg-success' : ($progress >= 50 ? 'bg-info' : 'bg-warning') }}"
                                                    role="progressbar"
                                                    style="width: {{ number_format($progress, 2) }}%;"
                                                    aria-valuenow="{{ number_format($progress, 2) }}"
                                                    aria-valuemin="0"
                                                    aria-valuemax="100">
                                                    {{ number_format($progress, 2) }}%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            @switch($project->status)
                                                @case('Pending')
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                    @break
                                                @case('Approved')
                                                    <span class="badge bg-success">Approved</span>
                                                    @break
                                                @case('Issued')
                                                    <span class="badge bg-primary">Issued</span>
                                                    @break
                                                @case('At Risk')
                                                    <span class="badge bg-danger">At Risk</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ $project->status }}</span>
                                            @endswitch
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Score</th>
                                        <td>
                                            <span class="badge 
                                                {{ $project->score < 50 || $project->has_complaint ? 'bg-danger' : 'bg-success' }}">
                                                {{ $project->score }}
                                            </span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Complaint Status</th>
                                        <td>
                                            @if ($project->has_complaint)
                                                <span class="badge bg-danger"><i class="fas fa-exclamation-triangle me-1"></i> Has Complaint</span>
                                            @else
                                                <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> No Complaint</span>
                                            @endif
                                        </td>
                                    </tr>

                                    @if (!empty($project->image))
                                        <tr>
                                            <th>Project Image</th>
                                            <td>
                                                <img src="{{ asset('uploads/projects/' . $project->image) }}" 
                                                     alt="Project Image" 
                                                     class="img-thumbnail" 
                                                     style="max-width: 200px;">
                                            </td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <th>Created At</th>
                                        <td>{{ $project->created_at->format('d M Y, h:i A') }}</td>
                                    </tr>

                                    <tr>
                                        <th>Last Updated</th>
                                        <td>{{ $project->updated_at->format('d M Y, h:i A') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Last Updated: {{ $project->updated_at->diffForHumans() }}</small>
                        </div>
                        <div>
                            <a href="{{ route('admin.project.edit', $project->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit me-1"></i> Edit
                            </a>
                            <form action="{{ route('admin.project.destroy', $project->id) }}" method="POST"
                                  style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure you want to delete this project?')">
                                    <i class="fas fa-trash-alt me-1"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
