@extends('vendor.news.admin.layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Manage Users</h1>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">All Users</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th class="d-none d-md-table-cell">Role</th>
                            <th class="d-none d-md-table-cell">Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td class="d-none d-md-table-cell">
                                    @if($user->isAdmin())
                                        <span class="badge bg-danger">Admin</span>
                                    @elseif($user->isAuthor())
                                        <span class="badge bg-warning">Author</span>
                                    @else
                                        <span class="badge bg-secondary">Reader</span>
                                    @endif
                                </td>
                                <td class="d-none d-md-table-cell">{{ $user->created_at->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center py-3 matrix-pagination">
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection
