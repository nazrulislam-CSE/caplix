<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EntrepreneurController;
use App\Http\Controllers\Entrepreneur\EntrepreneurProfileController;

Route::prefix('entrepreneur')->name('entrepreneur.')->group(function () {
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/dashboard', [EntrepreneurController::class, 'EntrepreneurDashboard'])->name('dashboard');
        Route::get('/logout', [EntrepreneurController::class, 'EntrepreneurDestroy'])->name('logout');
        Route::get('/profile', [EntrepreneurProfileController::class, 'index'])->name('profile');
        Route::post('/profile', [EntrepreneurProfileController::class, 'update'])->name('profile.update');
        Route::get('/password-update', [EntrepreneurProfileController::class, 'changePasswordForm'])->name('password.change');
        Route::post('/password-update', [EntrepreneurProfileController::class, 'updatePassword'])->name('password.update');
    });
});

require __DIR__.'/auth.php';

