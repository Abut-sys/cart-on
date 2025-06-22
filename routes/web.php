<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\AdminChatController;
use App\Http\Controllers\CostumersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ListOrderController;
use App\Http\Controllers\ProductAllController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\OrderReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\WaitingPaymentController;
use App\Http\Controllers\CategoryProductController;
use App\Http\Controllers\ProductReportController;

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

// Route yang sudah login dan bisa diakses oleh admin dan user
Route::middleware('auth')->group(function () {
    Route::get('/set-password', [PasswordController::class, 'create'])->name('password.create');
    Route::post('/set-password', [PasswordController::class, 'store'])->name('password.store');

    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/addresses', [ProfileController::class, 'editAddress'])->name('profile.address.edit');
    Route::post('/profile/addresses', [ProfileController::class, 'addAddress'])->name('profile.address.add');
    Route::delete('/profile/addresses/{id}', [ProfileController::class, 'deleteAddress'])->name('profile.address.delete');
    Route::get('/autocomplete/address', [ProfileController::class, 'autocompleteAddress'])->name('autocomplete.address');

    Route::post('/submit-rating', [RatingController::class, 'store'])->name('rating.store');
    Route::get('/rating/check', [RatingController::class, 'check'])->name('rating.check');

    Route::get('/notifications', [NotificationController::class, 'getNotifications'])->name('getNotifications');
    Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('markAsRead');
    Route::get('/notifications/all', [NotificationController::class, 'showAllNotifications'])->name('allNotifications');

    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
});


// Route untuk user
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/search', [SearchController::class, 'search'])->name('search');

    Route::get('wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('wishlist', [WishlistController::class, 'addToWishlist'])->name('wishlist.add');

    Route::get('/orders/history', [ListOrderController::class, 'history'])->name('orders.history');
    Route::post('/orders/{order}/confirm', [ListOrderController::class, 'confirm'])->name('orders.confirm');
    Route::post('/orders/{order}/rate', [ListOrderController::class, 'rate'])->name('orders.rate');

    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');

    Route::get('/orders/pending', [WaitingPaymentController::class, 'pending'])->name('orders.pending');
    Route::delete('/orders/{order}/cancel', [WaitingPaymentController::class, 'cancel'])->name('orders.cancel');
    Route::post('/order/{order}/pay', [WaitingPaymentController::class, 'triggerPayment'])->name('orders.triggerPayment');
    Route::post('/orders/{order}/check-status', [WaitingPaymentController::class, 'checkAndSyncStatus'])->name('orders.checkStatus');

    Route::get('/claim-voucher', [VoucherController::class, 'claim'])->name('voucher.claim');
    Route::get('/your-vouchers', [VoucherController::class, 'claimedVouchers'])->name('your-vouchers');
    Route::post('/claim/{voucher}', [VoucherController::class, 'claimVoucher'])->name('claim');

    Route::get('cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/selected', [CartController::class, 'checkoutSelected'])->name('cart.selected');
    Route::delete('cart/delete/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('cart/increase/{id}', [CartController::class, 'increase'])->name('cart.increase');
    Route::post('cart/decrease/{id}', [CartController::class, 'decrease'])->name('cart.decrease');

    Route::get('checkout/{id}', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('checkout/process', [CheckoutController::class, 'processPayment'])->name('checkout.process');
    Route::post('/order/notify-created', [CheckoutController::class, 'notifyOrderCreated'])->name('order.notifyCreated');
    Route::post('/cancel-order', [CheckoutController::class, 'cancelOrder'])->name('checkout.cancel');
    Route::post('voucher/check', [CheckoutController::class, 'checkVoucher'])->name('voucher.check');
    Route::post('voucher/updateUsage', [CheckoutController::class, 'updateVoucherUsage'])->name('voucher.updateUsage');
    Route::post('/get-shipping-cost', [CheckoutController::class, 'getShippingCost'])->name('get-shipping-cost');

    Route::post('/status/update-payment', [StatusController::class, 'updatePaymentStatus'])->name('status.update');
});

// Route admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/chat', [AdminChatController::class, 'index'])->name('admin.chat');
    Route::get('/admin/chat/messages/{userId}', [AdminChatController::class, 'getMessages']);
    Route::post('/admin/chat/send', [AdminChatController::class, 'sendMessage'])->name('admin.chat.send');
    
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/filter', [DashboardController::class, 'filterData']);

    Route::get('/admin/profile/edit', [ProfileController::class, 'edit'])->name('admin.profile.edit');


    Route::resource('brands', BrandController::class);

    Route::resource('products', ProductController::class);
    Route::get('reports/products', [ProductReportController::class, 'index'])->name('reports.products.index');
    Route::get('reports/products/export/pdf', [ProductReportController::class, 'exportPdf'])->name('reports.products.export.pdf');
    Route::get('reports/products/export/excel', [ProductReportController::class, 'exportExcel'])->name('reports.products.export.excel');

    Route::resource('categories', CategoryProductController::class);

    Route::resource('vouchers', VoucherController::class);

    Route::resource('orders', OrderController::class);
    Route::put('/orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::patch('/orders/{order}/tracking', [OrderController::class, 'updateTracking'])->name('orders.updateTracking');

    Route::get('/report/orders', [OrderReportController::class, 'index'])->name('report.orders');


    Route::resource('costumers', CostumersController::class);

    Route::resource('informations', InformationController::class);

    Route::put('/orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
});

// Route untuk memastikan pengguna yang belum memverifikasi akun mereka tidak dapat mengakses halaman login
Route::middleware(['guest', 'check.verified'])->group(function () {
    Route::get('login', [LoginController::class, 'create'])
        ->name('login')
        ->middleware('check.verified'); // Tambahkan middleware di sini jika diperlukan
    Route::post('login', [LoginController::class, 'store']);
});
