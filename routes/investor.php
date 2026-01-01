<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvestorController;
use App\Http\Controllers\Investor\InvestorProfileController;
use App\Http\Controllers\Investor\Project\ProjectController;
use App\Http\Controllers\Investor\Investment\InvestmentController;
use App\Http\Controllers\Investor\Kyc\KycController;
use App\Http\Controllers\Investor\Refer\ReferController;
use App\Http\Controllers\Investor\Deposit\DepositController;
use App\Http\Controllers\Investor\Claim\BonusController;
use App\Http\Controllers\Investor\Withdraw\WithdrawController;

Route::prefix('investor')->name('investor.')->group(function () {
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/dashboard', [InvestorController::class, 'InvestorDashboard'])->name('dashboard');
        Route::get('/logout', [InvestorController::class, 'InvestorDestroy'])->name('logout');
        Route::get('/profile', [InvestorProfileController::class, 'index'])->name('profile');
        Route::post('/profile', [InvestorProfileController::class, 'update'])->name('profile.update');
        Route::get('/password-update', [InvestorProfileController::class, 'changePasswordForm'])->name('password.change');
        Route::post('/password-update', [InvestorProfileController::class, 'updatePassword'])->name('password.update');

        // ✅ Project Routes
        Route::prefix('projects')->name('project.')->group(function () {
            Route::get('/project/{project}', [ProjectController::class, 'show'])->name('show');
            Route::get('/analysis', [ProjectController::class, 'analysis'])->name('analysis');
        });

        // ✅ Investment Routes
        Route::prefix('investments')->name('investment.')->group(function () {
            Route::get('/', [InvestmentController::class, 'index'])->name('index');
            Route::get('/create', [InvestmentController::class, 'create'])->name('create');
            Route::post('/', [InvestmentController::class, 'store'])->name('store');
            Route::post('/{investment}/add', [InvestmentController::class, 'addMoreInvestment'])->name('add.more');
            Route::get('/{investment}', [InvestmentController::class, 'show'])->name('show');
        });

        // ✅ Kyc Routes
        Route::prefix('kyc')->name('kyc.')->group(function () {
            Route::get('/', [KycController::class, 'create'])->name('create');
            Route::post('/', [KycController::class, 'store'])->name('store');
            Route::get('/status', [KycController::class, 'status'])->name('status');
            Route::get('/preview', [KycController::class, 'preview'])->name('preview');
            Route::post('/send-otp', [KycController::class, 'sendOtp'])->name('send-otp');
            Route::post('/verify-otp', [KycController::class, 'verifyOtp'])->name('verify-otp');
            Route::get('/download/{field}/{id}', [KycController::class, 'downloadDocument'])->name('download');
        });

        // ✅ Refer Routes
        Route::prefix('refer')->name('refer.')->group(function () {
            Route::get('/', [ReferController::class, 'index'])->name('index');
        });
        
        // ✅ Deposit Routes
        Route::prefix('deposit')->name('deposit.')->group(function () {
            Route::get('/', [DepositController::class, 'index'])->name('index');
            Route::get('/create', [DepositController::class, 'create'])->name('create');
            Route::post('/store', [DepositController::class, 'store'])->name('store');
        });

        // claim bonus
        Route::post('/bonus/claim', [BonusController::class, 'claimReferralBonus'])->name('bonus.claim');

        // ✅ Withdraw Routes
        Route::prefix('withdraw')->name('withdraw.')->group(function () {
            Route::get('/', [WithdrawController::class, 'index'])->name('request');
            Route::post('/store', [WithdrawController::class, 'store'])->name('store');
        });



    });
});

require __DIR__.'/auth.php';

