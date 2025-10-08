<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\AdminProfileController;

Route::prefix('admin')->name('admin.')->group(function () {
    // Admin Login Routes
    Route::get('login', [AdminController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminController::class, 'login']);

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'AdminDashboard'])->name('dashboard');
        Route::get('/logout', [AdminController::class, 'AdminDestroy'])->name('logout');
        Route::get('/profile', [AdminProfileController::class, 'index'])->name('profile');
        Route::post('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
    });
});

require __DIR__.'/auth.php';

