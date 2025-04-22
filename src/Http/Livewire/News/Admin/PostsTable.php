<?php

namespace App\Http\Livewire\News\Admin;

use App\Models\News\Post;
use Livewire\Component;
use Livewire\WithPagination;

class PostsTable extends Component
{
    use WithPagination;
    
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function deletePost(Post $post)
    {
        $post->delete();
        session()->flash('message', 'Post deleted successfully!');
    }
    
    public function render()
    {
        return view('vendor.news.livewire.admin.posts-table', [
            'posts' => Post::with(['user', 'category'])
                ->where('title', 'like', '%'.$this->search.'%')
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate(50),
        ]);
    }
}
