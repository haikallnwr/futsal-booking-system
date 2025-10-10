<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login', [
            'title' => 'Afuta | Login',
        ]);
    }
    public function register()
    {
        return view('auth.register', [
            'title' => 'Afuta | Register',
        ]);
    }
    public function registerStore(Request $request)
    {
        $data = $request->validate([
            'fullname' => 'required|min:5|max:255',
            'username' => 'required|min:5|max:255|unique:users',
            'email' => 'required|email:dns|unique:users',
            'notelp' => 'required|min:12',
            'alamat' => 'required|min:5',
            'password' => 'required|min:5|max:255'
        ]);
        $data['password'] = Hash::make($data['password']);
        $data['role_id'] = 3;
        User::create($data);

        return redirect('/login')->with('success', 'Registration successfull! Please login');
    }

    public function loginAuth(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);
        if (Auth::attempt($credentials)) {
            if (Auth::user()->role_id == 1) {
                $request->session()->regenerate();
                return redirect()->intended('/dashboarddev');
            } else if (Auth::user()->role_id == 2) {
                $request->session()->regenerate();
                return redirect()->intended('/dashboardadmin');
            } else if (Auth::user()->role_id == 3) {
                $request->session()->regenerate();
                return redirect()->intended('/');
            }
        }
        return back()->with('loginError', 'Login failed!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
