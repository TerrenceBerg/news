<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#212529">
    <title>Admin - {{ config('app.name', 'Laravel') }}</title>
    
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Load scripts in correct order -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    
    <!-- Summernote CSS and JS files - correct version for Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
    
    <!-- Ensure jQuery is globally available -->
    <script>
        window.jQuery = window.$ = jQuery;
    </script>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    
    @livewireStyles
    
    <style>
        body {
            overflow-x: hidden;
            touch-action: manipulation;
        }
        
        #sidebar-wrapper {
            min-height: 100vh;
            width: 250px;
            margin-left: -250px;
            transition: margin 0.25s ease-out;
        }
        
        #sidebar-wrapper .list-group-item {
            border-radius: 0;
            border-left: 0;
            border-right: 0;
            border-top: 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }
        
        #page-content-wrapper {
            min-width: 100vw;
        }
        
        #wrapper.toggled #sidebar-wrapper {
            margin-left: 0;
        }
        
        .sidebar-heading {
            padding: 0.875rem 1.25rem;
            font-size: 1.2rem;
        }
        
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        @media (min-width: 768px) {
            #sidebar-wrapper {
                margin-left: 0;
            }
            
            #page-content-wrapper {
                min-width: 0;
                width: calc(100% - 250px);
            }
            
            #wrapper.toggled #sidebar-wrapper {
                margin-left: -250px;
            }
            
            .sidebar-toggle {
                display: none;
            }
        }
        
        @media (max-width: 767.98px) {
            #sidebar-wrapper {
                margin-left: -250px;
                position: fixed;
                z-index: 1000;
            }
            
            #page-content-wrapper {
                min-width: 100vw;
                width: 100%;
            }
            
            #wrapper.toggled #sidebar-wrapper {
                margin-left: 0;
            }
            
            #wrapper.toggled .overlay {
                display: block;
                opacity: 1;
            }
            
            .container-fluid {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }
            
            .overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0,0,0,0.5);
                z-index: 999;
                display: none;
                opacity: 0;
                transition: opacity 0.25s ease-out;
            }
            
            /* Larger touch targets */
            .list-group-item i {
                font-size: 1.25rem;
            }
            
            .btn-sm {
                padding: 0.375rem 0.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div class="bg-dark border-right" id="sidebar-wrapper">
            <div class="sidebar-heading text-center py-4 text-light fs-4 fw-bold">
                <a href="{{ route('admin.news_dashboard') }}" class="text-decoration-none text-light d-flex align-items-center justify-content-center">
                    <i class="bi bi-newspaper me-2"></i> 
                    {{ config('app.name') }}
                </a>
            </div>
            <div class="list-group list-group-flush">
                <a href="{{ route('admin.news_dashboard') }}" class="list-group-item list-group-item-action bg-dark text-light">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
                <a href="{{ route('admin.posts.index') }}" class="list-group-item list-group-item-action bg-dark text-light">
                    <i class="bi bi-file-text me-2"></i> Posts
                </a>
                <a href="{{ route('admin.categories.index') }}" class="list-group-item list-group-item-action bg-dark text-light">
                    <i class="bi bi-folder me-2"></i> Categories
                </a>
                <a href="{{ route('admin.tags.index') }}" class="list-group-item list-group-item-action bg-dark text-light">
                    <i class="bi bi-tags me-2"></i> Tags
                </a>
                <a href="{{ route('admin.comments.index') }}" class="list-group-item list-group-item-action bg-dark text-light">
                    <i class="bi bi-chat-dots me-2"></i> Comments
                </a>
                <a href="{{ route('admin.news_users.index') }}" class="list-group-item list-group-item-action bg-dark text-light">
                    <i class="bi bi-people me-2"></i> Users
                </a>
                <a href="{{ url('/') }}" class="list-group-item list-group-item-action bg-dark text-light">
                    <i class="bi bi-eye me-2"></i> View Site
                </a>
                <a class="list-group-item list-group-item-action bg-dark text-light" href="{{ route('admin.submissions.index') }}"><i class="bi bi-file-text me-2"></i> User Submissions
                    @php
                        $pendingCount = App\Models\News\Post::where('is_published', 0)->count();
                    @endphp
                    @if($pendingCount > 0)
                        <span class="badge bg-danger ms-2">{{ $pendingCount }}</span>
                    @endif
                </a>
                <a href="{{ route('logout') }}" class="list-group-item list-group-item-action bg-dark text-light"
                   onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                </a>
                <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
        
        <!-- Overlay for mobile -->
        <div class="overlay" id="sidebar-overlay"></div>
        
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom sticky-top">
                <div class="container-fluid">
                    <button class="btn btn-primary sidebar-toggle" id="menu-toggle">        
                        <i class="bi bi-list"></i> <span class="d-none d-sm-inline">Menu</span>
                    </button>

                    <ul class="navbar-nav ms-auto d-flex flex-row align-items-center">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle me-1"></i>
                                <span class="d-none d-sm-inline">{{ Auth::user()->name ?? '' }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right me-1"></i> {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>

            <div class="container-fluid py-4">
                @if(session('message'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @yield('content')
            </div>
        </div>
    </div>
    
    <!-- Core Scripts -->
    @livewireScripts
    @stack('scripts')
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menu-toggle');
            const wrapper = document.getElementById('wrapper');
            const overlay = document.getElementById('sidebar-overlay');
            
            function toggleSidebar() {
                wrapper.classList.toggle("toggled");
            }
            
            menuToggle.addEventListener("click", function(e) {
                e.preventDefault();
                toggleSidebar();
            });
            
            overlay.addEventListener("click", function(e) {
                e.preventDefault();
                toggleSidebar();
            });
            
            // Close sidebar when window is resized to mobile size
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768 && wrapper.classList.contains('toggled') && getComputedStyle(menuToggle).display === 'none') {
                    wrapper.classList.remove('toggled');
                }
            });
            
            // Handle swipe gestures for mobile
            let touchStartX = 0;
            let touchEndX = 0;
            
            document.addEventListener('touchstart', function(e) {
                touchStartX = e.changedTouches[0].screenX;
            }, false);
            
            document.addEventListener('touchend', function(e) {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipe();
            }, false);
            
            function handleSwipe() {
                // Swipe right to open sidebar
                if (touchEndX - touchStartX > 100 && touchStartX < 50) {
                    if (!wrapper.classList.contains('toggled')) {
                        wrapper.classList.add('toggled');
                    }
                }
                
                // Swipe left to close sidebar
                if (touchStartX - touchEndX > 100 && wrapper.classList.contains('toggled')) {
                    wrapper.classList.remove('toggled');
                }
            }
        });

        // Fix livewire websocket connection issues
        document.addEventListener('livewire:available', function() {
            Livewire.hook('message.failed', (message, component) => {
                console.error('Livewire Error:', message);
            });
        });
    </script>
</body>
</html>
