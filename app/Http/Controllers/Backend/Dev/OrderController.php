<?php

namespace App\Http\Controllers\Backend\Dev;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Schedule;
use App\Models\Gor; // Untuk filter
use App\Models\User; // Untuk filter
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'gor', 'field'])->latest('id');

        if ($request->filled('search_order_id')) {
            $query->where('id', $request->search_order_id);
        }
        if ($request->filled('user_search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('fullname', 'like', '%' . $request->user_search . '%')
                  ->orWhere('username', 'like', '%' . $request->user_search . '%');
            });
        }
        if ($request->filled('gor_search')) {
             $gor_id = $request->gor_search;
             if ($gor_id !== 'all') { // Tambahkan kondisi untuk filter 'all'
                 $query->where('gor_id', $gor_id);
             }
        }
        if ($request->filled('status_search')) {
            $status = $request->status_search;
             if ($status !== 'all') { // Tambahkan kondisi untuk filter 'all'
                 $query->where('status', $status);
             }
        }
         if ($request->filled('date_search')) {
              $query->whereDate('tanggal_main', $request->date_search);
         }


        $orders = $query->paginate(15);
        $statuses = Order::distinct()->pluck('status')->sort()->values()->all();
        $gors = Gor::orderBy('nama_gor')->get();
        $title = 'Daftar Order';

        return view('backend.dev.orders.index', compact('orders', 'statuses', 'gors', 'title'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load(['user', 'gor', 'field', 'schedule']);
        $title = 'Lihat Order';
        return view('backend.dev.orders.show', compact('order', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     * (Di sini, 'edit' digunakan untuk menampilkan form update status)
     */
    public function edit(Order $order)
    {
        // Daftar status yang mungkin untuk dropdown, bisa juga diambil dari model atau config
        $possibleStatuses = ['Waiting for Payment', 'Payment Confirmed', 'Booked', 'On Progress', 'Completed', 'Cancelled', 'Failed'];
        $title = 'Edit Order';
        return view('backend.dev.orders.edit', compact('order', 'possibleStatuses', 'title'));
    }

    /**
     * Update the status of the specified resource in storage.
     * Ini adalah metode yang akan kita panggil dari form edit.
     * Anda juga bisa menggunakan metode update() standar dari resource controller jika lebih suka,
     * namun memisahkannya untuk status bisa lebih jelas.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:Waiting for Payment,Payment Confirmed,Booked,On Progress,Completed,Cancelled,Failed',
            'admin_notes' => 'nullable|string', // Jika ada field catatan admin
        ]);

        if ($validator->fails()) {
            return redirect()->route('dev.orders.edit', $order->id)
                        ->withErrors($validator)
                        ->withInput();
        }

        $newStatus = $request->input('status');
        $oldStatus = $order->status;

        DB::beginTransaction();
        try {
            $order->status = $newStatus;
            if ($request->filled('admin_notes')) {
                // Pastikan ada kolom 'admin_notes' di tabel 'orders' jika ingin menyimpan ini
                // $order->admin_notes = $request->admin_notes;
            }
            $order->save();

            // --- INGATKAN SAYA TENTANG LOGIKA SCHEDULE DI SINI ---
            // Jika $newStatus adalah 'Booked' atau 'Payment Confirmed', buat/update Schedule.
            // Jika $newStatus adalah 'Cancelled' atau 'Failed' dan $oldStatus adalah 'Booked'/'Payment Confirmed', update Schedule.
            // Jika $newStatus adalah 'Completed', update Schedule.
            $this->manageSchedule($order, $newStatus, $oldStatus);


            DB::commit();
            return redirect()->route('dev.orders.show', $order->id)->with('success', 'Order status updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            // Log error: \Log::error('Error updating order status (Dev): ' . $e->getMessage());
            return redirect()->route('dev.orders.edit', $order->id)->with('error', 'Failed to update order status. ' . $e->getMessage());
        }
    }

    /**
     * Helper method to manage schedule creation/update based on order status.
     * (Ini adalah logika yang kita tunda tadi)
     */
    private function manageSchedule(Order $order, string $newStatus, ?string $oldStatus)
    {
         if (in_array($newStatus, ['Booked', 'Payment Confirmed'])) {
             $schedule = Schedule::firstOrNew(['order_id' => $order->id]);
             $schedule->user_id = $order->user_id;
             $schedule->field_id = $order->field_id;
             $schedule->gor_id = $order->gor_id;
             // Status schedule bisa 'Booked' atau mengambil dari status order yang relevan.
             // 'Booked' lebih konsisten untuk jadwal yang aktif.
             $schedule->status = 'Booked';
             $schedule->save();
         } elseif (
             (in_array($newStatus, ['Cancelled', 'Failed']) && in_array($oldStatus, ['Booked', 'Payment Confirmed', 'Waiting for Payment', 'On Progress'])) ||
             ($newStatus === 'Completed' && in_array($oldStatus, ['Booked', 'Payment Confirmed', 'On Progress']))
         ) {
             $schedule = Schedule::where('order_id', $order->id)->first();
             if ($schedule) {
                 if ($newStatus === 'Completed') {
                     $schedule->status = 'Completed';
                 } else {
                     // Untuk 'Cancelled' atau 'Failed' order, schedule juga dianggap batal
                     $schedule->status = 'Cancelled';
                 }
                 $schedule->save();
             }
         }
         // Jika order kembali ke 'Waiting for Payment' dari status booked/confirmed,
         // mungkin schedule statusnya perlu dipertimbangkan (misal, kembali ke 'Pending' atau dihapus jika tidak relevan)
         // Untuk saat ini, logika di atas sudah mencakup kasus utama.
    }

    // public function update(Request $request, Order $order) { /* Bisa juga digabung di sini */ }
    // public function destroy(Order $order) { /* Sebaiknya tidak ada, ganti status jadi Cancelled */ }
}