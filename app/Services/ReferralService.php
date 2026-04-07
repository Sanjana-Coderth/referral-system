<?php

namespace App\Services;

use App\Models\ReferralLevel;
use App\Models\User;
use App\Models\WalletTransaction;

class ReferralService
{
    public function getReferrals($user)
    {
        $referrals = User::where('referred_by', $user->id)
            ->select('name', 'email', 'created_at')
            ->get();

        return $referrals->map(function ($ref) {
            return [
                'name' => $ref->name,
                'email' => $ref->email,
                'joined_at' => $ref->created_at->format('Y-m-d'),
            ];
        });
    }

    public function distributeLevelIncome($referrer)
    {
        $level = 1;
        $current = $referrer;

        while ($current && $level <= 10) {

            $levelData = ReferralLevel::where('level', $level)->first();

            if ($levelData) {
                $current->wallet_balance += $levelData->amount;
                $current->save();

                WalletTransaction::create([
                    'user_id' => $current->id,
                    'amount' => $levelData->amount,
                    'type' => 'credit',
                    'description' => 'Level ' . $level . ' Referral Bonus'
                ]);
            }

            $current = User::find($current->referred_by);
            $level++;
        }
    }

    public function getUserLevel($user)
    {
        $level = 1;

        while ($user->referred_by) {
            $user = User::find($user->referred_by);
            $level++;

            if ($level > 10) {
                break;
            }
        }

        return $level;
    }
}