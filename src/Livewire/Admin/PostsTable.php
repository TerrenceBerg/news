<?php

namespace App\Livewire\Admin;

use App\Models\Post;
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
        return view('livewire.admin.posts-table', [
            'posts' => Post::with(['user', 'category'])
                ->where('title', 'like', '%'.$this->search.'%')
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate(50),
        ]);
    }
}
