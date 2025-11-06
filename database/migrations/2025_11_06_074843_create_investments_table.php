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
        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->decimal('investment_amount', 15, 2);
            $table->decimal('current_value', 15, 2);
            $table->decimal('profit_loss', 15, 2);
            $table->decimal('profit_loss_percentage', 5, 2);
            $table->enum('status', ['active', 'managed', 'completed', 'cancelled'])->default('active');
            $table->enum('type', ['short-term', 'regular', 'fixed-deposit', 'long-term']);
            $table->string('risk_level')->default('medium');
            $table->timestamp('investment_date');
            $table->timestamp('maturity_date')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'status']);
            $table->index(['project_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investments');
    }
};
