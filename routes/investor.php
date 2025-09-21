<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvestorController;

Route::prefix('investor')->name('investor.')->group(function () {
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/dashboard', [InvestorController::class, 'InvestorDashboard'])->name('dashboard');
        Route::get('/logout', [InvestorController::class, 'InvestorDestroy'])->name('logout');
    });
});

require __DIR__.'/auth.php';

