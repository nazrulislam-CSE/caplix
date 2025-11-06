@extends('layouts.admin')

@section('content')
    <!-- Breadcrumb -->
    <div class="breadcrumb-header d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">{{ $pageTitle ?? 'Submit New Project for Funding' }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.project.index') }}">Projects</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add Project</li>
            </ol>
        </nav>
    </div>

    <!-- Project Form -->
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow border-0">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Add New Project</h5>
                        <a href="{{ route('admin.project.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>

                    <div class="card-body">
                        <p class="mb-4">
                            <i class="bi bi-info-circle text-primary"></i>
                            <strong>Requirement:</strong> An Entrepreneur must have an investment to submit a project. This
                            ensures commitment.
                        </p>

                        <form action="{{ route('admin.project.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            {{-- Project Title --}}
                            <div class="mb-3">
                                <label for="name" class="form-label">Project Title <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" id="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="e.g., Eco Weavers Ltd." value="{{ old('name') }}" required>
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
                                    <option value="short" {{ old('investment_type') == 'short' ? 'selected' : '' }}>Short
                                        Term Investment</option>
                                    <option value="regular" {{ old('investment_type') == 'regular' ? 'selected' : '' }}>
                                        Regular Investment</option>
                                    <option value="fdi" {{ old('investment_type') == 'fdi' ? 'selected' : '' }}>Fixed
                                        Deposit Investment (FDI)</option>
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
                                            {{ old('short_duration') == $i ? 'selected' : '' }}>{{ $i }}
                                            Month{{ $i > 1 ? 's' : '' }}</option>
                                    @endfor
                                </select>
                            </div>

                            {{-- Approximate ROI --}}
                            <div class="mb-3">
                                <label for="roi" class="form-label">Approximate ROI %</label>
                                <input type="number" name="roi" id="roi" step="0.01"
                                    class="form-control @error('roi') is-invalid @enderror" placeholder="e.g., 15"
                                    value="{{ old('roi') }}">
                                @error('roi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Description --}}
                            <div class="mb-3">
                                <label for="description" class="form-label">Project Description</label>
                                <textarea name="description" id="description" rows="4"
                                    class="form-control @error('description') is-invalid @enderror" placeholder="Describe your business idea...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Capital Required --}}
                            <div class="mb-3">
                                <label for="capital_required" class="form-label">Capital Required (৳)</label>
                                <input type="number" name="capital_required" id="capital_required" step="0.01"
                                    class="form-control @error('capital_required') is-invalid @enderror"
                                    placeholder="e.g., 500000" value="{{ old('capital_required') }}">
                                @error('capital_required')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Pitch Deck Upload --}}
                            <div class="mb-4">
                                <label for="pitch_deck" class="form-label">Upload Pitch Deck & Portfolio (PDF)</label>
                                <input type="file" name="pitch_deck" id="pitch_deck"
                                    class="form-control @error('pitch_deck') is-invalid @enderror" accept="application/pdf">
                                @error('pitch_deck')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <p class="small text-muted">
                                After submission, your project will be reviewed by our admin team. If approved, you will
                                earn badges like
                                <strong>'Verified Project'</strong>, a <strong>'Trust Score'</strong>, and a <strong>'Risk
                                    Level'</strong> assessment.
                            </p>

                            {{-- Submit Button --}}
                            <div class="text-end mt-4">
                                <a href="{{ route('admin.project.index') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-success">Submit for Review</button>
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

        // Show/hide duration select based on investment type
        investmentType.addEventListener('change', function() {
            if (this.value === 'short') {
                shortDurationDiv.style.display = 'block';
            } else {
                shortDurationDiv.style.display = 'none';
            }
        });

        // Show the duration if old value exists (on validation error)
        window.addEventListener('DOMContentLoaded', function() {
            if (investmentType.value === 'short') {
                shortDurationDiv.style.display = 'block';
            }
        });
    </script>
@endsection
