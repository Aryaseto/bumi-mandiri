<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesCoverImage;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreActivityRequest;
use App\Models\Activity;
use App\Models\Category;
use App\Models\Destination;
use Illuminate\Http\Request;

class AdminActivityController extends Controller
{
    use HandlesCoverImage;

    public function index(Request $request)
    {
        $activities = Activity::with(['category', 'destination'])
            ->withCount('bookingItems')
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%' . $request->input('q') . '%'))
            ->when($request->filled('category'), fn ($q) => $q->where('category_id', $request->input('category')))
            ->when($request->filled('destination'), fn ($q) => $q->where('destination_id', $request->input('destination')))
            ->when($request->input('status') === 'active', fn ($q) => $q->where('is_active', true))
            ->when($request->input('status') === 'inactive', fn ($q) => $q->where('is_active', false))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.activities.index', [
            'activities' => $activities,
            'categories' => Category::orderBy('name')->get(),
            'destinations' => Destination::orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.activities.form', [
            'activity' => new Activity(),
            'categories' => Category::orderBy('name')->get(),
            'destinations' => Destination::orderBy('name')->get(),
        ]);
    }

    public function store(StoreActivityRequest $request)
    {
        $data = $request->safe()->except(['cover_image', 'remove_cover_image']);
        $data['slug'] = Activity::uniqueSlug($data['name']);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');
        $data['cover_image'] = $this->storeCover($request, 'activities');

        Activity::create($data);

        return redirect()->route('admin.activities.index')->with('success', 'Aktivitas berhasil ditambahkan.');
    }

    public function edit(Activity $activity)
    {
        return view('admin.activities.form', [
            'activity' => $activity,
            'categories' => Category::orderBy('name')->get(),
            'destinations' => Destination::orderBy('name')->get(),
        ]);
    }

    public function update(StoreActivityRequest $request, Activity $activity)
    {
        $data = $request->safe()->except(['cover_image', 'remove_cover_image']);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');

        if ($newCover = $this->storeCover($request, 'activities')) {
            $this->deleteCover($activity->cover_image);
            $data['cover_image'] = $newCover;
        } elseif ($request->boolean('remove_cover_image')) {
            $this->deleteCover($activity->cover_image);
            $data['cover_image'] = null;
        }

        $activity->update($data);

        return redirect()->route('admin.activities.index')->with('success', 'Aktivitas berhasil diperbarui.');
    }

    public function destroy(Activity $activity)
    {
        // FK booking_items.activity_id memakai cascade: menghapus aktivitas yang
        // pernah dibooking akan menghilangkan riwayat transaksi. Cegah, sarankan Inactive.
        if ($activity->bookingItems()->exists()) {
            return back()->with('error', 'Aktivitas ini sudah memiliki riwayat booking sehingga tidak dapat dihapus. Ubah statusnya menjadi Inactive agar tidak tampil di website.');
        }

        $this->deleteCover($activity->cover_image);
        $activity->delete();

        return back()->with('success', 'Aktivitas berhasil dihapus.');
    }
}
