<?php

namespace App\Services;

use App\Models\User;

class TopReferralService
{
    public function getTopReferrals(array $data)
    {
        $perPage = $data['per_page'] ?? 10;

        return User::withCount('referrals')

            ->orderByDesc('referrals_count')

            ->paginate($perPage, [
                'id',
                'name',
                'email',
                'image',
                'referral_code'
            ]);
    }
}