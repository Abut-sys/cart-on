<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OtpNotification;
use Illuminate\Support\Facades\Session;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register'); // Tampilan pendaftaran
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'unique:users,email', 'email'], // Validasi email
            'password' => ['required', 'min:8'],
            'phone_number' => ['required', 'string', 'unique:users,phone_number'], // Validasi phone_number
        ]);

        // Membuat pengguna baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
        ]);

        // Kirim OTP
        $this->sendOtp($user);

        // Arahkan ke halaman verifikasi OTP
        return redirect()->route('verify-otp')->with('msg', 'Registration successful! Please verify your email with the OTP sent to you.');
    }

    public function showOtpForm()
    {
        return view('auth.verify-otp'); // Tampilan verifikasi OTP
    }

    public function verifyOtp(Request $request)
    {
        // Validasi input
        $request->validate([
            'otp' => 'required|digits:6', // Validasi OTP
            'email' => 'required|email' // Validasi email
        ]);

        // Periksa apakah OTP valid dan tidak kedaluwarsa
        if ($request->otp == session('otp') && $request->email == session('email') && session('otp_created_at') && (time() - session('otp_created_at') < 300)) {
            // OTP valid, set user sebagai terverifikasi
            $user = User::where('email', $request->email)->first();
            $user->is_verified = true; // Ubah status menjadi terverifikasi
            $user->save();

            // Hapus session
            session()->forget('otp');
            session()->forget('email');
            session()->forget('otp_created_at');

            // Arahkan pengguna ke halaman login
            return redirect()->route('login')->with('msg', 'Registration successful! You can now login.');
        }

        // Jika OTP tidak valid
        return redirect()->back()->withErrors(['otp' => 'Invalid or expired OTP!']);
    }

    public function resendOtp(Request $request)
    {
        // Validasi email
        $request->validate(['email' => 'required|email']);

        // Cek apakah email terdaftar
        $user = User::where('email', $request->email)->first();
        if ($user) {
            // Kirim OTP baru
            $this->sendOtp($user);

            return redirect()->route('verify-otp')->with('msg', 'New OTP has been sent to your email.');
        }

        return redirect()->back()->withErrors(['email' => 'Email not registered!']);
    }

    private function sendOtp(User $user)
    {
        // Generate OTP
        $otp = rand(100000, 999999);

        // Kirim OTP ke email
        Notification::send($user, new OtpNotification($otp));

        // Simpan OTP di session
        session(['otp' => $otp, 'email' => $user->email, 'otp_created_at' => time()]); // Simpan waktu pembuatan OTP
    }
}
