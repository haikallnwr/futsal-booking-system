@extends('backend.layouts.main')

@section('container')
    <div class="page-heading">
        <h3>Edit Field: {{ $field->nama_lapangan }} (GOR: {{ $gor->nama_gor }})</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dev.gors.index') }}">GOR List</a></li>
                <li class="breadcrumb-item"><a href="{{ route('dev.gors.show', $gor->id) }}">{{ $gor->nama_gor }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('dev.gors.fields.index', $gor->id) }}">Manage Fields</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Field</li>
            </ol>
        </nav>
    </div>
    <div class="page-content">
        <section class="section">
            <div class="card">
                <div class="card-body">
                    @include('backend.partials.alerts')
                    <form action="{{ route('dev.gors.fields.update', [$gor->id, $field->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="nama_lapangan" class="form-label">Field Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_lapangan') is-invalid @enderror" id="nama_lapangan" name="nama_lapangan" value="{{ old('nama_lapangan', $field->nama_lapangan) }}" required>
                            @error('nama_lapangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="keterangan_lapangan" class="form-label">Field Type/Description <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('keterangan_lapangan') is-invalid @enderror" id="keterangan_lapangan" name="keterangan_lapangan" value="{{ old('keterangan_lapangan', $field->keterangan_lapangan) }}" required>
                            @error('keterangan_lapangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="harga_sewa" class="form-label">Price per Hour (Rp) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('harga_sewa') is-invalid @enderror" id="harga_sewa" name="harga_sewa" value="{{ old('harga_sewa', $field->harga_sewa) }}" min="0" required>
                            @error('harga_sewa') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="foto_lapangan" class="form-label">Field Image (Leave blank to keep current)</label>
                            <input type="file" class="form-control @error('foto_lapangan') is-invalid @enderror" id="foto_lapangan" name="foto_lapangan">
                            <small class="form-text text-muted">Max file size 2MB. Allowed types: jpg, jpeg, png, gif.</small>
                            @error('foto_lapangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            @if($field->foto_lapangan)
                                <div class="mt-2">
                                    <p>Current Image:</p>
                                    <img src="{{ $field->foto_lapangan }}" alt="{{ $field->nama_lapangan }}" style="max-width: 200px; height: auto;" class="img-thumbnail">
                                </div>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary">Update Field</button>
                        <a href="{{ route('dev.gors.fields.index', $gor->id) }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection