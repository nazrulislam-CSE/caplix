<div class="kyc-details">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">{{ $kyc->company_name }}</h4>
                <span
                    class="badge bg-{{ $kyc->status == 'pending'
                        ? 'warning'
                        : ($kyc->status == 'under_review'
                            ? 'info'
                            : ($kyc->status == 'verified'
                                ? 'success'
                                : 'danger')) }} fs-6">
                    {{ ucfirst(str_replace('_', ' ', $kyc->status)) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-tabs" id="kycTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="business-tab" data-bs-toggle="tab" data-bs-target="#business"
                type="button" role="tab">
                <i class="fas fa-building me-2"></i>Business Info
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="owner-tab" data-bs-toggle="tab" data-bs-target="#owner" type="button"
                role="tab">
                <i class="fas fa-user-tie me-2"></i>Owner Info
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button"
                role="tab">
                <i class="fas fa-file-alt me-2"></i>Documents
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="shareholders-tab" data-bs-toggle="tab" data-bs-target="#shareholders"
                type="button" role="tab">
                <i class="fas fa-users me-2"></i>Shareholders
            </button>
        </li>
    </ul>

    <div class="tab-content p-3 border border-top-0 rounded-bottom" id="kycTabContent">
        <!-- Business Info Tab -->
        <div class="tab-pane fade show active" id="business" role="tabpanel">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label text-muted">Business Type</label>
                    <p class="fw-bold">{{ $kyc->business_type ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label text-muted">Registration No.</label>
                    <p class="fw-bold">{{ $kyc->registration_no ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label text-muted">Trade License No.</label>
                    <p class="fw-bold">{{ $kyc->trade_license_no ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label text-muted">TIN/BIN</label>
                    <p class="fw-bold">{{ $kyc->tin_bin ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label text-muted">Year of Establishment</label>
                    <p class="fw-bold">{{ $kyc->establishment_year ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label text-muted">Number of Employees</label>
                    <p class="fw-bold">{{ $kyc->number_of_employees ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label text-muted">Last Year Turnover (BDT)</label>
                    <p class="fw-bold">{{ $kyc->last_turnover ? number_format($kyc->last_turnover, 2) : 'N/A' }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label text-muted">Website</label>
                    <p class="fw-bold">
                        @if ($kyc->website)
                            <a href="{{ $kyc->website }}" target="_blank">{{ $kyc->website }}</a>
                        @else
                            N/A
                        @endif
                    </p>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label text-muted">Business Address</label>
                    <p class="fw-bold">{{ $kyc->business_address ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Owner Info Tab -->
        <div class="tab-pane fade" id="owner" role="tabpanel">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label text-muted">Owner Name</label>
                    <p class="fw-bold">{{ $kyc->owner_name }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label text-muted">Phone Number</label>
                    <p class="fw-bold">{{ $kyc->owner_phone }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label text-muted">Email</label>
                    <p class="fw-bold">{{ $kyc->owner_email }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label text-muted">NID/Passport</label>
                    <p class="fw-bold">{{ $kyc->owner_nid_passport }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label text-muted">Role in Company</label>
                    <p class="fw-bold">{{ $kyc->owner_role ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label text-muted">Entrepreneur Account</label>
                    <p class="fw-bold">
                        @if ($kyc->user)
                            {{ $kyc->user->name }} ({{ $kyc->user->email }})
                        @else
                            <span class="text-danger">User not found</span>
                        @endif
                    </p>
                </div>
            </div>

            <!-- Nominee Info -->
            @if ($kyc->nominee_name)
                <div class="mt-4 pt-3 border-top">
                    <h6 class="mb-3"><i class="fas fa-user-friends me-2"></i>Nominee Information</h6>
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label class="form-label text-muted">Nominee Name</label>
                            <p class="fw-bold">{{ $kyc->nominee_name }}</p>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label text-muted">Relation</label>
                            <p class="fw-bold">{{ $kyc->nominee_relation }}</p>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label text-muted">NID</label>
                            <p class="fw-bold">{{ $kyc->nominee_nid }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Documents Tab -->
        <div class="tab-pane fade" id="documents" role="tabpanel">
            <div class="row">
                @php
                    $documents = [
                        'doc_registration' => ['label' => 'Registration Certificate', 'icon' => 'fa-file-certificate'],
                        'doc_trade_license' => ['label' => 'Trade License', 'icon' => 'fa-file-contract'],
                        'doc_tin' => ['label' => 'TIN/BIN Certificate', 'icon' => 'fa-file-invoice-dollar'],
                        'doc_bank_statement' => ['label' => 'Bank Statement', 'icon' => 'fa-file-invoice'],
                        'doc_financials' => ['label' => 'Financial Statements', 'icon' => 'fa-chart-line'],
                    ];
                @endphp

                @foreach ($documents as $field => $doc)
                    <div class="col-md-6 mb-3">
                        <div class="card border">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas {{ $doc['icon'] }} text-primary me-2"></i>
                                        <strong>{{ $doc['label'] }}</strong>
                                    </div>
                                    {{-- @if (!empty($kyc->$field))
                                        <a href="{{ route('admin.kyc.download', [
                                            'kyc' => $kyc->id,
                                            'documentType' => str_replace('doc_', '', $field),
                                        ]) }}"
                                            class="btn btn-sm btn-outline-primary doc-badge" target="_blank">
                                            <i class="fas fa-download me-1"></i> Download
                                        </a>
                                    @else
                                        <span class="badge bg-secondary">Not Uploaded</span>
                                    @endif --}}
                                </div>
                                {{-- @if (!empty($kyc->$field))
                                    <small class="text-muted mt-2 d-block">
                                        <i class="fas fa-info-circle me-1"></i>
                                        {{ pathinfo($kyc->$field, PATHINFO_EXTENSION) | upper }} File
                                    </small>
                                @endif --}}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Shareholders Tab -->
        <div class="tab-pane fade" id="shareholders" role="tabpanel">
            @if (!empty($kyc->shareholders) && count($kyc->shareholders) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>NID/Passport</th>
                                <th>Share %</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kyc->shareholders as $index => $shareholder)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $shareholder['name'] }}</td>
                                    <td>{{ $shareholder['nid'] }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $shareholder['share'] }}%</span>
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="table-light">
                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                <td>
                                    <strong>{{ collect($kyc->shareholders)->sum('share') }}%</strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h5>No Shareholders Information</h5>
                    <p class="text-muted">No shareholders data provided.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Timeline / Activity -->
    <div class="mt-4 pt-3 border-top">
        <h6 class="mb-3"><i class="fas fa-history me-2"></i>Timeline</h6>
        <div class="timeline">
            <div class="timeline-item">
                <div class="timeline-point"></div>
                <div class="timeline-content">
                    <h6 class="mb-1">Submitted</h6>
                    <p class="text-muted mb-0">{{ $kyc->created_at->format('d M, Y h:i A') }}</p>
                </div>
            </div>
            @if ($kyc->verified_at)
                <div class="timeline-item">
                    <div class="timeline-point bg-success"></div>
                    <div class="timeline-content">
                        <h6 class="mb-1">Verified</h6>
                        <p class="text-muted mb-0">{{ $kyc->verified_at->format('d M, Y h:i A') }}</p>
                    </div>
                </div>
            @endif
            @if ($kyc->status == 'rejected' && $kyc->rejection_reason)
                <div class="timeline-item">
                    <div class="timeline-point bg-danger"></div>
                    <div class="timeline-content">
                        <h6 class="mb-1">Rejected</h6>
                        <p class="text-muted mb-0">{{ $kyc->updated_at->format('d M, Y h:i A') }}</p>
                        <p class="mb-0"><strong>Reason:</strong> {{ $kyc->rejection_reason }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>
        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }

        .timeline-point {
            position: absolute;
            left: -30px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: #007bff;
        }

        .timeline-content {
            padding: 5px 0;
        }
    </style>
</div>
