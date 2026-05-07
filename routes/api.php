<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);


Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh-token', [AuthController::class, 'refreshToken']);

    Route::get('/profile', [ProfileController::class, 'profile']);
    Route::post('/profile/update', [ProfileController::class, 'updateProfile']);
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword']);

    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/dashboard-chart/{type}', [DashboardController::class, 'chart']);
    Route::get('/recent-users', [DashboardController::class, 'recentUsers']);

    Route::get('/wallet', [WalletController::class, 'balance']);
    Route::get('/wallet-transactions', [WalletController::class, 'transactions']);

    Route::get('/referrals', [ReferralController::class, 'index']);
});

    Route::post(
    '/verify-email/{id}/{hash}',
    [AuthController::class, 'verifyEmail']
)
 ->name('user.verification.verify');

});