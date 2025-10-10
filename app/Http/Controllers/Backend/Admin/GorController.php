<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Gor;
use App\Models\GorImage; // Pastikan model ini ada dan benar
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class GorController extends Controller
{
    /**
     * Helper function untuk mendapatkan GOR yang dikelola oleh admin yang sedang login.
     * Akan mengembalikan error 404 jika GOR tidak ditemukan.
     */
    private function getMyGor()
    {
        $admin = Auth::user();
        // Asumsi satu admin hanya mengelola satu GOR.
        return Gor::where('user_id', $admin->id)->firstOrFail();
    }

    /**
     * Menampilkan form untuk mengedit detail GOR yang dikelola.
     */
    public function edit()
    {
        $gor = $this->getMyGor()->load('images'); // Eager load images
        $title = 'Gor';
        // Anda bisa menambahkan otorisasi dengan Policy jika diperlukan:
        $this->authorize('update', $gor);

        return view('backend.admin.gor.edit', compact('gor', 'title'));
    }

    /**
     * Mengupdate detail GOR yang dikelola.
     */
    public function update(Request $request)
    {
        $gor = $this->getMyGor();
        $this->authorize('update', $gor);

        $validator = Validator::make($request->all(), [
            'nama_gor' => 'required|string|max:255|unique:gors,nama_gor,' . $gor->id,
            'alamat_gor' => 'required|string',
            'latitude' => 'nullable|string|max:255', // Sesuaikan validasi jika perlu format angka
            'longitude' => 'nullable|string|max:255', // Sesuaikan validasi jika perlu format angka
            'deskripsi' => 'nullable|string',
            'fasilitas' => 'nullable|string', // Tambahkan ini
            'wilayah' => 'nullable|string|max:255',
            'kecamatan' => 'nullable|string|max:255',
            'whatsapp' => 'nullable|string|max:50',
            'instagram' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.gor.edit') // Rute edit untuk admin
                        ->withErrors($validator)
                        ->withInput();
        }

        $gor->update([
            'nama_gor' => $request->nama_gor,
            'slug_gor' => Str::slug($request->nama_gor), // Update slug jika nama GOR berubah
            'alamat_gor' => $request->alamat_gor,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'deskripsi' => $request->deskripsi,
            'fasilitas' => $request->fasilitas,
            'wilayah' => $request->wilayah,
            'kecamatan' => $request->kecamatan,
            'whatsapp' => $request->whatsapp,
            'instagram' => $request->instagram,
        ]);

        // Proses upload gambar baru ditangani oleh metode 'uploadImage'
        // Jika Anda ingin input file gambar ada di form edit utama, logikanya bisa digabung di sini.

        return redirect()->route('admin.gor.edit')->with('success', 'Your GOR details have been updated successfully.');
    }

    /**
     * Menangani upload gambar baru untuk GOR milik admin.
     * Rute untuk ini: POST /dashboardadmin/gor/images
     */
    public function uploadImage(Request $request)
    {
        $gor = $this->getMyGor(); //
        $this->authorize('update', $gor); //

        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', //
        ]);

        if ($request->hasFile('images')) { //
            foreach ($request->file('images') as $imagefile) { //
                // Menyimpan file ke 'storage/app/public/gor_images'
                // dan $relativePath akan berisi 'gor_images/namafileunik.jpg'
                $relativePath = $imagefile->store('gor_images', 'public'); // Menggunakan store() dengan disk 'public'

                // Simpan $relativePath ini ke database
                $gor->images()->create(['image_path' => $relativePath]);
            }
        }
        return back()->with('success', 'New images uploaded successfully.'); //
    }


    /**
     * Menghapus gambar spesifik dari GOR yang dikelola admin.
     * Rute: DELETE /dashboardadmin/gor/image/{gorImage}
     */
    public function deleteImage(GorImage $gorImage) // Menggunakan Route Model Binding
    {
        $gor = $this->getMyGor();

        // Validasi kepemilikan gambar
        if ($gorImage->gor_id !== $gor->id) {
            return back()->with('error', 'You are not authorized to delete this image.');
        }
        //$this->authorize('deleteImage', $gorImage); // Jika ada policy spesifik

        // Dapatkan path relatif dari URL publik untuk menghapus dari storage
        // Asumsi image_path disimpan sebagai URL publik (diawali /storage/)
        $relativePath = str_replace(url('/storage'), 'public', $gorImage->image_path);

        if (Storage::exists($relativePath)) {
            Storage::delete($relativePath);
        }
        $gorImage->delete(); // Hapus record dari database

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