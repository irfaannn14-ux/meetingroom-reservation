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
    Route::get('ruangan/index', [RuanganController::class, 'index'])->name('ruangan.index');
    Route::get('ruangan/tambah', [RuanganController::class, 'tambah'])->name('ruangan.tambah');
    Route::post('ruangan/tambah', [RuanganController::class, 'store'])->name('ruangan.store');
    Route::get('ruangan/{id}/edit', [RuanganController::class, 'edit'])->name('ruangan.edit');
    Route::put('ruangan/{id}', [RuanganController::class, 'update'])->name('ruangan.update');
    Route::delete('ruangan/{id}', [RuanganController::class, 'destroy'])->name('ruangan.destroy'); // Rute untuk menghapus

    
    //pengajuan
    Route::get('/index', [PengajuanController::class, 'index'])->name('pengajuan.index');
    Route::get('pengajuan/tambah', [PengajuanController::class, 'tambah'])->name('pengajuan.tambah');
    Route::post('pengajuan/tambah', [PengajuanController::class, 'store'])->name('pengajuan.store');
    Route::get('pengajuan/{id}/edit', [PengajuanController::class, 'edit'])->name('pengajuan.edit');
    Route::put('pengajuan/{id}', [PengajuanController::class, 'update'])->name('pengajuan.update');
    Route::delete('pengajuan/{id}', [PengajuanController::class, 'destroy'])->name('pengajuan.destroy');


    //manajemen user
    Route::get('user/',[UserController::class, 'index'])->name('user.index');
    Route::get('user/tambah',[UserController::class, 'create'])->name('user.tambah');
    Route::post('user',[UserController::class, 'store'])->name('user.store');
    Route::get('user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('user/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('user/{id}',[UserController::class, 'destroy'])->name('user.destroy');

    //history
    Route::get('/history', function () {return view('history');});
});
