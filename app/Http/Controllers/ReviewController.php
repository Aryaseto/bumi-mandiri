<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\StoreVisitorRatingRequest;
use App\Models\Activity;
use App\Models\Booking;
use App\Models\Review;
use App\Services\ReviewService;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function __construct(private ReviewService $reviews) {}

    /**
     * Review hanya dapat diberikan oleh user yang memiliki booking berstatus Completed
     * untuk activity yang bersangkutan.
     */
    public function store(StoreReviewRequest $request, Booking $booking, Activity $activity)
    {
        abort_if($booking->user_id !== Auth::id(), 403);
        abort_unless($booking->isCompleted(), 400, 'Review hanya dapat diberikan untuk booking yang sudah selesai.');

        $alreadyReviewed = $booking->reviews()
            ->where('activity_id', $activity->id)
            ->exists();
        abort_if($alreadyReviewed, 400, 'Anda sudah memberikan review untuk aktivitas ini.');

        $booking->reviews()->create([
            'user_id' => Auth::id(),
            'activity_id' => $activity->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => false, // menunggu approval admin
        ]);

        return back()->with('success', 'Terima kasih! Review Anda akan tampil setelah disetujui admin.');
    }

    /**
     * Rating pengunjung umum: tanpa login dan tanpa booking.
     * Rating langsung tampil, admin tetap bisa menyembunyikan atau menghapusnya.
     */
    public function storeVisitor(StoreVisitorRatingRequest $request, Activity $activity)
    {
        $data = $request->validated();

        Review::create([
            'user_id' => Auth::id(),
            'activity_id' => $activity->id,
            'booking_id' => null,
            'guest_name' => $data['name'],
            'guest_email' => $data['email'] ?? null,
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
            'ip_address' => $request->ip(),
            'is_approved' => true,
        ]);

        $this->reviews->syncActivityRating($activity->id);

        return redirect()
            ->to(route('activities.show', $activity).'#rating')
            ->with('rating_success', 'Terima kasih! Rating Anda sudah tampil di halaman ini.');
    }
}
