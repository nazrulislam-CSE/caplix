@extends('layouts.entrepreneur')

@section('content')
    <!-- Breadcrumb -->
    <div class="breadcrumb-header d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">{{ $pageTitle ?? 'Submit New Project for Funding' }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('entrepreneur.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#">Reports & Audit</a></li>
            </ol>
        </nav>
    </div>

    <!-- Project Form -->
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow border-0">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Reports & Audit</h5>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('entrepreneur.project.profit.report.store') }}" method="POST">
                            @csrf

                            <!-- Project Select -->
                            <div class="mb-3">
                                <label class="form-label">Select Project</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fa fa-briefcase"></i>
                                    </span>
                                    <select name="project_id" class="form-select" required>
                                        <option value="">-- Select Project --</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <!-- Month -->
                                <div class="col-md-4">
                                    <label class="form-label">Month</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                        <select name="month" class="form-select" required>
                                            @php
                                                $months = [
                                                    'January',
                                                    'February',
                                                    'March',
                                                    'April',
                                                    'May',
                                                    'June',
                                                    'July',
                                                    'August',
                                                    'September',
                                                    'October',
                                                    'November',
                                                    'December',
                                                ];
                                            @endphp

                                            @foreach ($months as $month)
                                                <option value="{{ $month }}"
                                                    {{ now()->format('F') == $month ? 'selected' : '' }}>
                                                    {{ $month }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Year -->
                                <div class="col-md-4">
                                    <label class="form-label">Year</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fa fa-clock"></i>
                                        </span>
                                        <select name="year" class="form-select" required>
                                            @for ($year = now()->year; $year >= now()->year - 9; $year--)
                                                <option value="{{ $year }}"
                                                    {{ now()->year == $year ? 'selected' : '' }}>
                                                    {{ $year }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>

                                <!-- Profit -->
                                <div class="col-md-4">
                                    <label class="form-label">Total Profit Amount</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fa fa-money-bill-wave"></i>
                                        </span>
                                        <input type="number" step="0.01" name="total_profit" class="form-control"
                                            placeholder="Enter Total Profit" required>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-paper-plane"></i> Submit Report for Audit
                                </button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
