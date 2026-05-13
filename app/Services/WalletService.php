<?php

namespace App\Services;

use App\Models\User;
use App\Models\WalletTransaction;

class WalletService
{
    public function addBalance(User $user, float $amount, string $description): void
    {
        $user->wallet_balance += $amount;
        $user->save();

        WalletTransaction::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'type' => 'credit',
            'description' => $description
        ]);
    }
}