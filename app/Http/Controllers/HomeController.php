<?php

namespace App\Http\Controllers;

use App\Models\Gor;
use App\Models\Field; // Assuming Field model exists and is used elsewhere
use App\Models\Payment; // Assuming Payment model exists and is used elsewhere
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        return view('frontend.home', [
            'title' => 'Afuta | Home',
            // Eager load 'images' relationship along with 'field'
            'gors' => Gor::with(['field', 'images'])->paginate(6) //
        ]);
    }

     public function show(Gor $gor)
    {
        // Mendapatkan tanggal hari ini dalam format YYYY-MM-DD
        // Asumsi tanggal_main di database Anda juga disimpan dalam format YYYY-MM-DD
        $today = now()->format('Y-m-d');

        // Memuat relasi GOR dan sekaligus memfilter jadwal yang akan ditampilkan
        $gor->load([
            'images', // Memuat relasi gambar
            'field.schedule' => function ($query) use ($today) {
                // Ambil jadwal yang statusnya 'Booked'
                $query->where('status', 'Booked')
                      // Dan yang relasi 'order'-nya punya tanggal_main hari ini atau di masa mendatang
                      ->whereHas('order', function ($subQuery) use ($today) {
                          $subQuery->where('tanggal_main', '>=', $today);
                      });
            },
            'field.schedule.user',  // Juga muat relasi user dari jadwal
            'field.schedule.order'  // Dan relasi order dari jadwal
        ]);

        return view('frontend.detail_gor', [
            'title' => $gor->nama_gor . ' | Afuta',
            'gor' => $gor
        ]);
    }

    public function search(Request $request)
    {
        $query = Gor::query()->with(['images', 'field']);

        if ($request->filled('wilayah')) {
            $query->where('wilayah', $request->wilayah);
        }

        if ($request->filled('kecamatan')) {
            $query->where('kecamatan', $request->kecamatan);
        }

        if ($request->filled('jenis_lapangan')) {
            $query->whereHas('field', function ($q) use ($request) {
                $q->where('keterangan_lapangan', $request->jenis_lapangan);
            });
        }

        $gors = $query->paginate(9)->withQueryString();

        // Mengarahkan ke view 'frontend.gor' yang menampilkan hasil pencarian
        return view('frontend.gor', [
            'title' => 'Hasil Pencarian',
            'gors' => $gors,
        ]);
    }

    // TAMBAHKAN METODE BARU INI
    public function getSearchFilters()
    {
        // Ambil wilayah unik dan kecamatannya dari tabel gors
        $locations = Gor::select('wilayah', 'kecamatan')
                        ->whereNotNull('wilayah')
                        ->whereNotNull('kecamatan')
                        ->distinct()
                        ->get()
                        ->groupBy('wilayah')
                        ->map(function ($items) {
                            return $items->pluck('kecamatan')->unique()->sort()->values();
                        });

        // Ambil jenis lapangan unik dari tabel fields
        $fieldTypes = Field::select('keterangan_lapangan')
                           ->whereNotNull('keterangan_lapangan')
                           ->distinct()
                           ->orderBy('keterangan_lapangan')
                           ->pluck('keterangan_lapangan');

        return response()->json([
            'locations' => $locations,
            'field_types' => $fieldTypes,
        ]);
    }

    public function listGor()
    {
        return view('frontend.gor', [
        'title' => 'Afuta | Daftar GOR',
        'gors' => Gor::with(['field', 'images'])->paginate(6)
    ]);
    }
}