@extends('layouts.investor', [$pageTitle => 'Profile'])

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
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="card shadow">
                    <div class="card-body">
                        <form action="{{ route('investor.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label>নাম</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" placeholder="Enter name">
                                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label>ইমেইল</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" placeholder="Enter email">
                                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label>ইউজারনেম</label>
                                <input type="text" name="username" value="{{ old('username', $user->username) }}" class="form-control" placeholder="Enter username">
                                @error('username') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label>ফোন নম্বর</label>
                                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control" placeholder="Enter phone">
                                @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label>ঠিকানা</label>
                                <textarea name="address" class="form-control" placeholder="Enter address">{{ old('address', $user->address) }}</textarea>
                                @error('address') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label>প্রোফাইল ছবি</label>
                                <input type="file" name="photo" class="form-control">
                                @error('photo') <span class="text-danger">{{ $message }}</span> @enderror

                                @if($user->photo)
                                    <div class="mt-2">
                                        <img src="{{ asset('upload/investor/' . $user->photo) }}" width="100">
                                    </div>
                                @endif
                            </div>

                            <button type="submit" class="btn btn-primary">আপডেট করুন</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
