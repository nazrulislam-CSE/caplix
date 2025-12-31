@extends('layouts.admin')

@section('content')
    <!-- Breadcrumb -->
    <div class="breadcrumb-header d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">{{ $pageTitle ?? 'User List' }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle ?? 'User List' }}</li>
            </ol>
        </nav>
    </div>

    <!-- User List Container -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">User List</h5>
                        <form action="{{ route('admin.user.index') }}" method="GET" class="d-flex">
                            <div class="input-group" style="width: 300px;">
                                <input type="text" name="search" class="form-control" placeholder="Search by name, email, username..." value="{{ request('search') }}">
                                <button class="btn btn-light" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                                @if(request('search'))
                                    <a href="{{ route('admin.user.index') }}" class="btn btn-outline-light">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Sl No</th>
                                        <th>Photo</th>
                                        <th>Name</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Role</th>
                                        <th>Balance</th>
                                        <th>Status</th>
                                        <th>Joined At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $user)
                                        <tr>
                                            <td>{{ $loop->iteration + (($users->currentPage() - 1) * $users->perPage()) }}</td>
                                            @php
                                                $photoPath = null;

                                                if ($user->photo) {
                                                    if ($user->role === 'admin') {
                                                        $photoPath = asset('upload/admin/' . $user->photo);
                                                    } elseif ($user->role === 'investor') {
                                                        $photoPath = asset('upload/investor/' . $user->photo);
                                                    } elseif ($user->role === 'entrepreneur') {
                                                        $photoPath = asset('upload/entrepreneur/' . $user->photo);
                                                    }
                                                }
                                            @endphp

                                            <td class="text-center">
                                                @if($photoPath)
                                                    <img src="{{ $photoPath }}"
                                                        alt="{{ $user->name }}"
                                                        class="rounded-circle"
                                                        width="40"
                                                        height="40">
                                                @else
                                                    <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png"
                                                        class="rounded-circle"
                                                        width="40"
                                                        height="40"
                                                        alt="Default Avatar">
                                                @endif
                                            </td>

                                            <td>
                                                <div class="fw-bold">{{ $user->name }}</div>
                                                @if($user->rank_level)
                                                    <span class="badge bg-info">{{ $user->rank_level }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $user->username }}</td>
                                           <td>
                                                <div class="fw-semibold">{{ $user->email }}</div>
                                            </td>
                                            <td>{{ $user->phone ?? 'N/A' }}</td>
                                            <td>
                                                @php
                                                    $roleColors = [
                                                        'admin' => 'danger',
                                                        'investor' => 'success',
                                                        'entrepreneur' => 'primary'
                                                    ];
                                                    $color = $roleColors[$user->role] ?? 'secondary';
                                                @endphp
                                                <span class="badge bg-{{ $color }}">{{ ucfirst($user->role) }}</span>
                                            </td>
                                            <td class="text-end">
                                                <div class="fw-bold">৳{{ number_format($user->balance, 2) }}</div>
                                                <small class="text-muted">Invested: ৳{{ number_format($user->investment_balance, 2) }}</small>
                                            </td>
                                            <td>
                                                @if($user->status)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>{{ $user->created_at->format('d M, Y') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">

                                                    <!-- View (always enabled) -->
                                                    <a href="{{ route('admin.user.show', $user->id) }}"
                                                    class="btn btn-sm btn-info text-light"
                                                    title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                    <!-- Delete -->
                                                    <button type="button"
                                                            class="btn btn-sm btn-danger {{ $user->role === 'admin' ? 'disabled' : '' }}"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#deleteModal{{ $user->id }}"
                                                            {{ $user->role === 'admin' ? 'disabled' : '' }}>
                                                        <i class="fas fa-trash"></i>
                                                    </button>

                                                    <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1">
                                                        <div class="modal-dialog modal-lg">
                                                            <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')

                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-danger text-white">
                                                                        <h5 class="modal-title">Confirm Delete</h5>
                                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                                    </div>

                                                                    <div class="modal-body text-center">
                                                                        <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                                                                        <p>
                                                                            Are you sure you want to delete
                                                                            <strong>{{ $user->name }}</strong>?
                                                                        </p>
                                                                    </div>

                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                        <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="11" class="text-center py-4">
                                                <div class="empty-state">
                                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                                    <h5>No users found</h5>
                                                    @if(request('search'))
                                                        <p class="text-muted">No users match your search criteria</p>
                                                        <a href="{{ route('admin.user.index') }}" class="btn btn-primary mt-2">
                                                            Clear Search
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                       <!-- Pagination -->
                        <div class="d-flex justify-content-end mt-4">
                            {{ $users->links('pagination::bootstrap-5') }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script>
    function showHash(hash) {
        document.getElementById('toastBody').innerText = hash;
        const toast = new bootstrap.Toast(
            document.getElementById('passwordToast')
        );
        toast.show();
    }
</script>
@endpush
