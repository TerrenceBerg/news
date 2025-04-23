@extends('vendor.news.layouts.app')

@section('title', 'Submit News')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h1 class="h4 mb-0">Submit News</h1>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('news.submissions.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                            <div class="form-text">Enter a descriptive title for your news submission.</div>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Content <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="content" name="content" rows="10" required>{{ old('content') }}</textarea>
                            <div class="form-text">
                                Write your news content here. Plain text only.
                                <br>URLs will be automatically converted to embeds where applicable.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="source_url" class="form-label">Source URL (Optional)</label>
                            <input type="url" class="form-control" id="source_url" name="source_url" value="{{ old('source_url') }}">
                            <div class="form-text">If applicable, provide the original source URL.</div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Submit News</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
