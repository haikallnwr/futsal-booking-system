<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order; // Pastikan ini di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator; // Import Validator
use Illuminate\Validation\Rule; // Import Rule
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Load orders dengan relasi yang dibutuhkan untuk profile.blade.php
        // Urutkan berdasarkan tanggal pembuatan terbaru
        $orders = Order::where('user_id', $user->id)
            ->with(['gor', 'field']) // Eager load relasi
            ->orderBy('created_at', 'desc')
            ->paginate(5); // Tambahkan paginasi, misal 5 order per halaman

        return view('frontend.profile', [
            'user' => $user,
            'orders' => $orders, // Kirim data orders yang sudah dipaginasi
            'title' => $user->username . ' | Profile'
        ]);
    }

    public function edit()
    {
        $user = Auth::user();
        return view('frontend.profile_edit', [ 
            'user' => $user,
            'title' => 'Edit Profile ' . $user->username
        ]);
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user(); // Mengambil data user yang sedang login

        // Validasi input dari form
        $validatedData = $request->validate([
            'fullname' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users')->ignore($user->id), // Username harus unik, kecuali untuk user ini sendiri
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id), 
            ],
            'notelp' => 'nullable|string|max:20', // Nomor telepon opsional
            'password' => 'nullable|string|min:8|confirmed', // Password opsional, jika diisi harus ada konfirmasi
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Foto opsional, dengan validasi tipe dan ukuran
        ]);

        // Siapkan data yang akan diupdate (tanpa password dan foto dulu)
        $updateData = [
            'fullname' => $validatedData['fullname'],
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            'notelp' => $validatedData['notelp'],
        ];

        // Jika user mengisi field password baru
        if (!empty($validatedData['password'])) {
            $updateData['password'] = Hash::make($validatedData['password']);
        }

        // Jika user mengupload foto profil baru
        if ($request->hasFile('profile_photo')) {
            // Hapus foto profil lama jika ada (dan bukan foto default jika ada)
            if ($user->profile_photo_path) {
                // Check if the path actually exists before attempting to delete
                if (Storage::disk('public')->exists($user->profile_photo_path)) {
                    Storage::disk('public')->delete($user->profile_photo_path);
                }
            }

            // Simpan foto baru ke folder 'profile-photos' di dalam 'storage/app/public'
            
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $updateData['profile_photo_path'] = $path; // Store the relative path
        }

        // Lakukan update pada data user
        if ($user->update($updateData)) {
            return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui!');
        } else {
            // Ini jarang terjadi jika validasi sudah lolos, tapi sebagai fallback
            return back()->with('error', 'Gagal memperbarui profil. Silakan coba lagi.')->withInput();
        }
    }

    public function orders()
    {
        $orders = Order::where('user_id', auth()->id())->latest()->paginate(10);
        $title = 'Daftar Pesanan Saya';
        return view('frontend.order', compact('orders', 'title'));
    }
}
