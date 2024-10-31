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
        $attributes = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'phone_number' => ['required', 'string', 'unique:users'],
            'password' => ['required', 'string', 'confirmed', 'min:8'], // Minimum password length
        ]);

        // Create a new user
        User::create([
            'name' => $attributes['name'],
            'email' => $attributes['email'],
            'phone_number' => $attributes['phone_number'],
            'password' => Hash::make($attributes['password']), // Hash the password
        ]);

        // Redirect to login with a success message
        return redirect()->route('login')->with('msg', 'Registration successful! Please login.');
    }
}
