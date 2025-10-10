@extends('backend.layouts.main')

@section('container')
    <div class="page-heading">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3>Manage Fields for: {{ $gor->nama_gor }}</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">My Fields</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('admin.fields.create') }}" class="btn btn-primary mb-3">Add New Field</a>
        </div>
    </div>
    <div class="page-content">
        <section class="section">
            <div class="card">
                <div class="card-body">
                    @include('backend.partials.alerts')

                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="table1">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Image</th>
                                    <th>Field Name</th>
                                    <th>Type</th>
                                    <th>Price/Hour</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($fields as $index => $field)
                                <tr>
                                    <td>{{ $fields->firstItem() + $index }}</td>
                                    <td>
                                        @if($field->foto_lapangan)
                                            <img src="{{ $field->foto_lapangan }}" alt="{{ $field->nama_lapangan }}" style="width: 100px; height: 70px; object-fit: cover;" class="img-thumbnail">
                                        @else
                                            <img src="https://via.placeholder.com/100x70.png?text=No+Image" alt="No Image" style="width: 100px;" class="img-thumbnail">
                                        @endif
                                    </td>
                                    <td>{{ $field->nama_lapangan }}</td>
                                    <td>{{ $field->keterangan_lapangan }}</td>
                                    <td>Rp {{ number_format($field->harga_sewa, 0, ',', '.') }}</td>
                                    <td>
                                        <a href="{{ route('admin.fields.show', $field->id) }}" class="btn btn-sm btn-info" title="View"><i class="bi bi-eye"></i></a>
                                        <a href="{{ route('admin.fields.edit', $field->id) }}" class="btn btn-sm btn-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                                        <form action="{{ route('admin.fields.destroy', $field->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this field? This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No fields found for your GOR. Click "Add New Field" to create one.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $fields->links() }}
                    </div>
                     <div class="mt-4">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection