@extends('backend.layouts.main')

@section('container')
    <div class="page-heading">
        <div class="d-flex justify-content-between">
            <h3>User Management</h3>
            <a href="{{ route('dev.users.create') }}" class="btn btn-primary mb-3">Add New User</a>
        </div>
    </div>
    <div class="page-content">
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <form method="GET" action="{{ route('dev.users.index') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" name="name_search" class="form-control" placeholder="Search by name, username, email..." value="{{ request('name_search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="role_search" class="form-select">
                                    <option value="">All Roles</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ request('role_search') == $role->id ? 'selected' : '' }}>
                                            {{ $role->role }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-info">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped" id="table1">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Full Name</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Phone</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $index => $user)
                                <tr>
                                    <td>{{ $users->firstItem() + $index }}</td>
                                    <td>{{ $user->fullname }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge bg-light-{{ strtolower($user->role->role ?? 'secondary') }}">
                                            {{ $user->role->role ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>{{ $user->notelp }}</td>
                                    <td>
                                        <a href="{{ route('dev.users.show', $user->id) }}" class="btn btn-sm btn-info" title="View"><i class="bi bi-eye"></i></a>
                                        <a href="{{ route('dev.users.edit', $user->id) }}" class="btn btn-sm btn-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                                        <form action="{{ route('dev.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No users found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $users->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection