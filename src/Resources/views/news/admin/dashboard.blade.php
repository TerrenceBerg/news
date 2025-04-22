@extends('vendor.news.admin.layouts.admin')

@section('content')
    <h1 class="h3 mb-4">Dashboard</h1>
    
    <div class="row g-3">
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0">Posts</h5>
                            <h2 class="my-2">{{ $stats['posts'] }}</h2>
                        </div>
                        <div class="fs-1 opacity-50">
                            <i class="bi bi-file-text"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('admin.posts.index') }}">View Details</a>
                    <div class="small text-white"><i class="bi bi-chevron-right"></i></div>
                </div>
            </div>
        </div>
        
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0">Users</h5>
                            <h2 class="my-2">{{ $stats['users'] }}</h2>
                        </div>
                        <div class="fs-1 opacity-50">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('admin.users.index') }}">View Details</a>
                    <div class="small text-white"><i class="bi bi-chevron-right"></i></div>
                </div>
            </div>
        </div>
        
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0">Comments</h5>
                            <h2 class="my-2">{{ $stats['comments'] }}</h2>
                        </div>
                        <div class="fs-1 opacity-50">
                            <i class="bi bi-chat-dots"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('admin.comments.index') }}">View Details</a>
                    <div class="small text-white"><i class="bi bi-chevron-right"></i></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-12 col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <i class="bi bi-file-text me-1"></i>
                    Recent Posts
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th class="d-none d-md-table-cell">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['recent_posts'] as $post)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.posts.edit', $post) }}" class="text-decoration-none">
                                                {{ Str::limit($post->title, 25) }}
                                            </a>
                                        </td>
                                        <td>{{ $post->user->name }}</td>
                                        <td class="d-none d-md-table-cell">{{ $post->created_at->format('M d') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <i class="bi bi-chat-dots me-1"></i>
                    Recent Comments
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Comment</th>
                                    <th class="d-none d-md-table-cell">Post</th>
                                    <th>User</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['recent_comments'] as $comment)
                                    <tr>
                                        <td>{{ Str::limit($comment->content, 25) }}</td>
                                        <td class="d-none d-md-table-cell">{{ Str::limit($comment->post->title, 20) }}</td>
                                        <td>{{ $comment->user->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
