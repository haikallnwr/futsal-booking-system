@extends('backend.layouts.main')

@section('container')
    <div class="page-heading">
        <h3>Booking Management for: {{ $gor->nama_gor ?? 'My GOR' }}</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Manage Bookings</li>
            </ol>
        </nav>
    </div>
    <div class="page-content">
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <form method="GET" action="{{ route('admin.orders.index') }}">
                        <div class="row g-3">
                            <div class="col-md-3">
                                 <label for="search_order_id" class="form-label">Order ID</label>
                                <input type="text" name="search_order_id" id="search_order_id" class="form-control form-control-sm" placeholder="Order ID" value="{{ request('search_order_id') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="user_search" class="form-label">User (Name/Username)</label>
                                <input type="text" name="user_search" id="user_search" class="form-control form-control-sm" placeholder="Search User..." value="{{ request('user_search') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="status_search" class="form-label">Status</label>
                                <select name="status_search" id="status_search" class="form-select form-select-sm">
                                    <option value="all">All Statuses</option>
                                    @foreach($statuses as $status)
                                        <option value="{{ $status }}" {{ request('status_search') == $status ? 'selected' : '' }}>
                                            {{ $status }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                             <div class="col-md-3">
                                <label for="date_search" class="form-label">Booking Date</label>
                                <input type="date" name="date_search" id="date_search" class="form-control form-control-sm" value="{{ request('date_search') }}">
                            </div>
                            <div class="col-md-12 text-end mt-2">
                                <button type="submit" class="btn btn-info btn-sm"><i class="bi bi-search"></i> Search</button>
                                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn-sm" title="Reset Filter"><i class="bi bi-arrow-clockwise"></i> Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    @include('backend.partials.alerts')

                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="table1">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Field</th>
                                    <th>Booking Date</th>
                                    <th>Time</th>
                                    <th>Subtotal</th>
                                    <th>Status</th>
                                    <th>Order At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($orders as $order)
                                <tr>
                                    <td>#{{ $order->id }}</td>
                                    <td>{{ $order->user->fullname ?? 'N/A' }}</td>
                                    <td>{{ $order->field->nama_lapangan ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($order->tanggal_main)->format('d M Y') }}</td>
                                    <td>{{ $order->jam_mulai }} - {{ $order->jam_selesai }}</td>
                                    <td>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge bg-light-{{ Illuminate\Support\Str::slug($order->status, '-') }} text-dark">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-info" title="View Details"><i class="bi bi-eye"></i></a>
                                        <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-sm btn-warning" title="Update Status"><i class="bi bi-pencil-square"></i></a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">No orders found for your GOR.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $orders->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection