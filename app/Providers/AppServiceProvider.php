<?php

namespace App\Providers;

use App\Models\PersonalAccessToken;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
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
        Password::defaults(fn() => Password::min(8)->letters()->mixedCase()->numbers()->symbols());
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
        // Gate::define('check', function ($user, string $module) {
        //     if ($user instanceof User) {
        //         if (in_array(str($module)->replace('\\', '.')->value, getAuthorize($user))) {
        //             return Response::allow();
        //         }
        //     }
        //     return Response::denyAsNotFound();
        // });
    }
}
