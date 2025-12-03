@extends('layouts.entrepreneur', [$pageTitle => $pageTitle])
@section('content')
    <!-- Breadcrumb -->
    <div class="breadcrumb-header d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">{{ $pageTitle ?? '' }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('entrepreneur.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
            </ol>
        </nav>
    </div>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow border-0">
                    <div class="card-body p-4">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(session('info'))
                            <div class="alert alert-info alert-dismissible fade show" role="alert">
                                {{ session('info') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="text-center mb-4">
                            <div class="mb-3">
                                <div class="d-inline-block p-3 rounded-circle bg-{{ 
                                    $kyc->status == 'verified' ? 'success' : 
                                    ($kyc->status == 'rejected' ? 'danger' : 'warning') 
                                }} text-white">
                                    <i class="fas fa-{{ 
                                        $kyc->status == 'verified' ? 'check-circle' : 
                                        ($kyc->status == 'rejected' ? 'times-circle' : 'hourglass-half') 
                                    }} fa-2x"></i>
                                </div>
                            </div>
                            <h4 class="mb-2">
                                @if($kyc->status == 'verified')
                                    KYC Verified Successfully!
                                @elseif($kyc->status == 'rejected')
                                    KYC Application Rejected
                                @else
                                    KYC Under Review
                                @endif
                            </h4>
                            <p class="text-muted">
                                Submitted on: {{ $kyc->created_at->format('d M, Y h:i A') }}
                                @if($kyc->verified_at)
                                    <br>Verified on: {{ $kyc->verified_at->format('d M, Y h:i A') }}
                                @endif
                            </p>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Business Information</h6>
                                        <p class="mb-1"><strong>Company:</strong> {{ $kyc->company_name }}</p>
                                        <p class="mb-1"><strong>Type:</strong> {{ $kyc->business_type }}</p>
                                        <p class="mb-1"><strong>Registration No:</strong> {{ $kyc->registration_no ?? 'N/A' }}</p>
                                        <p class="mb-1"><strong>TIN/BIN:</strong> {{ $kyc->tin_bin ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Owner Information</h6>
                                        <p class="mb-1"><strong>Name:</strong> {{ $kyc->owner_name }}</p>
                                        <p class="mb-1"><strong>Phone:</strong> {{ $kyc->owner_phone }}</p>
                                        <p class="mb-1"><strong>Email:</strong> {{ $kyc->owner_email }}</p>
                                        <p class="mb-0"><strong>NID:</strong> {{ $kyc->owner_nid_passport }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($kyc->status == 'rejected' && $kyc->rejection_reason)
                            <div class="alert alert-danger">
                                <h6 class="alert-heading">Rejection Reason:</h6>
                                <p class="mb-0">{{ $kyc->rejection_reason }}</p>
                            </div>
                        @endif

                        <div class="text-center mt-4">
                            <a href="{{ route('entrepreneur.dashboard') }}" class="btn btn-primary">
                                <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
                            </a>
                            
                            @if($kyc->status == 'rejected')
                                <a href="{{ route('entrepreneur.kyc.create') }}" class="btn btn-outline-primary ms-2">
                                    <i class="fas fa-redo me-2"></i> Re-submit KYC
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection