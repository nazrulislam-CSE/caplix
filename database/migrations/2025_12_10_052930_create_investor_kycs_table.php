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
        Schema::create('investor_kycs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Personal Information
            $table->string('full_name_bn');
            $table->string('full_name_en');
            $table->string('nid')->unique();
            $table->date('date_of_birth');
            $table->string('phone');
            $table->string('email');
            $table->text('permanent_address');
            $table->text('admin_notes')->nullable();
            
            // Bank Information
            $table->string('bank_name')->nullable();
            $table->string('bank_account_no')->nullable();
            
            // Investment Details
            $table->enum('investment_range', [
                '<100000',
                '100000-500000',
                '500000-2000000',
                '>2000000'
            ])->nullable();
            $table->string('occupation')->nullable();
            
            // Documents
            $table->string('nid_front')->nullable();
            $table->string('nid_back')->nullable();
            $table->string('passport')->nullable();
            
            // Nominees (JSON format)
            $table->json('nominees')->nullable();
            
            // OTP Verification
            $table->string('otp')->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->boolean('owner_verified')->default(false);
            
            // Status
            $table->enum('status', ['draft', 'pending', 'under_review', 'verified', 'rejected'])->default('draft');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('verified_at')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investor_kycs');
    }
};
