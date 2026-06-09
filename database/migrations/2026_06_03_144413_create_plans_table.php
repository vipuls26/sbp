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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->text('description')->nullable();
            $table->decimal('pricing', 10, 2);
            $table->enum('duration', ['monthly', 'annual']);
            $table->enum('is_active', ['true', 'false'])->default('true');
            $table->string('stripe_price_id')->unique();
            $table->foreignId('admin_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            // unique name and duration for plan
            $table->unique(['name', 'duration']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
