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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->morphs('reviewable'); // restaurant or delivery_agent
            $table->integer('rating')->default(5);
            $table->text('comment')->nullable();
            $table->text('owner_response')->nullable();
            $table->boolean('is_approved')->default(true);
            $table->timestamps();

            // $table->index(['reviewable_type', 'reviewable_id']);
            $table->index('rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
