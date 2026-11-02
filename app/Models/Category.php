<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'icon', 'description'];

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(Gallery::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
