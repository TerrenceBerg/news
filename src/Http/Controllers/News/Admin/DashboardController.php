<?php

namespace App\Http\Controllers\News\Admin;

use App\Http\Controllers\Controller;
use App\Models\News\Comment;
use App\Models\News\Post;
use App\Models\News\NewsUser;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\News\User as User;

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
        
        return view('vendor.News.admin.dashboard', compact('stats'));
    }
}
