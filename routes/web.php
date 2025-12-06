<?php

use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\NotificationController;
use App\Models\Pengajuan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PresensiController;


// Auth routes (tidak perlu middleware)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Test GD Extension route
Route::get('/test-gd-check', function() {
    $isGdLoaded = extension_loaded('gd');
    return response()->json([
        'gd_extension' => $isGdLoaded ? 'AKTIF ✅' : 'TIDAK AKTIF ❌',
        'gd_info' => $isGdLoaded ? gd_info() : null,
        'php_version' => PHP_VERSION,
        'instruction' => !$isGdLoaded ? 'Edit C:\xampp\php\php.ini, uncomment extension=gd, restart Apache' : 'GD sudah aktif, TTD akan muncul di PDF!'
    ]);
});

// Other auth pages
Route::get('/forgotpassword', function () {
    return view('auth.forgotpassword');
});
Route::get('/new_password', function () {
    return view('auth.new_password');
});
Route::get('/verifikasi_email', function () {
    return view('auth.verifikasi_email');
});

// Protected routes (memerlukan login)
Route::middleware(['auth.custom'])->group(function () {
    Route::get('/', [PengajuanController::class, 'dashboard'])->name('dashboard');

    // Profile routes (for all logged-in users)
    Route::get('/profile', [UserController::class, 'showProfile'])->name('profile.show');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');

    // default route
    Route::get('/welcome', function () {
        return view('welcome');
    });

    //sidebar, navbar, dan main
    Route::get('/sidebar', function () {
        return view('sidebar.sidebar');
    });
    Route::get('/navbar', function () {
        return view('navbar.navbar');
    });
    Route::get('/main', function () {
        return view('layout.main');
    });

    //ruangan
    Route::get('ruangan/index', [RuanganController::class, 'index'])->name('ruangan.index');
    Route::get('ruangan/tambah', [RuanganController::class, 'tambah'])->name('ruangan.tambah');
    Route::post('ruangan/tambah', [RuanganController::class, 'store'])->name('ruangan.store');
    Route::get('ruangan/{ruangan}/edit', [RuanganController::class, 'edit'])->name('ruangan.edit');
    Route::put('ruangan/{ruangan}', [RuanganController::class, 'update'])->name('ruangan.update');
    Route::delete('ruangan/{ruangan}', [RuanganController::class, 'destroy'])->name('ruangan.destroy');

    // pengajuan
    Route::get('/index', [PengajuanController::class, 'index'])->name('pengajuan.index');
    Route::get('pengajuan/tambah', [PengajuanController::class, 'tambah'])->name('pengajuan.tambah');
    Route::post('pengajuan/tambah', [PengajuanController::class, 'store'])->name('pengajuan.store');
    Route::get('pengajuan/{pengajuan}/edit', [PengajuanController::class, 'edit'])->name('pengajuan.edit');
    Route::put('pengajuan/{pengajuan}', [PengajuanController::class, 'update'])->name('pengajuan.update');
    Route::delete('pengajuan/{pengajuan}', [PengajuanController::class, 'destroy'])->name('pengajuan.destroy');
    Route::post('pengajuan/{pengajuan}/status', [PengajuanController::class, 'updateStatus'])->name('pengajuan.updateStatus');
    Route::get('/calendar-events', [PengajuanController::class, 'calendarEvents'])->name('calendar.events');

    //manajemen user
    Route::group(['middleware' => 'admin.access'], function () {
        Route::get('user/', [UserController::class, 'index'])->name('user.index');
        Route::get('user/tambah', [UserController::class, 'create'])->name('user.tambah');
        Route::post('user', [UserController::class, 'store'])->name('user.store');
        Route::get('user/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
        Route::put('user/{user}', [UserController::class, 'update'])->name('user.update');
        Route::delete('user/{user}', [UserController::class, 'destroy'])->name('user.destroy');
    });

    //history
    Route::get('/history', [PengajuanController::class, 'history'])->name('history');
    Route::get('/pengajuan/{id}/qrcode', [PengajuanController::class, 'generateQrCode']);

    // Log Aktivitas
    Route::get('/log-aktivitas', [ActivityLogController::class, 'index'])->name('log.index');

    // Notifications
    Route::get('/api/notifications', [NotificationController::class, 'getNotifications'])->name('notifications.get');

    // Presensi
    Route::get('/presensi/{id}', [PresensiController::class, 'create'])->name('presensi.create');
    Route::post('/presensi', [PresensiController::class, 'store'])->name('presensi.store');

    Route::get('/presensi/{pengajuan}/data', [PresensiController::class, 'show'])->name('presensi.show');

    Route::get('/presensi/{pengajuanId}/ttd/all', [PresensiController::class, 'downloadAllTtd'])->name('presensi.ttd.all');
});
