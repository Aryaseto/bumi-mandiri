<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Gallery;
use Illuminate\Http\Request;

class AdminGalleryController extends Controller
{
    public function index()
    {
        $gallery = Gallery::with('category')->latest()->paginate(16);

        return view('admin.gallery.index', compact('gallery'));
    }

    public function create()
    {
        return view('admin.gallery.form', ['item' => new Gallery(), 'categories' => Category::all()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        Gallery::create([
            'title' => $validated['title'],
            'category_id' => $validated['category_id'] ?? null,
            'image_path' => $request->file('image')->store('gallery', 'public'),
            'is_featured' => $request->boolean('is_featured'),
        ]);

        return redirect()->route('admin.gallery.index')->with('success', 'Foto berhasil ditambahkan.');
    }

    public function edit(Gallery $gallery)
    {
        return view('admin.gallery.form', ['item' => $gallery, 'categories' => Category::all()]);
    }

    public function update(Request $request, Gallery $gallery)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        $data = [
            'title' => $validated['title'],
            'category_id' => $validated['category_id'] ?? null,
            'is_featured' => $request->boolean('is_featured'),
        ];

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('gallery', 'public');
        }

        $gallery->update($data);

        return redirect()->route('admin.gallery.index')->with('success', 'Foto berhasil diperbarui.');
    }

    public function destroy(Gallery $gallery)
    {
        $gallery->delete();

        return back()->with('success', 'Foto berhasil dihapus.');
    }
}
