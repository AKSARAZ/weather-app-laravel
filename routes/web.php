<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController; // Controller baru kita
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\User\RequestController;

// Rute utama yang mengarahkan pengguna berdasarkan peran
Route::get('/', function () {
    if (!auth()->check()) return redirect()->route('login');
    return auth()->user()->is_admin ? redirect()->route('admin.dashboard') : redirect()->route('dashboard');
});

// Rute untuk pengguna yang sudah login
Route::middleware('auth')->group(function () {
    // Dashboard User (Simulasi)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Endpoint AJAX untuk mendapatkan data cuaca
    Route::post('/get-weather', [DashboardController::class, 'getWeatherForSimulation'])->name('weather.get');

    // Rute Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Rute untuk MENAMPILKAN halaman form
    Route::get('/request-installation', [RequestController::class, 'create'])->name('request.create');
    // Rute untuk MENYIMPAN data dari form
    Route::post('/request-installation', [RequestController::class, 'store'])->name('request.store');

    // --- RUTE BARU UNTUK HALAMAN RIWAYAT/STATUS ---
    Route::get('/my-requests', [RequestController::class, 'history'])->name('requests.history');

    // --- RUTE BARU UNTUK EDIT & HAPUS ---
    Route::get('/my-requests/{request}/edit', [RequestController::class, 'edit'])->name('requests.edit');
    Route::put('/my-requests/{request}', [RequestController::class, 'update'])->name('requests.update');
    Route::delete('/my-requests/{request}', [RequestController::class, 'destroy'])->name('requests.destroy');
    // --- RUTE UNTUK MENAMPILKAN HALAMAN DETAIL ---
    Route::get('/my-requests/{request}', [RequestController::class, 'show'])->name('requests.show');    
});

// --- RUTE KHUSUS ADMIN ---
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Rute BARU untuk halaman utama dashboard admin
    Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('dashboard');
    
    // Rute resource untuk mengelola SEMUA request (CRUD)
    Route::resource('requests', AdminDashboardController::class);
});

require __DIR__.'/auth.php';