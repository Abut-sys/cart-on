<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    // Tampilkan formulir lupa password
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');  // Pastikan Anda memiliki file Blade untuk tampilan ini
    }

    // Tangani pengiriman link reset password
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Kirim link reset password
        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('msg', 'Link reset password telah dikirim ke email Anda.')
            : back()->withErrors(['email' => 'Gagal mengirim link reset. Silakan coba lagi.']);
    }
}
