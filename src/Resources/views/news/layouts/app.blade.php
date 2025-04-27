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

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="{{url('css/blog.css')}}">
    <!-- Load scripts in correct order -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>

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

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('news.home') }}">Home</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="categoriesDropdown" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false">
                                Categories
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="categoriesDropdown">
                                @foreach(App\Models\News\Category::orderBy('name', 'ASC')->get() as $category)
                                    <li><a class="dropdown-item" href="{{ route('news.posts.category', $category->slug) }}">{{ $category->name }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('news.submissions.create') }}">
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
                                     <a class="dropdown-item" href="{{ route('news.user.posts', Auth::id()) }}">
                                        <i class="bi bi-file-text me-1"></i> My Posts
                                    </a>
                                    
                                    @if(Auth::user()->isAdmin())
                                        <a class="dropdown-item" href="{{ route('admin.news_dashboard') }}">
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
                                <a class="nav-link" href="{{ route('news.submissions.my-posts') }}">
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
        
        <footer class="py-4 mt-4">
            <div class="container">
                <div class="text-center mt-4">
                    <p class="mb-0">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                </div>
            </div>
        </footer>
        
        <!-- Mobile Bottom Navigation -->
        <div class="mobile-bottom-nav d-md-none">
            <div class="d-flex justify-content-around py-2">
                <a href="{{ route('news.home') }}" class="text-center text-decoration-none text-dark">
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
                            @foreach(App\Models\News\Category::withCount('posts')->get() as $category)
                                <a href="{{ route('news.posts.category', $category->slug) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
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
    @livewireScripts
    @stack('scripts')
</body>
</html>
