<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    public function __construct(private NotificationService $notificationService) {}

    public function index(Request $request)
    {
        $query = Booking::with(['user', 'items.activity']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->latest()->paginate(15)->withQueryString();

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'items.activity', 'package', 'payment', 'review']);

        return view('admin.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,confirmed,completed,cancelled'],
        ]);

        $booking->update($validated);

        if ($validated['status'] === 'confirmed') {
            $this->notificationService->bookingConfirmed($booking);
        } elseif ($validated['status'] === 'completed') {
            $this->notificationService->bookingCompleted($booking);
        }

        return back()->with('success', 'Status booking berhasil diperbarui.');
    }
}
