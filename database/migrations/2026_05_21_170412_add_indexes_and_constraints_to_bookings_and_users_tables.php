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
        Schema::table('bookings', function (Blueprint $table) {
            // Add indexes for frequently queried columns
            $table->index('user_id');
            $table->index('date');
            $table->index(['user_id', 'date']);
            $table->index(['user_id', 'date', 'start_time', 'end_time']);
            
            // Add unique constraint to prevent exact duplicate bookings
            $table->unique(['user_id', 'client_email', 'date', 'start_time'], 'unique_booking_slot');
        });

        Schema::table('users', function (Blueprint $table) {
            // Add index for email lookups (already unique, but explicit index helps queries)
            $table->index('email');
        });

        Schema::table('availabilities', function (Blueprint $table) {
            // Add indexes for availability queries
            $table->index('user_id');
            $table->index('day');
            $table->index(['user_id', 'day']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['date']);
            $table->dropIndex(['user_id', 'date']);
            $table->dropIndex(['user_id', 'date', 'start_time', 'end_time']);
            $table->dropUnique('unique_booking_slot');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['email']);
        });

        Schema::table('availabilities', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['day']);
            $table->dropIndex(['user_id', 'day']);
        });
    }
};
