<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Schedule; // Pastikan ini di-import
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Tidak digunakan langsung di orderStore, tapi mungkin di method lain
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB; // Pastikan ini di-import

class OrderController extends Controller
{
     public function orderStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gor_id' => 'required|exists:gors,id',
            'field' => 'required|string',
            'durasi' => 'required|integer|min:1',
            'tanggal_main' => 'required|date_format:d-m-Y',
            'jam_mulai' => 'required|date_format:H:i',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error_booking', 'Periksa kembali data pesanan Anda.');
        }

        $fieldInput = $request->input('field');
        $fieldParts = explode('|', $fieldInput);

        if (count($fieldParts) < 2 || !is_numeric($fieldParts[0]) || !is_numeric($fieldParts[1])) {
            return back()->with('error_booking', 'Data lapangan tidak valid.')->withInput();
        }

        $field_id = (int)$fieldParts[0];
        $harga_sewa = (int)$fieldParts[1];
        $durasi = (int)$request->input('durasi');
        $subtotal = $harga_sewa * $durasi;

        try {
            $tanggal_main_carbon = Carbon::createFromFormat('d-m-Y', $request->input('tanggal_main'))->startOfDay();
            $jam_mulai_carbon = Carbon::createFromFormat('H:i', $request->input('jam_mulai'));
            $jam_selesai_carbon = $jam_mulai_carbon->copy()->addHours($durasi);

            $waktu_mulai_pesanan = $tanggal_main_carbon->copy()->setTimeFrom($jam_mulai_carbon);

            if ($waktu_mulai_pesanan->lt(Carbon::now('Asia/Jakarta'))) {
                return back()->with('error_booking', 'Waktu yang Anda pilih sudah lewat.')->withInput();
            }
            
            // Pengecekan Jadwal
            $isNotAvailable = Schedule::where('field_id', $field_id)
                ->whereHas('order', function ($query) use ($tanggal_main_carbon, $jam_mulai_carbon, $jam_selesai_carbon) {
                    $query->where('tanggal_main', $tanggal_main_carbon->format('Y-m-d'))
                          ->where('jam_mulai', '<', $jam_selesai_carbon->format('H:i:s'))
                          ->where('jam_selesai', '>', $jam_mulai_carbon->format('H:i:s'));
                })
                ->whereIn('status', [Schedule::STATUS_BOOKED, Schedule::STATUS_ON_PROGRESS, Schedule::STATUS_TENTATIVE])
                ->exists();

            if ($isNotAvailable) {
                return back()->with('error_booking', 'Jadwal pada jam tersebut sudah terisi.')->withInput();
            }

            DB::beginTransaction();

            $order = Order::create([
                'user_id' => Auth::id(),
                'gor_id' => $request->input('gor_id'),
                'field_id' => $field_id,
                'subtotal' => $subtotal,
                'jam_mulai' => $jam_mulai_carbon->format('H:i:s'),
                'jam_selesai' => $jam_selesai_carbon->format('H:i:s'),
                'durasi' => $durasi . " Jam",
                'tanggal_main' => $tanggal_main_carbon->format('Y-m-d'),
                'status' => Order::STATUS_WAITING_FOR_PAYMENT,
            ]);

            Schedule::create([
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'field_id' => $order->field_id,
                'gor_id' => $order->gor_id,
                'status' => Schedule::STATUS_TENTATIVE,
            ]);
            
            // Konfigurasi Midtrans
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => $order->id, // Gunakan ID order sebagai ID unik
                    'gross_amount' => $order->subtotal,
                ],
                'customer_details' => [
                    'first_name' => Auth::user()->fullname,
                    'email' => Auth::user()->email,
                    'phone' => Auth::user()->notelp,
                ],
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($params);
            
            // Simpan Snap Token ke order
            $order->snap_token = $snapToken;
            $order->save();
            
            DB::commit();
            
            // Redirect ke halaman pembayaran
            return redirect()->route('order.pay', $order);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating order or getting Snap Token: ' . $e->getMessage());
            return back()->with('error_booking', 'Terjadi kesalahan sistem. Silakan coba lagi.')->withInput();
        }
    }

    public function showPaymentPage(Order $order)
    {
        // Pastikan hanya user yang bersangkutan yang bisa mengakses
        if (Auth::id() !== $order->user_id) {
            abort(403);
        }

        // Pastikan order masih menunggu pembayaran
        if ($order->status !== Order::STATUS_WAITING_FOR_PAYMENT) {
            return redirect()->route('profile.orders')->with('error_invoice', 'Pesanan ini sudah diproses atau dibatalkan.');
        }

        return view('frontend.orders.payment', [
            'title' => 'Pembayaran Pesanan ' . $order->id,
            'order' => $order,
            'snapToken' => $order->snap_token,
        ]);
    }

    public function showInvoice(Order $order)
    {
        if (Auth::id() !== $order->user_id) {
            abort(403);
        }

        $allowedStatus = [
            Order::STATUS_PAYMENT_CONFIRMED,
            Order::STATUS_BOOKED,
            Order::STATUS_ON_PROGRESS,
            Order::STATUS_COMPLETED,
        ];

        if (!in_array($order->status, $allowedStatus)) {
            return redirect()->route('profile.orders')->with('error_invoice', 'Bukti pesanan hanya tersedia untuk pesanan yang sudah lunas.');
        }

        $order->load(['user', 'field.gor']);
        return view('frontend.orders.invoice', [
            'title' => 'Invoice ' . $order->id,
            'order' => $order
        ]);
    }

    public function paymentSuccess(Order $order)
{
    // Pastikan hanya user yang benar yang bisa mengakses
    if (auth()->id() !== $order->user_id) {
        abort(403, 'AKSI TIDAK DIIZINKAN');
    }

    DB::beginTransaction();
    try {
        // Hanya ubah status jika masih 'Waiting for Payment' untuk mencegah pembaruan ganda
        if ($order->status === Order::STATUS_WAITING_FOR_PAYMENT) {
            $order->status = Order::STATUS_PAYMENT_CONFIRMED;
            $order->save();

            // Update juga status schedule terkait
            if ($order->schedule) {
                $order->schedule->status = Schedule::STATUS_BOOKED;
                $order->schedule->save();
            }
        }
        
        DB::commit();

        // Arahkan ke halaman riwayat pesanan dengan pesan sukses
        return redirect()->route('profile.orders')->with('success_booking', 'Pembayaran untuk pesanan #' . $order->id . ' berhasil dikonfirmasi!');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error confirming payment via callback for order #' . $order->id . ': ' . $e->getMessage());
        return redirect()->route('profile.orders')->with('error_invoice', 'Terjadi kesalahan saat mengkonfirmasi pembayaran Anda.');
    }
}
}