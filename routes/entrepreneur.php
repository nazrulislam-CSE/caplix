<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EntrepreneurController;

Route::prefix('entrepreneur')->name('entrepreneur.')->group(function () {
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/dashboard', [EntrepreneurController::class, 'EntrepreneurDashboard'])->name('dashboard');
        Route::get('/logout', [EntrepreneurController::class, 'EntrepreneurDestroy'])->name('logout');
    });
});

require __DIR__.'/auth.php';

