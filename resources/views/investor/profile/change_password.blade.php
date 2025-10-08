@extends('layouts.investor', [$pageTitle => $pageTitle])

@section('content')
<div class="container-fluid">
    <div class="breadcrumb-header d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">{{ $pageTitle }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#">হোম</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
            </ol>
        </nav>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-lg border-0">
                <div class="card-header">
                      <h5 class="card-title">
                        <i class="fas fa-key me-2 text-primary"></i> পাসওয়ার্ড পরিবর্তন
                    </h5>
                </div>
                <div class="card-body">

                    <form action="{{ route('investor.password.update') }}" method="POST">
                        @csrf

                        <div class="mb-3 position-relative">
                            <label for="current_password" class="form-label">বর্তমান পাসওয়ার্ড</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" name="current_password" id="current_password"
                                    class="form-control @error('current_password') is-invalid @enderror"
                                    placeholder="বর্তমান পাসওয়ার্ড লিখুন">
                                <span class="input-group-text toggle-password" onclick="togglePassword('current_password')">
                                    <i class="fas fa-eye" style="cursor: pointer;"></i>
                                </span>
                            </div>
                            @error('current_password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3 position-relative">
                            <label for="new_password" class="form-label">নতুন পাসওয়ার্ড</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" name="new_password" id="new_password"
                                    class="form-control @error('new_password') is-invalid @enderror"
                                    placeholder="নতুন পাসওয়ার্ড লিখুন">
                                <span class="input-group-text toggle-password" onclick="togglePassword('new_password')">
                                    <i class="fas fa-eye" style="cursor: pointer;"></i>
                                </span>
                            </div>
                            @error('new_password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3 position-relative">
                            <label for="new_password_confirmation" class="form-label">নতুন পাসওয়ার্ড নিশ্চিত করুন</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                                    class="form-control"
                                    placeholder="নতুন পাসওয়ার্ড পুনরায় লিখুন">
                                <span class="input-group-text toggle-password" onclick="togglePassword('new_password_confirmation')">
                                    <i class="fas fa-eye" style="cursor: pointer;"></i>
                                </span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">পরিবর্তন করুন</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = field.nextElementSibling.querySelector('i');
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endsection
