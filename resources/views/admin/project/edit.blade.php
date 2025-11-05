@extends('layouts.admin')

@section('content')
    <!-- Breadcrumb -->
    <div class="breadcrumb-header d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">{{ $pageTitle ?? 'Edit Project' }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.project.index') }}">Projects</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Project</li>
            </ol>
        </nav>
    </div>

    <!-- Edit Project Form -->
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Edit Project</h5>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('admin.project.update', $project->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            {{-- Project Title --}}
                            <div class="mb-3">
                                <label for="name" class="form-label">Project Title <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="e.g., Eco Weavers Ltd." value="{{ old('name', $project->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Investment Type --}}
                            <div class="mb-3">
                                <label for="investment_type" class="form-label">Investment Type</label>
                                <select name="investment_type" id="investment_type"
                                    class="form-select @error('investment_type') is-invalid @enderror">
                                    <option value="">-- Select Type --</option>
                                    <option value="Equity" {{ old('investment_type', $project->investment_type) == 'Equity' ? 'selected' : '' }}>Equity</option>
                                    <option value="Loan" {{ old('investment_type', $project->investment_type) == 'Loan' ? 'selected' : '' }}>Loan</option>
                                    <option value="Partnership" {{ old('investment_type', $project->investment_type) == 'Partnership' ? 'selected' : '' }}>Partnership</option>
                                </select>
                                @error('investment_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Approximate ROI --}}
                            <div class="mb-3">
                                <label for="roi" class="form-label">Approximate ROI %</label>
                                <input type="number" name="roi" id="roi" step="0.01"
                                    class="form-control @error('roi') is-invalid @enderror"
                                    placeholder="e.g., 15" value="{{ old('roi', $project->roi) }}">
                                @error('roi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Description --}}
                            <div class="mb-3">
                                <label for="description" class="form-label">Project Description</label>
                                <textarea name="description" id="description" rows="4"
                                    class="form-control @error('description') is-invalid @enderror"
                                    placeholder="Describe your business idea...">{{ old('description', $project->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Capital Required --}}
                            <div class="mb-3">
                                <label for="capital_required" class="form-label">Capital Required (৳)</label>
                                <input type="number" name="capital_required" id="capital_required" step="0.01"
                                    class="form-control @error('capital_required') is-invalid @enderror"
                                    placeholder="e.g., 500000" value="{{ old('capital_required', $project->capital_required) }}">
                                @error('capital_required')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Pitch Deck Upload --}}
                            <div class="mb-4">
                                <label for="pitch_deck" class="form-label">Upload Pitch Deck & Portfolio (PDF)</label>
                                <input type="file" name="pitch_deck" id="pitch_deck"
                                    class="form-control @error('pitch_deck') is-invalid @enderror"
                                    accept="application/pdf">
                                @error('pitch_deck')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                @if ($project->pitch_deck)
                                    <div class="mt-2">
                                        <a href="{{ asset('uploads/pitch_decks/' . $project->pitch_deck) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                            <i class="bi bi-file-earmark-pdf"></i> View Current File
                                        </a>
                                    </div>
                                @endif
                            </div>

                            {{-- Status --}}
                            <div class="mb-3">
                                <label for="status" class="form-label">Project Status</label>
                                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                                    <option value="Pending" {{ old('status', $project->status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Approved" {{ old('status', $project->status) == 'Approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="Issued" {{ old('status', $project->status) == 'Issued' ? 'selected' : '' }}>Issued</option>
                                    <option value="At Risk" {{ old('status', $project->status) == 'At Risk' ? 'selected' : '' }}>At Risk</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Has Complaint --}}
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" value="1" id="has_complaint"
                                    name="has_complaint" {{ old('has_complaint', $project->has_complaint) ? 'checked' : '' }}>
                                <label class="form-check-label" for="has_complaint">
                                    Has Complaint (Mark project as at risk)
                                </label>
                            </div>

                            {{-- Current Score --}}
                            <div class="mb-3">
                                <label for="score" class="form-label">Current Score</label>
                                <input type="number" name="score" id="score"
                                    class="form-control" value="{{ $project->score }}" readonly>
                            </div>

                            <p class="small text-muted">
                                Updating this project may affect its <strong>Trust Score</strong> and <strong>Risk Level</strong>.
                            </p>

                            {{-- Submit Buttons --}}
                            <div class="text-end mt-4">
                                <a href="{{ route('admin.project.index') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-success">Update Project</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
