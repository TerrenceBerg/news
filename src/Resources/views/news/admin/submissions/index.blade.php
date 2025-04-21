@extends('news.admin.layouts.admin')

@section('title', 'Manage Submissions')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">User Submissions</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Submissions</li>
    </ol>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Pending Submissions
        </div>
        <div class="card-body">
            @if($submissions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Submitted By</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($submissions as $submission)
                            <tr>
                                <td>{{ $submission->id }}</td>
                                <td>{{ Str::limit($submission->title, 50) }}</td>
                                <td>
                                    @if($submission->user_id && $submission->user)
                                        {{ $submission->user->name }}
                                    @else
                                        Guest
                                    @endif
                                </td>
                                <td>
                                    @if($submission->is_published)
                                        <span class="badge bg-success">Published</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </td>
                                <td>{{ $submission->created_at->format('M d, Y') }}</td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.submissions.edit', $submission->id) }}" 
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        @if(!$submission->is_published)
                                            <form action="{{ route('admin.submissions.publish', $submission->id) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fas fa-check"></i> Publish
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.submissions.destroy', $submission->id) }}" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this submission?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center">
                    {{ $submissions->links() }}
                </div>
            @else
                <div class="alert alert-info mb-0">
                    There are no pending submissions at this time.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
