@extends('vendor.News.admin.layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Edit Comment</h1>
        <a href="{{ route('admin.comments.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Comments
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.comments.update', $comment) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="content" class="form-label">Content</label>
                    <textarea class="form-control @error('content') is-invalid @enderror" 
                              id="content" name="content" rows="3" required>{{ old('content', $comment->content) }}</textarea>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_approved" name="is_approved" 
                          {{ old('is_approved', $comment->is_approved) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_approved">Approve this comment</label>
                </div>
                
                <div class="d-grid d-md-flex">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Update Comment
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Comment Information</h5>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Post:</dt>
                <dd class="col-sm-9">
                    <a href="{{ route('news.posts.show', $comment->post->slug) }}" target="_blank">
                        {{ $comment->post->title }}
                    </a>
                </dd>
                
                <dt class="col-sm-3">Author:</dt>
                <dd class="col-sm-9">{{ $comment->user->name }} ({{ $comment->user->email }})</dd>
                
                <dt class="col-sm-3">Date:</dt>
                <dd class="col-sm-9">{{ $comment->created_at->format('M d, Y H:i:s') }}</dd>
                
                <dt class="col-sm-3">Status:</dt>
                <dd class="col-sm-9">
                    @if($comment->is_approved)
                        <span class="badge bg-success">Approved</span>
                    @else
                        <span class="badge bg-warning">Pending</span>
                    @endif
                </dd>
            </dl>
        </div>
    </div>
@endsection
