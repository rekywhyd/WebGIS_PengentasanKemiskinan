<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MapController;
use App\Http\Controllers\PersetujuanController;

use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\KuponController;
use App\Http\Controllers\KonfirmasiController;

Route::get('/', function () {
    return view('welcome');
});

// Public routes (tanpa auth) - Konfirmasi penyerahan via QR
Route::get('/konfirmasi', [KonfirmasiController::class, 'show'])->name('konfirmasi.show');
Route::post('/konfirmasi', [KonfirmasiController::class, 'process'])->name('konfirmasi.process');

// Public routes (tanpa auth) - Pelaporan Warga
Route::get('/lapor/{kode_lapor}', [\App\Http\Controllers\LaporanController::class, 'create'])->name('lapor.create');
Route::post('/lapor/{kode_lapor}', [\App\Http\Controllers\LaporanController::class, 'store'])->name('lapor.store');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [MapController::class, 'index'])->name('dashboard');
    Route::get('/daftar-penerima-bantuan', [MapController::class, 'daftarPenerimaBantuan'])->name('penerima.index');
    Route::get('/daftar-tempat-ibadah', [MapController::class, 'daftarTempatIbadah'])->name('ibadah.index');
    Route::post('/map/save', [MapController::class, 'save'])->name('map.save');
    Route::post('/map/update-radius', [MapController::class, 'updateRadius'])->name('map.updateRadius');
    Route::post('/map/edit', [MapController::class, 'edit'])->name('map.edit');
    Route::get('/map/delete', [MapController::class, 'delete'])->name('map.delete');


    Route::post('/persetujuan/{id}', [PersetujuanController::class, 'updateStatus'])->name('persetujuan.update');

    // Riwayat
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
    Route::get('/riwayat/konfirmasi/{id_log}', [RiwayatController::class, 'konfirmasiPenyaluran'])->name('riwayat.konfirmasi');
    Route::get('/riwayat/cetak-pdf', [RiwayatController::class, 'cetakPdf'])->name('riwayat.cetakPdf');

    // Kupon
    Route::get('/kupon/{id}', [KuponController::class, 'show'])->name('kupon.show');
    Route::post('/kupon/cetak', [KuponController::class, 'cetak'])->name('kupon.cetak');

    // Scan Kupon
    Route::get('/scan', [KonfirmasiController::class, 'scan'])->name('scan.index');

    // Laporan Warga
    Route::get('/laporan-warga', [\App\Http\Controllers\LaporanController::class, 'index'])->name('laporan.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
