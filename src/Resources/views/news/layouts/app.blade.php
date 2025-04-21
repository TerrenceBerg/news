<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('meta_description', config('app.name') . ' - Your source for the latest news and insightful articles')">
    <meta name="theme-color" content="#000000">
    <meta name="author" content="@yield('meta_author', config('app.name'))">
    <meta name="keywords" content="@yield('meta_keywords', 'news, articles, blog, technology, business')">
    <meta name="robots" content="@yield('meta_robots', 'index, follow')">
    <meta name="googlebot" content="@yield('meta_googlebot', 'index, follow')">

    <!-- Canonical URL -->
    <link rel="canonical" href="@yield('canonical_url', Request::url())">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="@yield('og_url', Request::url())">
    <meta property="og:title" content="@yield('og_title', config('app.name'))">
    <meta property="og:description" content="@yield('og_description', config('app.name') . ' - Your source for the latest news and insightful articles')">
    <meta property="og:image" content="@yield('og_image', asset('images/og-default.jpg'))">
    <meta property="og:image:width" content="@yield('og_image_width', '1200')">
    <meta property="og:image:height" content="@yield('og_image_height', '630')">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="fb:app_id" content="@yield('fb_app_id', env('FACEBOOK_APP_ID', ''))">
    <meta property="article:publisher" content="@yield('article_publisher', env('FACEBOOK_PAGE', ''))">

    <!-- Twitter -->
    <meta name="twitter:card" content="@yield('twitter_card', 'summary_large_image')">
    <meta name="twitter:url" content="@yield('twitter_url', Request::url())">
    <meta name="twitter:title" content="@yield('twitter_title', config('app.name'))">
    <meta name="twitter:description" content="@yield('twitter_description', config('app.name') . ' - Your source for the latest news and insightful articles')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('images/og-default.jpg'))">
    <meta name="twitter:site" content="@yield('twitter_site', env('TWITTER_USERNAME', '@newssite'))">
    <meta name="twitter:creator" content="@yield('twitter_creator', env('TWITTER_USERNAME', '@newssite'))">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name'))</title>

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('favicon/site.webmanifest') }}">
    <link rel="mask-icon" href="{{ asset('favicon/safari-pinned-tab.svg') }}" color="#00ff00">
    <link rel="shortcut icon" href="{{ asset('favicon/favicon.ico') }}">
    <meta name="msapplication-TileColor" content="#000000">
    <meta name="msapplication-config" content="{{ asset('favicon/browserconfig.xml') }}">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="{{url('css/blog.css')}}">
    
    <!-- jQuery and Colorbox -->
    <script src="{{ asset('js/jquery.colorbox.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/colorbox.css') }}">
    
    <!-- Social Media Embed Scripts -->
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v17.0"></script>
    <script async src="//www.instagram.com/embed.js"></script>
    <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
    <script async src="https://www.tiktok.com/embed.js"></script>
    
    <!-- Meta tags for social embeds -->
    @stack('meta-tags')

    <!-- Scripts -->
    @vite(['resources/sass/app.scss'])
    @livewireStyles
    
    <!-- Matrix Background Effect -->
    <script src="{{ asset('js/matrix-background.js') }}"></script>
    
    <!-- Additional CSS -->
    <link href="{{ asset('css/lightbox.css') }}" rel="stylesheet">
    
    <!-- Matrix Mode Toggle Button - Better positioned for mobile visibility -->
    <style>
        /* Matrix toggle button styling */
        #matrix-toggle-container {
            position: fixed;
            bottom: 70px; /* Positioned higher to avoid navigation bars */
            right: 20px;
            z-index: 9999; /* Ensure it's above all other elements */
        }
        
        /* Matrix toggle button appearance */
        #matrix-toggle {
            background-color: rgba(0, 0, 0, 0.7);
            border: 1px solid #00FF00;
            color: #00FF00;
            box-shadow: 0 0 10px rgba(0, 255, 0, 0.5);
            padding: 8px 12px;
        }
        
        #matrix-toggle:hover {
            background-color: #00FF00;
            color: #000;
        }
        
        /* Make icon larger on mobile */
        @media (max-width: 767.98px) {
            #matrix-toggle {
                padding: 10px 15px;
                border-width: 2px;
            }
            
            #matrix-toggle .bi {
                font-size: 1.5rem;
            }
        }
    </style>
    
    <style>
        body {
            font-size: 16px;
            background-color: #050505; /* Darker background */
            color: #00FF00; /* Matrix green */
        }
        
        .navbar .navbar-brand {
            font-size: 1.25rem;
            color: #FFFFFF !important; /* White color */
        }
        
        .navbar-light .navbar-nav .nav-link {
            color: #CCCCCC !important; /* Light gray */
        }
        
        .navbar-light .navbar-nav .nav-link:hover,
        .navbar-light .navbar-nav .nav-link:focus {
            color: #FFFFFF !important; /* White on hover/focus */
        }
        
        .card {
            margin-bottom: 1.5rem;
            border-radius: 0.5rem;
        }
        
        .card-img-top {
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
            object-fit: cover;
            height: 180px;
        }
        
        @media (max-width: 767.98px) {
            .card-img-top {
                height: 200px;
            }
            
            .blog-post-title {
                font-size: 1.75rem;
            }
            
            .py-4 {
                padding-top: 1rem !important;
                padding-bottom: 1rem !important;
            }
            
            footer {
                text-align: center;
                margin-bottom: 3.5rem;
            }
            
            footer .col-md-4 {
                margin-bottom: 1.5rem;
            }
            
            .navbar .container {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }
            
            .mobile-bottom-nav {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                background-color: #000000 !important; /* Fixed black background */
                border-top: 1px solid #333;
                box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
                z-index: 1030;
            }
            
            .mobile-bottom-nav a {
                color: #00FF00 !important;
            }
            
            body {
                padding-bottom: 4rem;
            }
        }
        
        .badge {
            font-weight: 500;
            padding: 0.35em 0.65em;
        }
        
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        
        /* Focus state improvements for accessibility */
        a:focus, button:focus, input:focus, select:focus, textarea:focus {
            outline: 3px solid rgba(13, 110, 253, 0.25);
        }
        
        /* Smooth scrolling for better UX */
        html {
            scroll-behavior: smooth;
        }
        
        /* Lightbox styles */
        .modal-backdrop.show {
            opacity: 0.9;
        }
        
        #imageModal .modal-content {
            box-shadow: 0 5px 15px rgba(0,0,0,.5);
        }
        
        #imageModal .btn-close-white {
            filter: brightness(0) invert(1);
        }
        
        #modalImage {
            max-height: 80vh;
            object-fit: contain;
        }
        
        .lightbox-image {
            cursor: zoom-in;
            transition: opacity 0.2s;
        }
        
        .lightbox-image:hover {
            opacity: 0.9;
        }
        
        @media (max-width: 767.98px) {
            #imageModal .modal-footer {
                flex-direction: column;
                align-items: center;
            }
            
            #imageModal .modal-footer .ms-auto {
                margin-left: 0 !important;
                margin-top: 0.5rem;
            }
        }

        /* Matrix Mode Toggle Button - Improved for mobile devices */
        #matrix-toggle-container {
            position: fixed;
            bottom: 70px; /* Position higher on mobile to avoid navigation bars */
            right: 20px;
            z-index: 1000;
            opacity: 0.9;
            transition: opacity 0.3s;
        }
        
        #matrix-toggle-container:hover {
            opacity: 1;
        }
        
        #matrix-toggle {
            box-shadow: 0 0 10px rgba(0, 255, 0, 0.7);
            border: 1px solid #00FF00;
            padding: 8px 12px;
            font-size: 1.1rem;
        }
        
        #matrix-toggle .bi {
            font-size: 1.2rem;
        }
        
        /* Ensure the button has enough contrast to be visible */
        #matrix-toggle.btn-outline-success {
            color: #00FF00;
            border-color: #00FF00;
            background-color: rgba(0, 0, 0, 0.7);
        }
        
        #matrix-toggle.btn-success {
            color: #000000;
            background-color: #00FF00;
        }
        
        /* Hide text on small screens but keep icon visible */
        @media (max-width: 767.98px) {
            #matrix-toggle span {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-black shadow-sm"> <!-- Changed to bg-black -->
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                    <i class="bi bi-newspaper me-2" style="color: #00FF00;"></i>
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" style="background-color: #00FF00;" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}">Home</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="categoriesDropdown" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false">
                                Categories
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="categoriesDropdown">
                                @foreach(\App\Models\Category::orderBy('name', 'ASC')->get() as $category)
                                    <li><a class="dropdown-item" href="{{ route('posts.category', $category->slug) }}">{{ $category->name }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('submissions.create') }}">
                                <i class="bi bi-pencil-square me-1"></i> Submit News
                            </a>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">
                                        <i class="bi bi-box-arrow-in-right me-1"></i> {{ __('Login') }}
                                    </a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">
                                        <i class="bi bi-person-plus me-1"></i> {{ __('Register') }}
                                    </a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="bi bi-person-circle me-1"></i>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                     <a class="dropdown-item" href="{{ route('user.posts', Auth::id()) }}">
                                        <i class="bi bi-file-text me-1"></i> My Posts
                                    </a>
                                    
                                    @if(Auth::user()->isAdmin())
                                        <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                            <i class="bi bi-speedometer2 me-1"></i> Admin Dashboard
                                        </a>
                                    @endif
                                    
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right me-1"></i> {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('submissions.my-posts') }}">
                                    <i class="bi bi-file-earmark-text me-1"></i> My Posts
                                </a>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                @yield('content')
            </div>
        </main>
        
        <footer class="bg-black text-light py-4 mt-4"> <!-- Changed to bg-black and text-light -->
            <div class="container">
                <div class="row">
                    <div class="col-md-4 mb-4 mb-md-0">
                        <h5>{{ config('app.name') }}</h5>
                        <p>Your source for the latest news and articles.</p>
                    </div>
                    <div class="col-md-4 mb-4 mb-md-0">
                        {{-- <h5>Quick Links</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2"><a href="{{ route('home') }}" class="text-dark">Home</a></li>
                            @foreach(\App\Models\Category::take(3)->get() as $category)
                                <li class="mb-2"><a href="{{ route('posts.category', $category->slug) }}" class="text-dark">{{ $category->name }}</a></li>
                            @endforeach
                        </ul> --}}
                    </div>
                    <div class="col-md-4">
                        {{-- <h5>Connect With Us</h5>
                        <div class="d-flex justify-content-center justify-content-md-start mt-3">
                            <a href="#" class="text-dark me-3"><i class="bi bi-facebook fs-4"></i></a>
                            <a href="#" class="text-dark me-3"><i class="bi bi-twitter fs-4"></i></a>
                            <a href="#" class="text-dark"><i class="bi bi-instagram fs-4"></i></a>
                        </div> --}}
                    </div>
                </div>
                <div class="text-center mt-4">
                    <p class="mb-0">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                </div>
            </div>
        </footer>
        
        <!-- Mobile Bottom Navigation -->
        <div class="mobile-bottom-nav d-md-none">
            <div class="d-flex justify-content-around py-2">
                <a href="{{ route('home') }}" class="text-center text-decoration-none text-dark">
                    <i class="bi bi-house fs-5 d-block"></i>
                    <small>Home</small>
                </a>
                <a href="#" class="text-center text-decoration-none text-dark" data-bs-toggle="modal" data-bs-target="#categoriesModal">
                    <i class="bi bi-grid fs-5 d-block"></i>
                    <small>Categories</small>
                </a>
                <a href="#" class="text-center text-decoration-none text-dark" onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
                    <i class="bi bi-search fs-5 d-block"></i>
                    <small>Search</small>
                </a>
                @auth
                    <a href="{{ Auth::user()->isAuthor() ? route('admin.dashboard') : '#' }}" class="text-center text-decoration-none text-dark">
                        <i class="bi bi-person fs-5 d-block"></i>
                        <small>Account</small>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-center text-decoration-none text-dark">
                        <i class="bi bi-box-arrow-in-right fs-5 d-block"></i>
                        <small>Login</small>
                    </a>
                @endauth
            </div>
        </div>
        
        <!-- Mobile Categories Modal -->
        <div class="modal fade" id="categoriesModal" tabindex="-1" aria-labelledby="categoriesModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="categoriesModalLabel">Categories</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="list-group">
                            @foreach(\App\Models\Category::withCount('posts')->get() as $category)
                                <a href="{{ route('posts.category', $category->slug) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    {{ $category->name }}
                                    <span class="badge bg-primary rounded-pill">{{ $category->posts_count }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @vite(['resources/js/app.js'])
    <!-- Matrix initialization script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (localStorage.getItem('matrix_enabled') === 'true') {
                console.log('Matrix enabled, initializing...');
                if (typeof window.forceMatrix === 'function') {
                    setTimeout(window.forceMatrix, 500);
                }
            }
        });
    </script>
    
    @livewireScripts
    @stack('scripts')
</body>
</html>
