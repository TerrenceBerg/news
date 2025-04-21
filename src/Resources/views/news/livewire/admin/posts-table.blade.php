<div>
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0">All Posts</h5>
                </div>
                <div class="col-auto">
                    <div class="input-group">
                        <input wire:model.live.debounce.300ms="search" type="text" class="form-control" placeholder="Search posts...">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead>
                        <tr>
                            <th wire:click="sortBy('title')" style="cursor: pointer;">
                                Title
                                @if($sortField === 'title')
                                    @if($sortDirection === 'asc')
                                        <i class="bi bi-arrow-up-short"></i>
                                    @else
                                        <i class="bi bi-arrow-down-short"></i>
                                    @endif
                                @endif
                            </th>
                            <th wire:click="sortBy('is_published')" style="cursor: pointer;" class="d-none d-md-table-cell">
                                Status
                                @if($sortField === 'is_published')
                                    @if($sortDirection === 'asc')
                                        <i class="bi bi-arrow-up-short"></i>
                                    @else
                                        <i class="bi bi-arrow-down-short"></i>
                                    @endif
                                @endif
                            </th>
                            <th class="d-none d-md-table-cell">Category</th>
                            <th class="d-none d-lg-table-cell">Author</th>
                            <th wire:click="sortBy('created_at')" style="cursor: pointer;" class="d-none d-md-table-cell">
                                Date
                                @if($sortField === 'created_at')
                                    @if($sortDirection === 'asc')
                                        <i class="bi bi-arrow-up-short"></i>
                                    @else
                                        <i class="bi bi-arrow-down-short"></i>
                                    @endif
                                @endif
                            </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($posts as $post)
                            <tr>
                                <td>
                                    <div>{{ Str::limit($post->title, 30) }}</div>
                                    <div class="d-md-none">
                                        <span class="badge {{ $post->is_published ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $post->is_published ? 'Published' : 'Draft' }}
                                        </span>
                                        <small class="text-muted">{{ $post->created_at->format('M d') }}</small>
                                    </div>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    @if($post->is_published)
                                        <span class="badge bg-success">Published</span>
                                    @else
                                        <span class="badge bg-secondary">Draft</span>
                                    @endif
                                </td>
                                <td class="d-none d-md-table-cell">{{ $post->category->name }}</td>
                                <td class="d-none d-lg-table-cell">{{ $post->user->name }}</td>
                                <td class="d-none d-md-table-cell">{{ $post->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button wire:click="deletePost({{ $post->id }})" 
                                                wire:confirm="Are you sure you want to delete this post?" 
                                                class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center py-3 matrix-pagination">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</div>
