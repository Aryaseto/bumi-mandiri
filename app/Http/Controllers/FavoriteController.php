<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function toggle(Activity $activity)
    {
        $user = Auth::user();
        $existing = $user->favorites()->where('activity_id', $activity->id)->first();

        if ($existing) {
            $existing->delete();
            $message = 'Dihapus dari favorit.';
        } else {
            $user->favorites()->create(['activity_id' => $activity->id]);
            $message = 'Ditambahkan ke favorit.';
        }

        return back()->with('success', $message);
    }

    public function index()
    {
        $favorites = Auth::user()->favorites()->with('activity.category', 'activity.destination')->latest()->paginate(9);

        return view('dashboard.favorites', compact('favorites'));
    }
}
