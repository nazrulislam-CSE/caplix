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
        Schema::create('project_profit_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('entrepreneur_id')->constrained('users')->onDelete('cascade');
            $table->year('year');
            $table->string('month'); // September, October
            $table->decimal('total_profit', 15, 2); // মোট লাভ (যেমন: 18200)
            $table->decimal('admin_share', 15, 2)->default(0); // Admin এর অংশ (100)
            $table->decimal('investor_share', 15, 2)->default(0); // Investor এর অংশ (18000)
            $table->decimal('referral_share', 15, 2)->default(0); // Referral এর অংশ (100)
            $table->enum('status', ['pending', 'submitted', 'audited', 'approved', 'rejected'])->default('pending');
            $table->text('remarks')->nullable();
            $table->foreignId('audited_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('audited_at')->nullable();
            $table->timestamps();
            
            $table->index(['project_id', 'year', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_profit_reports');
    }
};
