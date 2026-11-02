<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Weekend Escape, Team Adventure, School Adventure, Custom Event
            $table->string('slug')->unique();
            $table->string('target_segment')->nullable(); // keluarga, perusahaan, sekolah, event
            $table->text('description')->nullable();
            $table->decimal('base_price', 12, 2)->default(0);
            $table->integer('min_participants')->default(1);
            $table->integer('max_participants')->nullable();
            $table->string('estimated_duration')->nullable();
            $table->string('cover_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Activities included in a package (many-to-many)
        Schema::create('package_activity', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('packages')->cascadeOnDelete();
            $table->foreignId('activity_id')->constrained('activities')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('package_activity');
        Schema::dropIfExists('packages');
    }
};
