<?php

use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Models\Pengajuan;
use Illuminate\Support\Facades\Route;

// Auth routes (tidak perlu middleware)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Other auth pages
Route::get('/forgotpassword', function () {return view('auth.forgotpassword');});
Route::get('/new_password', function () {return view('auth.new_password');});
Route::get('/verifikasi_email', function () {return view('auth.verifikasi_email');});

// Protected routes (memerlukan login)
Route::middleware(['auth.custom'])->group(function () {
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

    //dashboard
    // Route::get('/dashboard', function () {return view('dashboard');});

    //ruangan
    Route::get('ruangan/index', function(){return view('ruangan.index');});
    Route::get('ruangan/tambah', function(){return view('ruangan.tambah');});

    //pengajuan
    Route::get('/index', function(){return view('pengajuan.index');});
    Route::get('pengajuan/index', function(){return view('pengajuan.index');});
    Route::get('pengajuan/tambah', function(){return view('pengajuan.tambah');});

    //manajemen user
    Route::get('user/',[UserController::class, 'index'])->name('user.index');
    Route::get('user/tambah',[UserController::class, 'tambah'])->name('user.tambah');
    Route::post('user',[UserController::class, 'store'])->name('user.store');
    Route::get('user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('user/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('user/{id}',[UserController::class, 'destroy'])->name('user.destroy');

    //history
    Route::get('/history', function () {return view('history');});
});
