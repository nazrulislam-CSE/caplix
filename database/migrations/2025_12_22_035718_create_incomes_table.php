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
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->enum('type', [
                'referral_bonus',        // Refer bonus
                'deposit_bonus',         // Deposit bonus
                'investment_profit',     // Investment profit
                'commission_income',     // Commission
                'reward_bonus',          // Reward / Campaign
                'level_income',          // Multi-level referral income
                'daily_bonus',           // Daily login / activity bonus
                'performance_bonus',     // Target / performance based bonus
                'cashback_income',       // Cashback income
                'salary_income',         // Fixed income / salary
                'withdraw_reversal',     // Withdraw failed হলে ফেরত
                'adjustment_credit',     // Admin adjustment
                'penalty_refund',        // Penalty refund
                'interest_income',       // Interest income
                'affiliate_income'       // Affiliate marketing income
            ]);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
