@extends('news.admin.layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Edit User</h1>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Users
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                        <option value="reader" {{ old('role', $user->role) === 'reader' ? 'selected' : '' }}>Reader</option>
                        <option value="author" {{ old('role', $user->role) === 'author' ? 'selected' : '' }}>Author</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">New Password (leave blank to keep current)</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                           id="password" name="password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="d-grid d-md-flex">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Update User
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title mb-0">User Activity</h5>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Posts:</dt>
                <dd class="col-sm-9">{{ $user->posts->count() }}</dd>
                
                <dt class="col-sm-3">Comments:</dt>
                <dd class="col-sm-9">{{ $user->comments->count() }}</dd>
                
                <dt class="col-sm-3">Registered:</dt>
                <dd class="col-sm-9">{{ $user->created_at->format('M d, Y H:i:s') }}</dd>
                
                <dt class="col-sm-3">Last Login:</dt>
                <dd class="col-sm-9">Not tracked</dd>
            </dl>
        </div>
    </div>
@endsection
