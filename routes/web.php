<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryProductController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\VoucherController;

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

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('home');
    });

    Route::get('/home', function () {
        return view('home');
    });

    Route::resource('brands', BrandController::class);

    Route::resource('categories', CategoryProductController::class);

    Route::resource('vouchers', VoucherController::class);

    // Route untuk form edit profile
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');

    // Route untuk update profile
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
});

Route::middleware(['guest'])->group(function () {
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store']);

    Route::get('register', [RegisterController::class, 'create'])->name('register');
    Route::post('register', [RegisterController::class, 'store']);

    // Route::get('/kirimemail', [MalasngodingController::class, 'index']);
});

// Route::get('admin', function () {
//     return 'Admin Page';
// })->middleware('auth', 'admin');
