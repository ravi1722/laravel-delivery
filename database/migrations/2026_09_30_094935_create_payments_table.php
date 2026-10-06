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
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Razorpay IDs
            $table->string('razorpay_order_id')->unique();     // order_xxxxx
            $table->string('razorpay_payment_id')->nullable(); // pay_xxxxx
            $table->string('razorpay_signature')->nullable();  // for verification

            // Payment details
            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('INR');
            $table->enum('status', [
                'created',    // Razorpay order created
                'attempted',  // Payment attempted
                'paid',       // Payment successful
                'failed',     // Payment failed
                'refunded',   // Fully refunded
                'partially_refunded', // Partially refunded
            ])->default('created');

            // Metadata
            $table->json('razorpay_response')->nullable(); // full response from Razorpay
            $table->text('failure_reason')->nullable();    // if payment failed
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['razorpay_order_id']);
            $table->index(['razorpay_payment_id']);
            $table->index(['order_id', 'status']);
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
