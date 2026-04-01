<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

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
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'referral_code' => $this->generateReferralCode(),
        ]);

        return [
            'status' => true,
            'message' => 'User registered successfully',
            'data' => $user
        ];
    }


public function getToken($user, $remember = false): array
{
    // 🔥 Access Token Expiry (ONLY CURRENT TIME)
    $accessTokenExpiry = now(); // ✅ current date/time

    $data = [
        'access_token' => $user->createToken(
            'access_token',
            ['*']
        )->plainTextToken,
        'access_token_expires_at' => $accessTokenExpiry,
    ];

    // 🔥 Refresh Token Expiry (as per logic)
    $refreshTokenExpiry = $remember
        ? now()->addYear()     // 1 year
        : now()->addDays(7);   // 7 days

    $data['refresh_token'] = $user->createToken(
        'refresh_token',
        ['refresh']
    )->plainTextToken;

    $data['refresh_token_expires_at'] = $refreshTokenExpiry;

    return $data;
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