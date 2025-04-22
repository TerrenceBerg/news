@extends('vendor.news.layouts.app')

@section('title', config('app.name') . ' - Latest News & Articles')
@section('meta_description', 'Stay updated with the latest news, technology updates, business insights and more on ' . config('app.name'))
@section('meta_keywords', 'news, articles, latest news, trending, technology, business')
@section('canonical_url', route('home'))

@section('og_type', 'website')
@section('og_title', config('app.name') . ' - Latest News & Articles')
@section('og_description', 'Stay updated with the latest news, technology updates, business insights and more on ' . config('app.name'))

@push('meta-tags')
<!-- Schema.org markup for Google -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebSite",
    "name": "{{ config('app.name') }}",
    "url": "{{ url('/') }}",
    "potentialAction": {
        "@type": "SearchAction",
        "target": "{{ url('/search?q={search_term_string}') }}",
        "query-input": "required name=search_term_string"
    }
}
</script>

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "{{ config('app.name') }}",
    "url": "{{ url('/') }}",
    "logo": "{{ url('/images/logo.png') }}"
}
</script>
@endpush

@section('content')
<div class="row text-center">
    <div class="col-12 order-2 order-lg-1">
        <h2 class="h4 mb-3 px-2 px-md-0">Recent Posts</h2>

        @forelse($recentPosts as $post)
        <div class="card mb-3 rounded" style="border-color: #00FF00;border-radius: 25px;">
            <div class="row g-0">
                @if($post->display_image)
                    <div class="col-md-4 col-lg-4">
                        
                            <a href="{{ url($post->display_image_url) }}" class="colorbox-image" title="{{ $post->title }}">
                                <picture>
                                    <source srcset="{{ url($post->display_image_url_webp) }}" type="image/webp">
                                    <img src="{{ url($post->display_image_url) }}" class="img-fluid" alt="{{ $post->title }}">
                                </picture>
                            </a>
                    </div>
                <div class="col-md-8 col-lg-8">
                @endif
                @if(!$post->display_image)
                <div class="col-12">
                @endif
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="{{ route('posts.show', $post->slug) }}" class="text-decoration-none">
                                {{ $post->title }}
                            </a>
                        </h5>
                        <p class="card-text d-none d-md-block">{!! $post->content !!}</p>
                        
                        @if($post->source_url)
                        <div class="embed-preview">
                            {!! app(\App\Services\ContentParserService::class)->parseUrl($post->source_url) !!}
                        </div>
                        @endif
                        
                        <div class="d-flex justify-content-center">
                            <div class="mb-2 mb-md-0">
                                <span class="badge bg-secondary">{{ $post->category->name }}</span>
                                <small class="text-muted d-none d-md-inline-block ms-2"></small>
                            </div>
                            <a href="{{ route('posts.show', $post->slug) }}" class="btn btn-sm btn-outline-primary ms-3">Read More</a>
                        </div>
                        <a href="{{ route('user.posts', $post->user_id) }}" class="text-decoration-none">
                            More posts by {{ $post->user->name }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="alert alert-info">No posts available yet.</div>
        @endforelse

        <div class="d-flex justify-content-center mt-4">
            {{ $recentPosts->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Script handling for posts display -->
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
        
        /* Special styling for home page embeds */
        .embed-preview .instagram-embed {
            max-width: 100%;
        }
    `;
    document.head.appendChild(style);
});
</script>
@endpush
