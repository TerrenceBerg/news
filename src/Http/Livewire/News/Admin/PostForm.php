<?php

namespace App\Http\Livewire\News\Admin;
use App\Models\News\Category;
use App\Models\News\Post;
use App\Models\News\Tag;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class PostForm extends Component
{
    use WithFileUploads;
    
    // Remove type hinting from Post to prevent errors
    public $post;
    public $title = '';
    public $content = '';
    public $excerpt = '';
    public $category_id = '';
    public $is_published = false;
    public $featured_image;
    public $tags = [];
    public $allTags = [];
    public $categories = [];
    
    // Modify the mount method to better handle initialization
    public function mount($post = null)
    {
        // Make sure post is properly initialized
        $this->post = $post ?? new Post();
        
        // If it's an existing post, populate the form fields
        if ($this->post->exists) {
            $this->title = $this->post->title;
            $this->content = $this->post->content;
            $this->excerpt = $this->post->excerpt;
            $this->category_id = $this->post->category_id;
            $this->is_published = $this->post->is_published;
            $this->tags = $this->post->tags->pluck('id')->toArray();
        }
        
        // Always load these for the form
        $this->loadFormData();
    }

    // Extract loading form data to a separate method for clarity
    private function loadFormData()
    {
        $this->categories = Category::all();
        $this->allTags = Tag::all();
    }
    
    public function save()
    {
        $validated = $this->validate([
            'title' => 'required|min:3',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
            'featured_image' => $this->post->exists ? 'nullable|image|max:1024' : 'nullable|image|max:1024',
        ]);
        
        $this->post->title = $this->title;
        
        // Generate slug if doesn't exist or it's a new post
        if (!$this->post->exists || !$this->post->slug) {
            $this->post->slug = Str::slug($this->title);
        }
        
        $this->post->content = $this->content;
        $this->post->excerpt = $this->excerpt;
        $this->post->category_id = $this->category_id;
        $this->post->is_published = $this->is_published;
        
        if (!$this->post->exists) {
            $this->post->user_id = auth()->id();
        }
        
        if ($this->is_published && !$this->post->published_at) {
            $this->post->published_at = now();
        }
        
        if ($this->featured_image) {
            $filename = $this->featured_image->store('featured-images', 'public');
            $this->post->featured_image = $filename;
        }
        
        $this->post->save();
        
        if (!empty($this->tags)) {
            $this->post->tags()->sync($this->tags);
        }
        
        session()->flash('message', $this->post->wasRecentlyCreated ? 'Post created successfully!' : 'Post updated successfully!');
        
        return redirect()->route('admin.posts.index');
    }
    
    public function render()
    {
        return view('vendor.news.livewire.admin.post-form');
    }
}
