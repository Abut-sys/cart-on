<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckIfVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->email_verified_at === null) {
            // Redirect ke halaman verifikasi jika pengguna belum terverifikasi
            return redirect()->route('verify-otp')->with('msg', 'Please verify your account before proceeding.');
        }
        return $next($request);
    }
}
