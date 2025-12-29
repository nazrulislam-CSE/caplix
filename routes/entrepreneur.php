<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EntrepreneurController;
use App\Http\Controllers\Entrepreneur\EntrepreneurProfileController;
use App\Http\Controllers\Entrepreneur\Kyc\kycController;
use App\Http\Controllers\Entrepreneur\Project\ProjectController;
use App\Http\Controllers\Entrepreneur\Project\ProfitReportController;

Route::prefix('entrepreneur')->name('entrepreneur.')->group(function () {
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/dashboard', [EntrepreneurController::class, 'EntrepreneurDashboard'])->name('dashboard');
        Route::get('/logout', [EntrepreneurController::class, 'EntrepreneurDestroy'])->name('logout');
        Route::get('/profile', [EntrepreneurProfileController::class, 'index'])->name('profile');
        Route::post('/profile', [EntrepreneurProfileController::class, 'update'])->name('profile.update');
        Route::get('/password-update', [EntrepreneurProfileController::class, 'changePasswordForm'])->name('password.change');
        Route::post('/password-update', [EntrepreneurProfileController::class, 'updatePassword'])->name('password.update');

        // ✅ Project Routes
        Route::prefix('projects')->name('project.')->group(function () {
            Route::get('/', [ProjectController::class, 'index'])->name('index');
            Route::get('/create', [ProjectController::class, 'create'])->name('create');
            Route::get('/project/{project}', [ProjectController::class, 'show'])->name('show');
            Route::post('/store', [ProjectController::class, 'store'])->name('store');
            Route::get('/{project}/edit', [ProjectController::class, 'edit'])->name('edit');
            Route::put('/{project}', [ProjectController::class, 'update'])->name('update');
            Route::delete('/{project}', [ProjectController::class, 'destroy'])->name('destroy');
        });

        // ✅ Kyc Routes
        Route::prefix('kyc')->name('kyc.')->group(function () {
            Route::get('/', [kycController::class, 'index'])->name('index');
            Route::get('/create', [kycController::class, 'create'])->name('create');
            Route::get('/kyc/{id}', [kycController::class, 'show'])->name('show');
            Route::post('/store', [kycController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [kycController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [kycController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [kycController::class, 'destroy'])->name('destroy');
            Route::post('/send-otp', [KycController::class, 'sendOtp'])->name('send-otp');
            Route::get('/status', [KycController::class, 'status'])->name('status');
        });

        // ✅ Project Profit Report Routes
        Route::prefix('project/profit/report')->name('project.profit.report.')->group(function () {
            Route::get('/', [ProfitReportController::class, 'index'])->name('index');
            Route::get('/create', [ProfitReportController::class, 'create'])->name('create');
            Route::post('/store', [ProfitReportController::class, 'store'])->name('store');
        });
    });
});

require __DIR__.'/auth.php';

