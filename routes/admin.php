<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

Route::prefix('admin')->name('admin.')->group(function () {
    // Admin Login Routes
    Route::get('login', [AdminController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminController::class, 'login']);

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'AdminDashboard'])->name('dashboard');
        Route::get('/logout', [AdminController::class, 'AdminDestroy'])->name('logout');
    });
});

require __DIR__.'/auth.php';

