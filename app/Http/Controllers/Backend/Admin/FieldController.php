<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Gor;
use App\Models\Field;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FieldController extends Controller
{
    /**
     * Helper untuk mendapatkan GOR milik admin yang sedang login.
     */
    private function getMyGor()
    {
        return Gor::where('user_id', Auth::id())->firstOrFail();
    }

    /**
     * Menampilkan daftar lapangan untuk GOR milik admin.
     */
    public function index()
    {
        $gor = $this->getMyGor();
        // Ambil semua field yang 'gor_id'-nya adalah ID GOR milik admin
        $fields = Field::where('gor_id', $gor->id)->orderBy('nama_lapangan')->paginate(10);
        $title = 'Lapangan';
        return view('backend.admin.fields.index', compact('gor', 'fields', 'title'));
    }

    /**
     * Menampilkan form untuk membuat lapangan baru di GOR milik admin.
     */
    public function create()
    {
        $gor = $this->getMyGor(); // Untuk menampilkan info GOR di view
        $this->authorize('create', [Field::class, $gor]); // Policy: Boleh buat field di GOR ini?
        $title = 'Tambah Lapangan';
        return view('backend.admin.fields.create', compact('gor', 'title'));
    }

    /**
     * Menyimpan lapangan baru ke GOR milik admin.
     */
    public function store(Request $request)
    {
        $gor = $this->getMyGor();
        $this->authorize('create', [Field::class, $gor]);

        $validator = Validator::make($request->all(), [
            'nama_lapangan' => 'required|string|max:255',
            // Pastikan nama_lapangan unik untuk GOR ini, bisa ditambahkan custom validation rule jika perlu
            // Rule::unique('fields')->where(function ($query) use ($gor) {
            //     return $query->where('gor_id', $gor->id);
            // }),
            'keterangan_lapangan' => 'required|string|max:255',
            'harga_sewa' => 'required|integer|min:0',
            'foto_lapangan' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.fields.create') // Perhatikan nama rute 'admin.'
                        ->withErrors($validator)
                        ->withInput();
        }

        $imagePath = null;
        if ($request->hasFile('foto_lapangan')) {
            $file = $request->file('foto_lapangan');
            $filenameWithExt = $file->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            $path = $file->store('field_images/');
            $imagePath = Storage::url($path);
        }

        Field::create([
            'gor_id' => $gor->id, // Set gor_id secara otomatis
            'nama_lapangan' => $request->nama_lapangan,
            'slug_lapangan' => Str::slug($request->nama_lapangan . '-' . $gor->id . '-' . Str::random(5)), // Buat slug unik
            'keterangan_lapangan' => $request->keterangan_lapangan,
            'harga_sewa' => $request->harga_sewa,
            'foto_lapangan' => $imagePath,
        ]);

        return redirect()->route('admin.fields.index')->with('success', 'New field created successfully.');
    }

    /**
     * Menampilkan detail lapangan spesifik.
     */
    public function show(Field $field)
    {
        $gor = $this->getMyGor();
        $this->authorize('view', $field); // Policy: Boleh lihat field ini?
        $title = 'Lapangan';
        // Validasi: Pastikan field ini milik GOR admin
        if ($field->gor_id !== $gor->id) {
            abort(404, 'Field not found.');
        }
        return view('backend.admin.fields.show', compact('gor', 'field', 'title'));
    }

    /**
     * Menampilkan form untuk mengedit lapangan.
     */
    public function edit(Field $field)
    {
        $this->authorize('update', $field);
        $gor = $this->getMyGor();
        $title = 'Edit Lapangan';
        if ($field->gor_id !== $gor->id) {
            abort(404, 'Field not found.');
        }
        return view('backend.admin.fields.edit', compact('gor', 'field', 'title'));
    }

    /**
     * Mengupdate detail lapangan.
     */
    public function update(Request $request, Field $field)
    {
        $this->authorize('update', $field);
        $gor = $this->getMyGor();
        if ($field->gor_id !== $gor->id) {
            abort(403, 'Unauthorized action.');
        }

        $validator = Validator::make($request->all(), [
            'nama_lapangan' => 'required|string|max:255',
            // Rule::unique('fields')->where(function ($query) use ($gor, $field) {
            //     return $query->where('gor_id', $gor->id)->where('id', '!=', $field->id);
            // }),
            'keterangan_lapangan' => 'required|string|max:255',
            'harga_sewa' => 'required|integer|min:0',
            'foto_lapangan' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Foto opsional saat update
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.fields.edit', $field->id)
                        ->withErrors($validator)
                        ->withInput();
        }

        $fieldData = $request->only(['nama_lapangan', 'keterangan_lapangan', 'harga_sewa']);
        // Jika nama lapangan berubah, slug juga bisa diupdate (opsional)
        // $fieldData['slug_lapangan'] = Str::slug($request->nama_lapangan . '-' . $gor->id . '-' . Str::random(5));


        if ($request->hasFile('foto_lapangan')) {
            // Hapus foto lama jika ada
            if ($field->foto_lapangan) {
                $oldStoragePath = str_replace(Storage::url(''), 'public/', $field->foto_lapangan);
                if (Storage::exists($oldStoragePath)) {
                    Storage::delete($oldStoragePath);
                }
            }
            // Simpan foto baru
            $file = $request->file('foto_lapangan');
            $filenameWithExt = $file->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            $path = $file->storeAs('public/field_images', $fileNameToStore);
            $fieldData['foto_lapangan'] = Storage::url($path);
        }

        $field->update($fieldData);

        return redirect()->route('admin.fields.index')->with('success', 'Field updated successfully.');
    }

    /**
     * Menghapus lapangan.
     */
    public function destroy(Field $field)
    {
        $this->authorize('delete', $field);
        $gor = $this->getMyGor();
        if ($field->gor_id !== $gor->id) {
            abort(403, 'Unauthorized action.');
        }

        // Periksa dependensi sebelum menghapus (misalnya, apakah lapangan ini ada di order/schedule aktif)
        if ($field->order()->whereNotIn('status', ['Completed', 'Cancelled', 'Failed'])->count() > 0) {
            return redirect()->route('admin.fields.index')->with('error', 'Field cannot be deleted as it has active associated orders.');
        }
        if ($field->schedule()->whereNotIn('status', ['Completed', 'Cancelled'])->count() > 0) {
            return redirect()->route('admin.fields.index')->with('error', 'Field cannot be deleted as it has active associated schedules.');
        }

        // Hapus foto lapangan dari storage
        if ($field->foto_lapangan) {
            $oldStoragePath = str_replace(Storage::url(''), 'public/', $field->foto_lapangan);
            if (Storage::exists($oldStoragePath)) {
                Storage::delete($oldStoragePath);
            }
        }
        $field->delete();

        return redirect()->route('admin.fields.index')->with('success', 'Field deleted successfully.');
    }
}