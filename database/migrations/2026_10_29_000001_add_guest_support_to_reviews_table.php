<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Rating pengunjung boleh dikirim tanpa login dan tanpa booking.
     * Karena itu user_id dan booking_id dibuat nullable, lalu identitas
     * pengunjung disimpan pada kolom guest_*.
     */
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['booking_id']);
            $table->dropUnique(['booking_id', 'activity_id']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->change();
            $table->foreignId('booking_id')->nullable()->change();
            $table->string('guest_name', 80)->nullable()->after('user_id');
            $table->string('guest_email', 120)->nullable()->after('guest_name');
            $table->string('ip_address', 45)->nullable()->after('comment');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('booking_id')->references('id')->on('bookings')->cascadeOnDelete();
            $table->unique(['booking_id', 'activity_id']);
            $table->index(['activity_id', 'is_approved']);
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['booking_id']);
            $table->dropUnique(['booking_id', 'activity_id']);
            $table->dropIndex(['activity_id', 'is_approved']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['guest_name', 'guest_email', 'ip_address']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
            $table->foreignId('booking_id')->nullable(false)->change();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('booking_id')->references('id')->on('bookings')->cascadeOnDelete();
            $table->unique(['booking_id', 'activity_id']);
        });
    }
};
