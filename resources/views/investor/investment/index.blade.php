@extends('layouts.investor')

@section('content')
    <!-- Breadcrumb -->
    <div class="breadcrumb-header d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">{{ $pageTitle ?? 'My Investment Portfolio' }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('investor.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">My Portfolio</li>
            </ol>
        </nav>
    </div>

    <!-- Portfolio Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h3 class="card-title mb-2">My Investment Portfolio</h3>
                            <p class="card-text mb-0">Track your investments and monitor your financial growth</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <button class="btn btn-light btn-lg" data-bs-toggle="modal"
                                data-bs-target="#newInvestmentModal">
                                <i class="fa-solid fa-plus me-2"></i>Start New Investment
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Display error message -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Investment Portfolio Table -->
    <div class="card shadow border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">PROJECT NAME</th>
                            <th>INVESTMENT AMOUNT</th>
                            <th>CURRENT VALUE</th>
                            <th>PROFIT/LOSS</th>
                            <th>STATUS</th>
                            <th class="pe-4">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($investments as $investment)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-bold">{{ $investment->project->name }}</h6>
                                            <small class="text-muted d-block">
                                                @if ($investment->type == 'short-term')
                                                    <span class="badge bg-light text-dark me-1">Short-term</span> |
                                                @elseif($investment->type == 'regular')
                                                    <span class="badge bg-light text-dark me-1">Regular Investment</span> |
                                                @elseif($investment->type == 'fixed-deposit')
                                                    <span class="badge bg-light text-dark me-1">Fixed Deposit</span> |
                                                @endif
                                                <span
                                                    class="text-capitalize {{ $investment->risk_level == 'low' ? 'text-success' : ($investment->risk_level == 'medium' ? 'text-warning' : 'text-danger') }}">
                                                    {{ $investment->risk_level }} Risk
                                                </span>
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong
                                        class="text-dark">${{ number_format($investment->investment_amount, 2) }}</strong>
                                </td>
                                <td>
                                    <strong class="text-dark">৳{{ number_format($investment->current_value, 2) }}</strong>
                                </td>
                                <td>
                                    <span
                                        class="fw-bold {{ $investment->profit_loss >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $investment->profit_loss >= 0 ? '+' : '' }}৳{{ number_format($investment->profit_loss, 2) }}
                                        ({{ $investment->profit_loss >= 0 ? '+' : '' }}{{ number_format($investment->profit_loss_percentage, 2) }}%)
                                    </span>
                                </td>
                                <td>
                                    @if ($investment->status == 'active')
                                        <span class="badge bg-success rounded-pill">ACTIVE</span>
                                    @elseif($investment->status == 'managed')
                                        <span class="badge bg-info rounded-pill">MANAGED</span>
                                    @else
                                        <span
                                            class="badge bg-secondary rounded-pill">{{ strtoupper($investment->status) }}</span>
                                    @endif
                                </td>
                                <td class="pe-4">
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                                            data-bs-target="#viewInvestmentModal{{ $investment->id }}">
                                            View
                                        </button>
                                        @if ($investment->status == 'active')
                                            <button type="button" class="btn btn-outline-success" data-bs-toggle="modal"
                                                data-bs-target="#investMoreModal{{ $investment->id }}">
                                                Invest More
                                            </button>
                                        @endif
                                    </div>
                                </td>

                            </tr>

                            <!-- Invest More Modal for each investment -->
                            <div class="modal fade" id="investMoreModal{{ $investment->id }}" tabindex="-1"
                                aria-labelledby="investMoreModalLabel{{ $investment->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="investMoreModalLabel{{ $investment->id }}">Make a
                                                New Investment</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('investor.investment.add.more', $investment) }}"
                                            method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Select Project</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $investment->project->name }}" readonly>
                                                    <small class="form-text text-muted">You are adding more investment to
                                                        this project</small>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="additional_amount{{ $investment->id }}"
                                                        class="form-label">Investment Amount ($)</label>
                                                    <input type="number" class="form-control"
                                                        id="additional_amount{{ $investment->id }}"
                                                        name="additional_amount" min="1000" step="100" required
                                                        placeholder="e.g., 25000">
                                                    <div class="form-text">Enter the amount you want to add to your
                                                        investment</div>
                                                </div>
                                                <div class="alert alert-light border">
                                                    <small>
                                                        <i class="fa-solid fa-info-circle me-1 text-primary"></i>
                                                        Current Investment:
                                                        <strong>৳{{ number_format($investment->investment_amount, 2) }}</strong>
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Confirm Investment</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- View Investment Details Modal -->
                            <div class="modal fade" id="viewInvestmentModal{{ $investment->id }}" tabindex="-1"
                                aria-labelledby="viewInvestmentModalLabel{{ $investment->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title" id="viewInvestmentModalLabel{{ $investment->id }}">
                                                <i class="fa-solid fa-chart-line me-2"></i>
                                                Investment Details - {{ $investment->project->name }}
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <!-- Project Information -->
                                                <div class="col-md-6">
                                                    <div class="card border-0 shadow-sm mb-4">
                                                        <div class="card-header bg-light">
                                                            <h6 class="mb-0"><i class="fa-solid fa-building me-2"></i>Project Information</h6>
                                                        </div>
                                                        <div class="card-body">
                                                            <table class="table table-borderless table-sm">
                                                                <tr>
                                                                    <td class="text-muted" width="40%">Project Name:</td>
                                                                    <td class="fw-bold">{{ $investment->project->name }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-muted">Investment Type:</td>
                                                                    <td>
                                                                        <span class="badge bg-primary">
                                                                            {{ ucfirst($investment->type) }}
                                                                        </span>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-muted">Risk Level:</td>
                                                                    <td>
                                                                        <span
                                                                            class="badge bg-{{ $investment->risk_level == 'low' ? 'success' : ($investment->risk_level == 'medium' ? 'warning' : 'danger') }}">
                                                                            {{ ucfirst($investment->risk_level) }} Risk
                                                                        </span>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-muted">Project ROI:</td>
                                                                    <td class="fw-bold text-success">{{ $investment->project->roi }}%</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-muted">Project Status:</td>
                                                                    <td>
                                                                        <span class="badge bg-{{ $investment->project->status_color }}">
                                                                            {{ $investment->project->status }}
                                                                        </span>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Investment Performance -->
                                                <div class="col-md-6">
                                                    <div class="card border-0 shadow-sm mb-4">
                                                        <div class="card-header bg-light">
                                                            <h6 class="mb-0"><i class="fa-solid fa-chart-bar me-2"></i>Investment Performance
                                                            </h6>
                                                        </div>
                                                        <div class="card-body">
                                                            <table class="table table-borderless table-sm">
                                                                <tr>
                                                                    <td class="text-muted" width="40%">Initial Investment:</td>
                                                                    <td class="fw-bold">${{ number_format($investment->investment_amount, 2) }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-muted">Current Value:</td>
                                                                    <td class="fw-bold">${{ number_format($investment->current_value, 2) }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-muted">Profit/Loss:</td>
                                                                    <td
                                                                        class="fw-bold {{ $investment->profit_loss >= 0 ? 'text-success' : 'text-danger' }}">
                                                                        {{ $investment->profit_loss >= 0 ? '+' : '' }}${{ number_format($investment->profit_loss, 2) }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-muted">Return Percentage:</td>
                                                                    <td
                                                                        class="fw-bold {{ $investment->profit_loss_percentage >= 0 ? 'text-success' : 'text-danger' }}">
                                                                        {{ $investment->profit_loss_percentage >= 0 ? '+' : '' }}{{ number_format($investment->profit_loss_percentage, 2) }}%
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-muted">Investment Date:</td>
                                                                    <td class="fw-bold">{{ $investment->investment_date->format('M d, Y') }}</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Project Description -->
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="card border-0 shadow-sm">
                                                        <div class="card-header bg-light">
                                                            <h6 class="mb-0"><i class="fa-solid fa-file-lines me-2"></i>Project Description</h6>
                                                        </div>
                                                        <div class="card-body">
                                                            <p class="mb-0">
                                                                {{ $investment->project->description ?? 'No description available for this project.' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Investment Timeline -->
                                            <div class="row mt-4">
                                                <div class="col-12">
                                                    <div class="card border-0 shadow-sm">
                                                        <div class="card-header bg-light">
                                                            <h6 class="mb-0"><i class="fa-solid fa-clock-rotate-left me-2"></i>Investment
                                                                Timeline</h6>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="timeline">
                                                                <div class="timeline-item">
                                                                    <div class="timeline-marker bg-success"></div>
                                                                    <div class="timeline-content">
                                                                        <h6 class="mb-1">Investment Started</h6>
                                                                        <p class="text-muted mb-0">
                                                                            {{ $investment->investment_date->format('F d, Y') }}</p>
                                                                        <small>Initial investment of
                                                                            ${{ number_format($investment->investment_amount, 2) }}</small>
                                                                    </div>
                                                                </div>
                                                                @if ($investment->maturity_date)
                                                                    <div class="timeline-item">
                                                                        <div class="timeline-marker bg-info"></div>
                                                                        <div class="timeline-content">
                                                                            <h6 class="mb-1">Expected Maturity</h6>
                                                                            <p class="text-muted mb-0">
                                                                                {{ $investment->maturity_date->format('F d, Y') }}</p>
                                                                            <small>Projected completion date</small>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            @if ($investment->status == 'active')
                                                <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                                    data-bs-target="#investMoreModal{{ $investment->id }}" data-bs-dismiss="modal">
                                                    <i class="fa-solid fa-plus me-1"></i>Invest More
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="fa-solid fa-briefcase fa-4x text-muted mb-4"></i>
                                        <h4 class="text-muted">No Investments Yet</h4>
                                        <p class="text-muted mb-4">You haven't made any investments yet. Start your
                                            investment journey today!</p>
                                        <button class="btn btn-primary btn-lg" data-bs-toggle="modal"
                                            data-bs-target="#newInvestmentModal">
                                            <i class="fa-solid fa-plus me-2"></i>Start New Investment
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- New Investment Modal (Keep your existing one) -->
    <div class="modal fade" id="newInvestmentModal" tabindex="-1" aria-labelledby="newInvestmentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newInvestmentModalLabel">Make a New Investment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('investor.investment.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                       <div class="mb-3">
                            <label for="project_id" class="form-label">Select Project</label>
                            <select class="form-select" id="project_id" name="project_id" required>
                                <option value="">Choose a project...</option>
                                @foreach ($availableProjects as $project)
                                    <option value="{{ $project->id }}"
                                        data-min-investment="{{ $project->min_investment }}"
                                        data-max-investment="{{ $project->max_investment }}"
                                        data-risk="{{ $project->risk_level }}"
                                        data-return="{{ $project->expected_return }}"
                                        data-investment-type="{{ $project->investment_type }}">
                                        {{ $project->name }} -
                                        {{ ucfirst($project->type) }} |
                                        {{ ucfirst($project->risk_level) }} Risk |
                                        Expected Return: {{ $project->expected_return }}%
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">Choose from available investment projects</div>
                        </div>
                        <div class="mb-3">
                            <label for="investment_amount" class="form-label">Investment Amount (৳)</label>
                            <input type="number" class="form-control" id="investment_amount" name="investment_amount"
                                min="1000" step="100" required placeholder="e.g., 25000">
                            <div class="form-text" id="amountHelp">Minimum investment: ৳1,000</div>
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">Investment Type</label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="short-term">Short-term</option>
                                <option value="regular">Regular Investment</option>
                                <option value="fixed-deposit">Fixed Deposit</option>
                                <option value="long-term">Long-term</option>
                            </select>
                        </div>
                        <div class="alert alert-light border">
                            <small>
                                <i class="fa-solid fa-lightbulb me-1 text-warning"></i>
                                <strong>Investment Tip:</strong> Consider your risk tolerance and investment goals when
                                choosing projects.
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Confirm Investment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        }

        /* Timeline Styles */
        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }

        .timeline-marker {
            position: absolute;
            left: -30px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 3px solid #fff;
            box-shadow: 0 0 0 3px #dee2e6;
        }

        .timeline-content {
            padding: 10px 0;
        }

        .timeline-item:not(:last-child):after {
            content: '';
            position: absolute;
            left: -24px;
            top: 20px;
            bottom: -20px;
            width: 2px;
            background: #dee2e6;
        }

        /* Modal Enhancements */
        .modal-xl {
            max-width: 900px;
        }

        .card-header h6 {
            font-size: 1rem;
            font-weight: 600;
        }

        .table-sm td {
            padding: 0.5rem 0.25rem;
        }

        .badge {
            font-size: 0.75rem;
        }
    </style>
@endpush

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const projectSelect = document.getElementById('project_id');
    const typeSelect = document.getElementById('type');
    
    // Map database values to form values
    const investmentTypeMap = {
        'short': 'short-term',
        'regular': 'regular',
        'fdi': 'fixed-deposit'
        // Add 'long-term' if needed in database
    };
    
    projectSelect.addEventListener('change', function() {
        if (this.value === '') {
            // Reset to all options if no project selected
            resetTypeDropdown();
            return;
        }
        
        const selectedOption = this.options[this.selectedIndex];
        const projectInvestmentType = selectedOption.getAttribute('data-investment-type');
        
        // Map database value to form value
        const formInvestmentType = investmentTypeMap[projectInvestmentType] || projectInvestmentType;
        
        // Update the type dropdown
        updateTypeDropdown(formInvestmentType);
    });
    
    function updateTypeDropdown(allowedType) {
        // Disable all options first
        Array.from(typeSelect.options).forEach(option => {
            option.disabled = false;
            option.hidden = false;
        });
        
        // Enable only the matching option
        Array.from(typeSelect.options).forEach(option => {
            if (option.value !== allowedType && option.value !== '') {
                option.disabled = true;
                option.hidden = true;
            }
        });
        
        // Set the selected value
        typeSelect.value = allowedType;
    }
    
    function resetTypeDropdown() {
        Array.from(typeSelect.options).forEach(option => {
            option.disabled = false;
            option.hidden = false;
        });
        typeSelect.value = 'short-term'; // Default value
    }
    
    // Initialize on page load if a project is already selected
    if (projectSelect.value !== '') {
        projectSelect.dispatchEvent(new Event('change'));
    }
});
</script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const projectSelect = document.getElementById('project_id');
            const amountInput = document.getElementById('investment_amount');
            const amountHelp = document.getElementById('amountHelp');

            if (projectSelect && amountInput && amountHelp) {
                projectSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const minAmount = selectedOption.getAttribute('data-min-investment');
                    const maxAmount = selectedOption.getAttribute('data-max-investment');

                    if (minAmount) {
                        amountInput.min = minAmount;
                        let helpText = `Minimum investment: ৳${parseInt(minAmount).toLocaleString()}`;

                        if (maxAmount) {
                            helpText += ` | Maximum investment: ৳${parseInt(maxAmount).toLocaleString()}`;
                        }

                        amountHelp.textContent = helpText;
                    }
                });
            }
        });
    </script>
@endpush
