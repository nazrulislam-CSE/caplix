<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\Kyc\kycController;
use App\Http\Controllers\Admin\Kyc\InvestorKycController;
use App\Http\Controllers\Admin\Project\ProjectController;

Route::prefix('admin')->name('admin.')->group(function () {
    // Admin Login Routes
    Route::get('login', [AdminController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminController::class, 'login']);

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'AdminDashboard'])->name('dashboard');
        Route::get('/logout', [AdminController::class, 'AdminDestroy'])->name('logout');
        Route::get('/profile', [AdminProfileController::class, 'index'])->name('profile');
        Route::post('/profile', [AdminProfileController::class, 'update'])->name('profile.update');

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
            Route::get('/', [KycController::class, 'index'])->name('index');
            Route::get('/{kyc}/details', [KycController::class, 'details'])->name('details');
            Route::get('/{kyc}', [KycController::class, 'show'])->name('show');
            Route::get('/{kyc}/edit', [KycController::class, 'edit'])->name('edit');
            Route::put('/{kyc}', [KycController::class, 'update'])->name('update');
            Route::put('/{kyc}/status', [KycController::class, 'updateStatus'])->name('status.update');
            Route::delete('/{kyc}', [KycController::class, 'destroy'])->name('destroy');
            Route::get('/{kyc}/{documentType}', [KycController::class, 'downloadDocument'])->name('download');
            Route::get('/export', [KycController::class, 'export'])->name('export');
        });

        Route::prefix('investor-kyc')->name('investor-kyc.')->group(function () {
            Route::get('/', [InvestorKycController::class, 'index'])->name('index');
            Route::get('/{id}', [InvestorKycController::class, 'show'])->name('show');
            Route::put('/{id}/status', [InvestorKycController::class, 'updateStatus'])->name('update-status');
            Route::get('/download/{id}/{field}', [InvestorKycController::class, 'downloadDocument'])->name('download');
            Route::delete('/{id}', [InvestorKycController::class, 'destroy'])->name('destroy');
            Route::put('/{id}/notes', [InvestorKycController::class, 'updateNotes'])->name('update-notes');
            Route::get('/{id}/edit', [InvestorKycController::class, 'edit'])->name('edit');
            Route::put('/{id}/update', [InvestorKycController::class, 'update'])->name('update');
        });
       
    });
});

require __DIR__.'/auth.php';

