<?php

namespace App\Services;

use App\Models\WalletTransaction;

class WalletService
{
    public function addBalance($user, $amount, $description)
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