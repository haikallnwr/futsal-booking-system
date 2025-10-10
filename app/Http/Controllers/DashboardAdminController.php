<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Gor; // Pastikan ini di-import
use Illuminate\Support\Facades\DB;

class DashboardAdminController extends Controller
{
    public function index()
    {
        $admin = Auth::user();
        $gor = Gor::where('user_id', $admin->id)->first(); // Ambil GOR yang dikelola admin ini

        if (!$gor) {
            // Handle jika admin ini belum ditugaskan ke GOR manapun
            // Anda bisa redirect ke halaman error atau menampilkan pesan
            return redirect()->route('home')->with('error', 'You are not assigned to manage any GOR.');
        }

        // Ambil statistik spesifik untuk GOR ini
        $totalFields = $gor->field()->count();
        $totalBookingsToday = $gor->order()
                                  ->whereDate('tanggal_main', today())
                                  ->whereIn('status', ['Booked', 'Payment Confirmed', 'On Progress']) // Status yang dianggap aktif
                                  ->count();
        $pendingPayments = $gor->order()
                               ->where('status', 'Waiting for Payment')
                               ->count();

        
        return view('backend.admin.index', [
            'title' => $gor->nama_gor . ' | Dashboard',
            'gor' => $gor,
            'totalFields' => $totalFields,
            'totalBookingsToday' => $totalBookingsToday,
            'pendingPayments' => $pendingPayments,
            
        ]);
    }
}
