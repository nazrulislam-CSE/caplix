@extends('layouts.admin')

@section('content')
    <!-- Breadcrumb -->
    <div class="breadcrumb-header d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Edit KYC: {{ $kyc->company_name }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.kyc.index') }}">KYC List</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow border-0">
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.kyc.update', $kyc->id) }}">
                            @csrf
                            @method('PUT')
                            
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h5 class="mb-3">Status Update</h5>
                                    
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status</label>
                                        <select name="status" id="status" class="form-select" required>
                                            @foreach($statuses as $key => $label)
                                                <option value="{{ $key }}" {{ $kyc->status == $key ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3" id="rejection-reason-field" 
                                         style="{{ $kyc->status == 'rejected' ? '' : 'display: none;' }}">
                                        <label for="rejection_reason" class="form-label">Rejection Reason</label>
                                        <textarea name="rejection_reason" id="rejection_reason" 
                                                  class="form-control" rows="3">{{ $kyc->rejection_reason }}</textarea>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="notes" class="form-label">Admin Notes</label>
                                        <textarea name="notes" id="notes" class="form-control" rows="3">{{ $kyc->notes }}</textarea>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <h5 class="mb-3">Quick Info</h5>
                                    
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <p><strong>Company:</strong> {{ $kyc->company_name }}</p>
                                            <p><strong>Owner:</strong> {{ $kyc->owner_name }}</p>
                                            <p><strong>Email:</strong> {{ $kyc->owner_email }}</p>
                                            <p><strong>Phone:</strong> {{ $kyc->owner_phone }}</p>
                                            <p><strong>Submitted:</strong> {{ $kyc->created_at->format('d M, Y h:i A') }}</p>
                                            @if($kyc->verified_at)
                                                <p><strong>Verified:</strong> {{ $kyc->verified_at->format('d M, Y h:i A') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.kyc.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i> Back to List
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i> Update KYC
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script>
    // Show/hide rejection reason field based on status
    $('#status').change(function() {
        if ($(this).val() === 'rejected') {
            $('#rejection-reason-field').show();
        } else {
            $('#rejection-reason-field').hide();
        }
    });
</script>
@endpush