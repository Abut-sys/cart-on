<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str; // Import Str Class

class LoginController extends Controller
{
    // Menampilkan tampilan login
    public function create()
    {
        return view('auth.login'); // Tampilan login
    }

    // Menangani proses login manual
    public function store(Request $request)
    {
        // Validasi input
        $attributes = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Cek apakah pengguna ada
        $user = User::where('email', $attributes['email'])->first();

        // Jika pengguna ditemukan dan belum terverifikasi
        if ($user && !$user->email_verified_at) {
            // Redirect kembali dengan pesan kesalahan
            return redirect()->back()->withErrors(['email' => 'Your account is not verified. Please check your email for the OTP.']);
        }

        // Coba login
        if (Auth::attempt($attributes)) {
            // Redirect berdasarkan role pengguna
            return $this->redirectUserByRole();
        }

        // Jika login gagal, kembalikan pesan kesalahan
        throw ValidationException::withMessages([
            'email' => 'Your credentials do not match our records.',
        ]);
    }

    // Redirect ke Google untuk login
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // Callback setelah Google mengautentikasi pengguna
    public function GoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            return redirect('login')->withErrors(['msg' => 'Authentication failed']);
        }

        // Mencari atau membuat pengguna baru
        $existingUser = User::where('email', $user->getEmail())->first();

        if ($existingUser) {
            // Jika pengguna sudah ada, login
            Auth::login($existingUser);
        } else {
            // Jika pengguna baru, buat akun
            $newUser = User::create([
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'phone_number' => null, // Google tidak mengembalikan nomor telepon secara default
                'password' => bcrypt(Str::random(16)), // Menggunakan Str::random()
                'role' => 'user', // Atur default role, bisa disesuaikan
                'email_verified_at' => Carbon::now(), // Anggap sudah terverifikasi
                'google_id' => $user->getId(), // Simpan Google ID jika diperlukan
            ]);
            Auth::login($newUser);
            return redirect()->route('password.create');
        }

        if (!$existingUser->password) {
            return redirect()->route('password.create');
        }

        if (Auth::user()->role === 'admin') {
            return redirect()->route('dashboard.index')->with('msg', 'Welcome Admin!');
        }

        return redirect()->route('home.index')->with('msg', 'Welcome User!');;
    }

    // Fungsi untuk redirect pengguna berdasarkan role
    private function redirectUserByRole()
    {
        // Cek role pengguna dan arahkan ke halaman yang sesuai
        if (Auth::user()->role === 'admin') {
            return redirect()->route('dashboard.index')->with('msg', 'Welcome Admin!');
        }

        return redirect()->route('home.index')->with('msg', 'Welcome User!');
    }
}
