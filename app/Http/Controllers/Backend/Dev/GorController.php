<?php

namespace App\Http\Controllers\Backend\Dev;

use App\Http\Controllers\Controller;
use App\Models\Gor;
use App\Models\User; // Untuk memilih Admin GOR
use App\Models\GorImage; // Untuk mengelola gambar GOR
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Untuk manajemen file
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str; // Untuk membuat slug

class GorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Gor::with(['admin', 'images'])->latest('id'); // Eager load admin dan images

        if ($request->filled('search_gor')) {
            $query->where('nama_gor', 'like', '%' . $request->search_gor . '%')
                  ->orWhere('alamat_gor', 'like', '%' . $request->search_gor . '%');
        }

        $gors = $query->paginate(10);
        $title = 'Datar Gor';
        return view('backend.dev.gors.index', compact('gors', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil user dengan role_id 2 (Admin) yang belum menjadi admin GOR manapun
        // Atau tampilkan semua admin dan biarkan validasi yang menangani jika admin sudah punya GOR (tergantung kebutuhan)
        $admins = User::where('role_id', 2)
                      // ->whereDoesntHave('gorManaged') // Jika satu admin hanya boleh satu GOR
                       ->orderBy('fullname')->get();
        $title = 'Tambah Gor';               
        return view('backend.dev.gors.create', compact('admins', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_gor' => 'required|string|max:255|unique:gors,nama_gor',
            'alamat_gor' => 'required|string',
            'user_id' => 'required|exists:users,id', // Admin GOR
            'latitude' => 'nullable|string|max:255', // Sesuaikan validasi jika perlu format khusus
            'longitude' => 'nullable|string|max:255',// Sesuaikan validasi jika perlu format khusus
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi untuk multiple images
            'deskripsi' => 'nullable|string',
            'fasilitas' => 'nullable|string', // Tambahkan ini
            'wilayah' => 'nullable|string|max:255',
            'kecamatan' => 'nullable|string|max:255',
            'whatsapp' => 'nullable|string|max:50',
            'instagram' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->route('dev.gors.create')
                        ->withErrors($validator)
                        ->withInput();
        }
        
        // Cek apakah user_id yang dipilih sudah menjadi admin GOR lain (jika 1 admin 1 GOR)
        // $existingGorAdmin = Gor::where('user_id', $request->user_id)->first();
        // if ($existingGorAdmin) {
        //     return redirect()->route('dev.gors.create')
        //                 ->withErrors(['user_id' => 'This user is already an admin for another GOR.'])
        //                 ->withInput();
        // }

        $gor = Gor::create([
            'nama_gor' => $request->nama_gor,
            'slug_gor' => Str::slug($request->nama_gor),
            'alamat_gor' => $request->alamat_gor,
            'user_id' => $request->user_id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'deskripsi' => $request->deskripsi,
            'fasilitas' => $request->fasilitas,
            'wilayah' => $request->wilayah,
            'kecamatan' => $request->kecamatan,
            'whatsapp' => $request->whatsapp,
            'instagram' => $request->instagram,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imagefile) {
                $path = $imagefile->store('gor_images', 'public');

                
                $gor->images()->create(['image_path' => $path]);
            }
        }

        return redirect()->route('dev.gors.index')->with('success', 'GOR created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Gor $gor)
    {
        $gor->load(['admin', 'images', 'field']); // Load relasi fields juga
        $title = 'Lihat Gor';
        return view('backend.dev.gors.show', compact('gor', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gor $gor)
    {
        $admins = User::where('role_id', 2)->orderBy('fullname')->get();
        $gor->load('images');
        $title = 'Gor';   
        return view('backend.dev.gors.edit', compact('gor', 'admins', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gor $gor)
    {
        $validator = Validator::make($request->all(), [
            'nama_gor' => 'required|string|max:255|unique:gors,nama_gor,' . $gor->id,
            'alamat_gor' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'latitude' => 'nullable|string|max:255',
            'longitude' => 'nullable|string|max:255',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'deskripsi' => 'nullable|string',
            'fasilitas' => 'nullable|string',
            'wilayah' => 'nullable|string|max:255',
            'kecamatan' => 'nullable|string|max:255',
            'whatsapp' => 'nullable|string|max:50',
            'instagram' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->route('dev.gors.edit', $gor->id)
                        ->withErrors($validator)
                        ->withInput();
        }
        
        // Cek jika admin diganti dan admin baru sudah mengelola GOR lain (jika 1 admin 1 GOR)
        // if ($request->user_id != $gor->user_id) {
        //     $existingGorAdmin = Gor::where('user_id', $request->user_id)->first();
        //     if ($existingGorAdmin) {
        //         return redirect()->route('dev.gors.edit', $gor->id)
        //                     ->withErrors(['user_id' => 'The new selected user is already an admin for another GOR.'])
        //                     ->withInput();
        //     }
        // }

        $gor->update([
            'nama_gor' => $request->nama_gor,
            'slug_gor' => Str::slug($request->nama_gor),
            'alamat_gor' => $request->alamat_gor,
            'user_id' => $request->user_id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'deskripsi' => $request->deskripsi,
            'fasilitas' => $request->fasilitas,
            'wilayah' => $request->wilayah,
            'kecamatan' => $request->kecamatan,
            'whatsapp' => $request->whatsapp,
            'instagram' => $request->instagram,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imagefile) {
                // Hapus file lama jika perlu (misalnya replace gambar utama) atau cukup tambahkan baru
                $path = $imagefile->store('gor_images', 'public');

                
                $gor->images()->create(['image_path' => $path]);
            }
        }

        return redirect()->route('dev.gors.index')->with('success', 'GOR updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gor $gor)
    {
        // Hapus semua gambar terkait dari storage dan database
        foreach ($gor->images as $image) {
            // Dapatkan path relatif dari URL untuk menghapus dari storage
            // Misal: Storage::url('public/gor_images/image.jpg') -> public/gor_images/image.jpg
            $relativePath = str_replace(Storage::url(''), '', $image->image_path); 
            // Atau jika Anda menyimpan path storage langsung di DB:
            // $relativePath = $image->image_path; (jika image_path = 'public/gor_images/...')
            
            if (Storage::exists($relativePath)) {
                Storage::delete($relativePath);
            }
            $image->delete(); // Hapus record dari tabel gor_images
        }

        // Hapus GOR
        // Pertimbangkan apa yang terjadi dengan Fields, Orders, Schedules terkait GOR ini.
        // Anda mungkin perlu onDelete('cascade') di migration atau menghapusnya secara manual di sini.
        // Contoh: $gor->field()->delete(); $gor->order()->delete();
        
        // Periksa apakah ada field terkait
        if ($gor->field()->count() > 0) {
            return redirect()->route('dev.gors.index')->with('error', 'GOR cannot be deleted because it has associated fields. Please delete the fields first.');
        }
        // Periksa apakah ada order terkait
        if ($gor->order()->count() > 0) {
            return redirect()->route('dev.gors.index')->with('error', 'GOR cannot be deleted because it has associated orders. Please resolve the orders first.');
        }


        $gor->delete();

        return redirect()->route('dev.gors.index')->with('success', 'GOR and its images deleted successfully.');
    }

    /**
     * Delete a specific image of a GOR.
     */
    public function deleteImage(Gor $gor, GorImage $gorImage) // Route Model Binding untuk GorImage
    {
        // Pastikan gambar ini milik GOR yang benar (meskipun route sudah nested)
        if ($gorImage->gor_id !== $gor->id) {
            return back()->with('error', 'Image does not belong to this GOR.');
        }

        $relativePath = str_replace(Storage::url(''), '', $gorImage->image_path);
        if (Storage::exists($relativePath)) {
            Storage::delete($relativePath);
        }
        $gorImage->delete();

        return back()->with('success', 'Image deleted successfully.');
    }

    public function getKecamatan($wilayah)
    {
        // Data kecamatan untuk setiap wilayah di Jakarta
        $kecamatan = [
            'Jakarta Pusat' => ['Gambir', 'Sawah Besar', 'Kemayoran', 'Senen', 'Cempaka Putih', 'Menteng', 'Tanah Abang', 'Johar Baru'],
            'Jakarta Timur' => ['Matraman', 'Pulo Gadung', 'Jatinegara', 'Duren Sawit', 'Kramat Jati', 'Makasar', 'Pasar Rebo', 'Ciracas', 'Cipayung', 'Cakung'],
            'Jakarta Barat' => ['Cengkareng', 'Grogol Petamburan', 'Kalideres', 'Kebon Jeruk', 'Kembangan', 'Palmerah', 'Taman Sari', 'Tambora'],
            'Jakarta Selatan' => ['Kebayoran Baru', 'Kebayoran Lama', 'Pesanggrahan', 'Cilandak', 'Pasar Minggu', 'Jagakarsa', 'Mampang Prapatan', 'Pancoran', 'Tebet', 'Setiabudi'],
            'Jakarta Utara' => ['Cilincing', 'Koja', 'Kelapa Gading', 'Tanjung Priok', 'Pademangan', 'Penjaringan'],
        ];

        // Jika wilayah tidak ditemukan, kembalikan array kosong
        $data = $kecamatan[urldecode($wilayah)] ?? [];

        // Kembalikan data dalam format JSON
        return response()->json($data);
    }
}