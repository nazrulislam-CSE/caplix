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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('role', ['admin', 'investor', 'entrepreneur'])->default('investor');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('username')->unique();
            $table->integer('refer_by')->nullable();

            // 🔹 Financial columns
            $table->decimal('balance', 15, 2)->default(0); // current wallet balance
            $table->decimal('total_earnings', 15, 2)->default(0); // all income
            $table->decimal('total_withdrawn', 15, 2)->default(0); // withdrawn sum
            $table->decimal('pending_balance', 15, 2)->default(0); // pending deposits or bonuses
            $table->decimal('investment_balance', 15, 2)->default(0); // invested amount
            $table->decimal('referral_earnings', 15, 2)->default(0); // referral income
            $table->decimal('deposit_bonus_earned', 15, 2)->default(0); // total deposit bonuses
            $table->decimal('withdrawable_balance', 15, 2)->default(0); // balance available for withdrawal
            $table->decimal('locked_balance', 15, 2)->default(0); // locked for pending withdrawals or penalties
            $table->decimal('total_penalties', 15, 2)->default(0); // total penalties
            $table->decimal('total_interest_earned', 15, 2)->default(0); // interest earned

            // 🔹 Activity / rank tracking
            $table->integer('total_referral_count')->default(0); // total referrals count
            $table->string('rank_level')->default('Bronze'); // Gold, Silver, Platinum etc.
            $table->timestamp('last_deposit_at')->nullable();
            $table->timestamp('last_withdraw_at')->nullable();
            $table->decimal('total_cashback', 15, 2)->default(0);
            $table->decimal('total_commission', 15, 2)->default(0);

            // 🔹 Personal info
            $table->string('phone')->unique()->nullable();
            $table->string('address')->nullable();
            $table->string('photo')->nullable();
            $table->string('text')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->string('password');
            $table->rememberToken();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
