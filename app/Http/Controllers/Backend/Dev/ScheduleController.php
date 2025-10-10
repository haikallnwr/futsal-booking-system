<?php

namespace App\Http\Controllers\Backend\Dev;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Gor; // Untuk filter
use App\Models\Field; // Untuk filter
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Schedule::with(['user', 'field', 'gor', 'order' => function ($query) {
            // Eager load detail order yang relevan
            $query->select('id', 'tanggal_main', 'jam_mulai', 'jam_selesai', 'durasi', 'status');
        }])->latest('id'); // Urutkan berdasarkan yang terbaru

        // Filter berdasarkan GOR
        if ($request->filled('gor_id_search')) {
            $query->where('gor_id', $request->gor_id_search);
        }

        // Filter berdasarkan Lapangan
        if ($request->filled('field_id_search')) {
            $query->where('field_id', $request->field_id_search);
        }

        // Filter berdasarkan Tanggal Main dari Order
        if ($request->filled('date_search')) {
            $query->whereHas('order', function ($q) use ($request) {
                $q->whereDate('tanggal_main', $request->date_search);
            });
        }
        
        // Filter berdasarkan status Order (misal hanya yang 'Booked')
        if ($request->filled('status_search')) {
            $query->whereHas('order', function ($q) use ($request) {
                $q->where('status', $request->status_search);
            });
        } else {
            // Default hanya tampilkan yang status ordernya 'Booked' atau status schedule yang relevan
            // Anda bisa menyesuaikan ini. Contoh jika schedule punya kolom status sendiri:
            // $query->where('status', 'Booked'); 
            // Atau jika bergantung pada status order:
            $query->whereHas('order', function ($q) {
                 $q->whereIn('status', ['Booked', 'Payment Confirmed', 'Waiting for Payment']); // Sesuaikan dengan status yang relevan
            });
        }


        $schedules = $query->paginate(15);
        $gors = Gor::orderBy('nama_gor')->get();
        // Untuk field, mungkin lebih baik load dinamis via AJAX jika banyak,
        // atau tampilkan semua jika tidak terlalu banyak.
        // $fields = Field::orderBy('nama_lapangan')->get();

        // Ambil daftar status unik dari order untuk filter
        $orderStatuses = \App\Models\Order::distinct()->pluck('status')->toArray();
        $title = 'Jadwal';


        return view('backend.dev.schedules.index', compact('schedules', 'gors', 'orderStatuses', 'title' /*, 'fields' */));
    }
}