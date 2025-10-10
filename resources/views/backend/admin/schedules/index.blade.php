@extends('backend.layouts.main')

@section('container')
    <div class="page-heading">
        {{-- Pastikan variabel $gor, $title dikirim dari Admin\ScheduleController@index --}}
        <h3>{{ $title ?? 'Schedule Management' }} for: {{ $gor->nama_gor ?? 'Your GOR' }}</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">My Schedules</li>
            </ol>
        </nav>
    </div>
    <div class="page-content">
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <form method="GET" action="{{ route('admin.schedules.index') }}">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="field_id_search" class="form-label">Filter by Field</label>
                                <select name="field_id_search" id="field_id_search" class="form-select form-select-sm">
                                    <option value="all">All My Fields</option>
                                    {{-- Pastikan variabel $fields dikirim dari Admin\ScheduleController@index --}}
                                    @foreach($fields as $field)
                                        <option value="{{ $field->id }}" {{ request('field_id_search') == $field->id ? 'selected' : '' }}>
                                            {{ $field->nama_lapangan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="date_search" class="form-label">Filter by Date (Booking Date)</label>
                                <input type="date" name="date_search" id="date_search" class="form-control form-control-sm" value="{{ request('date_search') }}">
                            </div>
                             <div class="col-md-3">
                                <label for="status_search" class="form-label">Filter by Schedule Status</label>
                                <select name="status_search" id="status_search" class="form-select form-select-sm">
                                    <option value="all">All Statuses</option>
                                    {{-- Pastikan variabel $scheduleStatuses dikirim dari Admin\ScheduleController@index --}}
                                    @foreach($scheduleStatuses as $status)
                                        <option value="{{ $status }}" {{ request('status_search') == $status ? 'selected' : '' }}>
                                            {{ $status }}
                                        </option>
                                    @endforeach
                                     <option value="Booked" {{ request('status_search', 'Booked') == 'Booked' ? 'selected' : '' }}>Booked (Default)</option>
                                     <option value="On Progress" {{ request('status_search') == 'On Progress' ? 'selected' : '' }}>On Progress</option>
                                </select>
                            </div>
                            <div class="col-md-2 align-self-end">
                                <button type="submit" class="btn btn-info btn-sm w-100">Search</button>
                            </div>
                            <div class="col-md-1 align-self-end">
                                <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary btn-sm w-100" title="Reset Filter">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                    {{-- Tombol untuk fitur "Block Time" bisa ditambahkan di sini jika Anda mengembangkannya
                    <div class="mt-3">
                        <a href="{{-- route('admin.schedules.createBlock') " class="btn btn-warning">Block Time/Maintenance</a>
                    </div> --}}
                </div>
                <div class="card-body">
                    @include('backend.partials.alerts')

                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="table1">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Field</th>
                                    <th>User (Booked By)</th>
                                    <th>Date (Booking)</th>
                                    <th>Time (Booking)</th>
                                    <th>Schedule Status</th>
                                    <th>Order Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Pastikan variabel $schedules dikirim dari Admin\ScheduleController@index --}}
                                @forelse ($schedules as $index => $schedule_item) {{-- Mengganti $schedule menjadi $schedule_item untuk menghindari konflik jika ada --}}
                                <tr>
                                    <td>{{ $schedules->firstItem() + $index }}</td>
                                    <td>{{ $schedule_item->field->nama_lapangan ?? 'N/A' }}</td>
                                    <td>{{ $schedule_item->user->fullname ?? ($schedule_item->order->user->fullname ?? 'N/A (Manual Block)') }}</td>
                                    <td>
                                        @if($schedule_item->order)
                                            {{ $schedule_item->order->tanggal_main ? \Carbon\Carbon::parse($schedule_item->order->tanggal_main)->format('d M Y') : 'N/A' }}
                                        @else
                                            {{-- Jika ini jadwal blok manual, mungkin ada kolom tanggal sendiri di schedule --}}
                                            {{-- $schedule_item->tanggal_blok ? \Carbon\Carbon::parse($schedule_item->tanggal_blok)->format('d M Y') : 'N/A' --}}
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        @if($schedule_item->order)
                                            {{ $schedule_item->order->jam_mulai ?? 'N/A' }} - {{ $schedule_item->order->jam_selesai ?? 'N/A' }}
                                        @else
                                            {{-- $schedule_item->jam_mulai_blok ?? 'N/A' }} - {{ $schedule_item->jam_selesai_blok ?? 'N/A' --}}
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-light-{{ Illuminate\Support\Str::slug($schedule_item->status, '-') }} text-dark">
                                            {{ $schedule_item->status }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($schedule_item->order)
                                        <span class="badge bg-light-{{ Illuminate\Support\Str::slug($schedule_item->order->order_status ?? $schedule_item->order->status, '-') }} text-dark">
                                            {{-- Menggunakan order_status jika ada (dari alias di controller), jika tidak pakai status order biasa --}}
                                            {{ $schedule_item->order->order_status ?? $schedule_item->order->status }}
                                        </span>
                                        @else
                                        N/A (Not from order)
                                        @endif
                                    </td>
                                    <td>
                                        @if($schedule_item->order)
                                            <a href="{{ route('admin.orders.show', $schedule_item->order_id) }}" class="btn btn-sm btn-info" title="View Related Order">
                                                <i class="bi bi-receipt"></i>
                                            </a>
                                        @endif

                                        @if(in_array($schedule_item->status, ['Booked', 'On Progress']))
                                            <form action="{{ route('admin.schedules.cancel', $schedule_item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to cancel this schedule? If it is linked to an order, the order will also be cancelled.');">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Cancel Schedule">
                                                    <i class="bi bi-x-circle-fill"></i> Cancel
                                                </button>
                                            </form>
                                        @elseif($schedule_item->status === 'Blocked' || $schedule_item->status === 'Maintenance')
                                            {{-- Tombol untuk unblock jadwal jika ada --}}
                                            {{-- Contoh:
                                            <form action="{{ route('admin.schedules.unblock', $schedule_item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to unblock this schedule?');">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-success" title="Unblock Schedule">
                                                    <i class="bi bi-check-circle-fill"></i> Unblock
                                                </button>
                                            </form>
                                            --}}
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No schedules found for your GOR based on current filters.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{-- Pastikan $schedules adalah objek Paginator --}}
                        @if(isset($schedules) && $schedules instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            {{ $schedules->appends(request()->query())->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection