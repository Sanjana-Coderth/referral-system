<?php

namespace App\Services;

use App\Models\User;

class TopReferralService
{
    public function getTopReferrals(array $data)
    {
        $perPage = $data['per_page'] ?? 10;

        // GLOBAL TOP 3
        $topThree = User::withCount('referrals')
            ->orderByDesc('referrals_count')
            ->take(3)
            ->get([
                'id',
                'name',
                'email',
                'image',
                'referral_code'
            ]);

        // PAGINATION DATA
        $users = User::withCount('referrals')
            ->orderByDesc('referrals_count')
            ->paginate($perPage, [
                'id',
                'name',
                'email',
                'image',
                'referral_code'
            ]);

        return [
            'top_three' => $topThree,

            'current_page' => $users->currentPage(),

            'last_page' => $users->lastPage(),

            'per_page' => $users->perPage(),

            'total' => $users->total(),

            'data' => $users->items(),
        ];
    }
}