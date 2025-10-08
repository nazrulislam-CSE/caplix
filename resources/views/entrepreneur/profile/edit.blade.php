@extends('layouts.entrepreneur', [$pageTitle => 'Profile'])
@section('content')
    <!-- Breadcrumb -->
    <div class="breadcrumb-header d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">{{ $pageTitle ?? 'Dashboard' }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle ?? 'Dashboard' }}</li>
            </ol>
        </nav>
    </div>

    <!-- Profile Form Container -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-lg border-0">
                    <div class="card-body">
                        <form action="{{ route('entrepreneur.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label>নাম</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                    class="form-control" placeholder="Enter name">
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label>ইমেইল</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                    class="form-control" placeholder="Enter email">
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label>ইউজারনেম</label>
                                <input type="text" name="username" value="{{ old('username', $user->username) }}"
                                    class="form-control" placeholder="Enter username">
                                @error('username')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label>ফোন নম্বর</label>
                                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                    class="form-control" placeholder="Enter phone">
                                @error('phone')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label>ঠিকানা</label>
                                <textarea name="address" class="form-control" placeholder="Enter address">{{ old('address', $user->address) }}</textarea>
                                @error('address')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label>প্রোফাইল ছবি</label>
                                <input type="file" name="photo" class="form-control" id="photoInput">
                                @error('photo')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror

                                <div class="mt-2">
                                    <img id="photoPreview"
                                        src="{{ $user->photo ? asset('upload/entrepreneur/' . $user->photo) : '' }}"
                                        width="100">
                                </div>
                            </div>


                            <button type="submit" class="btn btn-primary">আপডেট করুন</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script>
        document.getElementById('photoInput').addEventListener('change', function(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const preview = document.getElementById('photoPreview');
                preview.src = reader.result;
            };
            if (event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            }
        });
    </script>
@endsection
