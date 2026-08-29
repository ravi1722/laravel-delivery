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
        // Orders — most queried table
        Schema::table('orders', function (Blueprint $table) {
            $table->index(['user_id', 'created_at']);
            $table->index(['restaurant_id', 'created_at']);
            $table->index(['delivery_agent_id', 'status']);
        });

        // Menu items — full text search
        // Already added in create_menu_items_table
        // Adding composite indexes for filtering
        Schema::table('menu_items', function (Blueprint $table) {
            $table->index(['restaurant_id', 'food_type', 'is_available']);
        });

        // Notifications — user inbox
        Schema::table('notifications', function (Blueprint $table) {
            $table->index(['notifiable_id', 'read_at', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
