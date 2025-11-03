@extends('layouts.admin')

@section('content')
    <!-- Breadcrumb -->
    <div class="breadcrumb-header d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">{{ $pageTitle ?? 'Project Details' }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.project.index') }}">Projects</a></li>
                <li class="breadcrumb-item active" aria-current="page">Project Details</li>
            </ol>
        </nav>
    </div>

    <!-- Project Details -->
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Project Information</h5>
                        <a href="{{ route('admin.project.index') }}" class="btn btn-light btn-sm">← Back to List</a>
                    </div>

                    <div class="card-body">
                        {{-- Project Name --}}
                        <div class="mb-3">
                            <h5 class="fw-bold">Project Name:</h5>
                            <p class="text-muted">{{ $project->name }}</p>
                        </div>

                        {{-- Description --}}
                        <div class="mb-3">
                            <h5 class="fw-bold">Description:</h5>
                            <p class="text-muted">
                                {{ $project->description ?? 'No description provided.' }}
                            </p>
                        </div>

                        {{-- Capital & Goal --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h5 class="fw-bold">Capital Raised:</h5>
                                <p class="text-muted">${{ number_format($project->capital_raised, 2) }}</p>
                            </div>
                            <div class="col-md-6">
                                <h5 class="fw-bold">Goal:</h5>
                                <p class="text-muted">${{ number_format($project->goal, 2) }}</p>
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="mb-3">
                            <h5 class="fw-bold">Status:</h5>
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
                        </div>

                        {{-- Score --}}
                        <div class="mb-3">
                            <h5 class="fw-bold">Score:</h5>
                            @if ($project->score < 50 || $project->has_complaint)
                                <span class="badge bg-danger">{{ $project->score }}</span>
                            @else
                                <span class="badge bg-success">{{ $project->score }}</span>
                            @endif
                        </div>

                        {{-- Complaint --}}
                        <div class="mb-3">
                            <h5 class="fw-bold">Complaint Status:</h5>
                            @if ($project->has_complaint)
                                <span class="badge bg-danger">Has Complaint 🚨</span>
                            @else
                                <span class="badge bg-success">No Complaint</span>
                            @endif
                        </div>

                        {{-- Created / Updated --}}
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">Created at: {{ $project->created_at->format('d M Y, h:i A') }}</small>
                            </div>
                            <div class="col-md-6 text-end">
                                <small class="text-muted">Last updated: {{ $project->updated_at->format('d M Y, h:i A') }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-end">
                        <a href="{{ route('admin.project.edit', $project->id) }}" class="btn btn-primary">Edit Project</a>
                        <form action="{{ route('admin.project.destroy', $project->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Are you sure you want to delete this project?')">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
