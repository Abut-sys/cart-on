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
        return view('auth.register'); // Return the registration view
    }

    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'name' => ['required', 'string', 'max:255'], // Ensure the name is not too long
            'email' => ['required', 'email', 'unique:users,email'], // Validate email uniqueness
            'phone_number' => ['required', 'string', 'unique:users,phone_number'], // Validate unique phone number
            'password' => ['required', 'string', 'min:8', 'confirmed'], // Confirm password and set a minimum length
        ]);

        // Create a new user
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password), // Hash the password before storing
        ]);

        // Redirect to login with a success message
        return redirect()->route('login')->with('msg', 'Registration successful! Please login.');
    }
}
