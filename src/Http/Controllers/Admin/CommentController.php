<?php

namespace Tuna976\NEWS\Http\Controllers\Admin;

use Tuna976\NEWS\Http\Controllers\Controller;
use Tuna976\NEWS\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommentController extends Controller
{
    public function index(): View
    {
        $comments = Comment::with(['post', 'user'])->latest()->paginate(10);
        return view('news::admin.comments.index', compact('comments'));
    }

    public function edit(Comment $comment): View
    {
        return view('news::admin.comments.edit', compact('comment'));
    }
}
