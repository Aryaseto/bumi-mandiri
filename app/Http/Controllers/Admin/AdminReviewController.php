<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Services\ReviewService;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    public function __construct(private ReviewService $reviews) {}

    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $search = trim((string) $request->get('q'));

        $query = Review::with(['user', 'activity', 'booking'])->latest();

        if ($status === 'approved') {
            $query->where('is_approved', true);
        } elseif ($status === 'pending') {
            $query->where('is_approved', false);
        }

        if ($search !== '') {
            $query->where(function ($sub) use ($search) {
                $sub->where('guest_name', 'like', "%{$search}%")
                    ->orWhere('comment', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($user) => $user->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('activity', fn ($activity) => $activity->where('name', 'like', "%{$search}%"));
            });
        }

        $reviews = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => Review::count(),
            'approved' => Review::approved()->count(),
            'pending' => Review::where('is_approved', false)->count(),
            'average' => round((float) Review::approved()->avg('rating'), 1),
        ];

        $statusCounts = [
            'all' => $stats['total'],
            'approved' => $stats['approved'],
            'pending' => $stats['pending'],
        ];

        return view('admin.reviews.index', compact(
            'reviews',
            'stats',
            'status',
            'statusCounts',
            'search'
        ));
    }

    public function approve(Review $review)
    {
        $review->update(['is_approved' => ! $review->is_approved]);

        $this->reviews->syncActivityRating($review->activity_id);

        return back()->with('success', $review->is_approved ? 'Review disetujui dan ditampilkan.' : 'Review disembunyikan dari situs.');
    }

    public function destroy(Review $review)
    {
        $activityId = $review->activity_id;
        $review->delete();

        $this->reviews->syncActivityRating($activityId);

        return back()->with('success', 'Review berhasil dihapus.');
    }
}
