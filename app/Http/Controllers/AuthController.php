<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // ─── Tampilkan Form Login ────────────────────────────────────────────────
    /**
     * Tampilkan Halaman Login.
     * 
     * Merender halaman login aplikasi.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    // ─── Proses Login Pengguna ───────────────────────────────────────────────
    /**
     * Proses Login Pengguna.
     * 
     * Melakukan autentikasi email & password pengguna dan melakukan pengalihan berdasarkan role (admin/dosen/mahasiswa).
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            $user = Auth::user();

            // Redirect berdasarkan role
            if ($user->role->name == 'admin') {
                return redirect('admin/dashboard');
            } elseif ($user->role->name == 'dosen') {
                return redirect('dosen/dashboard');
            } else {
                return redirect('mahasiswa/dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah',
        ])->onlyInput('email');
    }

    // ─── Proses Logout Pengguna ──────────────────────────────────────────────
    /**
     * Proses Logout Pengguna.
     * 
     * Mengeluarkan sesi login pengguna, menghapus data session, dan dialihkan ke halaman login.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}