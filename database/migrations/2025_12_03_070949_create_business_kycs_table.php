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
        Schema::create('business_kycs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Business Details
            $table->string('company_name');
            $table->string('registration_no')->nullable();
            $table->string('trade_license_no')->nullable();
            $table->enum('business_type', ['Proprietorship', 'Partnership', 'Private Limited', 'Limited Company', 'NGO/NPO'])->nullable();
            $table->string('tin_bin')->nullable();
            $table->year('establishment_year')->nullable();
            $table->integer('number_of_employees')->nullable();
            $table->decimal('last_turnover', 15, 2)->nullable();
            $table->text('business_address')->nullable();
            $table->string('website')->nullable();
            
            // Owner Details
            $table->string('owner_name');
            $table->string('owner_phone');
            $table->string('owner_email');
            $table->string('owner_nid_passport');
            $table->string('owner_role')->nullable();
            
            // Shareholders - JSON format এ store করব
            $table->json('shareholders')->nullable();
            
            // Documents
            $table->string('doc_registration')->nullable();
            $table->string('doc_trade_license')->nullable();
            $table->string('doc_tin')->nullable();
            $table->string('doc_bank_statement')->nullable();
            $table->string('doc_financials')->nullable();
            
            // Nominee
            $table->string('nominee_name')->nullable();
            $table->string('nominee_relation')->nullable();
            $table->string('nominee_nid')->nullable();
            
            // OTP Verification
            $table->string('owner_otp')->nullable();
            $table->timestamp('owner_otp_expires_at')->nullable();
            $table->boolean('owner_verified')->default(false);
            
            // Status
            $table->enum('status', ['pending', 'under_review', 'verified', 'rejected'])->default('pending');
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
        Schema::dropIfExists('business_kycs');
    }
};
