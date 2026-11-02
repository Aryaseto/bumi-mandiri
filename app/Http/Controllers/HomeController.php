<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Article;
use App\Models\Category;
use App\Models\Gallery;
use App\Models\Package;
use App\Models\Review;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('activities')->get();

        $popularActivities = Activity::active()
            ->with(['category', 'destination'])
            ->withCount('bookingItems')
            ->orderByDesc('booking_items_count')
            ->orderByDesc('rating_avg')
            ->take(6)
            ->get();

        $packages = Package::active()->take(4)->get();

        $gallery = Gallery::where('is_featured', true)->take(6)->get();

        $testimonials = Review::approved()
            ->with(['user', 'activity'])
            ->latest()
            ->take(6)
            ->get();

        $approvedReviews = Review::approved();
        $siteRatingCount = (clone $approvedReviews)->count();
        $siteRating = $siteRatingCount > 0
            ? round((float) $approvedReviews->avg('rating'), 1)
            : 0;

        $articles = Article::published()->latest()->take(3)->get();

        return view('home.index', compact(
            'categories',
            'popularActivities',
            'packages',
            'gallery',
            'testimonials',
            'siteRating',
            'siteRatingCount',
            'articles'
        ));
    }
}
