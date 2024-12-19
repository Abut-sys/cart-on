<?php

use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CostumersController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ProductAllController;
use App\Http\Controllers\WishlistController;

/*
|--------------------------------------------------------------------------
// Rest of your routes
|--------------------------------------------------------------------------
*/

// Route guest and user
Route::middleware(['guestOrUserOnly'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home.index');

    Route::get('products-all', [ProductAllController::class, 'index'])->name('products-all.index');
    Route::get('products-all/{id}', [ProductAllController::class, 'show'])->name('products-all.show');
    Route::post('products-all/stock', [ProductAllController::class, 'getStock'])->name('products-all.getStock');
});

// Route yang sudah login dan bisa diakses oleh admin dan user
Route::middleware('auth')->group(function () {
    Route::get('/set-password', [PasswordController::class, 'create'])->name('password.create');
    Route::post('/set-password', [PasswordController::class, 'store'])->name('password.store');

    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/addresses', [ProfileController::class, 'editAddress'])->name('profile.address.edit');
    Route::post('/profile/addresses', [ProfileController::class, 'addAddress'])->name('profile.address.add');
    Route::delete('/profile/addresses/{id}', [ProfileController::class, 'deleteAddress'])->name('profile.address.delete');

    Route::get('/notifications', [NotificationController::class, 'getNotifications'])->name('getNotifications');
    Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('markAsRead');
    Route::get('/notifications/all', [NotificationController::class, 'showAllNotifications'])->name('allNotifications');

    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
});

// Route untuk user
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('wishlist', [WishlistController::class, 'addToWishlist'])->name('wishlist.add');
    Route::get('cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('cart', [CartController::class, 'addToCart'])->name('cart.add');
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::delete('/cart/{id}/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/increase/{id}', [CartController::class, 'increase'])->name('cart.increase');
    Route::post('/cart/decrease/{id}', [CartController::class, 'decrease'])->name('cart.decrease');
    Route::get('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');


    Route::get('checkout/{id}', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('checkout/process', [CheckoutController::class, 'processPayment'])->name('checkout.process');
});

// Route guest
Route::middleware(['guest'])->group(function () {
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store']);

    Route::get('google', [LoginController::class, 'redirect'])->name('google.redirect');
    Route::get('google/callback', [LoginController::class, 'GoogleCallback'])->name('google.callback');

    Route::get('forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('forgot-password');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('forgot-password.send');

    Route::get('otp-verify', [ForgotPasswordController::class, 'showOtpForm'])->name('otp.form');
    Route::post('otp-verify', [ForgotPasswordController::class, 'verifyOtp'])->name('otp.verify');

    Route::get('reset-password', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset.form');
    Route::post('reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');

    Route::get('register', [RegisterController::class, 'create'])->name('register');
    Route::post('register', [RegisterController::class, 'store']);

    Route::get('/verify-otp', [RegisterController::class, 'showOtpForm'])->name('verify-otp');
    Route::post('/verify-otp', [RegisterController::class, 'verifyOtp'])->name('verify-otp.process');
    Route::post('/otp/resend', [RegisterController::class, 'resendOtp'])->name('otp.resend');

    // Route::get('/kirimemail', [MalasngodingController::class, 'index']);
});

// Route admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::get('/admin/profile/edit', [ProfileController::class, 'edit'])->name('admin.profile.edit');

    Route::resource('brands', BrandController::class);

    Route::resource('products', ProductController::class);

    Route::resource('categories', CategoryProductController::class);

    Route::resource('vouchers', VoucherController::class);

    Route::resource('orders', OrderController::class);

    Route::resource('costumers', CostumersController::class);

    Route::resource('informations', InformationController::class);
});

// Route untuk memastikan pengguna yang belum memverifikasi akun mereka tidak dapat mengakses halaman login
Route::middleware(['guest', 'check.verified'])->group(function () {
    Route::get('login', [LoginController::class, 'create'])
        ->name('login')
        ->middleware('check.verified'); // Tambahkan middleware di sini jika diperlukan
    Route::post('login', [LoginController::class, 'store']);
});


