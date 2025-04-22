@extends('layouts.app')

@section('title', 'Posts tagged with "' . $tag->name . '" | ' . config('app.name'))
@section('meta_description', 'Browse all articles and news tagged with "' . $tag->name . '" on ' . config('app.name'))
@section('meta_keywords', $tag->name . ', news, articles')
@section('canonical_url', route('posts.tag', $tag->slug))

@section('og_title', 'Posts tagged with "' . $tag->name . '" | ' . config('app.name'))
@section('og_description', 'Browse all articles and news tagged with "' . $tag->name . '" on ' . config('app.name'))

@section('content')
<nav aria-label="breadcrumb" class="d-none d-md-block">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active">Tag: {{ $tag->name }}</li>
    </ol>
</nav>

<div class="row">
    <div class="col-12 col-lg-8 order-2 order-lg-1">
        <h1 class="h3 mb-3">Posts tagged with "{{ $tag->name }}"</h1>

        @forelse($posts as $post)
            <div class="card mb-3">
                <div class="row g-0">
                    <div class="col-4">
                        @if($post->display_image)
                            <a href="{{ url($post->display_image_url) }}" class="colorbox-image" title="{{ $post->title }}">
                                <img src="{{ url($post->display_image_url_webp) }}" class="img-fluid rounded-start h-100 object-fit-cover w-100" alt="{{ $post->title }}">
                            </a>
                        @else
                            <div class="bg-light text-center py-5 h-100 d-flex align-items-center justify-content-center">
                                <i class="bi bi-image" style="font-size: 2rem;"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-8">
                        <div class="card-body p-2 p-md-3">
                            <h5 class="card-title fs-6 fs-md-5">{{ $post->title }}</h5>
                            <p class="card-text d-none d-md-block">{{ $post->excerpt ?? Str::limit(strip_tags($post->content), 100) }}</p>
                            
                            @if($post->source_url)
                            <div class="embed-preview">
                                {!! app(\App\Services\ContentParserService::class)->parseUrl($post->source_url) !!}
                            </div>
                            @endif
                            
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <div class="mb-2 mb-md-0">
                                    <span class="badge bg-secondary">{{ $post->category->name }}</span>
                                </div>
                                <a href="{{ route('news.posts.show', $post->slug) }}" class="btn btn-sm btn-outline-primary">Read More</a>
                            </div>
                            <p class="card-text mt-2 mb-0"><small class="text-muted">{{ $post->published_at ? $post->published_at->format('M d, Y') : 'Draft' }}</small></p>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-info">No posts available with this tag yet.</div>
        @endforelse

        <div class="d-flex justify-content-center mt-4">
            {{ $posts->links() }}
        </div>
    </div>

    <div class="col-12 col-lg-4 order-1 order-lg-2 mb-4">
        <div class="sticky-lg-top" style="top: 2rem; z-index: 1000;">
            <div class="card mb-4">
                <div class="card-header">Categories</div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach(Tuna976\NEWS\Models\Category::withCount('posts')->get() as $category)
                            <a href="{{ route('posts.category', $category->slug) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                {{ $category->name }}
                                <span class="badge bg-primary rounded-pill">{{ $category->posts_count }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">All Tags</div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        @foreach(\App\Models\Tag::withCount('posts')->get() as $t)
                            <a href="{{ route('news.posts.tag', $t->slug) }}" class="text-decoration-none mb-1">
                                <span class="badge {{ $t->id === $tag->id ? 'bg-primary' : 'bg-secondary' }}">
                                    {{ $t->name }} ({{ $t->posts_count }})
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Colorbox and Instagram handling -->
<script>
$(document).ready(function(){
    // Initialize Colorbox for images
    $('.colorbox-image').colorbox({
        maxWidth: '90%',
        maxHeight: '90%',
        opacity: 0.8,
        transition: 'elastic',
        fixed: true
    });
    
    // Handle Instagram embeds
    setTimeout(function() {
        if (window.instgrm) {
            window.instgrm.Embeds.process();
        } else {
            const script = document.createElement('script');
            script.src = "//www.instagram.com/embed.js";
            script.async = true;
            script.onload = function() {
                if (window.instgrm) {
                    window.instgrm.Embeds.process();
                }
            };
            document.body.appendChild(script);
        }
    }, 1000);

    // Add styling for Instagram embeds
    const style = document.createElement('style');
    style.textContent = `
        .instagram-embed {
            max-width: 540px;
            width: 100%;
            margin: 0 auto;
            display: flex;
            justify-content: center;
        }
        .instagram-embed iframe {
            width: 100% !important;
            min-width: 326px;
            border: none !important;
        }
        .instagram-reel-embed {
            min-height: 720px !important;
        }
        .instagram-post-embed {
            min-height: 500px !important;
        }
        @media (max-width: 540px) {
            .instagram-reel-embed {
                min-height: 800px !important;
            }
            .instagram-post-embed {
                min-height: 600px !important;
            }
        }
        
        /* Special handling for tag page */
        .embed-preview .instagram-embed {
            max-width: 100%;
            transform: scale(0.85);
            transform-origin: top center;
        }
    `;
    document.head.appendChild(style);
});
</script>
@endpush
