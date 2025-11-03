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
            $table->string('name'); // Project name
            $table->text('description')->nullable(); // Project description
            $table->decimal('capital_raised', 15, 2)->default(0); // Capital raised
            $table->decimal('goal', 15, 2)->default(0); // Goal amount
            $table->enum('status', ['Pending', 'Approved', 'Issued', 'At Risk'])->default('Pending');
            $table->boolean('has_complaint')->default(false); // Complaint flag
            $table->integer('score')->default(100); // Project score (will be reduced on complaints)
            $table->unsignedBigInteger('entrepreneur_id')->nullable(); // Relationship to user (if needed)
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
