<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::view('/products/pastel-donut-box', 'pages.products.show')->name('products.show');
Route::view('/contact', 'pages.contact')->name('contact');
Route::view('/login', 'pages.auth.login')->name('login');
Route::view('/login/otp', 'pages.auth.login_otp')->name('login.otp');
Route::view('/register', 'pages.auth.register')->name('register');
Route::view('/forgot-password', 'pages.auth.forgot_password')->name('password.request');
Route::view('/password/otp', 'pages.auth.otp')->name('password.otp');

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
