@extends('vendor.News.layouts.app')

@section('title', $post->title . ' | ' . config('app.name'))
@section('meta_description', $post->excerpt ?? Str::limit(strip_tags($post->content), 160))
@section('meta_author', $post->user->name)
@section('meta_keywords', $post->tags->pluck('name')->join(', '))
@section('canonical_url', route('news.posts.show', $post->slug))

@section('og_type', 'article')
@section('og_title', $post->title)
@section('og_description', $post->excerpt ?? Str::limit(strip_tags($post->content), 160))
@if($post->display_image)
    @section('og_image', $post->display_image_url)
@endif

@section('twitter_title', $post->title)
@section('twitter_description', $post->excerpt ?? Str::limit(strip_tags($post->content), 160))
@if($post->display_image)
    @section('twitter_image', $post->display_image_url)
@endif

@push('meta-tags')
<meta property="article:published_time" content="{{ $post->published_at ? $post->published_at->toIso8601String() : $post->created_at->toIso8601String() }}">
<meta property="article:modified_time" content="{{ $post->updated_at->toIso8601String() }}">
<meta property="article:author" content="{{ $post->user->name }}">
<meta property="article:section" content="{{ $post->category->name }}">
@foreach ($post->tags as $tag)
    <meta property="article:tag" content="{{ $tag->name }}">
@endforeach

<!-- Schema.org markup for Google -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "NewsArticle",
    "headline": "{{ $post->title }}",
    "image": [
        "{{ $post->display_image_url }}"
    ],
    "datePublished": "{{ $post->published_at ? $post->published_at->toIso8601String() : $post->created_at->toIso8601String() }}",
    "dateModified": "{{ $post->updated_at->toIso8601String() }}",
    "author": {
        "@type": "Person",
        "name": "{{ $post->user->name }}"
    },
    "publisher": {
        "@type": "Organization",
        "name": "{{ config('app.name') }}",
        "logo": {
            "@type": "ImageObject",
            "url": "{{ url('/images/logo.png') }}"
        }
    },
    "description": "{{ $post->excerpt ?? Str::limit(strip_tags($post->content), 160) }}",
    "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "{{ route('news.posts.show', $post->slug) }}"
    }
}
</script>
@endpush

@section('content')
<nav aria-label="breadcrumb" class="d-none d-md-block">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('news.home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('news.posts.category', $post->category->slug) }}">{{ $post->category->name }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $post->title }}</li>
    </ol>
</nav>

<div class="row justify-content-center">
    <div class="col-12 col-lg-10">
        <article class="blog-post">

            <div class="alert alert-success text-center border border-danger opacity-75 " role="alert">
                <h1 class="blog-post-title mb-3 fs-3 fs-md-2">{{ $post->title }}</h1>
            </div>
            
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                <p class="blog-post-meta mb-2 mb-md-0">
                    {{ $post->published_at ? $post->published_at->format('F d, Y') : 'Draft' }}
                </p>
                
                @if(auth()->check() && (auth()->user()->id === $post->user_id || auth()->user()->isAdmin()))
                <div>
                    {{-- <a href="{{ route('news.posts.edit', $post) }}" class="btn btn-sm btn-outline-success"> --}}
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                </div>
                @endif
            </div>

            <!-- Featured Image - Using Colorbox -->
            @if($post->display_image)
                <div class="featured-image mb-4">
                    <a href="{{ url($post->display_image_url) }}" class="colorbox-image" title="{{ $post->title }}">
                        <picture>
                            <source srcset="{{ url($post->display_image_url_webp) }}" type="image/webp">
                            <img src="{{ url($post->display_image_url) }}" 
                                 class="img-fluid rounded w-100" 
                                 style="max-height: 400px; object-fit: contain;"
                                 alt="{{ $post->title }}">
                        </picture>
                    </a>
                </div>
            @endif

            <div class="blog-post-content mb-4">
                {!! $post->content !!}
            </div>
            
            @if($post->source_url)
            <div class="blog-post-embed mb-4 text-center">
                <div class="card bg-dark border-success">
                    <div class="card-header text-light">
                        <i class="bi bi-share me-2"></i>Source Content
                    </div>
                    <div class="card-body">
                        {!! app(\App\Services\News\ContentParserService::class)->parseUrl($post->source_url) !!}
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Gallery with Colorbox -->
            @if($post->images && $post->images->count() > 0)
            <div class="post-gallery mb-4">
                <h4 class="h5 mb-3">Image Gallery</h4>
                <div class="row g-2">
                    @foreach($post->images as $index => $image)
                        <div class="col-6 col-md-4 mb-3">
                            <a href="{{ $image->webp_url }}" class="colorbox-gallery" 
                               title="{{ $image->alt_text ?? $post->title }}" 
                               rel="gallery-{{ $post->id }}">
                                <img src="{{ $image->webp_url }}" alt="{{ $image->alt_text }}" 
                                     class="img-fluid rounded" style="width: 100%; height: 160px; object-fit: cover;">
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="mb-4">
                <div class="d-flex flex-wrap gap-1 align-items-center">
                    <span class="text-muted me-1">Tags:</span>
                    @forelse($post->tags as $tag)
                        <a href="{{ route('news.posts.tag', $tag->slug) }}" class="badge bg-secondary text-decoration-none">{{ $tag->name }}</a>
                    @empty
                        <span class="text-muted">No tags</span>
                    @endforelse
                </div>
            </div>
        </article>

        <div class="card mb-4">
            <div class="card-header">Comments</div>
            <div class="card-body">
                @livewire('news-comment-section', ['post' => $post])
            </div>
        </div>

        @if(isset($relatedPosts) && $relatedPosts->count() > 0)
        <div class="mb-5">
            <h3 class="h5 mb-3">Related Posts</h3>
            <div class="row g-3">
                @foreach($relatedPosts as $related)
                <div class="col-12 col-md-4">
                    <div class="card h-100">
                        @if($related->display_image)
                        <a href="{{ route('posts.show', $related->slug) }}">
                            <picture>
                                <source srcset="{{ $related->display_image_url_webp }}" type="image/webp">
                                <img src="{{ $related->display_image_url }}" class="card-img-top" alt="{{ $related->title }}">
                            </picture>
                        </a>
                        @endif
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fs-6">
                                <a href="{{ route('news.posts.show', $related->slug) }}" class="text-decoration-none">
                                    {{ $related->title }}
                                </a>
                            </h5>
                            <p class="card-text small flex-grow-1">{{ Str::limit($related->excerpt ?? strip_tags($related->content), 60) }}</p>
                            <a href="{{ route('news.posts.show', $related->slug) }}" class="btn btn-sm btn-primary mt-2">Read More</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<!-- Colorbox Initialization -->
<script>
$(document).ready(function(){
    // Initialize Colorbox for individual images
    $('.colorbox-image').colorbox({
        maxWidth: '90%',
        maxHeight: '90%',
        opacity: 0.8,
        transition: 'elastic',
        current: "Image {current} of {total}",
        fixed: true
    });
    
    // Initialize Colorbox for gallery images
    $('.colorbox-gallery').colorbox({
        rel: 'gallery-{{ $post->id }}',
        maxWidth: '90%',
        maxHeight: '90%',
        opacity: 0.8,
        transition: 'elastic',
        current: "Image {current} of {total}",
        slideshow: true,
        slideshowSpeed: 5000,
        slideshowAuto: false,
        fixed: true
    });
    
    // Add keyboard navigation
    $(document).bind('keyup', function(e) {
        if (e.keyCode === 37 && $('.cboxPhoto').length) { // left arrow
            $.colorbox.prev();
        } else if (e.keyCode === 39 && $('.cboxPhoto').length) { // right arrow
            $.colorbox.next();
        }
    });
    
    // Handle touch swipe for mobile
    $('.cboxPhoto').live('swipeleft', function() {
        $.colorbox.next();
    });
    $('.cboxPhoto').live('swiperight', function() {
        $.colorbox.prev();
    });
    
    // Force Instagram embeds to load correctly
    if (window.instgrm) {
        window.instgrm.Embeds.process();
    } else {
        // If Instagram script hasn't loaded yet, retry after a delay
        setTimeout(function() {
            if (window.instgrm) {
                window.instgrm.Embeds.process();
            } else {
                // Try loading script manually
                var s = document.createElement('script');
                s.async = true;
                s.src = '//www.instagram.com/embed.js';
                document.body.appendChild(s);
                // Try processing after script loads
                s.onload = function() {
                    if (window.instgrm) {
                        window.instgrm.Embeds.process();
                    }
                };
            }
        }, 1000);
    }
    
    // Force Facebook embeds to load correctly
    if (window.FB) {
        FB.XFBML.parse();
    } else {
        // If Facebook SDK hasn't loaded yet
        window.fbAsyncInit = function() {
            FB.init({
                xfbml: true,
                version: 'v17.0'
            });
            FB.XFBML.parse();
        };
        
        // Try loading script manually
        setTimeout(function() {
            if (!window.FB) {
                (function(d, s, id) {
                    var js, fjs = d.getElementsByTagName(s)[0];
                    if (d.getElementById(id)) return;
                    js = d.createElement(s); js.id = id;
                    js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v17.0";
                    fjs.parentNode.insertBefore(js, fjs);
                }(document, 'script', 'facebook-jssdk'));
            }
        }, 1000);
    }

    // Simplified Instagram embed handling
    document.addEventListener('DOMContentLoaded', function() {
        // Check if Instagram embeds exist and process them
        if (document.querySelector('.instagram-embed') && window.instgrm) {
            window.instgrm.Embeds.process();
        } else {
            // If Instagram script hasn't loaded yet or isn't available, add it
            setTimeout(function() {
                var s = document.createElement('script');
                s.async = true;
                s.defer = true;
                s.src = '//www.instagram.com/embed.js';
                s.onload = function() {
                    if (window.instgrm) {
                        window.instgrm.Embeds.process();
                    }
                };
                document.body.appendChild(s);
            }, 500);
        }
    });

    // Additional CSS for Instagram embeds
    document.addEventListener('DOMContentLoaded', function() {
        // Add custom styling for Instagram embeds
        const style = document.createElement('style');
        style.textContent = `
            .instagram-embed {
                display: flex;
                justify-content: center;
                width: 100%;
                margin: 1rem auto;
            }
            .instagram-embed .instagram-media {
                min-height: 600px !important;
            }
            @media (max-width: 540px) {
                .instagram-embed .instagram-media {
                    min-height: 700px !important;
                }
            }
        `;
        document.head.appendChild(style);
        
        // Force Instagram embeds to load correctly
        if (window.instgrm) {
            window.instgrm.Embeds.process();
        } else {
            // If Instagram script hasn't loaded yet, retry after a delay
            setTimeout(function() {
                if (window.instgrm) {
                    window.instgrm.Embeds.process();
                } else {
                    // Try loading script manually
                    var s = document.createElement('script');
                    s.async = true;
                    s.src = '//www.instagram.com/embed.js';
                    document.body.appendChild(s);
                    // Try processing after script loads
                    s.onload = function() {
                        if (window.instgrm) {
                            window.instgrm.Embeds.process();
                        }
                    };
                }
            }, 1000);
        }
    });

    // Simplified approach for Instagram embeds
    document.addEventListener('DOMContentLoaded', function() {
        // Add custom styling for Instagram embeds
        const style = document.createElement('style');
        style.textContent = `
            .instagram-embed {
                margin: 0 auto;
                max-width: 540px;
                overflow: hidden;
            }
            .instagram-embed .instagram-media {
                margin: 0 auto !important;
            }
        `;
        document.head.appendChild(style);
        
        // Process Instagram embeds after a slight delay
        setTimeout(function() {
            if (window.instgrm) {
                window.instgrm.Embeds.process();
            } else {
                // If Instagram script is not loaded yet, load it
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
    });

    // Additional CSS for Instagram embeds with specific reel handling
    document.addEventListener('DOMContentLoaded', function() {
        // Add custom styling for Instagram embeds
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
                width: 100%;
                border: 0;
            }
            .instagram-reel-embed {
                min-height: 800px;
            }
            .instagram-post-embed {
                min-height: 500px;
            }
            @media (max-width: 540px) {
                .instagram-reel-embed {
                    min-height: 840px;
                }
                .instagram-post-embed {
                    min-height: 600px;
                }
            }
        `;
        document.head.appendChild(style);
    });

    // Clean up and streamline Instagram embed handling
    document.addEventListener('DOMContentLoaded', function() {
        // Add custom styling for Instagram embeds
        const style = document.createElement('style');
        style.textContent = `
            .instagram-embed {
                max-width: 540px;
                margin: 0 auto;
                display: flex;
                justify-content: center;
            }
            .instagram-embed iframe {
                width: 100% !important;
                min-width: 326px;
                border: none !important;
            }
        `;
        document.head.appendChild(style);

        // Remove duplicate event listeners (there are multiple in the file)
        const existingStyles = document.querySelectorAll('style');
        if (existingStyles.length > 5) {
            // Keep only the last one
            for (let i = 0; i < existingStyles.length - 1; i++) {
                if (existingStyles[i].textContent.includes('.instagram-embed')) {
                    existingStyles[i].remove();
                }
            }
        }
    });

    // Add custom styling for different Instagram embed types
    document.addEventListener('DOMContentLoaded', function() {
        // Add custom styling for Instagram embeds
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
            /* Different heights based on content type */
            .instagram-reel-embed {
                min-height: 720px !important;
            }
            .instagram-post-embed {
                min-height: 500px !important;
            }
            /* Responsive adjustments for mobile */
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

    // Add custom styling for Facebook embeds
    document.addEventListener('DOMContentLoaded', function() {
        const style = document.createElement('style');
        style.textContent = `
            .facebook-embed {
                max-width: 540px;
                width: 100%;
                margin: 0 auto;
                display: flex;
                flex-direction: column;
                align-items: center;
            }
            .facebook-embed .fb-post,
            .facebook-embed .fb-video {
                width: 100% !important;
                max-width: 500px !important;
            }
            /* Make Facebook embeds more compact */
            .facebook-embed iframe {
                max-height: 600px !important;
            }
            /* Fix Facebook page embeds */
            .facebook-embed .fb-page {
                width: 100% !important;
                max-width: 500px !important;
            }
            @media (max-width: 540px) {
                .facebook-embed .fb-post,
                .facebook-embed .fb-video,
                .facebook-embed .fb-page {
                    width: 100% !important;
                }
            }
        `;
        document.head.appendChild(style);
        
        // Force Facebook embeds to reparse after page is fully loaded
        if (window.FB) {
            setTimeout(() => FB.XFBML.parse(), 1000);
        }
    });
});
</script>
@endpush