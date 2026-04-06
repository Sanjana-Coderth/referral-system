<?php

namespace App\Services;

use App\Models\User;
use App\Models\WalletTransaction;

//
class DashboardService
{
    public function getDashboardData($user)
    {
        $totalReferrals = User::where('referred_by', $user->id)->count();

        $referralEarnings = WalletTransaction::where('user_id', $user->id)
            ->where('description', 'Referral Bonus')
            ->sum('amount');

        $transactions = WalletTransaction::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return [
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'referral_code' => $user->referral_code,
            ],
            'wallet' => [
                'balance' => $user->wallet_balance,
                'referral_earnings' => $referralEarnings,
            ],
            'referrals' => [
                'total_referrals' => $totalReferrals,
            ],
            'recent_transactions' => $transactions,
        ];
    }
}