<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    protected $walletService;
    protected $referralService;

    public function __construct(
        WalletService $walletService,
        ReferralService $referralService
    ) {
        $this->walletService = $walletService;
        $this->referralService = $referralService;
    }

    public function register($data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'referred_by' => null
        ]);

        // create wallet
        $this->walletService->createWallet($user->id);

        // apply referral
        if (!empty($data['referral_code'])) {

            $referral = $this->referralService->applyReferral(
                $data['referral_code'],
                $user->id
            );

            if ($referral) {
                $user->update([
                    'referred_by' => $referral->user_id
                ]);

                // reward
                $this->walletService->credit(
                    $referral->user_id,
                    100,
                    'Referral Bonus'
                );
            }
        }

        return $user;
    }

    public function login($data)
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return null;
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }
}