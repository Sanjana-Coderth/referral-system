<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ReferralLevel;

class ReferralLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $amount = 100;

        for ($i = 1; $i <= 10; $i++) {
            ReferralLevel::create([
                'level' => $i,
                'amount' => $amount
            ]);

            $amount -= 10;
        }
    }
}
