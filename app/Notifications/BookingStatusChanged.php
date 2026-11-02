<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BookingStatusChanged extends Notification
{
    use Queueable;

    public function __construct(
        public Booking $booking,
        public string $message
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'booking_code' => $this->booking->booking_code,
            'message' => $this->message,
        ];
    }
}
