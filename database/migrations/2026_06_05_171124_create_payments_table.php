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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            // subscriber id
            $table->foreignId('subscriber_id')->constrained('users')->cascadeOnDelete();
            // plan id
            $table->foreignId('plan_id')->constrained('plans')->cascadeOnDelete();

            // create order id when payment start
            $table->string('razor_order_id')->nullable();

            // payment id 
            $table->string('razor_payment_id')->nullable();

            // verify if payment is genuie
            $table->string('razor_signature')->nullable();

            $table->decimal('amount', 10, 2);

            $table->enum('status', ['pending','success','failed'])->default('pending');

            $table->timestamp('paid_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
