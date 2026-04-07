<?php

use Illuminate\Support\Facades\Route;

Route::get('/reset-password/{token}', function ($token) {
    return redirect(config('app.web_url') . '/reset-password?token=' . $token);
})->name('password.reset');

Route::get('/', function () {
    return response()->json([
        'message' => 'Referral System Web Routes Working'
    ]);
});