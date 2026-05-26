<?php

namespace App\Providers;

use App\Models\PersonalAccessToken;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Schema::defaultStringLength(200);

        Password::defaults(
            fn() =>
            Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
        );

        Sanctum::usePersonalAccessTokenModel(
            PersonalAccessToken::class
        );

        ResetPassword::createUrlUsing(
            function ($user, string $token) {

                return
                    "http://localhost:3000/reset-password?token="
                    . $token .
                    "&email=" .
                    urlencode($user->email);
            }
        );
    }
}