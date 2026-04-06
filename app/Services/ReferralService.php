<?php

namespace App\Services;

use App\Models\User;

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
}