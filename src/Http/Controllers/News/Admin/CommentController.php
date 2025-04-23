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

    public function update(Request $request, Comment $comment): RedirectResponse
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);
        
        $comment->update($validated);
        
        return redirect()->route('admin.comments.index')
            ->with('success', 'Comment updated successfully.');
    }
    
    public function destroy(Comment $comment): RedirectResponse
    {
        $comment->delete();
        
        return redirect()->route('admin.comments.index')
            ->with('success', 'Comment deleted successfully.');
    }
    
    public function approve(Comment $comment): RedirectResponse
    {
        $comment->update(['is_approved' => true]);
        
        return redirect()->route('admin.comments.index')
            ->with('success', 'Comment approved successfully.');
    }
    
    public function reject(Comment $comment): RedirectResponse
    {
        $comment->update(['is_approved' => false]);
        
        return redirect()->route('admin.comments.index')
            ->with('success', 'Comment rejected successfully.');
    }
}
