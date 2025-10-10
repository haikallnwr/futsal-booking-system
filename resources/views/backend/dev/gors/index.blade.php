@extends('backend.layouts.main')

@section('container')
    <div class="page-heading">
        <div class="d-flex justify-content-between">
            <h3>GOR Management</h3>
            <a href="{{ route('dev.gors.create') }}" class="btn btn-primary mb-3">Add New GOR</a>
        </div>
    </div>
    <div class="page-content">
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <form method="GET" action="{{ route('dev.gors.index') }}">
                        <div class="input-group">
                            <input type="text" name="search_gor" class="form-control" placeholder="Search GOR by name or address..." value="{{ request('search_gor') }}">
                            <button class="btn btn-info" type="submit">Search</button>
                            @if(request('search_gor'))
                            <a href="{{ route('dev.gors.index') }}" class="btn btn-secondary" title="Reset Search"><i class="bi bi-arrow-clockwise"></i></a>
                            @endif
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    @include('backend.partials.alerts') {{-- Buat partials untuk alerts success/error --}}

                    <div class="table-responsive">
                        <table class="table table-striped" id="table1">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>GOR Name</th>
                                    <th>Admin</th>
                                    <th>Address</th>
                                    <th>Images Count</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($gors as $index => $gor)
                                <tr>
                                    <td>{{ $gors->firstItem() + $index }}</td>
                                    <td>{{ $gor->nama_gor }}</td>
                                    <td>{{ $gor->admin->fullname ?? 'N/A' }}</td>
                                    <td>{{ Str::limit($gor->alamat_gor, 50) }}</td>
                                    <td>{{ $gor->images->count() }}</td>
                                    <td>
                                        <a href="{{ route('dev.gors.show', $gor->id) }}" class="btn btn-sm btn-info" title="View"><i class="bi bi-eye"></i></a>
                                        <a href="{{ route('dev.gors.edit', $gor->id) }}" class="btn btn-sm btn-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                                        <a href="{{ route('dev.gors.fields.index', $gor->id) }}" class="btn btn-sm btn-success" title="Manage Fields"><i class="bi bi-dribbble"></i> Fields</a>
                                        <form action="{{ route('dev.gors.destroy', $gor->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this GOR and all its associated images and data? This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No GORs found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $gors->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection