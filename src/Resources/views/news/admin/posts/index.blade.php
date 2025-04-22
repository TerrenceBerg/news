@extends('vendor.news.admin.layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manage Posts</h1>
        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add New Post
        </a>
    </div>

    @livewire('news.admin.posts-table')
@endsection
