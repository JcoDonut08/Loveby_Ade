<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\PasswordResetOtpController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/pastel-donut-box', [ProductController::class, 'showDefault'])->name('products.show');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show-by-slug');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');
Route::get('/search/suggestions', SearchController::class)->name('search.suggestions');
Route::delete('/search/recent', [SearchController::class, 'destroyRecent'])->name('search.recent.destroy');
Route::view('/notifications', 'pages.notifications')->name('notifications');
Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites');
Route::get('/favorites/summary', [FavoriteController::class, 'summary'])->name('favorites.summary');
Route::post('/favorites/items', [FavoriteController::class, 'store'])->name('favorites.items.store');
Route::delete('/favorites/items/{slug}', [FavoriteController::class, 'destroy'])->name('favorites.items.destroy');
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::get('/cart/summary', [CartController::class, 'summary'])->name('cart.summary');
Route::post('/cart/items', [CartController::class, 'store'])->name('cart.items.store');
Route::patch('/cart/items/{slug}', [CartController::class, 'update'])->name('cart.items.update');
Route::delete('/cart/items/{slug}', [CartController::class, 'destroy'])->name('cart.items.destroy');
Route::view('/orders/confirmed', 'pages.orders.confirmed')->name('orders.confirmed');
Route::middleware('auth')->group(function (): void {
    Route::get('/account', [AccountController::class, 'index'])->name('account');
    Route::patch('/account', [AccountController::class, 'update'])->name('account.update');
    Route::view('/orders', 'pages.orders.index')->name('orders.index');
    Route::view('/delivered-products', 'pages.delivered_products')->name('delivered-products.index');
    Route::post('/logout', LogoutController::class)->name('logout');
});

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'show'])->name('login');

    Route::post('/login', [LoginController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('login.store');

    Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])
        ->middleware('throttle:10,1')
        ->name('auth.google.redirect');

    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])
        ->middleware('throttle:10,1')
        ->name('auth.google.callback');

    Route::get('/register', [RegisteredUserController::class, 'show'])->name('register');

    Route::post('/register', [RegisteredUserController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('register.store');

    Route::get('/register/otp', [RegisteredUserController::class, 'showOtp'])
        ->name('register.otp');

    Route::post('/register/otp', [RegisteredUserController::class, 'verifyOtp'])
        ->middleware('throttle:5,1')
        ->name('register.otp.verify');

    Route::post('/register/otp/resend', [RegisteredUserController::class, 'resendOtp'])
        ->middleware('throttle:3,1')
        ->name('register.otp.resend');

    Route::get('/forgot-password', [PasswordResetOtpController::class, 'showEmailForm'])
        ->name('password.request');

    Route::post('/forgot-password', [PasswordResetOtpController::class, 'sendOtp'])
        ->middleware('throttle:5,1')
        ->name('password.email');

    Route::get('/password/otp', [PasswordResetOtpController::class, 'showOtp'])
        ->name('password.otp');

    Route::post('/password/otp', [PasswordResetOtpController::class, 'verifyOtp'])
        ->middleware('throttle:5,1')
        ->name('password.otp.verify');

    Route::post('/password/otp/resend', [PasswordResetOtpController::class, 'resendOtp'])
        ->middleware('throttle:3,1')
        ->name('password.otp.resend');

    Route::get('/password/reset', [PasswordResetOtpController::class, 'showResetForm'])
        ->name('password.reset');

    Route::put('/password/reset', [PasswordResetOtpController::class, 'reset'])
        ->middleware('throttle:5,1')
        ->name('password.update');
});

Route::redirect('/admin', '/admin/dashboard')->name('admin.home');
Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::view('/dashboard', 'pages.admin.dashboard')->name('dashboard');
    Route::view('/orders', 'pages.admin.orders')->name('orders');
    Route::view('/products', 'pages.admin.products')->name('products');
    Route::view('/customers', 'pages.admin.customers')->name('customers');
    Route::view('/promotions', 'pages.admin.promotions')->name('promotions');
    Route::view('/chat-inbox', 'pages.admin.chat_inbox')->name('chat-inbox');
    Route::view('/notifications', 'pages.admin.notifications')->name('notifications');
    Route::view('/analytics', 'pages.admin.analytics')->name('analytics');
    Route::view('/reports', 'pages.admin.reports')->name('reports');
    Route::view('/account', 'pages.admin.account')->name('account');
});
