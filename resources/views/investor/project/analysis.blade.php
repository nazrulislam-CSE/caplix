@extends('layouts.investor')

@section('content')
    <!-- Breadcrumb -->
    <div class="breadcrumb-header d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">{{ $pageTitle ?? 'Project Analysis' }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('investor.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Project Analysis</li>
            </ol>
        </nav>
    </div>

    <!-- Project Analysis Section -->
    <div class="card shadow-sm p-4">
        <h5 class="mb-3 fw-bold">Project Analysis</h5>
        <p class="text-muted">Explore detailed information about ongoing and upcoming projects before you invest.</p>

        <div class="row">
            @foreach ($projects as $project)
                <div class="col-md-6 mb-4">
                    <div class="border rounded-3 p-4 h-100 d-flex flex-column justify-content-between">
                        <div>
                            <h6 class="fw-bold">{{ $project->name }}</h6>
                            <p class="text-muted mb-2">{{ Str::limit($project->description, 80) }}</p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                {{-- ROI --}}
                                <div>
                                    <i class="bi bi-graph-up-arrow me-1"></i>
                                    {{ $project->roi ?? 0 }}% ROI
                                </div>

                                {{-- Risk Level --}}
                                <div>
                                    <i class="bi bi-shield-check me-1"></i>
                                    @if ($project->status == 'At Risk')
                                        High Risk
                                    @elseif($project->status == 'Approved')
                                        Medium Risk
                                    @else
                                        Low Risk
                                    @endif
                                </div>
                            </div>

                        </div>
                        <button class="btn btn-primary w-100 mt-auto" data-bs-toggle="modal"
                            data-bs-target="#projectModal{{ $project->id }}">
                            View Details
                        </button>
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="projectModal{{ $project->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title fw-bold">Project Details: {{ $project->name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-4">
                                <div class="row">
                                    <div class="col-md-6 border-end">
                                        <p>{{ $project->description }}</p>
                                        <span class="badge 
                                            @if ($project->status == 'Pending') bg-warning text-dark
                                            @elseif($project->status == 'Approved') bg-success
                                            @elseif($project->status == 'Issued') bg-primary
                                            @elseif($project->status == 'At Risk') bg-danger
                                            @else bg-secondary @endif">
                                            {{ $project->status }}
                                        </span>

                                        <h6 class="mt-3 fw-bold">100% Funded</h6>

                                        <p class="mt-2 mb-1"><strong>Capital Required:</strong>
                                            ৳{{ number_format($project->capital_required, 2) }}</p>
                                        <p><strong>Investors:</strong> {{ rand(10, 30) }}</p>
                                        <p><strong>Rating:</strong> ⭐ 4.8/5 ({{ rand(8, 20) }} Reviews)</p>
                                        <p><strong>Favorites:</strong> {{ rand(100, 200) }} Investors</p>

                                        <div class="d-flex mt-3">
                                            <button class="btn btn-danger me-2"><i class="bi bi-heart"></i>
                                                Love/Favorite</button>
                                            <button class="btn btn-outline-secondary">Highly Interested</button>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <h6 class="fw-bold mb-3">Profit History</h6>
                                        <canvas id="profitChart{{ $project->id }}" height="200"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @foreach ($projects as $project)
                new Chart(document.getElementById('profitChart{{ $project->id }}'), {
                    type: 'bar',
                    data: {
                        labels: ['Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
                        datasets: [{
                            label: 'Profit (৳)',
                            data: [1200, 1500, 900, 2200, 1800, 3000],
                            backgroundColor: '#198754'
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            @endforeach
        });
    </script>
@endpush
