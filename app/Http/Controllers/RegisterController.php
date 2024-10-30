<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'unique:users', 'email'],
            'password' => ['required', 'min:8'],
            'phone_number' => ['required', 'string', 'unique:users'] // Validasi agar phone_number unik
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' =>  $request->phone_number,
            'password' => Hash::make($request->password),
        ]);

        return redirect('login')->with('msg', 'Registration successful. Please log in.');
    }
}
