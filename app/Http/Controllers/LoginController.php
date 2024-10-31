<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function create()
    {
        return view('auth.login'); // Tampilan login
    }

    public function store(Request $request)
    {
        // Validasi input
        $attributes = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Cek apakah pengguna ada
        $user = User::where('email', $attributes['email'])->first();

        // Jika pengguna ditemukan dan belum terverifikasi
        if ($user && !$user->is_verified) {
            // Redirect kembali dengan pesan kesalahan
            return redirect()->back()->withErrors(['email' => 'Your account is not verified. Please check your email for the OTP.']);
        }

        // Coba login
        if (Auth::attempt($attributes)) {
            // Redirect berdasarkan role pengguna
            return $this->redirectUserByRole();
        }

        // Jika login gagal, kembalikan pesan kesalahan
        throw ValidationException::withMessages([
            'email' => 'Your credentials do not match our records.',
        ]);
    }

    // Fungsi untuk redirect pengguna berdasarkan role
    private function redirectUserByRole()
    {
        // Cek role pengguna dan arahkan ke halaman yang sesuai
        if (Auth::user()->role === 'admin') {
            return redirect()->route('dashboard.index')->with('msg', 'Welcome Admin!');
        }

        return redirect()->route('home.index')->with('msg', 'Welcome User!');
    }
}
