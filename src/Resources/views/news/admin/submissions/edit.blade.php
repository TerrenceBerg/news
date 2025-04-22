@extends('vendor.news.admin.layouts.admin')

@section('title', 'Edit Submission')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Submission</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.submissions.index') }}">Submissions</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>

    <div class="row">
        <div class="col-xl-12">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-edit me-1"></i>
                    Edit Submission #{{ $submission->id }}
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.submissions.update', $submission->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $submission->title) }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $submission->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="content" class="form-label">Content</label>
                            <textarea class="form-control" id="content" name="content" rows="10" required>{{ old('content', $submission->content) }}</textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="source_url" class="form-label">Source URL</label>
                            <input type="url" class="form-control" id="source_url" name="source_url" value="{{ old('source_url', $submission->source_url) }}">
                        </div>
                        
                        <div class="mb-3">
                            <label for="featured_image" class="form-label">Featured Image</label>
                            @if($submission->featured_image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $submission->featured_image) }}" 
                                         alt="Current featured image" class="img-thumbnail" style="max-height: 200px;">
                                </div>
                            @endif
                            <input type="file" class="form-control" id="featured_image" name="featured_image">
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_published" name="is_published" 
                                  {{ old('is_published', $submission->is_published) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_published">Publish this post</label>
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Update Submission</button>
                            <a href="{{ route('admin.submissions.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Optional: Add a rich text editor for the content field
    // You can add your preferred editor initialization here
</script>
@endsection
