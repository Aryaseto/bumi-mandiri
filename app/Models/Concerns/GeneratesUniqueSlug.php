<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

trait GeneratesUniqueSlug
{
    /**
     * Buat slug yang dijamin unik pada tabel model ini.
     * Jika "tubing-seru" sudah ada, hasilnya "tubing-seru-2", "tubing-seru-3", dst.
     */
    public static function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'item';
        $slug = $base;
        $counter = 2;

        while (static::query()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base . '-' . $counter++;
        }

        return $slug;
    }
}
