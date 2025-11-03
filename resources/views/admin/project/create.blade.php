@extends('layouts.admin')

@section('content')
    <!-- Breadcrumb -->
    <div class="breadcrumb-header d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">{{ $pageTitle ?? 'Add New Project' }}</h4>
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
            <div class="col-lg-8">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Add New Project</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.project.store') }}" method="POST">
                            @csrf

                            {{-- Project Name --}}
                            <div class="mb-3">
                                <label for="name" class="form-label">Project Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Enter project name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Description --}}
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" id="description" rows="4"
                                    class="form-control @error('description') is-invalid @enderror"
                                    placeholder="Write a short description...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Capital Raised --}}
                            <div class="mb-3">
                                <label for="capital_raised" class="form-label">Capital Raised ($)</label>
                                <input type="number" step="0.01" name="capital_raised" id="capital_raised"
                                    class="form-control @error('capital_raised') is-invalid @enderror"
                                    placeholder="0.00" value="{{ old('capital_raised', 0) }}">
                                @error('capital_raised')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Goal --}}
                            <div class="mb-3">
                                <label for="goal" class="form-label">Goal Amount ($)</label>
                                <input type="number" step="0.01" name="goal" id="goal"
                                    class="form-control @error('goal') is-invalid @enderror"
                                    placeholder="0.00" value="{{ old('goal', 0) }}">
                                @error('goal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Status --}}
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                                    <option value="Pending" {{ old('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Approved" {{ old('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="Issued" {{ old('status') == 'Issued' ? 'selected' : '' }}>Issued</option>
                                    <option value="At Risk" {{ old('status') == 'At Risk' ? 'selected' : '' }}>At Risk</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Submit Button --}}
                            <div class="text-end">
                                <a href="{{ route('admin.project.index') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Save Project</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
