<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        Auth::logout();

        // Optional: Invalidate the user's session
        $request->session()->invalidate();

        // Optional: Regenerate the session token to prevent session fixation attacks
        $request->session()->regenerateToken();

        session()->forget('wishlist');

        return redirect('/');
    }
}
