<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->foreignId('destination_id')->constrained('destinations')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->integer('duration_minutes')->default(60);
            $table->integer('capacity')->default(10);
            $table->string('cover_image')->nullable();
            $table->json('gallery')->nullable(); // array of image paths
            $table->decimal('rating_avg', 3, 2)->default(0);
            $table->integer('review_count')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'is_featured']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
