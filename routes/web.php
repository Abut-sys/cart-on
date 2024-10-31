<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ForgotPasswordController;

/*
|--------------------------------------------------------------------------
// Rest of your routes
|-------------------------------------------------------------------------- 
*/

// Route untuk halaman utama
Route::get('/', [HomeController::class, 'index'])->name('home.index');

// Route untuk logout yang hanya bisa diakses oleh pengguna yang terautentikasi
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
});

// Route untuk pengguna biasa yang terautentikasi
Route::middleware(['auth', 'role:user'])->group(function () {
    // Route untuk form edit profile
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');

    // Route untuk update profile
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});

// Route untuk pengguna yang tidak terautentikasi
Route::middleware(['guest'])->group(function () {
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store']);

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('forgot-password');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('forgot-password.send');

    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);

    Route::get('/verify-otp', [RegisterController::class, 'showOtpForm'])->name('verify-otp');
    Route::post('/verify-otp', [RegisterController::class, 'verifyOtp'])->name('verify-otp.process');
    Route::post('/otp/resend', [RegisterController::class, 'resendOtp'])->name('otp.resend');
});

// Route untuk pengguna yang terautentikasi dan berperan admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::resource('brands', BrandController::class);
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryProductController::class);
    Route::resource('vouchers', VoucherController::class);
});

// Route untuk memastikan pengguna yang belum memverifikasi akun mereka tidak dapat mengakses halaman login
Route::middleware(['guest', 'check.verified'])->group(function () {
    Route::get('login', [LoginController::class, 'create'])->name('login')->middleware('check.verified'); // Tambahkan middleware di sini jika diperlukan
    Route::post('login', [LoginController::class, 'store']);
});
