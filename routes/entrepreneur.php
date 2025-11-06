<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EntrepreneurController;
use App\Http\Controllers\Entrepreneur\EntrepreneurProfileController;
use App\Http\Controllers\Entrepreneur\Project\ProjectController;

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
    });
});

require __DIR__.'/auth.php';

