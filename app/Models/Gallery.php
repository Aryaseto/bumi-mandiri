<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gallery extends Model
{
    protected $fillable = ['category_id', 'title', 'image_path', 'is_featured'];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function imageUrl(): string
    {
        if ($this->image_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->image_path)) {
            return asset('storage/' . $this->image_path);
        }

        if (preg_match('/placeholder-(\d+)/', (string) $this->image_path, $matches)) {
            return asset('images/gallery/' . ((($matches[1] - 1) % 6) + 1) . '.jpg');
        }

        return asset('images/gallery/' . ((($this->id ?: 1) - 1) % 6 + 1) . '.jpg');
    }
}
