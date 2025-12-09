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
            $table->string('name'); // Project title
            $table->string('url')->nullable(); // Project Url
            $table->enum('investment_type', ['short', 'regular', 'fdi'])->nullable();
            $table->tinyInteger('short_duration')->nullable(); // Duration in months (for short-term)
            $table->tinyInteger('regular_duration')->nullable(); // Duration in years (for short-term)
            $table->decimal('roi', 5, 2)->nullable(); // ROI %
            $table->text('description')->nullable();
            $table->decimal('capital_required', 15, 2)->nullable();
            $table->string('pitch_deck')->nullable(); // File path
            $table->enum('status', ['Pending', 'Approved', 'Issued', 'At Risk'])->default('Pending');
            $table->decimal('capital_raised', 15, 2)->default(0);
            $table->decimal('score', 5, 2)->default(0);
            $table->boolean('is_red')->default(false); // For your table row styling
            $table->unsignedBigInteger('entrepreneur_id')->nullable();
            $table->boolean('has_complaint')->default(false);
            $table->string('created_by')->nullable(); 
            $table->string('updated_by')->nullable(); 
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
