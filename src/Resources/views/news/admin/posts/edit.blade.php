@extends('news::news.admin.layouts.admin')

@section('content')
<h1 class="h3 text-center">Edit Post</h1>
<div class="d-flex justify-content-end mb-4">
    <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to Posts
    </a>
    &nbsp;&nbsp;
    <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Add New Post
    </a>
</div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="title" class="form-label fw-bold">Title</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                           id="title" name="title" value="{{ old('title', $post->title) }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="content" class="form-label fw-bold">Content</label>
                    <textarea class="form-control" id="content" name="content">{{ old('content', $post->content) }}</textarea>
                </div>
                
                <div class="mb-3">
                    <label for="source_url" class="form-label fw-bold">Source URL</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-link"></i></span>
                        <input type="url" class="form-control border-dark" id="source_url" name="source_url" 
                               placeholder="URL for embedded content (YouTube, Twitter, Facebook, Instagram, TikTok)"
                               value="{{ old('source_url', $post->source_url) }}">
                    </div>
                    <small class="text-muted">Enter a URL from YouTube, Twitter, Facebook, Instagram, etc. to embed the content</small>
                </div>
                
                <div class="mb-3">
                    <label for="excerpt" class="form-label fw-bold">Excerpt</label>
                    <textarea class="form-control" id="excerpt" name="excerpt" rows="3">{{ old('excerpt', $post->excerpt) }}</textarea>
                    <small class="text-muted">This will be shown in post listings. If left empty, an excerpt will be generated from your content.</small>
                </div>
                
                <div class="mb-3">
                    <label for="category_id" class="form-label fw-bold">Category</label>
                    <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                        <option value="">Select Category</option>
                        @foreach(\App\Models\Category::orderBy('name', 'ASC')->get() as $category)
                            <option value="{{ $category->id }}" {{ (old('category_id', $post->category_id) == $category->id) ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3 form-check form-switch">
                    <input type="checkbox" class="form-check-input" id="is_published" name="is_published" 
                          {{ old('is_published', $post->is_published) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_published">Publish this post</label>
                </div>
                
                <div class="mb-3">
                    <label for="featured_image" class="form-label fw-bold">Featured Image</label>
                    <input type="file" class="form-control @error('featured_image') is-invalid @enderror" 
                          id="featured_image" name="featured_image">
                    <small class="text-muted">Leave empty to keep the current image.</small>
                    @error('featured_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                @if($post->featured_image)
                    <div class="mb-4">
                        <label class="form-label fw-bold">Current Featured Image</label>
                        <img src="{{ Storage::url($post->featured_image) }}" alt="Current Featured Image" class="img-fluid rounded" style="max-height: 250px">
                    </div>
                @endif
                
                <div class="mb-4">
                    <label class="form-label fw-bold">Post Images</label>
                    
                    <div id="image-upload-container" class="border rounded p-3 bg-light mb-3"
                         ondrop="handleDrop(event)" ondragover="handleDragOver(event)" ondragleave="handleDragLeave(event)">
                        <div class="text-center py-5" id="drop-message">
                            <i class="bi bi-cloud-arrow-up fs-1 text-muted"></i>
                            <p class="mb-2">Drag and drop images here</p>
                            <span class="text-muted">or</span>
                            <div class="mt-2">
                                <label for="image-upload" class="btn btn-sm btn-outline-primary">Select Files</label>
                                <input type="file" id="image-upload" multiple accept="image/*" style="display: none;"
                                       onchange="handleFileSelect(event)">
                            </div>
                        </div>
                    </div>
                    
                    <div id="image-preview-container" class="row g-3 mb-3 sortable-container">
                        @foreach($images as $image)
                            <div class="col-6 col-sm-4 col-md-3 mb-3 sortable-item" data-id="{{ $image->id }}">
                                <div class="card h-100">
                                    <img src="{{ Storage::url($image->path) }}" class="card-img-top" style="height: 120px; object-fit: cover;">
                                    <div class="card-body p-2">
                                        <input type="text" class="form-control form-control-sm mb-2" 
                                               placeholder="Alt text" value="{{ $image->alt_text }}"
                                               onchange="updateAltText({{ $image->id }}, this.value)">
                                        <button type="button" class="btn btn-sm btn-danger w-100" 
                                                onclick="deleteImage({{ $image->id }}, this)">
                                            <i class="bi bi-trash"></i> Remove
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label fw-bold">Tags</label>
                    <div class="d-flex flex-wrap gap-2 border p-3 rounded bg-light">
                        @php $postTags = $post->tags->pluck('id')->toArray(); @endphp
                        @foreach(\App\Models\Tag::all() as $tag)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="{{ $tag->id }}" 
                                      id="tag-{{ $tag->id }}" name="tags[]" {{ in_array($tag->id, old('tags', $postTags)) ? 'checked' : '' }}>
                                <label class="form-check-label" for="tag-{{ $tag->id }}">
                                    {{ $tag->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="d-grid d-sm-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Update Post
                    </button>
                    <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-1"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
<script>
    // Initialize Sortable.js for drag-and-drop reordering
    document.addEventListener('DOMContentLoaded', function() {
        const sortableContainer = document.querySelector('.sortable-container');
        if (sortableContainer) {
            const sortable = new Sortable(sortableContainer, {
                animation: 150,
                ghostClass: 'bg-light',
                onEnd: function() {
                    updateImageOrder();
                }
            });
        }
    });

    // Handle drag and drop styling
    function handleDragOver(e) {
        e.preventDefault();
        document.getElementById('image-upload-container').classList.add('border-primary');
        document.getElementById('drop-message').classList.add('text-primary');
    }

    function handleDragLeave(e) {
        e.preventDefault();
        document.getElementById('image-upload-container').classList.remove('border-primary');
        document.getElementById('drop-message').classList.remove('text-primary');
    }

    // Handle file drop
    function handleDrop(e) {
        e.preventDefault();
        document.getElementById('image-upload-container').classList.remove('border-primary');
        document.getElementById('drop-message').classList.remove('text-primary');
        
        if (e.dataTransfer.items) {
            // Use DataTransferItemList interface to access the files
            [...e.dataTransfer.items].forEach((item, i) => {
                // If dropped items aren't files, reject them
                if (item.kind === 'file') {
                    const file = item.getAsFile();
                    if (file.type.startsWith('image/')) {
                        uploadImage(file);
                    }
                }
            });
        } else {
            // Use DataTransfer interface to access the files
            [...e.dataTransfer.files].forEach((file) => {
                if (file.type.startsWith('image/')) {
                    uploadImage(file);
                }
            });
        }
    }

    // Handle file selection from input
    function handleFileSelect(e) {
        const files = e.target.files;
        if (files) {
            [...files].forEach(file => {
                if (file.type.startsWith('image/')) {
                    uploadImage(file);
                }
            });
        }
        // Reset the input
        e.target.value = '';
    }

    // Upload image via AJAX - Fixed with improved error handling
    function uploadImage(file) {
        const formData = new FormData();
        formData.append('image', file);
        formData.append('_token', '{{ csrf_token() }}');
        
        // Show uploading indicator
        const uploadingDiv = document.createElement('div');
        uploadingDiv.className = 'col-6 col-sm-4 col-md-3 mb-3';
        uploadingDiv.innerHTML = `
            <div class="card h-100">
                <div class="card-body d-flex align-items-center justify-content-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        `;
        document.getElementById('image-preview-container').appendChild(uploadingDiv);
        
        fetch('{{ route('admin.posts.upload-image', $post->id) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Remove uploading indicator
                uploadingDiv.remove();
                
                // Add the new image to the preview container
                const imageDiv = document.createElement('div');
                imageDiv.className = 'col-6 col-sm-4 col-md-3 mb-3 sortable-item';
                imageDiv.dataset.id = data.image.id;
                imageDiv.innerHTML = `
                    <div class="card h-100">
                        <img src="${data.url}" class="card-img-top" style="height: 120px; object-fit: cover;">
                        <div class="card-body p-2">
                            <input type="text" class="form-control form-control-sm mb-2" 
                                   placeholder="Alt text" value="${data.image.alt_text}"
                                   onchange="updateAltText(${data.image.id}, this.value)">
                            <button type="button" class="btn btn-sm btn-danger w-100" 
                                    onclick="deleteImage(${data.image.id}, this)">
                                <i class="bi bi-trash"></i> Remove
                            </button>
                        </div>
                    </div>
                `;
                document.getElementById('image-preview-container').appendChild(imageDiv);
                
                // Update image order
                updateImageOrder();
                
                console.log('Image uploaded successfully:', data);
            } else {
                console.error('Upload failed:', data.message || 'Unknown error');
                alert('Failed to upload image: ' + (data.message || 'Unknown error'));
                uploadingDiv.remove();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while uploading the image: ' + error.message);
            uploadingDiv.remove();
        });
    }

    // Delete image via AJAX
    function deleteImage(imageId, buttonElement) {
        if (confirm('Are you sure you want to delete this image?')) {
            fetch(`{{ url('admin/posts/images') }}/${imageId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the image from the DOM
                    const imageDiv = buttonElement.closest('.sortable-item');
                    imageDiv.remove();
                    
                    // Update image order
                    updateImageOrder();
                } else {
                    alert('Failed to delete image.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the image.');
            });
        }
    }

    // Update image alt text
    function updateAltText(imageId, altText) {
        fetch(`{{ url('admin/posts/images') }}/${imageId}/alt`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ alt_text: altText })
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                alert('Failed to update alt text.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    // Update image order
    function updateImageOrder() {
        const imageItems = document.querySelectorAll('.sortable-item');
        const imageOrder = [];
        
        imageItems.forEach((item, index) => {
            imageOrder.push({
                id: item.dataset.id,
                order: index
            });
        });
        
        fetch('{{ route('admin.posts.reorder-images') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ images: imageOrder })
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    $(document).ready(function() {
        $('#content').summernote({
            placeholder: 'Write your post content here...',
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'italic', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            callbacks: {
                onImageUpload: function(files) {
                    // Handle image upload if needed
                    // You can implement image upload functionality here
                }
            }
        });
    });
</script>
@endpush
