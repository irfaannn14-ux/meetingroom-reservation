<?php

use App\Http\Controllers\pengajuanController;
use App\Http\Controllers\RuanganController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
});

// default route
Route::get('/welcome', function () {
    return view('welcome');
});

//sidebar, navbar, dan main
Route::get('/sidebar', function () {return view('sidebar.sidebar');});
Route::get('/navbar', function () {return view('navbar.navbar');});
Route::get('/main', function () {return view('layout.main');});

// Login
Route::get('/login', function () {return view('login');});
Route::get('/forgotpassword', function () {return view('auth.forgotpassword');});
Route::get('/new_password', function () {return view('auth.new_password');});
Route::get('/verifikasi_email', function () {return view('auth.verifikasi_email');});

//dashboard
// Route::get('/dashboard', function () {return view('dashboard');});

//ruangan
Route::get('ruangan/',[RuanganController::class, 'index'])->name('ruangan.index');
Route::get('ruangan/tambah',[RuanganController::class, 'tambah'])->name('ruangan.tambah');
Route::post('ruangan',[RuanganController::class, 'store'])->name('ruangan.store');
Route::get('ruangan/{id}/edit',[RuanganController::class, 'edit'])->name('ruangan.edit');
Route::put('ruangan/{id}',[RuanganController::class, 'update'])->name('ruangan.update');
Route::delete('ruangan/{id}',[RuanganController::class, 'destroy'])->name('ruangan.destroy');

//pengajuan
Route::get('/listdata', function () {return view('pengajuan.listdata');});
Route::get('/pengajuan', function () {return view('pengajuan');});

//user
Route::get('/user', function () {return view('user');});
