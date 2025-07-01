<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OtpNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register'); // Registration view
    }

    public function store(Request $request)
    {
        Log::info('Registration attempt', $request->all());

        // Custom validation: at least one of email or phone_number is required
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'unique:users,email'],
            'phone_number' => ['nullable', 'string', 'unique:users,phone_number'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Check if at least one contact method is provided
        if (empty($request->email) && empty($request->phone_number)) {
            return redirect()
                ->back()
                ->withErrors([
                    'contact' => 'Please provide either an email address or phone number.',
                ])
                ->withInput();
        }

        // Additional validation for uniqueness only if the fields are provided
        if ($request->email) {
            $existingEmailUser = User::where('email', $request->email)->first();
            if ($existingEmailUser) {
                return redirect()
                    ->back()
                    ->withErrors([
                        'email' => 'The email has already been taken.',
                    ])
                    ->withInput();
            }
        }

        if ($request->phone_number) {
            $existingPhoneUser = User::where('phone_number', $request->phone_number)->first();
            if ($existingPhoneUser) {
                return redirect()
                    ->back()
                    ->withErrors([
                        'phone_number' => 'The phone number has already been taken.',
                    ])
                    ->withInput();
            }
        }

        // Create new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
        ]);

        // Send OTP based on provided contact method
        $this->sendOtp($user);

        // Store user identifier in session for OTP verification
        if ($user->email) {
            session(['verification_email' => $user->email]);
        }
        if ($user->phone_number) {
            session(['verification_phone' => $user->phone_number]);
        }

        // Redirect to OTP verification page
        return redirect()->route('verify-otp');
    }

    public function showOtpForm()
    {
        // Check if we have either email or phone in session
        if (!session('verification_email') && !session('verification_phone')) {
            return redirect()
                ->route('register')
                ->withErrors(['error' => 'Please register first.']);
        }

        return view('auth.verify-otp'); // OTP verification view
    }

    public function verifyOtp(Request $request)
    {
        // Validate input
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        // Get the contact method from session
        $email = session('verification_email');
        $phone = session('verification_phone');

        // Check if OTP is valid and not expired
        if ($request->otp == session('otp') && session('otp_created_at') && time() - session('otp_created_at') < 300) {
            // Find user by email or phone
            $user = null;
            if ($email) {
                $user = User::where('email', $email)->first();
            } elseif ($phone) {
                $user = User::where('phone_number', $phone)->first();
            }

            if ($user) {
                // OTP is valid, mark user as verified
                if ($email) {
                    $user->email_verified_at = now();
                } else {
                    $user->phone_verified_at = now(); // You might need to add this column to users table
                }
                $user->save();

                // Clear session
                session()->forget(['otp', 'verification_email', 'verification_phone', 'otp_created_at']);

                // Redirect user to login page
                return redirect()->route('login')->with('success', 'Registration successful! You can now login.');
            }
        }

        // If OTP is not valid
        return redirect()
            ->back()
            ->withErrors(['otp' => 'Invalid or expired OTP!']);
    }

    public function resendOtp(Request $request)
    {
        // Get contact method from session
        $email = session('verification_email');
        $phone = session('verification_phone');

        // Find user by email or phone
        $user = null;
        if ($email) {
            $user = User::where('email', $email)->first();
        } elseif ($phone) {
            $user = User::where('phone_number', $phone)->first();
        }

        if ($user) {
            // Send new OTP
            $this->sendOtp($user);
            return redirect()->route('verify-otp')->with('success', 'OTP has been resent!');
        }

        return redirect()
            ->back()
            ->withErrors(['error' => 'User not found!']);
    }

    private function sendOtp(User $user)
    {
        // Generate OTP
        $otp = rand(100000, 999999);

        // Determine which contact method to use for OTP
        if ($user->email) {
            // Send OTP to email
            Notification::send($user, new OtpNotification($otp, 'email'));
            $contact_method = 'email';
        } elseif ($user->phone_number) {
            // Send OTP to phone (you'll need to implement SMS notification)
            // For now, we'll use email notification but you can modify this
            Notification::send($user, new OtpNotification($otp, 'sms'));
            $contact_method = 'phone';
        }

        // Store OTP in session
        session([
            'otp' => $otp,
            'otp_created_at' => time(),
            'otp_method' => $contact_method,
        ]);
    }
}
