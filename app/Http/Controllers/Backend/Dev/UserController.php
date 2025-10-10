<?php

namespace App\Http\Controllers\Backend\Dev;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role; // Kita butuh model Role
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator; // Untuk validasi

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::with('role'); // Eager load relasi role

        if ($request->has('role_search') && $request->role_search != '') {
            $query->where('role_id', $request->role_search);
        }

        if ($request->has('name_search') && $request->name_search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('fullname', 'like', '%' . $request->name_search . '%')
                  ->orWhere('username', 'like', '%' . $request->name_search . '%')
                  ->orWhere('email', 'like', '%' . $request->name_search . '%');
            });
        }

        $users = $query->orderBy('fullname')->paginate(10);
        $roles = Role::all(); // Untuk filter dropdown
        $title = 'Daftar Pengguna';

        return view('backend.dev.users.index', compact('users', 'roles', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        $title = 'Tambah Pengguna';
        return view('backend.dev.users.create', compact('roles', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'notelp' => 'required|string|min:10|max:15',
            'alamat' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return redirect()->route('dev.users.create')
                        ->withErrors($validator)
                        ->withInput();
        }

        User::create([
            'fullname' => $request->fullname,
            'username' => $request->username,
            'email' => $request->email,
            'notelp' => $request->notelp,
            'alamat' => $request->alamat,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);

        return redirect()->route('dev.users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load('role'); // Load relasi role
        $title = 'Lihat Pengguna';
        return view('backend.dev.users.show', compact('user', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $title = 'Edit Pengguna';
        return view('backend.dev.users.edit', compact('user', 'roles', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'notelp' => 'required|string|min:10|max:15',
            'alamat' => 'required|string',
            'password' => 'nullable|string|min:6|confirmed', // Password opsional saat update
            'role_id' => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return redirect()->route('dev.users.edit', $user->id)
                        ->withErrors($validator)
                        ->withInput();
        }

        $userData = [
            'fullname' => $request->fullname,
            'username' => $request->username,
            'email' => $request->email,
            'notelp' => $request->notelp,
            'alamat' => $request->alamat,
            'role_id' => $request->role_id,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        return redirect()->route('dev.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Tambahkan pengecekan agar developer tidak bisa menghapus dirinya sendiri
        if ($user->id === auth()->id()) {
            return redirect()->route('dev.users.index')->with('error', 'You cannot delete your own account.');
        }
        
        // Tambahkan pengecekan lain jika user adalah admin GOR yang masih punya GOR, dll.
        // if ($user->role_id == 2 && $user->gorManaged) {
        // return redirect()->route('dev.users.index')->with('error', 'This user is an admin of a GOR. Please reassign or delete the GOR first.');
        // }


        try {
            $user->delete();
            return redirect()->route('dev.users.index')->with('success', 'User deleted successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Tangani error jika ada constraint foreign key, misalnya user punya order.
            // Anda mungkin perlu logika tambahan di sini, misal menonaktifkan user atau menghapus data terkait dulu.
            return redirect()->route('dev.users.index')->with('error', 'User cannot be deleted. It might be associated with other data (e.g., orders).');
        }
    }
}