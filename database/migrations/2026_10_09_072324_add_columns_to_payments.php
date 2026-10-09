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
        Schema::table('payments', function (Blueprint $table) {
            // Payment method info
            $table->string('method')->nullable();    // card, upi, netbanking, wallet
            $table->string('bank')->nullable();      // for netbanking
            $table->string('wallet')->nullable();    // for wallet payments
            $table->string('vpa')->nullable();       // UPI VPA (Virtual Payment Address)
            $table->string('card_network')->nullable(); // Visa, Mastercard, etc.
            $table->string('card_last4')->nullable();   // last 4 digits
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            //
        });
    }
};
