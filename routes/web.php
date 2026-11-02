<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\PetugasController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MahasiswaController;
use App\Http\Controllers\Admin\PengaduanController;
use App\Http\Controllers\Admin\TanggapanController;

// Mahasiswa
Route::get('/', [UserController::class, 'index'])->name('pewa.index');

Route::post('/login/auth', [UserController::class, 'login'])->name('pewa.login');

Route::get('/register', [UserController::class, 'formRegister'])->name('pewa.formRegister');
Route::post('/register/auth', [UserController::class, 'register'])->name('pewa.register');

Route::post('/store', [UserController::class, 'storePengaduan'])->name('pewa.store');
Route::get('/laporan/{siapa?}', [UserController::class, 'laporan'])->name('pewa.laporan');

Route::get('/logout', [UserController::class, 'logout'])->name('pewa.logout');

Route::prefix('admin')->group(function(){
    
    Route::get('/', [AdminController::class, 'formLogin'])->name('admin.formLogin');
    Route::post('/login', [AdminController::class, 'login'])->name('admin.login');
    Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::resource('pengaduan', PengaduanController::class);
    Route::resource('petugas', PetugasController::class);
    Route::resource('mahasiswa', MahasiswaController::class);
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index'); 

    Route::post('tanggapan/createOrUpdate', [TanggapanController::class, 'createOrUpdate'])->name('tanggapan.createOrUpdate');

    Route::post('getLaporan', [LaporanController::class, 'getLaporan'])->name('laporan.getLaporan');

    Route::get('laporan/cetak/{from}/{to}', [LaporanController::class, 'cetakLaporan'])->name('laporan.cetakLaporan');
});
