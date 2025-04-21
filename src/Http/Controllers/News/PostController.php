<?php

namespace Tuna976\NEWS\Http\Controllers\News;

use Tuna976\NEWS\Http\Controllers\Controller;
use Tuna976\NEWS\Models\Category;
use Tuna976\NEWS\Models\Post;
use Tuna976\NEWS\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    public function show(Post $post): View
    {
        if (!$post->is_published && (!auth()->user() || !auth()->user()->isAuthor())) {
            abort(404);
        }
        
        return view('news::news.posts.show', compact('post'));
    }
    
    public function byCategory(Category $category): View
    {
        $posts = Post::where('category_id', $category->id)
            ->where('is_published', true)
            ->latest('published_at')
            ->paginate(9);
            
        return view('news::news.posts.category', compact('category', 'posts'));
    }
    
    public function byTag(Tag $tag): View
    {
        $posts = $tag->posts()
            ->where('is_published', true)
            ->latest('published_at')
            ->paginate(9);
            
        return view('news::news.posts.tag', compact('tag', 'posts'));
    }
}
