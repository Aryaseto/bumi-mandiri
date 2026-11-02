<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesCoverImage;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDestinationRequest;
use App\Models\Destination;
use Illuminate\Http\Request;

class AdminDestinationController extends Controller
{
    use HandlesCoverImage;

    public function index(Request $request)
    {
        $destinations = Destination::withCount('activities')
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%' . $request->input('q') . '%';
                $query->where(fn ($w) => $w->where('name', 'like', $term)->orWhere('location', 'like', $term));
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.destinations.index', compact('destinations'));
    }

    public function create()
    {
        return view('admin.destinations.form', ['destination' => new Destination()]);
    }

    public function store(StoreDestinationRequest $request)
    {
        $data = $request->safe()->except(['cover_image', 'remove_cover_image']);
        $data['slug'] = Destination::uniqueSlug($data['name']);
        $data['cover_image'] = $this->storeCover($request, 'destinations');

        Destination::create($data);

        return redirect()->route('admin.destinations.index')->with('success', 'Destinasi berhasil ditambahkan.');
    }

    public function edit(Destination $destination)
    {
        return view('admin.destinations.form', compact('destination'));
    }

    public function update(StoreDestinationRequest $request, Destination $destination)
    {
        // Slug sengaja tidak diubah saat edit supaya URL publik/bookmark tidak rusak.
        $data = $request->safe()->except(['cover_image', 'remove_cover_image']);

        if ($newCover = $this->storeCover($request, 'destinations')) {
            $this->deleteCover($destination->cover_image);
            $data['cover_image'] = $newCover;
        } elseif ($request->boolean('remove_cover_image')) {
            $this->deleteCover($destination->cover_image);
            $data['cover_image'] = null;
        }

        $destination->update($data);

        return redirect()->route('admin.destinations.index')->with('success', 'Destinasi berhasil diperbarui.');
    }

    public function destroy(Destination $destination)
    {
        // FK activities.destination_id memakai cascade: menghapus destinasi akan
        // ikut menghapus semua aktivitas (dan booking_items miliknya). Cegah itu.
        $count = $destination->activities()->count();

        if ($count > 0) {
            return back()->with('error', "Destinasi tidak dapat dihapus karena masih memiliki {$count} aktivitas. Pindahkan atau hapus aktivitasnya terlebih dahulu.");
        }

        $this->deleteCover($destination->cover_image);
        $destination->delete();

        return back()->with('success', 'Destinasi berhasil dihapus.');
    }
}
