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

    <!-- Project Form -->
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow border-0">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Edit Project</h5>
                        <a href="{{ route('admin.project.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('admin.project.update', $project->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            {{-- Project Title --}}
                            <div class="mb-3">
                                <label for="name" class="form-label">Project Title <span
                                        class="text-danger">*</span></label>
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
                                    <option value="short"
                                        {{ old('investment_type', $project->investment_type) == 'short' ? 'selected' : '' }}>
                                        Short Term Investment</option>
                                    <option value="regular"
                                        {{ old('investment_type', $project->investment_type) == 'regular' ? 'selected' : '' }}>
                                        Regular Investment</option>
                                    <option value="fdi"
                                        {{ old('investment_type', $project->investment_type) == 'fdi' ? 'selected' : '' }}>
                                        Fixed Deposit Investment (FDI)</option>
                                </select>
                                @error('investment_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Duration (hidden by default) --}}
                            <div class="mb-3" id="short_duration_div" style="display: none;">
                                <label for="short_duration" class="form-label">Duration (Months)</label>
                                <select name="short_duration" id="short_duration" class="form-select">
                                    <option value="">-- Select Duration --</option>
                                    @for ($i = 2; $i <= 8; $i++)
                                        <option value="{{ $i }}"
                                            {{ old('short_duration', $project->short_duration) == $i ? 'selected' : '' }}>
                                            {{ $i }} Month{{ $i > 1 ? 's' : '' }}</option>
                                    @endfor
                                </select>
                            </div>

                            {{-- Duration for Regular Investment (hidden by default) --}}
                            <div class="mb-3" id="regular_duration_div" style="display: none;">
                                <label for="regular_duration" class="form-label">Duration (Years)</label>
                                <select name="regular_duration" id="regular_duration" class="form-select">
                                    <option value="">-- Select Duration --</option>
                                    @for ($i = 1; $i <= 20; $i++)
                                        <option value="{{ $i }}"
                                            {{ old('regular_duration', $project->regular_duration) == $i ? 'selected' : '' }}>
                                            {{ $i }} Year{{ $i > 1 ? 's' : '' }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            {{-- Approximate ROI --}}
                            <div class="mb-3">
                                <label for="roi" class="form-label">Approximate ROI %</label>
                                <input type="number" name="roi" id="roi" step="0.01"
                                    class="form-control @error('roi') is-invalid @enderror" placeholder="e.g., 15"
                                    value="{{ old('roi', $project->roi) }}">
                                @error('roi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Description --}}
                            <div class="mb-3">
                                <label for="description" class="form-label">Project Description</label>
                                <textarea name="description" id="description" rows="4"
                                    class="form-control @error('description') is-invalid @enderror" placeholder="Describe your business idea...">{{ old('description', $project->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Capital Required --}}
                            <div class="mb-3">
                                <label for="capital_required" class="form-label">Capital Required (৳)</label>
                                <input type="number" name="capital_required" id="capital_required" step="0.01"
                                    class="form-control @error('capital_required') is-invalid @enderror"
                                    placeholder="e.g., 500000"
                                    value="{{ old('capital_required', $project->capital_required) }}">
                                @error('capital_required')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Entrepreneur Select --}}
                            <div class="mb-3">
                                <label for="entrepreneur_id" class="form-label">
                                    Select Entrepreneur <span class="text-danger">*</span>
                                </label>

                                <select name="entrepreneur_id" id="entrepreneur_id"
                                    class="form-select @error('entrepreneur_id') is-invalid @enderror">

                                    <option value="">-- Select Entrepreneur --</option>

                                    @foreach ($entrepreneurs as $entrepreneur)
                                        <option value="{{ $entrepreneur->id }}"
                                            {{ (old('entrepreneur_id') ?? $project->entrepreneur_id) == $entrepreneur->id ? 'selected' : '' }}>
                                            {{ $entrepreneur->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('entrepreneur_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Project Url --}}
                            <div class="mb-3">
                                <label for="url" class="form-label">Project URL</label>
                                <input type="text" name="url" id="url" class="form-control @error('url') is-invalid @enderror"
                                    placeholder="Enter project url" value="{{ old('url',$project->url) }}">
                                @error('url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>



                            {{-- Status --}}
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status"
                                    class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="Pending"
                                        {{ old('status', $project->status) == 'Pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="Approved"
                                        {{ old('status', $project->status) == 'Approved' ? 'selected' : '' }}>Approved
                                    </option>
                                    <option value="Issued"
                                        {{ old('status', $project->status) == 'Issued' ? 'selected' : '' }}>Issued</option>
                                    <option value="At Risk"
                                        {{ old('status', $project->status) == 'At Risk' ? 'selected' : '' }}>At Risk
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Pitch Deck Upload --}}
                            <div class="mb-4">
                                <label for="pitch_deck" class="form-label">Upload Pitch Deck & Portfolio (PDF)</label>
                                <input type="file" name="pitch_deck" id="pitch_deck"
                                    class="form-control @error('pitch_deck') is-invalid @enderror"
                                    accept="application/pdf">
                                @if ($project->pitch_deck)
                                    <small class="text-muted">Current File: <a
                                            href="{{ asset('storage/' . $project->pitch_deck) }}"
                                            target="_blank">View</a></small>
                                @endif
                                @error('pitch_deck')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Submit Button --}}
                            <div class="text-end mt-4">
                                <a href="{{ route('admin.project.index') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Update Project</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const investmentType = document.getElementById('investment_type');
        const shortDurationDiv = document.getElementById('short_duration_div');
        const regularDurationDiv = document.getElementById('regular_duration_div');

        investmentType.addEventListener('change', function() {
            if (this.value === 'short') {
                shortDurationDiv.style.display = 'block';
                regularDurationDiv.style.display = 'none';
            } 
            else if (this.value === 'fdi') {
                regularDurationDiv.style.display = 'block';
                shortDurationDiv.style.display = 'none';
            }
            else {
                // hide all if none selected
                shortDurationDiv.style.display = 'none';
                regularDurationDiv.style.display = 'none';
            }
        });

        // On page load (for validation errors)
        window.addEventListener('DOMContentLoaded', function() {
            if (investmentType.value === 'short') {
                shortDurationDiv.style.display = 'block';
            }
            if (investmentType.value === 'regular') {
                regularDurationDiv.style.display = 'block';
            }
        });
    </script>
@endsection
