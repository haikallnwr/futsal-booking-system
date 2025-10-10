@extends('backend.layouts.main')

@section('container')
    <div class="page-heading">
        <h3>Update Order Status #{{ $order->id }}</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dev.orders.index') }}">Order List</a></li>
                <li class="breadcrumb-item"><a href="{{ route('dev.orders.show', $order->id) }}">Order #{{ $order->id }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Update Status</li>
            </ol>
        </nav>
    </div>
    <div class="page-content">
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h4>Current Status: <span class="badge bg-info">{{ $order->status }}</span></h4>
                    <p>GOR: {{ $order->gor->nama_gor ?? 'N/A' }} | Field: {{ $order->field->nama_lapangan ?? 'N/A' }} | User: {{ $order->user->fullname ?? 'N/A' }}</p>
                </div>
                <div class="card-body">
                    @include('backend.partials.alerts')
                    <form action="{{ route('dev.orders.updateStatus', $order->id) }}" method="POST">
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

                        <button type="submit" class="btn btn-primary">Update Order Status</button>
                        <a href="{{ route('dev.orders.show', $order->id) }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection