<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        // Jika sudah login, redirect ke dashboard
        if (session()->has('user_id')) {
            return redirect('/');
        }
        
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // Set session data
            session([
                'user_id' => $user->id,
                'user_nama' => $user->nama,
                'user_role' => $user->role,
                'user_foto' => $user->foto_profil,
                'user_admin' => $user->admin,
                'user_superadmin' => $user->superadmin,
            ]);

            return redirect('/')->with('success', 'Login berhasil! Selamat datang ' . $user->nama);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->except('password'));
    }

    public function logout()
    {
        // Ambil nama user sebelum logout untuk notifikasi
        $userName = session('user_nama', 'User');
        
        // Hapus semua session
        session()->flush();
        
        return redirect('/login')->with('success', 'Logout berhasil!');
    }
}