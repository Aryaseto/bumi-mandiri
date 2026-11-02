<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Concerns\GeneratesUniqueSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Activity extends Model
{
    use HasFactory, GeneratesUniqueSlug;

    protected $fillable = [
        'category_id', 'destination_id', 'name', 'slug',
        'short_description', 'description', 'price',
        'duration_minutes', 'capacity', 'cover_image', 'gallery',
        'rating_avg', 'review_count', 'is_featured', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'gallery' => 'array',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'price' => 'decimal:2',
            'rating_avg' => 'decimal:2',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }

    public function bookingItems(): HasMany
    {
        return $this->hasMany(BookingItem::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    public function favoritedBy(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(Package::class, 'package_activity');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function imageUrl(): string
    {
        if ($this->cover_image) {
            return asset('storage/' . $this->cover_image);
        }

        $perActivity = [
            'tubing-experience' => 'tubing-experience.jpg',
            'paintball-battle' => 'paintball-battle.jpg',
            'camping-ground-stay' => 'camping-ground-stay.jpg',
            'api-unggun-malam' => 'api-unggun-malam.jpg',
            'archery-challenge' => 'archery-challenge.jpg',
            'meeting-gathering-package' => 'meeting-gathering-package.jpg',
            'yearbook-photo-session' => 'yearbook-photo-session.jpg',
        ];

        if (isset($perActivity[$this->slug])) {
            return asset('images/activities/' . $perActivity[$this->slug]);
        }

        $fallback = $this->category?->slug ?? 'adventure';

        if (! in_array($fallback, ['adventure', 'nature', 'team-building', 'skill', 'education'], true)) {
            $fallback = 'adventure';
        }

        return asset('images/activities/' . $fallback . '.jpg');
    }

    public function durationLabel(): string
    {
        $hours = intdiv($this->duration_minutes, 60);
        $minutes = $this->duration_minutes % 60;

        if ($hours > 0 && $minutes > 0) {
            return "{$hours} jam {$minutes} menit";
        }

        return $hours > 0 ? "{$hours} jam" : "{$minutes} menit";
    }
}
