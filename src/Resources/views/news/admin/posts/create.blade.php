@extends('news::news.admin.layouts.admin')

@section('content')
<h1 class="h3 text-center">Create New Post</h1>
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
            <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data" id="post-form">
                @csrf
                <div class="container text-center">
                    <div class="row">
                      <div class="col">
                        <div class="mb-3"> 
                            <select class="form-select border-dark" id="category_id" name="category_id">
                                <option value="">Select Category</option>
                                @foreach(Tuna976\NEWS\Models\Category::orderBy('name', 'ASC')->get() as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                      </div>
                      <div class="col">
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input border-dark" id="is_published" name="is_published" 
                                  {{ old('is_published') ? 'checked' : '' }} checked>
                            <label class="form-check-label" for="is_published">Publish this post</label>
                        </div>
                      </div>
                    </div>
                  </div>

                <div class="mb-3">
                    <label for="title" class="form-label fw-bold">Title</label>
                    <input type="text" class="form-control border-dark" id="title" name="title" 
                           value="{{ old('title') }}" placeholder="Enter post title">
                </div>
                
                <div class="mb-3">
                    <label for="content" class="form-label fw-bold">Content</label>
                    <textarea class="form-control" id="content" name="content">{{ old('content') }}</textarea>
                </div>
                
                <div class="mb-3">
                    <label for="source_url" class="form-label fw-bold">Source URL</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-link"></i></span>
                        <input type="url" class="form-control border-dark" id="source_url" name="source_url" 
                               placeholder="URL for embedded content (YouTube, Twitter, Facebook, Instagram, TikTok)"
                               value="{{ old('source_url') }}">
                    </div>
                    <small class="text-muted">Enter a URL from YouTube, Twitter, Facebook, Instagram, etc. to embed the content</small>
                </div>
                
                <div class="mb-3">
                    <label for="excerpt" class="form-label fw-bold">Excerpt</label>
                    <textarea class="form-control border-dark" id="excerpt" name="excerpt" rows="3" 
                              placeholder="Brief summary of your post (optional)">{{ old('excerpt') }}</textarea>
                </div>
                
                <div class="mb-3">
                    <label for="featured_image" class="form-label fw-bold">Featured Image</label>
                    <input type="file" class="form-control border-dark" id="featured_image" name="featured_image">
                </div>
                
                <!-- New section for post images -->
                <div class="mb-4">
                    <label class="form-label fw-bold">Additional Post Images</label>
                    
                    <div id="image-upload-container" class="border rounded p-3 bg-light mb-3"
                         ondrop="handleDrop(event)" ondragover="handleDragOver(event)" ondragleave="handleDragLeave(event)">
                        <div class="text-center py-5" id="drop-message">
                            <i class="bi bi-cloud-arrow-up fs-1 text-muted"></i>
                            <p class="mb-2">Drag and drop images here</p>
                            <span class="text-muted">or</span>
                            <div class="mt-2">
                                <label for="image-upload" class="btn btn-sm btn-outline-primary border-dark">Select Files</label>
                                <input type="file" id="image-upload" multiple accept="image/*" style="display: none;"
                                       onchange="handleFileSelect(event)">
                            </div>
                        </div>
                    </div>
                    
                    <div id="image-preview-container" class="row g-3 mb-3">
                        <!-- Images will be displayed here -->
                    </div>
                    
                    <!-- Hidden field to store temporary image data -->
                    <input type="hidden" id="temp-images" name="temp_images" value="">
                </div>
                
                {{-- <div class="mb-3">
                    <label class="form-label fw-bold">Tags</label>
                    <div class="d-flex flex-wrap gap-2 border p-3 rounded bg-light">
                        @foreach(\App\Models\Tag::all() as $tag)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="{{ $tag->id }}" 
                                      id="tag-{{ $tag->id }}" name="tags[]" {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="tag-{{ $tag->id }}">
                                    {{ $tag->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div> --}}
                
                <!-- Add debug information to see what's happening -->
                @if($errors->any())
                <div class="alert alert-danger mb-3">
                    <h5>Form Error Details:</h5>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <div class="text-center">
                    <button type="submit" class="btn btn-primary" id="submit-button">
                        <i class="bi bi-save me-1"></i> Create Post
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Wait a bit for scripts to fully load
    setTimeout(function() {
        if (typeof jQuery !== 'undefined' && typeof jQuery.fn.summernote !== 'undefined') {
            try {
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
                    ]
                });
                console.log('Summernote initialized successfully');
            } catch (error) {
                console.error('Error initializing Summernote:', error);
            }
        } else {
            console.error('Summernote not available - jQuery or Summernote plugin missing');
            console.log('jQuery available:', typeof jQuery !== 'undefined');
            console.log('Summernote plugin available:', typeof jQuery !== 'undefined' && typeof jQuery.fn.summernote !== 'undefined');
        }
    }, 500); // Short delay to ensure everything is loaded
});

// Store temporary images
let tempImages = [];
let imageCounter = 0;
    
// Initialize form when the DOM is ready or when Bootstrap is loaded
function initializeForm() {
    // Check if the form exists before trying to add listeners
    const form = document.getElementById('post-form');
    if (!form) {
        console.error('Form element not found');
        return;
    }
    
    console.log('Initializing form...');
    
    // Set up form submission handler
    form.addEventListener('submit', function() {
        // Prepare image data
        const tempImagesField = document.getElementById('temp-images');
        if (tempImagesField) {
            tempImagesField.value = JSON.stringify(
                tempImages.map(img => ({
                    id: img.id,
                    alt_text: img.alt_text || ''
                }))
            );
        }
        
        console.log('Submitting form with', tempImages.length, 'images');
    });
}

// Try to initialize immediately and also after DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, checking Bootstrap availability');
    initializeForm();
    
    // In case Bootstrap wasn't available initially, listen for our custom event
    document.addEventListener('bootstrap:loaded', function() {
        console.log('Bootstrap loaded event detected, reinitializing form');
        initializeForm();
    });
});

// Handle drag and drop styling
function handleDragOver(e) {
    e.preventDefault();
    e.stopPropagation();
    const container = document.getElementById('image-upload-container');
    const message = document.getElementById('drop-message');
    
    if (container && message) {
        container.classList.add('border-primary');
        message.classList.add('text-primary');
    }
}

function handleDragLeave(e) {
    e.preventDefault();
    e.stopPropagation();
    const container = document.getElementById('image-upload-container');
    const message = document.getElementById('drop-message');
    
    if (container && message) {
        container.classList.remove('border-primary');
        message.classList.remove('text-primary');
    }
}

// Handle file drop
function handleDrop(e) {
    e.preventDefault();
    e.stopPropagation();
    const container = document.getElementById('image-upload-container');
    const message = document.getElementById('drop-message');
    
    if (container && message) {
        container.classList.remove('border-primary');
        message.classList.remove('text-primary');
    }
    
    const dt = e.dataTransfer;
    const files = dt.files;
    
    handleFiles(files);
}

// Handle file selection from input
function handleFileSelect(e) {
    const files = e.target.files;
    if (files) {
        handleFiles(files);
    }
    // Reset the input
    e.target.value = '';
}

// Process multiple files
function handleFiles(files) {
    [...files].forEach(file => {
        if (file.type.startsWith('image/')) {
            previewImage(file);
        } else {
            console.warn('Skipping non-image file:', file.name);
        }
    });
}

// Preview and prepare image for form submission
function previewImage(file) {
    imageCounter++;
    const tempId = 'temp-' + Date.now() + '-' + imageCounter;
    
    // Create a reader to generate preview
    const reader = new FileReader();
    reader.onload = function(e) {
        // Create the preview element
        const previewContainer = document.getElementById('image-preview-container');
        if (!previewContainer) return;
        
        const previewDiv = document.createElement('div');
        previewDiv.className = 'col-6 col-sm-4 col-md-3';
        previewDiv.dataset.tempId = tempId;
        previewDiv.innerHTML = `
            <div class="card h-100">
                <img src="${e.target.result}" class="card-img-top" style="height: 120px; object-fit: cover;">
                <div class="card-body p-2">
                    <input type="text" class="form-control form-control-sm mb-2" 
                           placeholder="Alt text" id="alt-${tempId}"
                           onchange="updateTempAltText('${tempId}', this.value)">
                    <button type="button" class="btn btn-sm btn-danger w-100" 
                            onclick="removeTempImage('${tempId}')">
                        <i class="bi bi-trash"></i> Remove
                    </button>
                </div>
            </div>
        `;
        
        previewContainer.appendChild(previewDiv);
    };
    
    reader.readAsDataURL(file);
    
    // Add to temp images array
    tempImages.push({
        id: tempId,
        alt_text: ''
    });
    
    // Create and attach the file input to the form
    const form = document.getElementById('post-form');
    if (!form) return;
    
    const fileInput = document.createElement('input');
    fileInput.type = 'file';
    fileInput.name = 'temp_image_' + tempId;
    fileInput.id = 'file-' + tempId;
    fileInput.style.display = 'none';
    
    try {
        // Use DataTransfer to set the files property - wrapped in try/catch for browser compatibility
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        fileInput.files = dataTransfer.files;
        form.appendChild(fileInput);
        console.log('Added file input:', fileInput.name, 'for file:', file.name);
    } catch (e) {
        console.error('Error attaching file to form:', e);
        // Fallback method - might not work in all browsers but better than nothing
        form.appendChild(fileInput);
    }
}

// Update alt text for a temporary image
function updateTempAltText(tempId, altText) {
    for (let i = 0; i < tempImages.length; i++) {
        if (tempImages[i].id === tempId) {
            tempImages[i].alt_text = altText;
            break;
        }
    }
}

// Remove a temporary image
function removeTempImage(tempId) {
    // Remove from DOM
    const element = document.querySelector(`[data-temp-id="${tempId}"]`);
    if (element) {
        element.remove();
    }
    
    // Remove from temp images array
    tempImages = tempImages.filter(img => img.id !== tempId);
    
    // Remove hidden input if it exists
    const input = document.getElementById('file-' + tempId);
    if (input) {
        input.remove();
    }
}
</script>
@endpush
