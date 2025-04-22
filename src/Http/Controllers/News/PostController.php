<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use App\Models\News\Category;
use App\Models\News\Post;
use App\Models\News\Tag;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    public function show(Post $post): View
    {
        if (!$post->is_published && (!auth()->user() || !auth()->user()->isAuthor())) {
            abort(404);
        }
        
        return view('vendor.news.posts.show', compact('post'));
    }
    
    public function byCategory(Category $category): View
    {
        $posts = Post::where('category_id', $category->id)
            ->where('is_published', true)
            ->latest('published_at')
            ->paginate(9);
            
        return view('vendor.news.posts.category', compact('category', 'posts'));
    }
    
    public function byTag(Tag $tag): View
    {
        $posts = $tag->posts()
            ->where('is_published', true)
            ->latest('published_at')
            ->paginate(9);
            
        return view('vendor.news.posts.tag', compact('tag', 'posts'));
    }
}
