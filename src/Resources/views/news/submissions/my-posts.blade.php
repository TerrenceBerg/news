@extends('vendor.News.layouts.app')

@section('title', $user->name . '\'s Posts')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3">{{ $user->name }}'s Posts</h1>
            <p class="text-muted">
                <i class="bi bi-person-circle me-1"></i> Member since {{ $user->created_at->format('M Y') }}
            </p>
        </div>
    </div>

    @if ($posts->count() > 0)
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach ($posts as $post)
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        @if($post->featured_image)
                            <img src="{{ asset('storage/' . $post->featured_image) }}" 
                                class="card-img-top" alt="{{ $post->title }}">
                        @else
                            <div class="bg-light text-center py-5">
                                <i class="bi bi-file-earmark-text" style="font-size: 3rem; color: #ccc;"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $post->title }}</h5>
                            <p class="card-text text-muted small">
                                <i class="bi bi-calendar me-1"></i> {{ $post->created_at->format('M d, Y') }}
                            </p>
                            <p class="card-text">{{ Str::limit(strip_tags($post->content), 100) }}</p>
                        </div>
                        <div class="card-footer bg-white">
                            <a href="{{ route('news.posts.show', $post->slug) }}" class="btn btn-sm btn-outline-primary">Read More</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="d-flex justify-content-center mt-4">
            {{ $posts->links() }}
        </div>
    @else
        <div class="alert alert-info">
            <p class="mb-0">This user hasn't published any posts yet.</p>
        </div>
    @endif
</div>
@endsection
