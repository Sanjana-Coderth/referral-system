<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'),
            'referral_code' => 'ADMIN01',
            'wallet_balance' => 100,
            'email_verified_at' => now(),
        ]);

        WalletTransaction::create([
            'user_id' => $admin->id,
            'type' => 'credit',
            'amount' => 100,
            'description' => 'Initial Wallet Bonus',
        ]);
    }
}