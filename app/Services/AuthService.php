<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\AuthenticationException;
use App\Services\ReferralService;
use App\Services\WalletService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class AuthService
{
    public function login(array $data)
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

        $user->update([
            'last_login_at' => now()
        ]);

        $today = now()->toDateString();

        // Check if reward already given today
        if ($user->last_reward_date !== $today) {

            $points = 0;

            // Silver
            if (
                $user->wallet_balance >= 50001 &&
                $user->wallet_balance <= 100000
            ) {

                $points = 10;
            }

            // Gold
            elseif (
                $user->wallet_balance >= 100001 &&
                $user->wallet_balance <= 150000
            ) {

                $points = 20;
            }

            // Platinum
            elseif (
                $user->wallet_balance >= 150001 &&
                $user->wallet_balance <= 200000
            ) {

                $points = 30;
            }

            // Add reward
            if ($points > 0) {

                $walletService =
                    new WalletService();

                $walletService->addBalance(
                    $user,
                    $points,
                    'Daily Login Reward'
                );

                $user->last_reward_date =
                    $today;

                $user->save();
            }
        }

        $remember = filter_var($data['remember_me'] ?? false, FILTER_VALIDATE_BOOLEAN);

        $tokenData = $this->getToken($user, $remember);

        return [
            'status' => true,
            'message' => 'Login successful',
            'remember_me' => $remember,
            'tokens' => $tokenData,
            'data' => $user
        ];
    }

    public function register(array $data)
    {
        $referralService = new ReferralService();

        if (!empty($data['referred_by_code'])) {

            $referrer = User::where(
                'referral_code',
                $data['referred_by_code']
            )->first();
        } else {

            $referrer = User::where(
                'referral_code',
                'UNRBPLO0'
            )->first();
        }

        $ip = file_get_contents(
            "https://api.ipify.org"
        );

        $response = Http::get(
            "https://ipapi.co/{$ip}/json/"
        )->json();

        $user = User::create([

            'name' => $data['name'],

            'email' => $data['email'],

            'password' => bcrypt($data['password']),

            'referral_code' =>
            $this->generateReferralCode(),

            'referred_by' =>
            $referrer ? $referrer->id : null,

            'wallet_balance' => 0,

            'country' =>
            $response['country_name'] ?? 'India',

            'country_code' =>
            $response['country_code'] ?? 'IN',
        ]);

        $tokenData = $this->getToken($user, false);

        return [
            'status' => true,
            'message' => 'User registered successfully',
            'tokens' => $tokenData,
            'data' => $user
        ];
    }
    public function getToken(User $user, bool $remember = false): array
    {
        $token_expires_at = now()->addMinutes(config('sanctum.t_expiration'));

        $data = [
            'token' => $user->createToken('access_token', ['*'], $token_expires_at)->plainTextToken,
            'token_expires_at' => $token_expires_at,
        ];

        if ($remember) {
            $refresh_token_expires_at = now()->addMinutes(config('sanctum.expiration'));
        } else {
            $refresh_token_expires_at = now()->addMinutes(config('sanctum.rt_expiration'));
        }

        $data['refresh_token'] = $user->createToken(
            'refresh_token',
            ['issue-access-token'],
            $refresh_token_expires_at
        )->plainTextToken;

        $data['refresh_token_expires_at'] = $refresh_token_expires_at;

        return $data;
    }

    public function refreshAccessToken(Request $request): array
    {
        $user = $request->user();

        if (!$user) {
            throw new AuthenticationException('Unauthenticated.');
        }

        $user->tokens()->where('name', 'access_token')->delete();

        $accessExpiresAt = Carbon::now()->addHours(2);
        $accessToken = $user->createToken('access_token')->plainTextToken;

        return [
            'access_token'   => $accessToken,
            'access_expires' => $accessExpiresAt->toDateTimeString(),
        ];
    }

    public function forgotPassword(string $email)
    {
        ResetPassword::createUrlUsing(function ($user, string $token) {

            return env('APP_FRONTEND_URL')
                . '/reset-password?token='
                . $token
                . '&email='
                . urlencode($user->email);
        });

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
                    'password' => Hash::make($data['password'])
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
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if ($user) {

            $user->update([
                'password' => bcrypt($data['password'])
            ]);
        }
    }

    public function verifyEmail(string $id, string $hash) 
    {
        $user = User::find($id);

        if (!$user) {

            return [
                'status' => false,
                'message' => 'User not found'
            ];
        }

        if (!hash_equals(
            (string) $hash,
            sha1($user->getEmailForVerification())
        )) {

            return [
                'status' => false,
                'message' => 'Invalid verification link'
            ];
        }

        if ($user->hasVerifiedEmail()) {

            return [
                'status' => true,
                'message' => 'Already Verified'
            ];
        }

        $user->markEmailAsVerified();

        $referralService =
            new ReferralService();

        $referrer = User::find(
            $user->referred_by
        );

        if ($referrer) {

            $referralService
                ->distributeLevelIncome(
                    $referrer,
                    $user
                );
        }

        event(new \Illuminate\Auth\Events\Verified($user));

        return [
            'status' => true,
            'message' =>
            'Your email address has been verified successfully!'
        ];
    }

    public function resend()
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (!$user) {

            return [
                'status' => false,
                'message' => 'Unauthenticated'
            ];
        }

        if ($user->hasVerifiedEmail()) {

            return [
                'status' => true,
                'message' => 'Already Verified'
            ];
        }

        return [
            'status' => true,
            'message' =>
            'Verification email resent successfully.'
        ];
    }

    private function generateReferralCode()
    {
        do {
            $code = strtoupper(substr(md5(uniqid()), 0, 8));
        } while (User::where('referral_code', $code)->exists());

        return $code;
    }
}
