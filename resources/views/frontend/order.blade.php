{{-- resources/views/frontend/profile.blade.php --}}
@extends('auth.layouts.main')
{{-- @section('hide_footer')
@endsection --}}
@section('container')
    @auth
        <div class="container" style="padding-top: 8rem">
            <div class="card shadow rounded-4">
                <div class="card-body px-4 pt-1">
                    {{-- Area Notifikasi Global --}}
                    @if (session('success_booking'))
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                        {{ session('success_booking') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error_booking'))
                    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                        {{ session('error_booking') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    @if (session('success_upload_proof'))
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                        {{ session('success_upload_proof') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    @if (session('error_upload_proof'))
                    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                        {{ session('error_upload_proof') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                @if (session('error_invoice'))
                     <div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
                         {{ session('error_invoice') }}
                         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif
                        
                <div class="row mt-1"> {{-- Beri sedikit margin jika notif muncul --}}
                    <div class="col-md-4 mb-3">
                        <div class="card">
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <h3 class="mb-4 fw-bold text-center">Pesanan Anda</h3>
                        @if ($orders->isEmpty())
                        <div class="alert alert-info">Anda belum memiliki riwayat pesanan.</div>
                        @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle table-bordered">
                                <thead class="table-primary text-center">
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">ID</th>
                                            <th scope="col">Tgl. Main</th>
                                            <th scope="col">GOR</th>
                                            <th scope="col">Lapangan</th>
                                            <th scope="col">Jam</th>
                                            <th scope="col">Status</th>
                                            <th scope="col" style="min-width: 260px;">Aksi / Bukti</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders as $order)
                                        <tr>
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td>{{ $order->id }}</td>
                                            <td>{{ \Carbon\Carbon::parse($order->tanggal_main)->format('d M Y') }}</td>
                                            <td>{{ $order->gor->nama_gor }}</td>
                                            <td>{{ $order->field->nama_lapangan }}</td>
                                            <td>{{ \Carbon\Carbon::parse($order->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($order->jam_selesai)->format('H:i') }}</td>
                                            <td>
                                                <span class="badge rounded-pill
                                                @if($order->status == \App\Models\Order::STATUS_WAITING_FOR_PAYMENT) text-bg-warning
                                                @elseif($order->status == \App\Models\Order::STATUS_PENDING_CONFIRMATION) text-bg-info
                                                @elseif(in_array($order->status, [\App\Models\Order::STATUS_PAYMENT_CONFIRMED, \App\Models\Order::STATUS_BOOKED, \App\Models\Order::STATUS_ON_PROGRESS, \App\Models\Order::STATUS_COMPLETED])) text-bg-success
                                                @elseif(in_array($order->status, [\App\Models\Order::STATUS_CANCELLED, \App\Models\Order::STATUS_FAILED])) text-bg-danger
                                                @else text-bg-secondary @endif">
                                                {{ $order->status }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($order->status == \App\Models\Order::STATUS_WAITING_FOR_PAYMENT)
                                                {{-- Tombol ini mengarah ke halaman pembayaran kita --}}
                                                <a href="{{ route('order.pay', $order->id) }}" class="btn btn-primary btn-sm d-block">
                                                    <i class="bi bi-credit-card"></i> Bayar Sekarang
                                                </a>
                                            @endif

                                            @if(in_array($order->status, [\App\Models\Order::STATUS_PAYMENT_CONFIRMED, \App\Models\Order::STATUS_BOOKED, \App\Models\Order::STATUS_ON_PROGRESS, \App\Models\Order::STATUS_COMPLETED]))
                                                <a href="{{ route('order.invoice', $order->id) }}" class="btn btn-success btn-sm d-block text-truncate">
                                                    <i class="bi bi-receipt"></i> Bukti Pesanan
                                                </a>
                                            @elseif($order->status == \App\Models\Order::STATUS_PENDING_CONFIRMATION)
                                                <small class="text-muted d-block fst-italic">Menunggu Pembayaran</small>
                                            @elseif($order->status == \App\Models\Order::STATUS_FAILED)
                                                <small class="text-danger d-block fst-italic">Pembayaran Gagal/Ditolak</small>
                                            @endif
                                        </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{-- Pagination Links --}}
                            @if ($orders->hasPages())
                            <div class="mt-3 d-flex justify-content-center">
                                {{ $orders->links() }}
                            </div>
                            @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endauth
@endsection