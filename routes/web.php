<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route; // Pastikan ini ada

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sini tempat Anda bisa mendefinisikan rute web aplikasi Anda.
|
*/

// Rute Default - Arahkan ke Login atau ke rute yang aman
Route::get('/', function () {
    return redirect()->route('login');
});

// Grup Rute yang HANYA membutuhkan autentikasi (user sudah login)
Route::middleware(['auth'])->group(function () {
    
    // Rute Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rute Folder
    Route::get('/folders/{folder?}', [FolderController::class, 'index'])->name('folders.index');
    Route::post('/folders', [FolderController::class, 'store'])->name('folders.store');
    
    // Rute File
    Route::post('/files', [FileController::class, 'store'])->name('files.store');
    // Jika Anda ingin user bisa mendownload file, tambahkan rute ini:
    // Route::get('/files/download/{file}', [FileController::class, 'download'])->name('files.download');

});

// Rute Autentikasi (Login, Register, dsb.)
require __DIR__.'/auth.php';