<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Notifications\DatabaseNotification;

class NotificationService
{
    public function bookingCreated(Booking $booking): void
    {
        $booking->user->notify(new \App\Notifications\BookingStatusChanged(
            $booking,
            "Booking {$booking->booking_code} berhasil dibuat."
        ));
    }

    public function bookingConfirmed(Booking $booking): void
    {
        $booking->user->notify(new \App\Notifications\BookingStatusChanged(
            $booking,
            "Booking Anda ({$booking->booking_code}) telah dikonfirmasi."
        ));
    }

    public function bookingCompleted(Booking $booking): void
    {
        $booking->user->notify(new \App\Notifications\BookingStatusChanged(
            $booking,
            "Booking Anda ({$booking->booking_code}) telah selesai. Terima kasih!"
        ));
    }
}
