<?php

namespace App\Http\Controllers\News;
use App\Http\Controllers\Controller;
use App\Models\News\Category;
use App\Models\News\Post;
use App\Models\News\Tag;
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
            
        return view('vendor.news.home', compact('featuredPosts', 'recentPosts', 'categories', 'tags'));
    }
}
