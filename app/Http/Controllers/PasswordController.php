<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PasswordController extends Controller
{
    public function create()
    {
        return view('auth.set-password');
    }

    public function store(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return $this->redirectUserByRole();
    }

    private function redirectUserByRole()
    {
        // Cek role pengguna dan arahkan ke halaman yang sesuai
        if (Auth::user()->role === 'admin') {
            return redirect()->route('dashboard.index')->with('msg', 'Password has been set successfully! Welcome Admin!');
        }

        return redirect()->route('home.index')->with('msg', 'Password has been set successfully! Welcome User!');
    }
}
