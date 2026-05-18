<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {

            $table->uuid('id')->primary();

            // Receiver
            $table->uuid('user_id');

            // From User
            $table->uuid('from_id')->nullable();
            $table->string('from_email')->nullable();

            // To User
            $table->uuid('to_id')->nullable();
            $table->string('to_email')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('type'); // credit / debit
            $table->string('description')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('from_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('to_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
