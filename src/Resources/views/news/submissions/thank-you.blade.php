@extends('layouts.app')

@section('title', 'Submission Received')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="card">
                <div class="card-body py-5">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                    <h1 class="mt-4">Thank You!</h1>
                    
                    @if (session('success'))
                        <div class="alert alert-success my-4">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <p class="lead mb-4">
                        Your submission has been received and will be reviewed by our editorial team.
                    </p>
                    
                    <div class="mt-5">
                        <a href="{{ route('news.home') }}" class="btn btn-outline-primary me-3">Return Home</a>
                        <a href="{{ route('news.submissions.create') }}" class="btn btn-primary">Submit Another</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
