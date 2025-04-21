<?php

namespace Tuna976\NEWS\Http\Controllers\News;
use Tuna976\NEWS\Http\Controllers\Controller;
use Tuna976\NEWS\Models\Category;
use Tuna976\NEWS\Models\Post;
use Tuna976\NEWS\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featuredPosts = Post::where('is_published', true)
            ->latest('published_at')
            ->take(20)
            ->get();
        
        $recentPosts = Post::where('is_published', true)
            ->latest('published_at')
            ->paginate(20);
            
        $categories = Category::withCount('posts')->get();
        $tags = Tag::withCount('posts')->get();
            
        return view('news::news.home', compact('featuredPosts', 'recentPosts', 'categories', 'tags'));
    }
}
