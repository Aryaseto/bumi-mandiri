<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    protected $fillable = [
        'author_id', 'title', 'slug', 'cover_image', 'excerpt',
        'content', 'reading_time', 'is_published',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function imageUrl(): string
    {
        if ($this->cover_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->cover_image)) {
            return asset('storage/' . $this->cover_image);
        }

        return asset('images/articles/' . ((($this->id ?: 1) - 1) % 12 + 1) . '.jpg');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
