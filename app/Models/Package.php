<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    protected $fillable = [
        'name', 'slug', 'target_segment', 'description', 'base_price',
        'min_participants', 'max_participants', 'estimated_duration',
        'cover_image', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'base_price' => 'decimal:2',
        ];
    }

    public function activities(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class, 'package_activity');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
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

        return asset('images/packages/' . ((($this->id ?: 1) - 1) % 4 + 1) . '.jpg');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
