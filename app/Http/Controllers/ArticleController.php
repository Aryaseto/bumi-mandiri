<?php

namespace App\Http\Controllers;

use App\Models\Article;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::published()->latest()->paginate(9);

        return view('articles.index', compact('articles'));
    }

    public function show(Article $article)
    {
        $related = Article::published()
            ->where('id', '!=', $article->id)
            ->latest()
            ->take(3)
            ->get();

        return view('articles.show', compact('article', 'related'));
    }
}
