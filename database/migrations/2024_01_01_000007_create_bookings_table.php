<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique(); // BM-2026-00001
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('package_id')->nullable()->constrained('packages')->nullOnDelete();
            $table->date('booking_date');
            $table->time('booking_time')->nullable();
            $table->integer('participants');
            $table->decimal('subtotal', 14, 2)->default(0);
            $table->decimal('discount', 14, 2)->default(0);
            $table->decimal('total_price', 14, 2)->default(0);
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });

        Schema::create('booking_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('activity_id')->constrained('activities')->cascadeOnDelete();
            $table->integer('quantity')->default(1);
            $table->decimal('price', 12, 2);
            $table->decimal('subtotal', 14, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_items');
        Schema::dropIfExists('bookings');
    }
};
