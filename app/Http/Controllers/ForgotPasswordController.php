<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Mail\OtpMail;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    // Show the forgot password form
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    // Handle the sending of the reset link (OTP)
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Generate a 6-digit OTP
        $otp = rand(100000, 999999);

        // Send OTP via email using the OtpMail Mailable class
        Mail::to($request->email)->send(new OtpMail($otp));

        // Store OTP and email in session with an expiration time
        session(['otp' => $otp, 'email' => $request->email, 'otp_expires_at' => now()->addMinutes(10)]);

        // Redirect to the OTP verification form with a message
        return redirect()->route('otp.form')->with('msg', 'An OTP has been sent to your email address.');
    }

    // Show the OTP verification form
    public function showOtpForm()
    {
        return view('auth.otp-verify');
    }

    // Verify the OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|integer', // Keep the OTP validation
        ]);

        // Check if the OTP matches the one stored in the session and is still valid
        if (session('otp') == $request->otp && now()->lessThan(session('otp_expires_at'))) {
            // OTP is verified, proceed to reset password
            session(['otp_verified' => true]);  // Mark OTP as verified
            return redirect()->route('password.reset.form');
        } else {
            return back()->withErrors(['otp' => 'Invalid OTP or OTP expired.']);
        }
    }

    // Show the reset password form
    public function showResetForm()
    {
        // Check if OTP is verified
        if (session('otp_verified')) {
            return view('auth.reset-password');
        }
        return redirect()->route('forgot-password');
    }

    // Handle the password reset
    public function reset(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed|min:8'
        ]);

        // Find the user by email
        $user = User::where('email', session('email'))->first();

        if ($user) {
            // Update the user's password
            $user->password = Hash::make($request->password);
            $user->save();

            // Clear session data after reset
            Session::forget(['otp', 'email', 'otp_expires_at', 'otp_verified']);

            return redirect()->route('login')->with('status', 'Password has been reset successfully.');
        }

        return back()->withErrors(['email' => 'Unable to reset password for the provided email.']);
    }
}
