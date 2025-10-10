@extends('backend.layouts.main')

@section('container')
    <div class="page-heading">
        <h3>Schedule Management</h3>
        {{-- Tombol tambah schedule mungkin tidak relevan jika schedule dibuat otomatis dari Order --}}
        {{-- <a href="{{ route('dev.schedules.create') }}" class="btn btn-primary mb-3">Add New Schedule</a> --}}
    </div>
    <div class="page-content">
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <form method="GET" action="{{ route('dev.schedules.index') }}">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="gor_id_search" class="form-label">Filter by GOR</label>
                                <select name="gor_id_search" id="gor_id_search" class="form-select">
                                    <option value="">All GORs</option>
                                    @foreach($gors as $gor)
                                        <option value="{{ $gor->id }}" {{ request('gor_id_search') == $gor->id ? 'selected' : '' }}>
                                            {{ $gor->nama_gor }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- Tambahkan filter by Field jika diperlukan, mungkin dengan AJAX --}}
                            <div class="col-md-3">
                                <label for="date_search" class="form-label">Filter by Date</label>
                                <input type="date" name="date_search" id="date_search" class="form-control" value="{{ request('date_search') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="status_search" class="form-label">Filter by Order Status</label>
                                <select name="status_search" id="status_search" class="form-select">
                                    <option value="">All Relevant Statuses</option>
                                    @foreach($orderStatuses as $status)
                                        <option value="{{ $status }}" {{ request('status_search') == $status ? 'selected' : '' }}>
                                            {{ $status }}
                                        </option>
                                    @endforeach
                                     <option value="Booked" {{ request('status_search') == 'Booked' ? 'selected' : '' }}>Booked (Default View)</option>
                                </select>
                            </div>
                            <div class="col-md-2 align-self-end">
                                <button type="submit" class="btn btn-info w-100">Search</button>
                            </div>
                             <div class="col-md-1 align-self-end">
                                <a href="{{ route('dev.schedules.index') }}" class="btn btn-secondary w-100" title="Reset Filter">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped" id="table1">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>GOR</th>
                                    <th>Field</th>
                                    <th>User</th>
                                    <th>Date</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Duration</th>
                                    <th>Status (Order)</th>
                                    <th>Schedule Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($schedules as $index => $schedule)
                                <tr>
                                    <td>{{ $schedules->firstItem() + $index }}</td>
                                    <td>{{ $schedule->gor->nama_gor ?? 'N/A' }}</td>
                                    <td>{{ $schedule->field->nama_lapangan ?? 'N/A' }}</td>
                                    <td>{{ $schedule->user->fullname ?? 'N/A' }}</td>
                                    <td>{{ $schedule->order->tanggal_main ? \Carbon\Carbon::parse($schedule->order->tanggal_main)->format('d M Y') : 'N/A' }}</td>
                                    <td>{{ $schedule->order->jam_mulai ?? 'N/A' }}</td>
                                    <td>{{ $schedule->order->jam_selesai ?? 'N/A' }}</td>
                                    <td>{{ $schedule->order->durasi ? $schedule->order->durasi . ' Jam' : 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-light-{{ strtolower(str_replace(' ', '-', $schedule->order->status ?? 'info')) }}">
                                            {{ $schedule->order->status ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                         <span class="badge bg-light-{{ strtolower(str_replace(' ', '-', $schedule->status ?? 'info')) }}">
                                            {{ $schedule->status ?? 'N/A' }} {{-- Kolom status dari tabel schedules --}}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('dev.orders.show', $schedule->order_id) }}" class="btn btn-sm btn-info" title="View Order">
                                            <i class="bi bi-receipt"></i> View Order
                                        </a>
                                        {{-- Tombol untuk edit atau delete schedule mungkin tidak diperlukan jika schedule di-manage via Order --}}
                                        {{-- 
                                        <a href="{{ route('dev.schedules.edit', $schedule->id) }}" class="btn btn-sm btn-warning" title="Edit Schedule"><i class="bi bi-pencil"></i></a>
                                        <form action="{{ route('dev.schedules.destroy', $schedule->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete Schedule"><i class="bi bi-trash"></i></button>
                                        </form>
                                        --}}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="11" class="text-center">No schedules found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $schedules->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection