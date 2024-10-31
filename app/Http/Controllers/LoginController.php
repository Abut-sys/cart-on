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
        $attributes = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Attempt to log the user in
        if (Auth::attempt($attributes)) {
            return redirect('/')->with('msg', 'Welcome back!'); // Redirect to home after login
        }

        throw ValidationException::withMessages([
            'email' => 'Your credentials do not match our records.',
        ]);
    }
}
