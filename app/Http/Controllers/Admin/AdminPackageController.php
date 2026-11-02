<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminPackageController extends Controller
{
    public function index()
    {
        $packages = Package::withCount('activities')->paginate(15);

        return view('admin.packages.index', compact('packages'));
    }

    public function create()
    {
        return view('admin.packages.form', ['package' => new Package(), 'activities' => Activity::all()]);
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);
        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active', true);

        $package = Package::create($validated);
        $package->activities()->sync($request->input('activity_ids', []));

        return redirect()->route('admin.packages.index')->with('success', 'Paket berhasil ditambahkan.');
    }

    public function edit(Package $package)
    {
        return view('admin.packages.form', ['package' => $package, 'activities' => Activity::all()]);
    }

    public function update(Request $request, Package $package)
    {
        $validated = $this->validated($request);
        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active');

        $package->update($validated);
        $package->activities()->sync($request->input('activity_ids', []));

        return redirect()->route('admin.packages.index')->with('success', 'Paket berhasil diperbarui.');
    }

    public function destroy(Package $package)
    {
        $package->delete();

        return back()->with('success', 'Paket berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'target_segment' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'min_participants' => ['required', 'integer', 'min:1'],
            'max_participants' => ['nullable', 'integer', 'min:1'],
            'estimated_duration' => ['nullable', 'string', 'max:100'],
        ]);
    }
}
