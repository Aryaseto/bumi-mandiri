<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = [
        'user_id', 'booking_id', 'activity_id', 'guest_name', 'guest_email',
        'rating', 'comment', 'ip_address', 'is_approved',
    ];

    protected function casts(): array
    {
        return [
            'is_approved' => 'boolean',
            'rating' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('is_approved', true);
    }

    /**
     * Nama yang ditampilkan: akun terdaftar, lalu nama pengunjung umum.
     */
    public function displayName(): string
    {
        return $this->user?->name
            ?? $this->guest_name
            ?? 'Pengunjung Bumi Mandiri';
    }

    public function initials(): string
    {
        $name = trim($this->displayName());
        $parts = preg_split('/\s+/', $name) ?: [];
        $initials = '';

        foreach (array_slice($parts, 0, 2) as $part) {
            $initials .= mb_strtoupper(mb_substr($part, 0, 1));
        }

        return $initials !== '' ? $initials : 'P';
    }

    /**
     * Ulasan dari booking yang sudah selesai dianggap terverifikasi.
     */
    public function isVerified(): bool
    {
        return $this->booking_id !== null;
    }
}
