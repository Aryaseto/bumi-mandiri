<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

class Booking extends Model
{
    protected $fillable = [
        'booking_code', 'user_id', 'package_id', 'booking_date', 'booking_time',
        'participants', 'subtotal', 'discount', 'total_price', 'status', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
            'subtotal' => 'decimal:2',
            'discount' => 'decimal:2',
            'total_price' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(BookingItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function timeLabel(): ?string
    {
        if (! $this->booking_time) {
            return null;
        }

        return Carbon::parse($this->booking_time)->format('H:i');
    }
}
