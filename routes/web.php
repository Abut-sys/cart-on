<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\CategoryProductController;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home.index');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
});

Route::middleware(['auth', 'role:user'])->group(function () {
    // Route untuk form edit profile
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');

    // Route untuk update profile
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware(['guest'])->group(function () {

    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store']);

    Route::get('forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('forgot-password');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('forgot-password.send');

    Route::get('otp-verify', [ForgotPasswordController::class, 'showOtpForm'])->name('otp.form');
    Route::post('otp-verify', [ForgotPasswordController::class, 'verifyOtp'])->name('otp.verify');

    Route::get('reset-password', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset.form');
    Route::post('reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');

    Route::get('register', [RegisterController::class, 'create'])->name('register');
    Route::post('register', [RegisterController::class, 'store']);
});

Route::middleware('auth', 'role:admin')->group(function () {
    // Route untuk form edit profile
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');

    // Route untuk update profile
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::resource('brands', BrandController::class);

    Route::resource('categories', CategoryProductController::class);

    Route::resource('vouchers', VoucherController::class);
});

// Route::get('admin', function () {
//     return 'Admin Page';
// })->middleware('auth', 'admin');
