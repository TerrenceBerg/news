<div>
    @if(session('comment_message'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('comment_message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @auth
        <div class="mb-4">
            <form wire:submit="addComment">
                <div class="mb-3">
                    <label for="content" class="form-label">Leave a comment</label>
                    <textarea class="form-control @error('content') is-invalid @enderror" id="content" rows="3" 
                              wire:model="content" placeholder="Share your thoughts on this post..."></textarea>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="d-grid d-md-block">
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="addComment">Submit Comment</span>
                        <span wire:loading wire:target="addComment">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Submitting...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    @else
        <div class="alert alert-info mb-4 d-flex align-items-center">
            <i class="bi bi-info-circle me-2 fs-4"></i>
            <div>
                Please <a href="{{ route('login') }}" class="alert-link">login</a> to leave a comment.
                <a href="{{ route('register') }}" class="alert-link ms-2">Register here</a> if you don't have an account.
            </div>
        </div>
    @endauth

    <h4 class="h5 mb-3">
        <i class="bi bi-chat-quote me-2"></i>
        {{ $comments->count() }} {{ Str::plural('Comment', $comments->count()) }}
    </h4>

    @forelse($comments as $comment)
        <div class="card mb-3">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="card-subtitle mb-0 text-primary d-flex align-items-center">
                        <i class="bi bi-person-circle me-1"></i>
                        {{ $comment->user->name }}
                    </h6>
                    <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                </div>
                <p class="card-text mb-0">{{ $comment->content }}</p>
            </div>
        </div>
    @empty
        <div class="text-center text-muted py-4 border rounded bg-light">
            <i class="bi bi-chat-left-text d-block mb-2" style="font-size: 2rem;"></i>
            <p class="mb-0">No comments yet. Be the first to comment!</p>
        </div>
    @endforelse
    
    <div wire:loading wire:target="addComment" class="text-center my-3">
        <div class="spinner-grow text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</div>
