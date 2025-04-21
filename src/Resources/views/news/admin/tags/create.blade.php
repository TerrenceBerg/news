@extends('news::news.admin.layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Create Tag</h1>
        <a href="{{ route('admin.tags.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Tags
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.tags.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="slug" class="form-label">Slug</label>
                    <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                           id="slug" name="slug" value="{{ old('slug') }}">
                    <div class="form-text">Leave empty to auto-generate from name.</div>
                    @error('slug')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="d-grid d-md-flex">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Create Tag
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
