<?php

namespace App\Http\Controllers\News\Admin;

use App\Http\Controllers\Controller;
use App\Models\News\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommentController extends Controller
{
    public function index(): View
    {
        $comments = Comment::with(['post', 'user'])->latest()->paginate(10);
        return view('vendor.news.admin.comments.index', compact('comments'));
    }

    public function edit(Comment $comment): View
    {
        return view('vendor.news.admin.comments.edit', compact('comment'));
    }
}
