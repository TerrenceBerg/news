<div>
    <div class="card">
        <div class="card-body">
            <form wire:submit.prevent="save">
                <div class="mb-3">
                    <label for="title" class="form-label fw-bold">Title</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" wire:model.live="title" 
                           placeholder="Enter post title">
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="content" class="form-label fw-bold">Content</label>
                    <textarea class="form-control @error('content') is-invalid @enderror" id="content" rows="10" wire:model.live="content"
                             placeholder="Write your post content here..."></textarea>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="excerpt" class="form-label fw-bold">Excerpt</label>
                    <textarea class="form-control" id="excerpt" rows="3" wire:model.live="excerpt" 
                              placeholder="Brief summary of your post (optional)"></textarea>
                    <small class="text-muted">This will be shown in post listings. If left empty, an excerpt will be generated from your content.</small>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="category_id" class="form-label fw-bold">Category</label>
                        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" wire:model.live="category_id" 
                               aria-label="Select category">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold d-block">Publication Status</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_published" wire:model.live="is_published">
                            <label class="form-check-label" for="is_published">
                                {{ $is_published ? 'Published' : 'Draft' }}
                            </label>
                        </div>
                        <small class="text-muted">Toggle to publish or save as draft</small>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="featured_image" class="form-label fw-bold">Featured Image</label>
                    <input type="file" class="form-control @error('featured_image') is-invalid @enderror" 
                          id="featured_image" wire:model.live="featured_image" accept="image/*">
                    <small class="text-muted">Recommended size: 1200×800 pixels (16:9 ratio)</small>
                    @error('featured_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                @if($featured_image)
                    <div class="mb-4">
                        <img src="{{ $featured_image->temporaryUrl() }}" alt="Preview" class="img-fluid rounded" style="max-height: 250px">
                    </div>
                @elseif(isset($post) && $post->featured_image)
                    <div class="mb-4">
                        <img src="{{ Storage::url($post->featured_image) }}" alt="Featured Image" class="img-fluid rounded" style="max-height: 250px">
                    </div>
                @endif
                
                <div class="mb-4">
                    <label class="form-label fw-bold">Tags</label>
                    <div class="d-flex flex-wrap gap-2 border p-3 rounded bg-light">
                        @foreach($allTags as $tag)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="{{ $tag->id }}" 
                                      id="tag-{{ $tag->id }}" wire:model.live="tags">
                                <label class="form-check-label" for="tag-{{ $tag->id }}">
                                    {{ $tag->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="d-grid d-sm-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> {{ $post && $post->exists ? 'Update Post' : 'Create Post' }}
                    </button>
                    <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-1"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <div wire:loading wire:target="save, featured_image" class="position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center" style="background-color: rgba(0,0,0,0.4); z-index: 9999;">
        <div class="spinner-border text-light" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</div>
