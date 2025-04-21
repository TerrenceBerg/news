@extends('news.layouts.app')

@section('title', $category->name . ' News & Articles | ' . config('app.name'))
@section('meta_description', $category->description ?? 'Browse the latest ' . $category->name . ' news and articles on ' . config('app.name'))
@section('canonical_url', route('posts.category', $category->slug))

@section('og_type', 'website')
@section('og_title', $category->name . ' | ' . config('app.name'))
@section('og_description', $category->description ?? 'Browse the latest ' . $category->name . ' news and articles on ' . config('app.name'))

@push('meta-tags')
<!-- Schema.org markup for Google -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "CollectionPage",
    "headline": "{{ $category->name }} - News & Articles",
    "description": "{{ $category->description ?? 'Browse the latest ' . $category->name . ' news and articles' }}",
    "url": "{{ route('posts.category', $category->slug) }}",
    "publisher": {
        "@type": "Organization",
        "name": "{{ config('app.name') }}",
        "logo": {
            "@type": "ImageObject",
            "url": "{{ url('/images/logo.png') }}"
        }
    }
}
</script>
@endpush

@section('content')
<nav aria-label="breadcrumb" class="d-none d-md-block">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('news.home') }}">Home</a></li>
        <li class="breadcrumb-item active">{{ $category->name }}</li>
    </ol>
</nav>

<div class="row text-center">
    <div class="col-12 col-lg-12 order-2 order-lg-1">
        
        @if($category->description)
            <div class="mb-4">        
                <h1 class="h3 mb-3">{{ $category->name }}</h1>
            </div>
        @endif

        @forelse($posts as $post)
            <div class="card mb-3">
                <div class="row g-0">
                    <div class="col-4">
                        @if($post->display_image)
                            <a href="{{ url($post->display_image_url) }}" class="colorbox-image" title="{{ $post->title }}">
                                <img src="{{ url($post->display_image_url_webp0) }}" class="img-fluid rounded-start h-100 object-fit-cover w-100" alt="{{ $post->title }}">
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
                            <p class="">{!! $post->content !!}</p>
                            
                            @if($post->source_url)
                            <div class="embed-preview">
                                {!! app(\App\Services\ContentParserService::class)->parseUrl($post->source_url) !!}
                            </div>
                            @endif
                            
                            <p class="card-text mt-2 mb-0"><small class="text-muted">{{ $post->published_at ? $post->published_at->format('M d, Y') : 'Draft' }}</small></p>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-info">No posts available in this category yet.</div>
        @endforelse

        <div class="d-flex justify-content-center mt-4">
            {{ $posts->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Colorbox Initialization -->
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
    
    // Process Instagram embeds
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
    `;
    document.head.appendChild(style);
});
</script>
@endpush
