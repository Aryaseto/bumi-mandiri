<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Activity;
use App\Models\Booking;
use App\Models\Package;
use App\Services\BookingService;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function __construct(
        private BookingService $bookingService,
        private NotificationService $notificationService,
    ) {}

    /**
     * STEP 01-05: Choose Experience -> Select Date -> Participants -> Package -> Customize
     * Ditampilkan dalam satu halaman dengan Alpine.js step-state agar UX tetap "multi-step"
     * tanpa reload, sesuai instruksi "jangan buat form panjang dalam satu halaman".
     */
    public function start(Activity $activity)
    {
        $packages = Package::active()->get();

        return view('booking.start', compact('activity', 'packages'));
    }

    /**
     * STEP 06: Checkout -> simpan booking
     */
    public function store(StoreBookingRequest $request, Activity $activity)
    {
        $package = $request->package_id ? Package::find($request->package_id) : null;

        $booking = $this->bookingService->createBooking(
            Auth::user(),
            $activity,
            $request->validated(),
            $package
        );

        $this->notificationService->bookingCreated($booking);

        return redirect()->route('booking.confirmed', $booking);
    }

    /**
     * STEP 07: Booking Confirmed
     */
    public function confirmed(Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id(), 403);

        $booking->load('items.activity', 'package');

        return view('booking.confirmed', compact('booking'));
    }

    public function index()
    {
        $bookings = Auth::user()->bookings()->with('items.activity')->latest()->paginate(10);

        return view('dashboard.bookings-index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id(), 403);

        $booking->load('items.activity', 'package', 'payment', 'review');

        return view('dashboard.bookings-show', compact('booking'));
    }

    public function cancel(Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id(), 403);
        abort_if(! in_array($booking->status, ['pending', 'confirmed']), 400);

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Booking berhasil dibatalkan.');
    }
}
