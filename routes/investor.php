<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvestorController;
use App\Http\Controllers\Investor\InvestorProfileController;

Route::prefix('investor')->name('investor.')->group(function () {
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/dashboard', [InvestorController::class, 'InvestorDashboard'])->name('dashboard');
        Route::get('/logout', [InvestorController::class, 'InvestorDestroy'])->name('logout');
        Route::get('/profile', [InvestorProfileController::class, 'index'])->name('profile');
        Route::post('/profile', [InvestorProfileController::class, 'update'])->name('profile.update');
        Route::get('/password-update', [InvestorProfileController::class, 'changePasswordForm'])->name('password.change');
        Route::post('/password-update', [InvestorProfileController::class, 'updatePassword'])->name('password.update');

    });
});

require __DIR__.'/auth.php';

