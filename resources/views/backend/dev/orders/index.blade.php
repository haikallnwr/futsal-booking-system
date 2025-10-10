@extends('backend.layouts.main')

@section('container')
    <div class="page-heading">
        <h3>Order Management</h3>
    </div>
    <div class="page-content">
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <form method="GET" action="{{ route('dev.orders.index') }}">
                        <div class="row g-3">
                            <div class="col-md-2">
                                 <label for="search_order_id" class="form-label">Order ID</label>
                                <input type="text" name="search_order_id" id="search_order_id" class="form-control" placeholder="Order ID" value="{{ request('search_order_id') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="user_search" class="form-label">User (Name/Username)</label>
                                <input type="text" name="user_search" id="user_search" class="form-control" placeholder="Search User..." value="{{ request('user_search') }}">
                            </div>
                            <div class="col-md-2">
                                <label for="gor_search" class="form-label">GOR</label>
                                <select name="gor_search" id="gor_search" class="form-select">
                                    <option value="all">All GORs</option>
                                    @foreach($gors as $gor)
                                        <option value="{{ $gor->id }}" {{ request('gor_search') == $gor->id ? 'selected' : '' }}>
                                            {{ $gor->nama_gor }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="status_search" class="form-label">Status</label>
                                <select name="status_search" id="status_search" class="form-select">
                                    <option value="all">All Statuses</option>
                                    @foreach($statuses as $status)
                                        <option value="{{ $status }}" {{ request('status_search') == $status ? 'selected' : '' }}>
                                            {{ $status }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                             <div class="col-md-2">
                                <label for="date_search" class="form-label">Tanggal Main</label>
                                <input type="date" name="date_search" id="date_search" class="form-control" value="{{ request('date_search') }}">
                            </div>
                            <div class="col-md-1 align-self-end">
                                <button type="submit" class="btn btn-info w-100"><i class="bi bi-search"></i></button>
                            </div>
                            <div class="col-md-1 align-self-end">
                                <a href="{{ route('dev.orders.index') }}" class="btn btn-secondary w-100" title="Reset Filter"><i class="bi bi-arrow-clockwise"></i></a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    @include('backend.partials.alerts')

                    <div class="table-responsive">
                        <table class="table table-striped" id="table1">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>GOR</th>
                                    <th>Field</th>
                                    <th>Tgl Main</th>
                                    <th>Jam</th>
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
                                    <td>{{ $order->gor->nama_gor ?? 'N/A' }}</td>
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
                                        <a href="{{ route('dev.orders.show', $order->id) }}" class="btn btn-sm btn-info" title="View Details"><i class="bi bi-eye"></i></a>
                                        <a href="{{ route('dev.orders.edit', $order->id) }}" class="btn btn-sm btn-warning" title="Update Status"><i class="bi bi-pencil-square"></i></a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center">No orders found.</td>
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