<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Gor;
use App\Models\Order; // Pastikan namespace App\Models\Order benar
use App\Models\Schedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule; // Untuk Rule::in
use Carbon\Carbon; // Untuk filter tanggal

class OrderController extends Controller
{
    private function getMyGor()
    {
        return Gor::where('user_id', Auth::id())->firstOrFail();
    }

    public function index(Request $request)
    {
        $gor = $this->getMyGor();
        //$this->authorize('viewAny', [Order::class, $gor]);

        $query = Order::where('gor_id', $gor->id)
                      ->with(['user', 'field'])
                      ->latest('id'); // Urutkan berdasarkan ID terbaru

        if ($request->filled('search_order_id')) {
            $query->where('id', $request->search_order_id);
        }
        if ($request->filled('user_search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('fullname', 'like', '%' . $request->user_search . '%')
                  ->orWhere('username', 'like', '%' . $request->user_search . '%')
                  ->orWhere('email', 'like', '%' . $request->user_search . '%');
            });
        }
        if ($request->filled('status_search') && $request->status_search !== 'all') {
            $query->where('status', $request->status_search);
        }
        if ($request->filled('date_search')) {
            try {
                // Asumsi format input dari date picker adalah Y-m-d
                $date = Carbon::parse($request->date_search)->format('d-m-Y');
                $query->where('tanggal_main', $date); // Gunakan whereDate untuk membandingkan tanggal saja
            } catch (\Exception $e) {
                // Abaikan jika format tanggal tidak valid, atau log error
                Log::info('Invalid date format for search: ' . $request->date_search);
            }
        }

        $orders = $query->paginate(10)->withQueryString(); // withQueryString untuk menjaga filter saat paginasi
        $statuses = Order::where('gor_id', $gor->id)->distinct()->pluck('status')->sort()->values()->all();
        $title = 'Order';

        return view('backend.admin.orders.index', compact('orders', 'gor', 'statuses', 'title'));
    }

    public function show(Order $order)
    {
        $gor = $this->getMyGor();
        if ($order->gor_id !== $gor->id) {
            return redirect()->route('admin.orders.index')->with('error', 'Pesanan tidak ditemukan atau Anda tidak berhak mengaksesnya.');
        }
        // Autorisasi, jika ada policy: $this->authorize('view', [$order, $gor]);
        $order->load(['user', 'field', 'schedule']);
        $title = 'Order';
        return view('backend.admin.orders.show', compact('order', 'gor', 'title'));
    }

    public function edit(Order $order)
    {
        $gor = $this->getMyGor();
        if ($order->gor_id !== $gor->id) {
            return redirect()->route('admin.orders.index')->with('error', 'Pesanan tidak ditemukan atau Anda tidak berhak mengaksesnya.');
        }
        // Autorisasi, jika ada policy: $this->authorize('update', [$order, $gor]);

        $possibleStatuses = [
            Order::STATUS_WAITING_FOR_PAYMENT, Order::STATUS_PENDING_CONFIRMATION,
            Order::STATUS_PAYMENT_CONFIRMED, Order::STATUS_BOOKED, Order::STATUS_ON_PROGRESS,
            Order::STATUS_COMPLETED, Order::STATUS_CANCELLED, Order::STATUS_FAILED,
        ];
        $title = 'Edit Order';
        return view('backend.admin.orders.edit', compact('order', 'gor', 'possibleStatuses', 'title'));
    }

    public function updateStatus(Request $request, Order $order) // Ini untuk update status manual oleh admin
    {
        $gor = $this->getMyGor();
        if ($order->gor_id !== $gor->id) {
            return redirect()->route('admin.orders.index')->with('error', 'Pesanan tidak ditemukan.');
        }
        // Autorisasi, jika ada policy: $this->authorize('update', [$order, $gor]);

        $possibleStatuses = [Order::STATUS_WAITING_FOR_PAYMENT, Order::STATUS_PENDING_CONFIRMATION, Order::STATUS_PAYMENT_CONFIRMED, Order::STATUS_BOOKED, Order::STATUS_ON_PROGRESS, Order::STATUS_COMPLETED, Order::STATUS_CANCELLED, Order::STATUS_FAILED];

        $validator = Validator::make($request->all(), [
            'status' => ['required', 'string', Rule::in($possibleStatuses)],
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.orders.edit', $order->id)
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $oldStatus = $order->status;
            $newStatus = $request->input('status');

            $order->status = $newStatus;
            $order->save();

            // Kelola jadwal berdasarkan perubahan status
            $this->manageSchedule($order, $newStatus, $oldStatus);

            DB::commit();
            return redirect()->route('admin.orders.show', $order->id)->with('success', 'Status pesanan berhasil diperbarui menjadi ' . $newStatus . '.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error updating order status (manual admin) for order #{$order->id}: " . $e->getMessage());
            return redirect()->route('admin.orders.edit', $order->id)->with('error', 'Gagal memperbarui status pesanan. Silakan coba lagi.');
        }
    }

    public function confirmPayment(Request $request, Order $order)
    {
        $gor = $this->getMyGor();
        if ($order->gor_id !== $gor->id) {
            return redirect()->route('admin.orders.index')->with('error', 'Aksi tidak diizinkan untuk pesanan ini.');
        }
        // Autorisasi, jika ada policy: $this->authorize('update', [$order, $gor]);

        if ($order->status === Order::STATUS_PENDING_CONFIRMATION) {
            DB::beginTransaction();
            try {
                $oldStatus = $order->status;
                $newStatus = Order::STATUS_PAYMENT_CONFIRMED; // Status setelah pembayaran dikonfirmasi

                $order->status = $newStatus;
                $order->save();

                $this->manageSchedule($order, $newStatus, $oldStatus); // Update status schedule menjadi 'Booked'
                DB::commit();
                // TODO: Opsional kirim notifikasi ke user
                return redirect()->route('admin.orders.show', $order->id)->with('success', 'Pembayaran untuk pesanan #' . $order->id . ' berhasil dikonfirmasi. Status: ' . $newStatus);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Error confirming payment for order #{$order->id}: " . $e->getMessage());
                return redirect()->route('admin.orders.show', $order->id)->with('error', 'Gagal mengkonfirmasi pembayaran.');
            }
        }
        return redirect()->route('admin.orders.show', $order->id)->with('warning', 'Pesanan ini tidak dalam status "' . Order::STATUS_PENDING_CONFIRMATION . '".');
    }

    public function rejectPayment(Request $request, Order $order)
    {
        $gor = $this->getMyGor();
        if ($order->gor_id !== $gor->id) {
            return redirect()->route('admin.orders.index')->with('error', 'Aksi tidak diizinkan untuk pesanan ini.');
        }
        // Autorisasi, jika ada policy: $this->authorize('update', [$order, $gor]);

        if ($order->status === Order::STATUS_PENDING_CONFIRMATION) {
            DB::beginTransaction();
            try {
                $oldStatus = $order->status;
                $newStatus = Order::STATUS_FAILED; // Status jika pembayaran ditolak

                $order->status = $newStatus;
                // Jika Anda menambahkan field alasan penolakan di modal:
                // $order->rejection_reason = $request->input('rejection_reason');
                $order->save();

                $this->manageSchedule($order, $newStatus, $oldStatus); // Update status schedule menjadi 'Cancelled' atau 'Available'
                DB::commit();
                // TODO: Opsional kirim notifikasi ke user beserta alasan jika ada
                return redirect()->route('admin.orders.show', $order->id)->with('success', 'Pembayaran untuk pesanan #' . $order->id . ' berhasil ditolak. Status: ' . $newStatus);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Error rejecting payment for order #{$order->id}: " . $e->getMessage());
                return redirect()->route('admin.orders.show', $order->id)->with('error', 'Gagal menolak pembayaran.');
            }
        }
        return redirect()->route('admin.orders.show', $order->id)->with('warning', 'Pesanan ini tidak dalam status "' . Order::STATUS_PENDING_CONFIRMATION . '".');
    }

    private function manageSchedule(Order $order, string $newStatus, ?string $oldStatus)
    {
        $activeOrderStatuses = [Order::STATUS_PAYMENT_CONFIRMED, Order::STATUS_BOOKED, Order::STATUS_ON_PROGRESS];
        $inactiveOrderStatuses = [Order::STATUS_CANCELLED, Order::STATUS_FAILED];

        // Cek apakah schedule sudah ada
        $schedule = Schedule::where('order_id', $order->id)->first();

        if (in_array($newStatus, $activeOrderStatuses)) {
            if (!$schedule) { // Buat schedule baru jika belum ada
                $schedule = new Schedule();
                $schedule->order_id = $order->id;
                $schedule->user_id = $order->user_id; // Diambil dari order
                $schedule->field_id = $order->field_id; // Diambil dari order
                $schedule->gor_id = $order->gor_id;   // Diambil dari order
            }
            $schedule->status = 'Booked'; // Status schedule menjadi 'Booked'
            $schedule->save();
        } elseif (
            in_array($newStatus, $inactiveOrderStatuses) &&
            $oldStatus && // Pastikan oldStatus ada
            in_array($oldStatus, array_merge($activeOrderStatuses, [Order::STATUS_PENDING_CONFIRMATION, Order::STATUS_WAITING_FOR_PAYMENT]))
        ) {
            // Jika status baru adalah Cancelled/Failed DAN status lama adalah salah satu dari yang aktif/pending/wait
            if ($schedule) {
                $schedule->status = 'Cancelled'; // Atau 'Available' agar bisa dipesan ulang langsung
                $schedule->save();
            }
        } elseif ($newStatus === Order::STATUS_COMPLETED && $oldStatus && in_array($oldStatus, $activeOrderStatuses)) {
             if ($schedule) {
                $schedule->status = 'Completed';
                $schedule->save();
            }
        }
        // Kondisi lain bisa ditambahkan, misal jika order kembali ke Waiting for Payment, apa yang terjadi dengan schedule.
    }
}