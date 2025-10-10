@extends('backend.layouts.main')

@section('container')
    <div class="page-heading">
        <h3>Field Details: {{ $field->nama_lapangan }}</h3>
        <p class="text-subtitle text-muted">GOR: {{ $gor->nama_gor }}</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.fields.index') }}">Manage My Fields</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $field->nama_lapangan }}</li>
            </ol>
        </nav>
    </div>
    <div class="page-content">
        <section class="section">
            <div class="card">
                 <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>{{ $field->nama_lapangan }}</h4>
                    <a href="{{ route('admin.fields.edit', $field->id) }}" class="btn btn-warning">Edit Field</a>
                </div>
                <div class="card-body">
                    @include('backend.partials.alerts')
                    <div class="row">
                        <div class="col-md-4">
                            @if($field->foto_lapangan)
                                <img src="{{ $field->foto_lapangan }}" alt="{{ $field->nama_lapangan }}" class="img-fluid rounded mb-3">
                            @else
                                <img src="https://via.placeholder.com/400x300.png?text=No+Image" alt="No Image" class="img-fluid rounded mb-3">
                            @endif
                        </div>
                        <div class="col-md-8">
                            <p><strong>Field Name:</strong> {{ $field->nama_lapangan }}</p>
                            <p><strong>Type/Description:</strong> {{ $field->keterangan_lapangan }}</p>
                            <p><strong>Price per Hour:</strong> Rp {{ number_format($field->harga_sewa, 0, ',', '.') }}</p>
                            <p><strong>Slug:</strong> {{ $field->slug_lapangan }}</p>
                            <p><strong>Created At:</strong> {{ $field->created_at->format('d M Y, H:i') }}</p>
                            <p><strong>Last Updated:</strong> {{ $field->updated_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    <hr>
                    <a href="{{ route('admin.fields.index') }}" class="btn btn-secondary">Back to Fields List</a>
                </div>
            </div>
        </section>
        {{-- Anda bisa menambahkan bagian untuk menampilkan jadwal spesifik lapangan ini jika relevan untuk Admin GOR --}}
    </div>
@endsection