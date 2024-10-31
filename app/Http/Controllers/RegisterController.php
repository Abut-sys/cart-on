<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register'); // Your registration view
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'unique:users', 'email'],
            'password' => ['required', 'min:8'],
            'phone_number' => ['required', 'string', 'unique:users'], // Validasi agar phone_number unik
            'role' => ['required', 'string', 'in:user,admin'], // Validasi agar role hanya 'user' atau 'admin'
        ]);

        // Membuat pengguna baru
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'role' => $request->role, // Simpan role pengguna
        ]);

        // Redirect to login with a success message
        return redirect()->route('login')->with('msg', 'Registration successful! Please login.');
    }
}
