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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Project Title
            $table->string('investment_type')->nullable(); // e.g., Equity, Loan, etc.
            $table->decimal('roi', 5, 2)->nullable(); // Approximate ROI %
            $table->text('description')->nullable(); // Project Description
            $table->decimal('capital_required', 15, 2)->default(0); // Capital Goal
            $table->decimal('capital_raised', 15, 2)->default(0); // Raised so far
            $table->enum('status', ['Pending', 'Approved', 'Issued', 'At Risk'])->default('Pending');
            $table->string('pitch_deck')->nullable(); // Uploaded PDF
            $table->boolean('has_complaint')->default(false);
            $table->integer('score')->default(100);
            $table->unsignedBigInteger('entrepreneur_id')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
