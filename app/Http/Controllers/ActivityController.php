<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Category;
use App\Models\Review;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::active()->with(['category', 'destination']);
        $hasReviews = Activity::where('review_count', '>', 0)->exists();

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('q')) {
            $query->where('name', 'like', '%'.$request->q.'%');
        }

        if ($hasReviews && $request->filled('rating')) {
            $query->where('rating_avg', '>=', (float) $request->rating);
        }

        if ($request->filled('duration')) {
            match ($request->duration) {
                'short' => $query->where('duration_minutes', '<=', 90),
                'medium' => $query->whereBetween('duration_minutes', [91, 240]),
                'long' => $query->where('duration_minutes', '>', 240),
                default => null,
            };
        }

        $sort = $hasReviews ? $request->get('sort', 'popular') : 'price_low';
        match ($sort) {
            'price_low' => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            'rating' => $query->orderByDesc('rating_avg'),
            default => $query->orderByDesc('review_count'),
        };

        $activities = $query->paginate(9)->withQueryString();
        $categories = Category::withCount('activities')->get();

        $approvedReviews = Review::approved();
        $siteRatingCount = (clone $approvedReviews)->count();
        $siteRating = $siteRatingCount > 0
            ? round((float) $approvedReviews->avg('rating'), 1)
            : 0;

        return view('activities.index', compact(
            'activities',
            'categories',
            'hasReviews',
            'siteRating',
            'siteRatingCount'
        ));
    }

    public function show(Activity $activity)
    {
        $activity->load([
            'category',
            'destination',
            'reviews' => fn ($query) => $query->with('user')->latest(),
        ]);

        $reviews = $activity->reviews;
        $ratingCount = $reviews->count();
        $ratingAverage = $ratingCount > 0 ? round((float) $reviews->avg('rating'), 1) : 0;
        $ratingBreakdown = collect([5, 4, 3, 2, 1])->mapWithKeys(fn ($star) => [
            $star => $reviews->where('rating', $star)->count(),
        ]);

        $related = Activity::active()
            ->where('category_id', $activity->category_id)
            ->where('id', '!=', $activity->id)
            ->take(3)
            ->get();

        $isFavorited = auth()->check()
            ? $activity->favoritedBy()->where('user_id', auth()->id())->exists()
            : false;

        return view('activities.show', compact(
            'activity',
            'related',
            'isFavorited',
            'reviews',
            'ratingCount',
            'ratingAverage',
            'ratingBreakdown'
        ));
    }
}
