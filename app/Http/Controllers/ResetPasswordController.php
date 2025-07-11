<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class ResetPasswordController extends Controller
{
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset-password')->with(['token' => $token, 'email' => $request->email]);
    }

    public function reset(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email',
                'password' => 'required|confirmed|min:8',
                'token' => 'required',
                'otp' => 'required|integer',
            ],
            [
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Masukkan alamat email yang valid.',
                'password.required' => 'Silakan masukkan kata sandi baru.',
                'password.confirmed' => 'Konfirmasi kata sandi tidak sesuai.',
                'password.min' => 'Kata sandi minimal harus 8 karakter.',
                'token.required' => 'Token wajib diisi.',
                'otp.required' => 'OTP wajib diisi.',
                'otp.integer' => 'OTP harus berupa angka.',
            ],
        );

        // Validasi masa berlaku OTP
        if (!session('otp_time') || now()->diffInMinutes(session('otp_time')) > 15) {
            session()->forget(['otp', 'email', 'otp_time']);
            throw ValidationException::withMessages(['otp' => 'OTP telah kedaluwarsa. Silakan minta OTP baru.']);
        }

        if ($request->otp != session('otp') || $request->email != session('email')) {
            throw ValidationException::withMessages(['otp' => 'OTP atau email tidak valid.']);
        }

        $user = \App\Models\User::where('email', $request->email)->first();
        if (!$user) {
            throw ValidationException::withMessages(['email' => 'Pengguna dengan email ini tidak ditemukan.']);
        }

        $status = Password::reset($request->only('email', 'password', 'password_confirmation', 'token'), function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password),
            ])->setRememberToken(Str::random(60));
            $user->save();
        });

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages(['otp_time' => 'Gagal mereset password. Silakan coba lagi.']);
        }

        // Hapus OTP dari sesi setelah reset berhasil
        session()->forget(['otp', 'email', 'otp_time']);

        // Redirect ke halaman login dengan pesan sukses
        return redirect()->route('login')->with('msg', 'Password berhasil di-reset. Silakan login.');
    }
}
