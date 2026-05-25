<?php

namespace App\Services;

use App\Models\User;
use App\Models\WalletTransaction;

class WalletService
{
    public function addBalance(User $user, float $amount, string $description, ?User $fromUser = null   ): void {

        $user->wallet_balance += $amount;
        $user->save();

        WalletTransaction::create([
            'user_id' => $user->id,

            'from_id' => $fromUser?->id,
            'from_email' => $fromUser?->email,

            'to_id' => $user->id,
            'to_email' => $user->email,

            'amount' => $amount,
            'type' => 'credit',
            'description' => $description
        ]);
    }
}