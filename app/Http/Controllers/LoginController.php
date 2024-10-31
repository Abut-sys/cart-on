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
        return view('auth.login'); // Your login view
    }

    public function store(Request $request)
    {
        // Validasi input
        $attributes = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Coba login
        if (Auth::attempt($attributes)) {
            // Redirect berdasarkan role pengguna
            if (Auth::user()->role == 'admin') {
                return redirect()->route('dashboard.index')->with('msg', 'Welcome Admin!');
            }

            return redirect()->route('home.index')->with('msg', 'Welcome User!');
        }

        // Jika login gagal, kembalikan pesan kesalahan
        throw ValidationException::withMessages([
            'email' => 'Your credentials do not match our records.',
        ]);
    }
}
