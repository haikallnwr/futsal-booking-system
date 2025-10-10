@extends('backend.layouts.main')

@section('container')
    <div class="page-heading">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3>Fields for: {{ $gor->nama_gor }}</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dev.gors.index') }}">GOR List</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dev.gors.show', $gor->id) }}">{{ $gor->nama_gor }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Manage Fields</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('dev.gors.fields.create', $gor->id) }}" class="btn btn-primary mb-3">Add New Field</a>
        </div>
    </div>
    <div class="page-content">
        <section class="section">
            <div class="card">
                <div class="card-body">
                    @include('backend.partials.alerts')

                    <div class="table-responsive">
                        <table class="table table-striped" id="table1">
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
                                            <img src="{{ $field->foto_lapangan }}" alt="{{ $field->nama_lapangan }}" style="width: 100px; height: auto; object-fit: cover;" class="img-thumbnail">
                                        @else
                                            <img src="https://via.placeholder.com/100x70.png?text=No+Image" alt="No Image" style="width: 100px;" class="img-thumbnail">
                                        @endif
                                    </td>
                                    <td>{{ $field->nama_lapangan }}</td>
                                    <td>{{ $field->keterangan_lapangan }}</td>
                                    <td>Rp {{ number_format($field->harga_sewa, 0, ',', '.') }}</td>
                                    <td>
                                        <a href="{{ route('dev.gors.fields.show', [$gor->id, $field->id]) }}" class="btn btn-sm btn-info" title="View"><i class="bi bi-eye"></i></a>
                                        <a href="{{ route('dev.gors.fields.edit', [$gor->id, $field->id]) }}" class="btn btn-sm btn-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                                        <form action="{{ route('dev.gors.fields.destroy', [$gor->id, $field->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this field?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No fields found for this GOR.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $fields->links() }}
                    </div>
                     <div class="mt-4">
                        <a href="{{ route('dev.gors.show', $gor->id) }}" class="btn btn-secondary">Back to GOR Details</a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection