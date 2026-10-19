@extends('layouts.app')

@section('content')
<div class="container py-2" style="max-width: 800px;">
    <!-- Page Header & Search -->
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <h1 class="h3 fw-bold mb-0">Latest Articles</h1>
        
        <form action="{{ route('index') }}" method="GET" class="d-flex gap-2">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                placeholder="Search articles..." 
                class="form-control form-control-sm"
            >
            <button type="submit" class="btn btn-dark btn-sm">Search</button>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Single Column Post Feed -->
    <div class="d-flex flex-column gap-4">
        @forelse ($posts ?? [] as $item)
            <div class="card shadow-sm border-0 overflow-hidden">
                
                <!-- Author & Date Header -->
                <div class="card-header bg-white border-bottom-0 d-flex justify-content-between text-muted small pt-3 px-3 pb-2">
                    <span>By {{ $item->author->name ?? $item->author_name ?? 'Admin' }}</span>
                    <span>{{ $item->created_at ? $item->created_at->format('M d, Y') : 'Recent' }}</span>
                </div>

                <!-- Media Container (Shows the entire image/video without cropping) -->
                @if($item->media_path)
                    <div class="w-100 bg-light text-center">
                        @if($item->media_type === 'video')
                            <video controls class="w-100" style="max-height: 500px;">
                                <source src="{{ asset('storage/' . $item->media_path) }}">
                                Your browser does not support the video tag.
                            </video>
                        @else
                            <img 
                                src="{{ asset('storage/' . $item->media_path) }}" 
                                class="img-fluid w-100" 
                                style="max-height: 550px; object-fit: contain; background-color: #f8f9fa;" 
                                alt="{{ $item->title }}"
                            >
                        @endif
                    </div>
                @endif

                <!-- Card Body -->
                <div class="card-body">
                    <h4 class="card-title mb-2">
                        <a href="{{ route('post.show', $item->slug ?? $item->id) }}" class="text-decoration-none text-dark fw-bold">
                            {{ $item->title }}
                        </a>
                    </h4>
                    
                    <p class="card-text text-secondary mb-3">
                        {{ Str::limit(strip_tags($item->content), 200) }}
                    </p>

                    <hr class="my-3">

                    <!-- Comments Section -->
                    <div class="comments-section">
                        <h6 class="fw-bold small text-muted text-uppercase mb-3">
                            Comments ({{ $item->comments->count() }})
                        </h6>

                        <!-- Existing Comments List (Before the Input) -->
                        <div class="comment-list mb-3" style="max-height: 200px; overflow-y: auto;">
                            @forelse($item->comments as $comment)
                                <div class="bg-light rounded p-2 mb-2 border-start border-3 border-dark">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <strong class="small text-dark">
                                            {{ $comment->user->name ?? 'User' }}
                                        </strong>
                                        <span class="text-muted" style="font-size: 0.75rem;">
                                            {{ $comment->created_at ? $comment->created_at->diffForHumans() : 'Just now' }}
                                        </span>
                                    </div>
                                    <p class="mb-0 small text-secondary" style="font-size: 0.85rem;">
                                        {{ $comment->body }}
                                    </p>
                                </div>
                            @empty
                                <p class="text-muted small fst-italic mb-2">No comments yet.</p>
                            @endforelse
                        </div>

                        <!-- Comment Form or Login Message -->
                        @auth
                            <form action="{{ route('comments.store', $item->id) }}" method="POST">
                                @csrf
                                <div class="input-group input-group-sm">
                                    <input 
                                        type="text" 
                                        name="body" 
                                        class="form-control" 
                                        placeholder="Write a comment..." 
                                        required
                                    >
                                    <button class="btn btn-dark" type="submit">Post</button>
                                </div>
                            </form>
                        @else
                            <div class="alert alert-light border small text-center mb-0 py-2">
                                Please <a href="{{ route('login') }}" class="fw-bold text-decoration-none">login</a> to leave a comment.
                            </div>
                        @endauth
                    </div>
                </div>

            </div>
        @empty
            <div class="text-center py-5 text-muted">
                <p>No articles published yet.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination Links -->
    @if(method_exists($posts, 'links'))
        <div class="d-flex justify-content-center mt-4">
            {{ $posts->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection