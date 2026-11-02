<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::whereHas('role', fn ($q) => $q->where('name', 'user'))->get();
        $activities = Activity::all();
        $statuses = ['pending', 'confirmed', 'completed', 'completed', 'cancelled'];

        foreach ($users as $index => $user) {
            $status = $statuses[$index % count($statuses)];
            $participants = rand(2, 10);
            $activity = $activities->random();
            $subtotal = $activity->price * $participants;

            $booking = Booking::create([
                'booking_code' => 'BM-2026-' . str_pad($index + 1, 5, '0', STR_PAD_LEFT),
                'user_id' => $user->id,
                'package_id' => null,
                'booking_date' => now()->addDays(rand(-30, 30))->toDateString(),
                'booking_time' => '09:00:00',
                'participants' => $participants,
                'subtotal' => $subtotal,
                'discount' => 0,
                'total_price' => $subtotal,
                'status' => $status,
                'notes' => null,
            ]);

            BookingItem::create([
                'booking_id' => $booking->id,
                'activity_id' => $activity->id,
                'quantity' => $participants,
                'price' => $activity->price,
                'subtotal' => $subtotal,
            ]);

            if (in_array($status, ['confirmed', 'completed'])) {
                Payment::create([
                    'booking_id' => $booking->id,
                    'payment_code' => 'PAY-' . $booking->booking_code,
                    'amount' => $subtotal,
                    'payment_method' => 'manual_transfer',
                    'payment_status' => 'paid',
                    'paid_at' => now(),
                ]);
            }
        }
    }
}
