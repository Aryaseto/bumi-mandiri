<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with('author')->latest()->paginate(15);

        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        return view('admin.articles.form', ['article' => new Article()]);
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);
        $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(4);
        $validated['author_id'] = Auth::id();
        $validated['is_published'] = $request->boolean('is_published', true);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('articles', 'public');
        }

        Article::create($validated);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil ditambahkan.');
    }

    public function edit(Article $article)
    {
        return view('admin.articles.form', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        $validated = $this->validated($request);
        $validated['is_published'] = $request->boolean('is_published');

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('articles', 'public');
        }

        $article->update($validated);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(Article $article)
    {
        $article->delete();

        return back()->with('success', 'Artikel berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'reading_time' => ['required', 'integer', 'min:1'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);
    }
}
