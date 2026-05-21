<?php

namespace App\Providers;

use App\Models\PersonalAccessToken;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Illuminate\Auth\Notifications\ResetPassword;
use App\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
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

        VerifyEmail::toMailUsing(
            function ($notifiable, $url) {

                $frontendUrl =
                    env("APP_FRONTEND_URL");

                $verifyUrl =
                    $frontendUrl .
                    "/verify-email?url=" .
                    urlencode($url);

                return (new MailMessage)
                    ->subject("Verify Email")
                    ->line(
                        "Click button below to verify your email."
                    )
                    ->action(
                        "Verify Email",
                        $verifyUrl
                    );
            }
        );
    }
}
