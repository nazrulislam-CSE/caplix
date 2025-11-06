@extends('layouts.entrepreneur')

@section('content')
    <!-- Breadcrumb -->
    <div class="breadcrumb-header d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">{{ $pageTitle ?? 'Project List' }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle ?? 'Project List' }}</li>
            </ol>
        </nav>
    </div>

    <!-- Project List Container -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Project List</h5>
                        <a href="{{ route('entrepreneur.project.create') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-plus"></i> Add New Project
                        </a>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Sl No</th>
                                        <th>Project Name</th>
                                        <th>Status</th>
                                        <th>Capital Required</th>
                                        <th>Investors</th>
                                        <th>Complaints</th>
                                        <th>Created By</th>
                                        {{-- <th>Actions</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($projects as $project)
                                        <tr @if ($project->is_red) style="color:red;" @endif>
                                            <td>{{ $loop->iteration + ($projects->currentPage() - 1) * $projects->perPage() }}
                                            </td>
                                            <td>{{ $project->name }}</td>
                                            <td>
                                                @if ($project->status === 'Pending')
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                @elseif ($project->status === 'Approved')
                                                    <span class="badge bg-success">Approved</span>
                                                @elseif ($project->status === 'Issued')
                                                    <span class="badge bg-primary">Issued</span>
                                                @elseif ($project->status === 'At Risk')
                                                    <span class="badge bg-danger">At Risk</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $project->status }}</span>
                                                @endif
                                            </td>
                                            <td>${{ number_format($project->capital_required, 2) }}</td>
                                            <td>{{ $project->investors_count ?? 0 }}</td>
                                            <td>{{ $project->complaints_count ?? 0 }}</td>
                                            <td>{{ $project->entrepreneur->name ?? '' }}</td>
                                            {{-- <td>
                                                <a href="{{ route('entrepreneur.project.show', $project->id) }}"
                                                    class="btn btn-sm btn-info text-light" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                <a href="{{ route('entrepreneur.project.edit', $project->id) }}"
                                                    class="btn btn-sm btn-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <form action="{{ route('entrepreneur.project.destroy', $project->id) }}"
                                                    method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Are you sure?')" title="Delete">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </td> --}}
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No projects found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $projects->links('pagination::bootstrap-5') }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
