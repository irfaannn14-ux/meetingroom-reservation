<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('Home');
});

// default route
Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/sidebar', function () {
    return view('sidebar.sidebar');
});

Route::get('/navbar', function () {
    return view('navbar.navbar');
});

Route::get('/forgotpassword', function () {
    return view('forgotpassword.forgotpassword');
});
Route::get('/new_password', function () {
    return view('forgotpassword.new_password');
});
Route::get('/verifikasi_email', function () {
    return view('forgotpassword.verifikasi_email');
});
