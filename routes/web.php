<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SKController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KategoriSKController;
// ... imports
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\LegalisirController;
use App\Http\Controllers\KlasifikasiArsipController;

// ... after existing routes

// Activity Logs
Route::middleware(['auth'])->group(function () {
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity_logs.index');
    
    // Legalisir Routes (Disabled)
    // Route::get('/legalisir', [LegalisirController::class, 'index'])->name('legalisir.index');
    // Manajemen Klasifikasi (Admin)
    // Manajemen Klasifikasi (Admin)
    Route::group(['prefix' => 'admin', 'middleware' => function ($request, $next) {
        if (auth()->check() && auth()->user()->role === 'admin') {
            return $next($request);
        }
        return redirect('/'); // or abort(403)
    }], function () {
        Route::get('/klasifikasi', [KlasifikasiArsipController::class, 'index'])->name('klasifikasi.index');
        Route::post('/klasifikasi', [KlasifikasiArsipController::class, 'store'])->name('klasifikasi.store');
        Route::put('/klasifikasi/{id}', [KlasifikasiArsipController::class, 'update'])->name('klasifikasi.update');
        Route::delete('/klasifikasi/{id}', [KlasifikasiArsipController::class, 'destroy'])->name('klasifikasi.destroy');

        // Admin Resources
        Route::resource('kategori-sks', KategoriSKController::class);
        Route::resource('users', UserManagementController::class);
    });

    // Public search for Create SK form
    Route::get('/klasifikasi/search', [KlasifikasiArsipController::class, 'search'])->name('klasifikasi.search');
    // Route::patch('/legalisir/{id}/status', [LegalisirController::class, 'updateStatus'])->name('legalisir.update_status');
});

// 🔹 Download file SK - dengan middleware auth

// 🔹 Halaman utama / Dashboard
Route::get('/', [SKController::class, 'dashboard'])->middleware('auth')->name('dashboard');

// 🔹 Autentikasi
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'processLogin'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 🔹 CRUD Surat Keputusan (SK) - dengan middleware auth
Route::prefix('/sk')->middleware('auth')->group(function () {
    Route::get('/create', [SKController::class, 'create'])->name('sk.create');
    Route::post('/store', [SKController::class, 'store'])->name('sk.store');
    Route::get('/arsip', [SKController::class, 'archive'])->name('sk.archive');
    Route::post('/update', [SKController::class, 'update'])->name('sk.update');
    Route::delete('/destroy/{id}', [SKController::class, 'destroy'])->name('sk.destroy');
});

// routes/web.php
Route::get('/sk/export', [SKController::class, 'export'])->name('sk.export');


Route::get('/sk/view/{id}', [SKController::class, 'viewPdf'])->name('sk.view');

// Hapus SK
Route::post('/sk/delete', [SKController::class, 'delete'])->name('sk.delete');

Route::middleware(['auth'])->group(function () {
   // Resources moved to admin group
});


// 🔹 Download file SK - dengan middleware auth
Route::get('/download', [SKController::class, 'download'])->middleware('auth')->name('download.pdf');
