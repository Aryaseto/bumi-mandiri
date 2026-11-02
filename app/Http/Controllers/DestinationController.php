<?php

namespace App\Http\Controllers;

use App\Models\Destination;

class DestinationController extends Controller
{
    public function index()
    {
        $destinations = Destination::withCount('activities')->paginate(9);

        return view('destinations.index', compact('destinations'));
    }

    public function show(Destination $destination)
    {
        $destination->load(['activities' => fn ($q) => $q->active()]);

        return view('destinations.show', compact('destination'));
    }
}
