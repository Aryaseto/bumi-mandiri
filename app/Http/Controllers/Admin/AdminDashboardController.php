<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Review;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalRevenue = (float) Payment::where('payment_status', 'paid')->sum('amount');
        $revenueThisMonth = (float) Payment::where('payment_status', 'paid')
            ->where('paid_at', '>=', now()->startOfMonth())
            ->sum('amount');

        $totalBooking = Booking::count();
        $pendingBooking = Booking::where('status', 'pending')->count();
        $todayBookings = Booking::whereDate('booking_date', today())
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();

        $activeUsers = User::whereHas('role', fn ($q) => $q->where('name', 'user'))->count();
        $newUsersThisMonth = User::whereHas('role', fn ($q) => $q->where('name', 'user'))
            ->where('created_at', '>=', now()->startOfMonth())
            ->count();

        $totalExperiences = Activity::where('is_active', true)->count();
        $totalActivities = Activity::count();

        $statusCounts = Booking::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $statusLabels = ['pending', 'confirmed', 'completed', 'cancelled'];
        $statusBreakdown = collect($statusLabels)->mapWithKeys(fn ($status) => [
            $status => (int) ($statusCounts[$status] ?? 0),
        ]);

        // Booking overview for the last six months. Aggregation happens in PHP
        // so the query stays portable between MySQL and SQLite.
        $months = collect(range(0, 5))->map(fn ($i) => now()->subMonths(5 - $i)->startOfMonth());

        $bookingOverview = Booking::where('created_at', '>=', $months->first())
            ->get(['created_at'])
            ->countBy(fn ($booking) => $booking->created_at->format('Y-m'));

        $bookingChartLabels = $months->map(fn ($month) => $month->translatedFormat('M'))->toArray();
        $bookingChartData = $months->map(fn ($month) => $bookingOverview[$month->format('Y-m')] ?? 0)->toArray();
        $bookingChartMax = max(1, max($bookingChartData));

        $popularActivities = Activity::query()
            ->withCount('bookingItems')
            ->whereHas('bookingItems')
            ->orderByDesc('booking_items_count')
            ->take(5)
            ->get(['id', 'name', 'slug', 'price', 'rating_avg', 'review_count', 'is_active']);

        $recentBookings = Booking::with(['user', 'items.activity'])->latest()->take(6)->get();

        $upcomingBookings = Booking::with(['user', 'items.activity'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->whereDate('booking_date', '>=', today())
            ->orderBy('booking_date')
            ->take(4)
            ->get();

        $pendingReviews = Review::with(['user', 'activity'])
            ->where('is_approved', false)
            ->latest()
            ->take(3)
            ->get();

        $pendingReviewCount = Review::where('is_approved', false)->count();

        $approvedReviewCount = Review::approved()->count();
        $averageRating = $approvedReviewCount > 0
            ? round((float) Review::approved()->avg('rating'), 1)
            : 0;

        return view('admin.dashboard', compact(
            'totalRevenue', 'revenueThisMonth', 'totalBooking', 'pendingBooking', 'todayBookings',
            'activeUsers', 'newUsersThisMonth', 'totalExperiences', 'totalActivities',
            'statusBreakdown', 'bookingChartLabels', 'bookingChartData', 'bookingChartMax',
            'popularActivities', 'recentBookings', 'upcomingBookings',
            'pendingReviews', 'pendingReviewCount', 'approvedReviewCount', 'averageRating'
        ));
    }
}
