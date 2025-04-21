<?php

namespace Tuna976\NEWS\Livewire\News;

use Tuna976\NEWS\Models\Comment;
use Tuna976\NEWS\Models\Post;
use Livewire\Component;

class CommentSection extends Component
{
    public Post $post;
    public $content = '';
    
    public function mount(Post $post)
    {
        $this->post = $post;
    }
    
    public function addComment()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        $this->validate([
            'content' => 'required|min:3',
        ]);
        
        Comment::create([
            'content' => $this->content,
            'post_id' => $this->post->id,
            'user_id' => auth()->id(),
            'is_approved' => auth()->user()->isAdmin() || auth()->user()->isAuthor(),
        ]);
        
        $this->content = '';
        session()->flash('comment_message', 'Comment added successfully!');
    }
    
    public function render()
    {
        return view('livewire.news.comment-section', [
            'comments' => $this->post->comments()
                ->where('is_approved', true)
                ->with('user')
                ->latest()
                ->get(),
        ]);
    }
}
