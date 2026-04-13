<?php

use App\Http\Controllers\AreaParkirController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LogAktivitasController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TarifController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('home');
});

Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/home', function () {
        return view('home');
    })->name('home');

    // ── Admin ──────────────────────────────────────
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('tarif', TarifController::class)->except(['show']);
    Route::resource('area-parkir', AreaParkirController::class)->except(['show']);
    Route::resource('kendaraan', KendaraanController::class)->except(['show']);
    Route::get('log-aktivitas', [LogAktivitasController::class, 'index'])->name('log-aktivitas.index');

    // ── Petugas ────────────────────────────────────
    Route::resource('transaksi', TransaksiController::class)->except(['show', 'destroy']);
    Route::get('transaksi/{transaksi}/tiket', [TransaksiController::class, 'tiket'])->name('transaksi.tiket');
    Route::get('transaksi/{transaksi}/struk', [TransaksiController::class, 'struk'])->name('transaksi.struk');

    // ── Owner ──────────────────────────────────────
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
});
