@extends('backend.layouts.main')

@section('container')
    <div class="page-heading">
        <div class="d-flex justify-content-between">
            <h3>GOR Details: {{ $gor->nama_gor }}</h3>
            <a href="{{ route('dev.gors.fields.index', $gor->id) }}" class="btn btn-success mb-3">Manage Fields ({{ $gor->field->count() }})</a>
        </div>
    </div>
    <div class="page-content">
        <section class="section">
            <div class="card">
                <div class="card-body">
                    @include('backend.partials.alerts')
                    <div class="row mb-3">
                        <div class="col-md-7">
                            <p><strong>GOR Name:</strong> {{ $gor->nama_gor }}</p>
                            <p><strong>Slug:</strong> {{ $gor->slug_gor }}</p>
                            <p><strong>Admin:</strong> {{ $gor->admin->fullname ?? 'N/A' }} ({{ $gor->admin->email ?? 'N/A' }})</p>
                            <p><strong>Address:</strong></p>
                            <p>{{ $gor->alamat_gor }}</p>
                            <p><strong>Latitude:</strong> {{ $gor->latitude ?? '-' }}</p>
                            <p><strong>Longitude:</strong> {{ $gor->longitude ?? '-' }}</p>
                            <p><strong>Description:</strong></p>
                            <p>{{ $gor->deskripsi ?? 'No description provided.' }}</p>
                            <p><strong>Created At:</strong> {{ $gor->created_at->format('d M Y, H:i') }}</p>
                            <p><strong>Last Updated:</strong> {{ $gor->updated_at->format('d M Y, H:i') }}</p>
                        </div>
                         <div class="col-md-5">
                            <h5>Fields ({{ $gor->field->count() }})</h5>
                            @if($gor->field->count() > 0)
                                <ul class="list-group">
                                    @foreach($gor->field as $field)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            {{ $field->nama_lapangan }} ({{ $field->keterangan_lapangan }})
                                            <span class="badge bg-primary rounded-pill">Rp {{ number_format($field->harga_sewa) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p>No fields have been added to this GOR yet.</p>
                            @endif
                            <a href="{{ route('dev.gors.fields.create', $gor->id) }}" class="btn btn-sm btn-outline-primary mt-2">Add New Field</a>
                        </div>
                    </div>

                    <hr>
                    <h5>GOR Images ({{ $gor->images->count() }})</h5>
                    @if($gor->images->count() > 0)
                        <div class="row">
                            @foreach($gor->images as $image)
                                <div class="col-md-3 mb-3">
                                    <a href="{{ $image->image_path }}" data-bs-toggle="lightbox" data-gallery="gor-gallery">
                                        <img src="{{ $image->image_path }}" alt="GOR Image {{ $loop->iteration }}" class="img-fluid img-thumbnail" style="max-height: 200px; object-fit: cover;">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p>No images uploaded for this GOR.</p>
                    @endif
                    <hr>
                    <a href="{{ route('dev.gors.edit', $gor->id) }}" class="btn btn-warning">Edit GOR</a>
                    <a href="{{ route('dev.gors.index') }}" class="btn btn-secondary">Back to GOR List</a>

                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bs5-lightbox@1.8.3/dist/index.bundle.min.js"></script>
@endpush