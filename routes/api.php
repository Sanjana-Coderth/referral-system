<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TelegramController;

// AUTH
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::get('/default-referral', [AuthController::class, 'defaultReferral']);

  //Telegram
    Route::get('/telegram-stats', [TelegramController::class, 'stats']);
    
// PROTECTED ROUTES
Route::middleware('auth:sanctum')->group(function () {

    // AUTH
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/refresh-token', [AuthController::class, 'refreshToken']);

    Route::middleware('throttle:6,1')->group(function () {
        Route::get('/resend', [AuthController::class, 'resend']);
        Route::post('/verify-email/{id}/{hash}',[AuthController::class, 'verifyEmail'])->middleware('signed')->name('user.verification.verify');
    });

    // PROFILE
    Route::get('/profile', [ProfileController::class, 'profile']);

    Route::post('/profile/update', [ProfileController::class, 'updateProfile']);

    Route::post('/profile/change-password', [ProfileController::class, 'changePassword']);

    // DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::get('/dashboard-chart/{type}', [DashboardController::class, 'chart']);

    Route::get('/recent-users', [DashboardController::class, 'recentUsers']);

    // WALLET
    Route::get('/wallet', [WalletController::class, 'balance']);

    Route::get('/wallet-transactions', [WalletController::class, 'transactions']);

    // REFERRALS
    Route::get('/referrals', [ReferralController::class, 'index']);

    Route::get('/referral-tree', [ReferralController::class, 'tree']);

    Route::get('/top-referrals', [ReferralController::class, 'topReferrals']);

    //Twitter
    // Route::get('/twitter', [TelegramController::class, 'twitter']);

});
