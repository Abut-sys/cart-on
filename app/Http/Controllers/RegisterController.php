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
        return view('auth.register'); // Registration view
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

        // Create new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
        ]);

        // Send OTP
        $this->sendOtp($user);

        // Redirect to OTP verification page
        return redirect()->route('verify-otp')->with('msg', 'Registration successful! Please verify your email with the OTP sent to you.');
    }

    public function showOtpForm()
    {
        return view('auth.verify-otp'); // OTP verification view
    }

    public function verifyOtp(Request $request)
    {
        // Validate input
        $request->validate([
            'otp' => 'required|digits:6', // OTP validation
            'email' => 'required|email' // Email validation
        ]);

        // Check if OTP is valid and not expired
        if ($request->otp == session('otp') && $request->email == session('email') && session('otp_created_at') && (time() - session('otp_created_at') < 300)) {
            // OTP is valid, mark user as verified
            $user = User::where('email', $request->email)->first();
            $user->email_verified_at = now(); // Set the email_verified_at timestamp
            $user->save();

            // Clear session
            session()->forget(['otp', 'email', 'otp_created_at']);

            // Redirect user to login page
            return redirect()->route('login')->with('msg', 'Registration successful! You can now login.');
        }

        // If OTP is not valid
        return redirect()->back()->withErrors(['otp' => 'Invalid or expired OTP!']);
    }

    public function resendOtp(Request $request)
    {
        // Validate email
        $request->validate(['email' => 'required|email']);

        // Check if email is registered
        $user = User::where('email', $request->email)->first();
        if ($user) {
            // Send new OTP
            $this->sendOtp($user);

            return redirect()->route('verify-otp')->with('msg', 'New OTP has been sent to your email.');
        }

        return redirect()->back()->withErrors(['email' => 'Email not registered!']);
    }

    private function sendOtp(User $user)
    {
        // Generate OTP
        $otp = rand(100000, 999999);

        // Send OTP to email
        Notification::send($user, new OtpNotification($otp));

        // Store OTP in session
        session(['otp' => $otp, 'email' => $user->email, 'otp_created_at' => time()]); // Store OTP creation time
    }
}
