<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $query = Gallery::with('category');

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
        }

        $gallery = $query->latest()->paginate(16);
        $categories = Category::all();

        return view('gallery.index', compact('gallery', 'categories'));
    }
}
