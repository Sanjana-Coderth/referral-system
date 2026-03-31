<?php

namespace App\Services;

use App\Models\Wallet;
use App\Models\WalletTransaction;

class WalletService
{
    public function createWallet($userId)
    {
        return Wallet::create([
            'user_id' => $userId,
            'balance' => 0
        ]);
    }

    public function credit($userId, $amount, $description = 'Credit')
    {
        $wallet = Wallet::firstOrCreate(
            ['user_id' => $userId],
            ['balance' => 0]
        );

        $wallet->increment('balance', $amount);

        WalletTransaction::create([
            'user_id' => $userId,
            'amount' => $amount,
            'type' => 'credit',
            'description' => $description
        ]);

        return $wallet;
    }

    public function getWallet($userId)
    {
        return Wallet::where('user_id', $userId)->first();
    }

    public function getTransactions($userId)
    {
        return WalletTransaction::where('user_id', $userId)->latest()->get();
    }
}