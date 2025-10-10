<?php

namespace App\Http\Controllers\Backend\Dev;

use App\Http\Controllers\Controller;
use App\Models\Gor;
use App\Models\Field;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FieldController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Gor $gor) // Menerima objek Gor dari route model binding
    {
        $fields = $gor->field()->orderBy('nama_lapangan')->paginate(10); // Ambil field milik GOR ini
        $title = 'Daftar Lapangan';
        return view('backend.dev.fields.index', compact('gor', 'fields', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Gor $gor)
    {
        $title = 'Tambah Lapangan';
        return view('backend.dev.fields.create', compact('gor', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Gor $gor)
    {
        $validator = Validator::make($request->all(), [
            'nama_lapangan' => 'required|string|max:255',
            'keterangan_lapangan' => 'required|string|max:255', // Misal: Vinyl, Rumput Sintetis
            'harga_sewa' => 'required|integer|min:0',
            'foto_lapangan' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // Tambahkan validasi lain jika ada
        ]);

        if ($validator->fails()) {
            return redirect()->route('dev.gors.fields.create', $gor->id)
                        ->withErrors($validator)
                        ->withInput();
        }

        $path = null;
        if ($request->hasFile('foto_lapangan')) {
            $path = $request->file('foto_lapangan')->store('public/field_images');
        }

        $gor->field()->create([
            'nama_lapangan' => $request->nama_lapangan,
            'slug_lapangan' => Str::slug($request->nama_lapangan . '-' . $gor->id . '-' . time()), // Buat slug unik
            'keterangan_lapangan' => $request->keterangan_lapangan,
            'harga_sewa' => $request->harga_sewa,
            'foto_lapangan' => $path ? Storage::url($path) : null, // Simpan URL publik
            // 'gor_id' akan otomatis terisi karena kita menggunakan relasi $gor->field()->create()
        ]);

        return redirect()->route('dev.gors.fields.index', $gor->id)->with('success', 'Field created successfully for ' . $gor->nama_gor . '.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Gor $gor, Field $field)
    {
        // Pastikan field ini milik GOR yang benar
        if ($field->gor_id !== $gor->id) {
            abort(404);
        }
        $title = 'Lihat Lapangan';
        return view('backend.dev.fields.show', compact('gor', 'field', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gor $gor, Field $field)
    {
        if ($field->gor_id !== $gor->id) {
            abort(404);
        }
        $title = 'Edit Lapangan';
        return view('backend.dev.fields.edit', compact('gor', 'field', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gor $gor, Field $field)
    {
        if ($field->gor_id !== $gor->id) {
            abort(404);
        }

        $validator = Validator::make($request->all(), [
            'nama_lapangan' => 'required|string|max:255',
            'keterangan_lapangan' => 'required|string|max:255',
            'harga_sewa' => 'required|integer|min:0',
            'foto_lapangan' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->route('dev.gors.fields.edit', [$gor->id, $field->id])
                        ->withErrors($validator)
                        ->withInput();
        }

        $fieldData = [
            'nama_lapangan' => $request->nama_lapangan,
            // Slug bisa diupdate atau dibiarkan (biasanya slug tidak sering diubah setelah dibuat)
            // 'slug_lapangan' => Str::slug($request->nama_lapangan . '-' . $gor->id . '-' . time()),
            'keterangan_lapangan' => $request->keterangan_lapangan,
            'harga_sewa' => $request->harga_sewa,
        ];

        if ($request->hasFile('foto_lapangan')) {
            // Hapus foto lama jika ada
            if ($field->foto_lapangan) {
                $oldPath = str_replace(Storage::url(''), 'public/', $field->foto_lapangan);
                if (Storage::exists($oldPath)) {
                    Storage::delete($oldPath);
                }
            }
            $path = $request->file('foto_lapangan')->store('public/field_images');
            $fieldData['foto_lapangan'] = Storage::url($path);
        }

        $field->update($fieldData);

        return redirect()->route('dev.gors.fields.index', $gor->id)->with('success', 'Field updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gor $gor, Field $field)
    {
        if ($field->gor_id !== $gor->id) {
            abort(404);
        }

        // Periksa apakah ada order atau schedule terkait field ini sebelum menghapus
        if ($field->order()->count() > 0 || $field->schedule()->count() > 0) {
            return redirect()->route('dev.gors.fields.index', $gor->id)->with('error', 'Field cannot be deleted as it has associated orders or schedules.');
        }


        // Hapus foto lapangan dari storage
        if ($field->foto_lapangan) {
            $oldPath = str_replace(Storage::url(''), 'public/', $field->foto_lapangan);
            if (Storage::exists($oldPath)) {
                Storage::delete($oldPath);
            }
        }

        $field->delete();

        return redirect()->route('dev.gors.fields.index', $gor->id)->with('success', 'Field deleted successfully.');
    }
}