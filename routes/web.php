<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MapController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [MapController::class, 'index'])->name('dashboard');
    Route::get('/daftar-penerima-bantuan', [MapController::class, 'daftarPenerimaBantuan'])->name('penerima.index');
    Route::get('/daftar-tempat-ibadah', [MapController::class, 'daftarTempatIbadah'])->name('ibadah.index');
    Route::post('/map/save', [MapController::class, 'save'])->name('map.save');
    Route::post('/map/update-radius', [MapController::class, 'updateRadius'])->name('map.updateRadius');
    Route::post('/map/edit', [MapController::class, 'edit'])->name('map.edit');
    Route::get('/map/delete', [MapController::class, 'delete'])->name('map.delete');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
