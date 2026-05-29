<?php

namespace App\Services;

use App\Models\User;

class TopReferralService
{
    public function getTopReferrals(array $data)
    {
        $perPage = $data['per_page'] ?? 10;

        $search = $data['search'] ?? null;

        // GLOBAL TOP 3
        $topThreeQuery = User::withCount('referrals');

        if ($search) {
            $topThreeQuery->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $topThree = $topThreeQuery
            ->orderByDesc('referrals_count')
            ->orderBy('updated_at', 'asc')
            ->take(3)
            ->get([
                'id',
                'name',
                'email',
                'image',
                'referral_code'
            ]);

        // PAGINATION DATA
        $query = User::withCount('referrals');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $users = $query
            ->orderByDesc('referrals_count')
            ->orderBy('updated_at', 'asc')
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