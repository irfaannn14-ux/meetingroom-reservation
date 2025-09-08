<?php

use App\Http\Controllers\pengajuanController;
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
    return view('auth.forgotpassword');
});
Route::get('/new_password', function () {
    return view('auth.new_password');
});
Route::get('/verifikasi_email', function () {
    return view('auth.verifikasi_email');
});
Route::get('/dashboard', function () {
    return view('dashboard');
});
Route::get('/list_ruangan', function () {
    return view('list_ruangan');
});
Route::get('/main', function () {
    return view('layout.main');
});
Route::get('/pengajuan', function () {
    return view('pengajuan');
});
