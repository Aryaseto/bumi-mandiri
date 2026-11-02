<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Package;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class BookingService
{
    /**
     * Hitung ringkasan harga secara real-time berdasarkan activity, package, dan jumlah peserta.
     */
    public function calculateSummary(Activity $activity, int $participants, ?Package $package = null): array
    {
        $activitySubtotal = $activity->price * $participants;
        $packagePrice = $package ? $package->base_price : 0;

        $subtotal = $activitySubtotal + $packagePrice;
        $discount = 0;

        // Contoh aturan diskon sederhana: rombongan besar dapat potongan 5%
        if ($participants >= 20) {
            $discount = $subtotal * 0.05;
        }

        $total = $subtotal - $discount;

        return [
            'activity_price' => $activity->price,
            'participants' => $participants,
            'activity_subtotal' => $activitySubtotal,
            'package_price' => $packagePrice,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $total,
        ];
    }

    /**
     * Buat booking + booking_items + generate kode booking unik BM-2026-xxxxx.
     */
    public function createBooking(User $user, Activity $activity, array $data, ?Package $package = null): Booking
    {
        return DB::transaction(function () use ($user, $activity, $data, $package) {
            $summary = $this->calculateSummary($activity, (int) $data['participants'], $package);

            $booking = Booking::create([
                'booking_code' => $this->generateBookingCode(),
                'user_id' => $user->id,
                'package_id' => $package?->id,
                'booking_date' => $data['booking_date'],
                'booking_time' => $data['booking_time'] ?? null,
                'participants' => $data['participants'],
                'subtotal' => $summary['subtotal'],
                'discount' => $summary['discount'],
                'total_price' => $summary['total'],
                'status' => 'pending',
                'notes' => $data['notes'] ?? null,
            ]);

            BookingItem::create([
                'booking_id' => $booking->id,
                'activity_id' => $activity->id,
                'quantity' => $data['participants'],
                'price' => $activity->price,
                'subtotal' => $summary['activity_subtotal'],
            ]);

            return $booking;
        });
    }

    private function generateBookingCode(): string
    {
        $year = now()->year;
        $lastNumber = (int) Booking::where('booking_code', 'like', "BM-{$year}-%")
            ->orderByDesc('id')
            ->value(DB::raw('SUBSTRING(booking_code, -5)')) ?: 0;

        $next = str_pad((string) ($lastNumber + 1), 5, '0', STR_PAD_LEFT);

        return "BM-{$year}-{$next}";
    }
}
