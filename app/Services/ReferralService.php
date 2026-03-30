<?php

namespace App\Services;

use App\Models\User;
use App\Models\Referral;

class ReferralService
{
    public function applyReferral($referralCode, $newUserId)
    {
        $referrer = User::where('referral_code', $referralCode)->first();

        if (!$referrer || $referrer->id == $newUserId) {
            return null;
        }

        return Referral::create([
            'user_id' => $referrer->id,
            'referred_user_id' => $newUserId,
            'reward' => 100
        ]);
    }

    public function getMyReferrals($userId)
    {
        return Referral::with('referredUser')
            ->where('user_id', $userId)
            ->get();
    }

    public function getReferralCount($userId)
    {
        return Referral::where('user_id', $userId)->count();
    }
}