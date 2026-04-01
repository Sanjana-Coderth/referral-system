<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\AuthenticationException;

class AuthService
{

    public function login($data)
    {
        if (!Auth::attempt([
            'email' => $data['email'],
            'password' => $data['password']
        ])) {
            return [
                'status' => false,
                'message' => 'Invalid credentials'
            ];
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $remember = $data['remember_me'] ?? false;

        $tokenData = $this->getToken($user, $remember);

        return [
            'status' => true,
            'message' => 'Login successful',
            'remember_me' => $remember,
            'tokens' => $tokenData,
            'data' => $user
        ];
    }

    public function register($data)
    {
        $referrer = null;

        if (!empty($data['referred_by_code'])) {
            $referrer = User::where('referral_code', $data['referred_by_code'])->first();
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'referral_code' => $this->generateReferralCode(),
            'referred_by' => $referrer ? $referrer->id : null,
        ]);

        return [
            'status' => true,
            'message' => 'User registered successfully',
            'data' => $user
        ];
    }
    public function getToken($user, $login = true): array
    {
        // 🔹 Access Token Expiry
        $token_expires_at = now()->addMinutes(config('sanctum.t_expiration'));

        $data = [
            'token' => $user->createToken(
                'access_token',
                ['*'],
                $token_expires_at
            )->plainTextToken,
            'token_expires_at' => $token_expires_at,
        ];

        if ($login) {

            // 🔥 Proper if-else logic (NO ternary)
            if (request()->boolean('remember_me')) {
                // 1 year
                $refresh_token_expires_at = now()->addMinutes(config('sanctum.expiration'));
            } else {
                // 7 days
                $refresh_token_expires_at = now()->addMinutes(config('sanctum.rt_expiration'));
            }

            $data['refresh_token'] = $user->createToken(
                'refresh_token',
                ['issue-access-token'],
                $refresh_token_expires_at
            )->plainTextToken;

            $data['refresh_token_expires_at'] = $refresh_token_expires_at;
        }

        return $data;
    }

    public function refreshAccessToken(Request $request): array
    {
        $user = $request->user();

        if (!$user) {
            throw new AuthenticationException('Unauthenticated.');
        }

        // 🔥 old access tokens delete
        $user->tokens()->where('name', 'access_token')->delete();

        // 🔥 expiry (2 hours)
        $accessExpiresAt = Carbon::now()->addHours(2);

        // 🔥 new access token
        $accessToken = $user->createToken('access_token')->plainTextToken;

        return [
            'access_token'   => $accessToken,
            'access_expires' => $accessExpiresAt->toDateTimeString(),
        ];
    }
    public function forgotPassword(string $email)
    {
        $status = Password::broker('users')->sendResetLink([
            'email' => $email
        ]);

        return [
            'status' => $status === Password::RESET_LINK_SENT,
            'message' => __($status)
        ];
    }

    public function resetPasswordWeb(array $data)
    {
        $status = Password::broker('users')->reset(
            $data,
            function ($user) use ($data) {
                $user->forceFill([
                    'password' => $data['password']
                ])->save();
                event(new PasswordReset($user));
            }
        );

        return [
            'status' => $status === Password::PASSWORD_RESET,
            'message' => __($status)
        ];
    }

    public function passwordUpdate(array $data): void
    {
        request()->user()->update([
            'password' => $data['password']
        ]);
    }

    private function generateReferralCode()
    {
        do {
            $code = strtoupper(substr(md5(uniqid()), 0, 8));
        } while (User::where('referral_code', $code)->exists());

        return $code;
    }
}
