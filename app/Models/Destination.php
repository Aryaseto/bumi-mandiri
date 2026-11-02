<?php

namespace App\Models;

use App\Models\Concerns\GeneratesUniqueSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Destination extends Model
{
    use GeneratesUniqueSlug;

    protected $fillable = [
        'name', 'slug', 'description', 'location',
        'cover_image', 'latitude', 'longitude', 'google_maps_url',
    ];

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
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

        return asset('images/destinations/' . ((($this->id ?: 1) - 1) % 3 + 1) . '.jpg');
    }
}
