<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('activity_id')->constrained('activities')->cascadeOnDelete();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->unsignedTinyInteger('rating'); // 1-5
            $table->text('comment')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->timestamps();

            $table->unique(['booking_id', 'activity_id']); // 1 review per booking-item
        });

        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('activity_id')->constrained('activities')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'activity_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorites');
        Schema::dropIfExists('reviews');
    }
};
