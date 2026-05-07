<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/reset-password/{token}', function ($token) {
    return redirect(config('app.web_url') . '/reset-password?token=' . $token);
})->name('password.reset');

Route::get('/', function () {
    return response()->json([
        'message' => 'Referral System Web Routes Working'
    ]);
});
Route::get('/api/verify-email/{id}/{hash}', function (Request $request, $id, $hash) {

    return redirect(
        'http://localhost:3000/verify-email/' .
        $id . '/' . $hash .
        '?' . $request->getQueryString()
    );

})->name('verification.verify');