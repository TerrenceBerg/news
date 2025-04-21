<?php

namespace Tuna976\NEWS\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Tuna976\NEWS\Models\Comment;
use Tuna976\NEWS\Models\Post;
use Tuna976\NEWS\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'posts' => Post::count(),
            'users' => User::count(),
            'comments' => Comment::count(),
            'recent_posts' => Post::latest()->take(5)->get(),
            'recent_comments' => Comment::with(['post', 'user'])->latest()->take(5)->get(),
        ];
        
        return view('news::admin.dashboard', compact('stats'));
    }
}
