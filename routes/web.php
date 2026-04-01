<?php

use Illuminate\Support\Facades\Route;

Route::get('/reset-password/{token}', function ($token) {
    return response()->json([
        'token' => $token,
        'message' => 'Use this token in reset-password API'
    ]);
})->name('password.reset');
