{{-- resources/views/frontend/orders/invoice.blade.php --}}
@extends('auth.layouts.main') {{-- Sesuaikan dengan layout frontend Anda --}}
@push('styles') {{-- Untuk styling saat cetak --}}
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .card, .card * {
            visibility: visible;
        }
        .container.my-5.pt-5 { /* Target spesifik untuk kontainer invoice */
            padding-top: 0 !important;
            margin-top: 0 !important;
        }
        .card {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 0;
            border: none !important;
            box-shadow: none !important;
            font-size: 0.50 rem
        }
        .btn, .card-footer, .alert:not(.alert-success), .navbar, header { /* Sembunyikan tombol, footer, dan alert selain sukses saat print */
            display: none !important;
        }
        .card-header.bg-success { /* Pastikan warna header tetap saat cetak */
            background-color: #198754 !important; /* Warna hijau bootstrap */
            color: white !important;
            -webkit-print-color-adjust: exact; /* Chrome, Safari */
            color-adjust: exact; /* Firefox */
        }
        .alert.alert-success { /* Pastikan alert sukses terlihat baik saat print */
            background-color: #d1e7dd !important;
            color: #0f5132 !important;
            border-color: #badbcc !important;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
        }
        .badge {
            border: 1px solid #dee2e6; /* Beri border pada badge agar terlihat jika background color tidak tercetak */
            padding: .35em .65em;
            font-size: .85em;
        }
        .table-bordered th, .table-bordered td {
            border: 1px solid #dee2e6 !important; /* Pastikan border tabel terlihat */
        }
        .table-striped tbody tr:nth-of-type(odd) { /* Pastikan striping tabel terlihat jika memungkinkan */
            background-color: rgba(0,0,0,.03) !important;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
        }
    }
</style>
@endpush
@section('container')
<div class="container my-5 pt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0"><i class="bi bi-receipt-cutoff"></i> Bukti Pemesanan (Invoice) #{{ $order->id }}</h4>
        </div>
        <div class="card-body p-4">
            @if (session('error_invoice'))
                <div class="alert alert-danger">
                    {{ session('error_invoice') }}
                </div>
            @endif

            <div class="row mb-2">
                <div class="col-md-6">
                    <h5>Dipesan Oleh:</h5>
                    <ul class="list-unstyled">
                        <li><strong>Nama:</strong> {{ $order->user->fullname }}</li>
                        <li><strong>Email:</strong> {{ $order->user->email }}</li>
                        <li><strong>No. Telepon:</strong> {{ $order->user->notelp ?? '-' }}</li>
                    </ul>
                </div>
                <div class="col-md-6 text-md-end">
                    <h5>Tanggal Transaksi:</h5>
                    <p>{{ $order->updated_at->translatedFormat('l, d F Y - H:i T') }}</p>
                    <h5>Status Pesanan:</h5>
                    <p><span class="badge fs-6 rounded-pill
                        @if($order->status == \App\Models\Order::STATUS_PAYMENT_CONFIRMED || $order->status == \App\Models\Order::STATUS_BOOKED || $order->status == \App\Models\Order::STATUS_ON_PROGRESS || $order->status == \App\Models\Order::STATUS_COMPLETED) bg-success
                        @else bg-secondary @endif">
                        {{ strtoupper(str_replace('_', ' ', $order->status)) }}
                    </span></p>
                </div>
            </div>

            <hr class="my-2">

            <h4>Detail Sewa Lapangan:</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <th style="width: 30%;">ID Pesanan</th>
                            <td>#{{ $order->id }}</td>
                        </tr>
                        <tr>
                            <th>Nama GOR</th>
                            <td>{{ $order->gor->nama_gor ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Alamat GOR</th>
                            <td>{{ $order->gor->alamat_gor ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Nama Lapangan</th>
                            <td>{{ $order->field->nama_lapangan }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Main</th>
                            <td>{{ \Carbon\Carbon::parse($order->tanggal_main)->translatedFormat('l, d F Y') }}</td>
                        </tr>
                        <tr>
                            <th>Jam Mulai</th>
                            <td>{{ \Carbon\Carbon::parse($order->jam_mulai)->format('H:i') }} WIB</td>
                        </tr>
                        <tr>
                            <th>Jam Selesai</th>
                            <td>{{ \Carbon\Carbon::parse($order->jam_selesai)->format('H:i') }} WIB</td>
                        </tr>
                         <tr>
                            <th>Durasi</th>
                            <td>{{ $order->durasi }} Jam</td>
                        </tr>
                        <tr>
                            <th>Total Pembayaran</th>
                            <td class="fw-bold">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <hr class="my-2">

            @if(in_array($order->status, [\App\Models\Order::STATUS_PAYMENT_CONFIRMED, \App\Models\Order::STATUS_BOOKED, \App\Models\Order::STATUS_ON_PROGRESS, \App\Models\Order::STATUS_COMPLETED]))
            <div class="alert alert-success text-center" role="alert">
                <i class="bi bi-check-circle-fill"></i> Pembayaran Anda telah dikonfirmasi. Terima kasih!
            </div>
            @endif

            <div class="text-center mt-2">
                <p class="text-muted">Terima kasih telah melakukan pemesanan melalui sistem kami.</p>
                @if(in_array($order->status, [\App\Models\Order::STATUS_PAYMENT_CONFIRMED, \App\Models\Order::STATUS_BOOKED, \App\Models\Order::STATUS_ON_PROGRESS, \App\Models\Order::STATUS_COMPLETED]))
                    <p>Harap tunjukkan bukti pemesanan ini kepada petugas di GOR.</p>
                @endif
                <button onclick="window.print()" class="btn btn-primary btn-lg me-2"><i class="bi bi-printer-fill"></i> Cetak Bukti Pemesanan</button>
                <a href="{{ route('profile') }}" class="btn btn-secondary btn-lg"><i class="bi bi-arrow-left-square"></i> Kembali ke Profil</a>
            </div>
        </div>
        <div class="card-footer text-muted text-center py-3">
            Sistem Informasi Penyewaan Lapangan Futsal Jakarta - {{ now()->year }}
        </div>
    </div>
</div>
@endsection

