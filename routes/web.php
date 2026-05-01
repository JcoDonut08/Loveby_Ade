<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::view('/products/pastel-donut-box', 'pages.products.show')->name('products.show');
Route::view('/contact', 'pages.contact')->name('contact');
Route::view('/login', 'pages.auth.login')->name('login');
Route::view('/register', 'pages.auth.register')->name('register');
