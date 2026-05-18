<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('referral_levels', function (Blueprint $table) {

            $table->uuid('id')->primary();

            $table->integer('level');

            $table->decimal('amount', 10, 2);

            $table->timestamps();
        });

        $amount = 100;

        for ($i = 1; $i <= 10; $i++) {

            \App\Models\ReferralLevel::create([
                'level' => $i,
                'amount' => $amount
            ]);

            $amount -= 10;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_levels');
    }
};