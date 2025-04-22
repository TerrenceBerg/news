@extends('news::news.layouts.app')

@section('title', $user->name . '\'s Posts')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h1 class="h4 mb-0">{{ $user->name }}'s Posts</h1>
                    <p class="text-muted mb-0 small mt-1">
                        <i class="bi bi-person-circle me-1"></i> Member since {{ $user->created_at->format('M Y') }}
                    </p>
                </div>
                <div class="card-body">
                    @if ($posts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Posted On</th>
                                        <th width="120">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($posts as $post)
                                    <tr>
                                        <td>{{ $post->title }}</td>
                                        <td>
                                            @if ($post->is_published)
                                                <span class="badge bg-success">Published</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Pending Review</span>
                                            @endif
                                        </td>
                                        <td>{{ $post->created_at->format('M d, Y') }}</td>
                                        <td>
                                            @if ($post->is_published)
                                                <a href="{{ route('news.posts.show', $post->slug) }}" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-eye"></i> View
                                                </a>
                                            @else
                                                <span class="text-muted small">
                                                    <i class="bi bi-info-circle"></i> Awaiting admin approval
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $posts->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            <p class="mb-0">You haven't submitted any posts yet.</p>
                        </div>
                        
                        <div class="text-center mt-3">
                            <a href="{{ route('news.submissions.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i> Create Your First Post
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
