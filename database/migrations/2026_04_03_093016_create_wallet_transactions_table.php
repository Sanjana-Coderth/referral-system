<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID primary key

            $table->uuid('user_id'); // UUID foreign key

            $table->decimal('amount', 10, 2);
            $table->string('type'); // credit / debit
            $table->string('description')->nullable();

            $table->timestamps();

            // 🔥 Foreign key manually
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};