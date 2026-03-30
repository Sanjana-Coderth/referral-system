<?
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\DashboardController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/wallet', [WalletController::class, 'wallet']);
    Route::get('/transactions', [WalletController::class, 'transactions']);

    Route::get('/my-referrals', [ReferralController::class, 'myReferrals']);

    Route::get('/dashboard', [DashboardController::class, 'index']);
});