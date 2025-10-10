@extends('backend.layouts.main')

@section('container')
    <div class="page-heading">
        <h3>Update Booking Status #{{ $order->id }}</h3>
        <p class="text-subtitle text-muted">GOR: {{ $gor->nama_gor }}</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Manage Bookings</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.orders.show', $order->id) }}">Booking #{{ $order->id }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Update Status</li>
            </ol>
        </nav>
    </div>
    <div class="page-content">
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h4>Current Status: <span class="badge bg-light-{{ Illuminate\Support\Str::slug($order->status, '-') }} text-dark">{{ $order->status }}</span></h4>
                    <p>Field: {{ $order->field->nama_lapangan ?? 'N/A' }} | User: {{ $order->user->fullname ?? 'N/A' }} | Date: {{ \Carbon\Carbon::parse($order->tanggal_main)->format('d M Y') }}</p>
                </div>
                <div class="card-body">
                    @include('backend.partials.alerts')
                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="status" class="form-label">New Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                @foreach($possibleStatuses as $statusOption)
                                    <option value="{{ $statusOption }}" {{ old('status', $order->status) == $statusOption ? 'selected' : '' }}>
                                        {{ $statusOption }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{--
                        <div class="mb-3">
                            <label for="admin_notes" class="form-label">Admin Notes (Optional)</label>
                            <textarea class="form-control @error('admin_notes') is-invalid @enderror" id="admin_notes" name="admin_notes" rows="3">{{ old('admin_notes', $order->admin_notes ?? '') }}</textarea>
                            @error('admin_notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        --}}

                        <button type="submit" class="btn btn-primary">Update Booking Status</button>
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection