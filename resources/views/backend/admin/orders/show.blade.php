@extends('backend.layouts.main')

@section('container')
    <div class="page-heading">
        <h3>Order Details #{{ $order->id }}</h3>
        <p class="text-subtitle text-muted">GOR: {{ $gor->nama_gor }}</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Manage Bookings</a></li>
                <li class="breadcrumb-item active" aria-current="page">Order #{{ $order->id }}</li>
            </ol>
        </nav>
    </div>
    <div class="page-content">
        <section class="section">
            @include('backend.partials.alerts')
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Order Information</h4>
                    <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-warning">Update Status</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Order ID:</strong> #{{ $order->id }}</p>
                            <p><strong>User:</strong> {{ $order->user->fullname ?? 'N/A' }} ({{ $order->user->email ?? 'N/A' }})</p>
                            <p><strong>Phone:</strong> {{ $order->user->notelp ?? 'N/A' }}</p>
                            <p><strong>Field:</strong> {{ $order->field->nama_lapangan ?? 'N/A' }} ({{ $order->field->keterangan_lapangan ?? '' }})</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Booking Date:</strong> {{ \Carbon\Carbon::parse($order->tanggal_main)->format('l, d F Y') }}</p>
                            <p><strong>Start Time:</strong> {{ $order->jam_mulai }}</p>
                            <p><strong>End Time:</strong> {{ $order->jam_selesai }}</p>
                            <p><strong>Duration:</strong> {{ $order->durasi }} Jam</p>
                            <p><strong>Subtotal:</strong> Rp {{ number_format($order->subtotal, 0, ',', '.') }}</p>
                            <p><strong>Status:</strong> <span class="badge bg-light-{{ Illuminate\Support\Str::slug($order->status, '-') }} text-dark">{{ $order->status }}</span></p>
                            <p><strong>Order Created:</strong> {{ $order->created_at->format('d M Y, H:i:s') }}</p>
                            <p><strong>Last Updated:</strong> {{ $order->updated_at->format('d M Y, H:i:s') }}</p>
                        </div>
                    </div>
                    <hr>
                    <h5>Payment Proof (Foto Struk)</h5>
                    @if($order->foto_struk)
                        <div class="mb-3">
                            {{-- Pastikan path ke storage/app/public benar. Jika foto_struk menyimpan path dari 'public/', maka asset('storage/'. $order->foto_struk) benar --}}
                            <a href="{{ asset('storage/' . $order->foto_struk) }}" data-bs-toggle="lightbox" data-gallery="struk-gallery-{{$order->id}}">
                                <img src="{{ asset('storage/' . $order->foto_struk) }}" alt="Foto Struk" class="img-thumbnail" style="max-width: 300px; max-height: 400px; object-fit: contain;">
                            </a>
                            <p><small>Click image to enlarge.</small></p>
                        </div>
                    @else
                        <p>No payment proof uploaded.</p>
                    @endif

                    @if($order->schedule)
                    <hr>
                    <h5>Associated Schedule</h5>
                    <p><strong>Schedule ID:</strong> {{ $order->schedule->id }}</p>
                    <p><strong>Schedule Status:</strong> <span class="badge bg-light-{{ Illuminate\Support\Str::slug($order->schedule->status, '-') }} text-dark">{{ $order->schedule->status }}</span></p>
                    @endif

                    {{-- Jika ada admin_notes di model Order, tampilkan di sini --}}
                    {{-- @if($order->admin_notes)
                    <hr>
                    <h5>Admin Notes</h5>
                    <p>{!! nl2br(e($order->admin_notes)) !!}</p>
                    @endif --}}
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Back to Bookings List</a>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
{{-- Jika Anda menggunakan bs5-lightbox atau sejenisnya untuk preview gambar struk --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/bs5-lightbox@1.8.3/dist/index.bundle.min.js"></script> --}}
@endpush