<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $upcomingBooking = $user->bookings()
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('booking_date', '>=', now()->toDateString())
            ->with('items.activity')
            ->orderBy('booking_date')
            ->first();

        $recentBookings = $user->bookings()->with('items.activity')->latest()->take(5)->get();

        $totalBookings = $user->bookings()->count();
        $completedBookings = $user->bookings()->where('status', 'completed')->count();

        $favoriteCount = $user->favorites()->count();

        $favoriteActivities = $user->favorites()
            ->with(['activity.category', 'activity.destination'])
            ->latest()
            ->take(3)
            ->get()
            ->pluck('activity')
            ->filter();

        $notifications = $user->notifications()->latest()->take(4)->get();

        return view('dashboard.index', compact(
            'user', 'upcomingBooking', 'recentBookings', 'totalBookings', 'completedBookings',
            'favoriteCount', 'favoriteActivities', 'notifications'
        ));
    }

    public function profile()
    {
        return view('dashboard.profile', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
