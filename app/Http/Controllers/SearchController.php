<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Article;
use App\Models\Package;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $keyword = trim((string) $request->get('q'));

        $activities = collect();
        $packages = collect();
        $articles = collect();

        if ($keyword !== '') {
            $activities = Activity::active()
                ->where('name', 'like', "%{$keyword}%")
                ->orWhere('short_description', 'like', "%{$keyword}%")
                ->with(['category', 'destination'])
                ->get();

            $packages = Package::active()
                ->where('name', 'like', "%{$keyword}%")
                ->get();

            $articles = Article::published()
                ->where('title', 'like', "%{$keyword}%")
                ->get();
        }

        return view('search.index', compact('keyword', 'activities', 'packages', 'articles'));
    }
}
