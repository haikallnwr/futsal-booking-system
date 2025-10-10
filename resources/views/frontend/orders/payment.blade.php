@extends('auth.layouts.main')

@section('container')
<div class="container" style="padding-top: 120px; padding-bottom: 120px;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header text-white">
                    <h4 class="mb-0"><i class="bi bi-credit-card-2-front-fill"></i> Selesaikan Pembayaran</h4>
                </div>
                <div class="card-body p-4">
                    <h5 class="card-title">Detail Pesanan Anda:</h5>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th style="width: 30%;">ID Pesanan</th>
                                    <td>#{{ $order->id }}</td>
                                </tr>
                                <tr>
                                    <th>GOR</th>
                                    <td>{{ $order->gor->nama_gor }}</td>
                                </tr>
                                <tr>
                                    <th>Lapangan</th>
                                    <td>{{ $order->field->nama_lapangan }}</td>
                                </tr>
                                <tr>
                                    <th>Jadwal Main</th>
                                    <td>{{ \Carbon\Carbon::parse($order->tanggal_main)->format('d F Y') }}, {{ \Carbon\Carbon::parse($order->jam_mulai)->format('H:i') }}</td>
                                </tr>
                                 <tr>
                                    <th>Durasi</th>
                                    <td>{{ $order->durasi }}</td>
                                </tr>
                                <tr class="table-info">
                                    <th class="fw-bold">Total Pembayaran</th>
                                    <td class="fw-bold fs-5">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <p class="text-muted">Klik tombol di bawah untuk melanjutkan ke pembayaran. Anda akan diarahkan ke halaman aman Midtrans untuk memilih metode pembayaran.</p>

                    <div class="d-grid gap-2">
                        <button id="pay-button" class="btn btn-success btn-lg">
                            <i class="bi bi-shield-check-fill"></i> Bayar Sekarang
                        </button>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <small>Powered by Midtrans</small>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script Midtrans Snap --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script type="text/javascript">
    document.getElementById('pay-button').onclick = function(){
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result){
                /* GANTI baris window.location.href di bawah ini */
                console.log(result);
                // Arahkan ke route 'payment.success' dengan membawa ID order
                window.location.href = "{{ route('payment.success', $order->id) }}";
            },
            onPending: function(result){
                console.log(result);
                // Untuk pembayaran pending, arahkan ke halaman order biasa
                window.location.href = "{{ route('profile.orders') }}?payment_status=pending";
            },
            onError: function(result){
                console.log(result);
                alert("Pembayaran gagal!");
            },
            onClose: function(){
                alert('Anda menutup jendela pembayaran sebelum menyelesaikannya.');
            }
        });
    };
</script>
@endsection