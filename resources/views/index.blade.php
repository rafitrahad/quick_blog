@extends('layouts.app')

@section('content')
<div class="row g-4 justify-content-center">
    <!-- Left Column: Billboard for Ads -->
    <div class="col-12 col-lg-3 col-xl-3 d-none d-lg-block" style="max-width: 320px;">
        <div class="sidebar-reactive-scroll" style="position: sticky; top: 76px; max-height: calc(100vh - 90px); overflow-y: auto; overscroll-behavior: contain;">
            <div class="card border-0 shadow-sm text-center p-4 bg-white" style="border-radius: 12px; border: 2px dashed #cbd5e1 !important;">
                <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mx-auto mb-3" style="width: 56px; height: 56px;">
                    <i class="bi bi-badge-ad text-muted fs-3"></i>
                </div>
                <h6 class="fw-bold text-dark mb-1">Billboard for Ads</h6>
                <p class="text-muted small mb-0">
                    Space reserved for advertisements.
                </p>
            </div>
        </div>
    </div>

    <!-- Main Column: Articles Feed -->
    <div class="col-12 col-lg-6 col-xl-6 flex-grow-1" style="max-width: 800px;">
        <!-- Page Header & Search -->
        <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
            <h1 class="h3 fw-bold mb-0">Latest Articles</h1>
            
            <form action="{{ route('home') }}" method="GET" class="d-flex gap-2">
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
                    <div class="d-flex align-items-center gap-3">
                        <span>{{ $item->created_at ? $item->created_at->format('M d, Y') : 'Recent' }}</span>
                    </div>
                </div>

                <!-- Media Container -->
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
                        <a href="{{ route('posts.show', $item->slug ?? $item->id) }}" class="text-decoration-none text-dark fw-bold">
                            {{ $item->title }}
                        </a>
                    </h4>
                    
                    <p class="card-text text-secondary mb-3">
                        {{ Str::limit(strip_tags($item->content), 200) }}
                    </p>

                    <!-- Post Actions & Comment Anchor Link (toggles comments) -->
                    <div class="d-flex justify-content-between align-items-center pt-1">
                        <a href="{{ route('posts.show', $item->slug ?? $item->id) }}" class="text-decoration-none small text-dark fw-semibold">
                            Read more &rarr;
                        </a>

                        <a 
                            href="#comments-collapse-{{ $item->id }}" 
                            class="text-decoration-none small text-secondary d-inline-flex align-items-center gap-1 py-1 px-2 rounded hover-bg-light"
                            data-bs-toggle="collapse" 
                            role="button" 
                            aria-expanded="false" 
                            aria-controls="comments-collapse-{{ $item->id }}"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M5 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0m4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0m3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
                                <path d="m2.165 15.803.02-.004c1.83-.363 2.948-.842 3.468-1.105A9 9 0 0 0 8 15c4.418 0 8-3.134 8-7s-3.582-7-8-7-8 3.134-8 7c0 1.76.743 3.37 1.97 4.6a10.4 10.4 0 0 1-.524 2.318l-.003.011a11 11 0 0 1-.244.637c-.079.186.074.394.273.362a22 22 0 0 0 .693-.125m.8-3.108a1 1 0 0 0-.287-.801C1.618 10.85 1 9.5 1 8c0-3.314 3.134-6 7-6s7 2.686 7 6-3.134 6-7 6c-.848 0-1.66-.134-2.4-.38a1 1 0 0 0-.585-.029c-.732.185-1.57.43-2.39.637.284-.663.504-1.33.626-1.926"/>
                            </svg>
                            <span>{{ $item->comments_count ?? $item->comments->count() }} {{ \Illuminate\Support\Str::plural('Comment', $item->comments_count ?? $item->comments->count()) }}</span>
                        </a>
                    </div>

                    <!-- Collapsible Comments Section (Hidden by default until clicked) -->
                    <div class="collapse" id="comments-collapse-{{ $item->id }}">
                        <hr class="my-3">

                        <div class="comments-section">
                            <h6 class="fw-bold small text-muted text-uppercase mb-3">
                                Comments ({{ $item->comments_count ?? $item->comments->count() }})
                            </h6>

                        <div class="comment-list mb-3" style="max-height: 380px; overflow-y: auto;">
                            @forelse($item->comments as $comment)
                                @if($comment->isHiddenFor(Auth::id()))
                                    @continue
                                @endif

                                <div class="bg-light rounded p-2 mb-2 border-start border-3 border-dark">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div class="d-flex align-items-center gap-2">
                                            <strong class="small text-dark">
                                                {{ $comment->user->name ?? 'User' }}
                                            </strong>
                                            @if($comment->edited_at)
                                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle py-0 px-1" style="font-size: 0.65rem;" title="Edited {{ $comment->edited_at->diffForHumans() }}">(edited)</span>
                                            @endif
                                        </div>

                                        <div class="d-flex align-items-center gap-2">
                                            <span class="text-muted" style="font-size: 0.75rem;">
                                                {{ $comment->created_at ? $comment->created_at->diffForHumans() : 'Just now' }}
                                            </span>

                                            <!-- Three Dots Dropdown Menu -->
                                            <div class="dropdown">
                                                <button class="btn btn-link text-muted p-0 text-decoration-none" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="line-height: 1;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                        <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                                                    </svg>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 py-1" style="font-size: 0.8rem; min-width: 140px;">
                                                    @auth
                                                        @if(Auth::id() === $comment->user_id)
                                                            <!-- Edit: Only for user who made comment -->
                                                            <li>
                                                                <button type="button" class="dropdown-item py-1" data-bs-toggle="collapse" data-bs-target="#edit-comment-{{ $comment->id }}">
                                                                    Edit
                                                                </button>
                                                            </li>
                                                            <!-- Delete: Soft delete for everyone -->
                                                            <li>
                                                                <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this comment?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="dropdown-item py-1 text-danger">
                                                                        Delete
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        @else
                                                            <!-- Hide: Removes from this user's view only -->
                                                            <li>
                                                                <form action="{{ route('comments.hide', $comment->id) }}" method="POST">
                                                                    @csrf
                                                                    <button type="submit" class="dropdown-item py-1 text-muted">
                                                                        Hide comment
                                                                    </button>
                                                                </form>
                                                            </li>
                                                            @if(Auth::user()->isAdmin())
                                                                <li>
                                                                    <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('Delete this comment as admin?');">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="dropdown-item py-1 text-danger">
                                                                            Delete (Admin)
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                            @endif
                                                        @endif
                                                    @else
                                                        <li>
                                                            <a href="{{ route('login') }}" class="dropdown-item py-1 text-muted">
                                                                Login to manage
                                                            </a>
                                                        </li>
                                                    @endauth
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <p class="mb-1 small text-secondary" style="font-size: 0.85rem;">
                                        {{ $comment->body }}
                                    </p>

                                    <!-- Edit Comment Form (Inline Collapse, Author Only) -->
                                    @auth
                                        @if(Auth::id() === $comment->user_id)
                                            <div class="collapse my-2" id="edit-comment-{{ $comment->id }}">
                                                <form action="{{ route('comments.update', $comment->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="input-group input-group-sm">
                                                        <input 
                                                            type="text" 
                                                            name="body" 
                                                            value="{{ $comment->body }}" 
                                                            class="form-control" 
                                                            required
                                                        >
                                                        <button class="btn btn-dark" type="submit">Save</button>
                                                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#edit-comment-{{ $comment->id }}">Cancel</button>
                                                    </div>
                                                </form>
                                            </div>
                                        @endif
                                    @endauth

                                    <!-- Reply Action -->
                                    <div class="d-flex align-items-center gap-2 mt-1">
                                        @auth
                                            <button 
                                                type="button" 
                                                class="btn btn-sm btn-link text-decoration-none text-muted p-0 d-inline-flex align-items-center gap-1" 
                                                style="font-size: 0.78rem;" 
                                                data-bs-toggle="collapse" 
                                                data-bs-target="#reply-form-{{ $comment->id }}"
                                                aria-expanded="false"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M5.921 11.9 1.353 8.62a.72.72 0 0 1 0-1.238L5.921 4.1A.716.716 0 0 1 7 4.619V6c2.827.167 4.878 1.178 6.179 2.979-2.038-.112-4.341.529-6.179 2.13v1.172c0 .58-.654.949-1.079.619"/>
                                                </svg>
                                                <span>Reply</span>
                                            </button>
                                        @else
                                            <a href="{{ route('login') }}" class="text-muted text-decoration-none d-inline-flex align-items-center gap-1" style="font-size: 0.78rem;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M5.921 11.9 1.353 8.62a.72.72 0 0 1 0-1.238L5.921 4.1A.716.716 0 0 1 7 4.619V6c2.827.167 4.878 1.178 6.179 2.979-2.038-.112-4.341.529-6.179 2.13v1.172c0 .58-.654.949-1.079.619"/>
                                                </svg>
                                                <span>Reply</span>
                                            </a>
                                        @endauth

                                        @if($comment->replies && $comment->replies->count() > 0)
                                            <span class="text-muted" style="font-size: 0.75rem;">&bull;</span>
                                            <span class="text-muted" style="font-size: 0.75rem;">
                                                {{ $comment->replies->count() }} {{ \Illuminate\Support\Str::plural('reply', $comment->replies->count()) }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Reply Form (Collapsible) -->
                                    @auth
                                        <div class="collapse mt-2" id="reply-form-{{ $comment->id }}">
                                            <form action="{{ route('comments.store', $item->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                                <div class="input-group input-group-sm">
                                                    <input 
                                                        type="text" 
                                                        name="body" 
                                                        class="form-control" 
                                                        placeholder="Reply to {{ $comment->user->name ?? 'User' }}..." 
                                                        required
                                                    >
                                                    <button class="btn btn-outline-dark" type="submit">Reply</button>
                                                </div>
                                            </form>
                                        </div>
                                    @endauth

                                    <!-- Nested Replies List -->
                                    @if($comment->replies && $comment->replies->count() > 0)
                                        <div class="mt-2 ps-3 border-start border-2 border-secondary-subtle d-flex flex-column gap-2">
                                            @foreach($comment->replies as $reply)
                                                @if($reply->isHiddenFor(Auth::id()))
                                                    @continue
                                                @endif

                                                <div class="bg-white rounded p-2 shadow-xs">
                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                        <div class="d-flex align-items-center gap-1">
                                                            <strong class="small text-dark d-inline-flex align-items-center gap-1" style="font-size: 0.8rem;">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="currentColor" class="text-muted" viewBox="0 0 16 16">
                                                                    <path fill-rule="evenodd" d="M1.5 1.5A.5.5 0 0 0 1 2v4.5A2.5 2.5 0 0 0 3.5 9h9.293l-3.147 3.146a.5.5 0 0 0 .708.708l4-4a.5.5 0 0 0 0-.708l-4-4a.5.5 0 0 0-.708.708L12.793 8.5H3.5A1.5 1.5 0 0 1 2 7V2a.5.5 0 0 0-.5-.5"/>
                                                                </svg>
                                                                {{ $reply->user->name ?? 'User' }}
                                                            </strong>
                                                            @if($reply->edited_at)
                                                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle py-0 px-1" style="font-size: 0.6rem;" title="Edited {{ $reply->edited_at->diffForHumans() }}">(edited)</span>
                                                            @endif
                                                        </div>

                                                        <div class="d-flex align-items-center gap-2">
                                                            <span class="text-muted" style="font-size: 0.7rem;">
                                                                {{ $reply->created_at ? $reply->created_at->diffForHumans() : 'Just now' }}
                                                            </span>

                                                            <!-- Three Dots Dropdown for Reply -->
                                                            <div class="dropdown">
                                                                <button class="btn btn-link text-muted p-0 text-decoration-none" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="line-height: 1;">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                                                                        <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                                                                    </svg>
                                                                </button>
                                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 py-1" style="font-size: 0.78rem; min-width: 130px;">
                                                                    @auth
                                                                        @if(Auth::id() === $reply->user_id)
                                                                            <li>
                                                                                <button type="button" class="dropdown-item py-1" data-bs-toggle="collapse" data-bs-target="#edit-reply-{{ $reply->id }}">
                                                                                    Edit
                                                                                </button>
                                                                            </li>
                                                                            <li>
                                                                                <form action="{{ route('comments.destroy', $reply->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this reply?');">
                                                                                    @csrf
                                                                                    @method('DELETE')
                                                                                    <button type="submit" class="dropdown-item py-1 text-danger">
                                                                                        Delete
                                                                                    </button>
                                                                                </form>
                                                                            </li>
                                                                        @else
                                                                            <li>
                                                                                <form action="{{ route('comments.hide', $reply->id) }}" method="POST">
                                                                                    @csrf
                                                                                    <button type="submit" class="dropdown-item py-1 text-muted">
                                                                                        Hide reply
                                                                                    </button>
                                                                                </form>
                                                                            </li>
                                                                            @if(Auth::user()->isAdmin())
                                                                                <li>
                                                                                    <form action="{{ route('comments.destroy', $reply->id) }}" method="POST" onsubmit="return confirm('Delete this reply as admin?');">
                                                                                        @csrf
                                                                                        @method('DELETE')
                                                                                        <button type="submit" class="dropdown-item py-1 text-danger">
                                                                                            Delete (Admin)
                                                                                        </button>
                                                                                    </form>
                                                                                </li>
                                                                            @endif
                                                                        @endif
                                                                    @else
                                                                        <li>
                                                                            <a href="{{ route('login') }}" class="dropdown-item py-1 text-muted">
                                                                                Login to manage
                                                                            </a>
                                                                        </li>
                                                                    @endauth
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <p class="mb-1 text-secondary" style="font-size: 0.82rem;">
                                                        {{ $reply->body }}
                                                    </p>

                                                    <!-- Edit Reply Form (Author Only) -->
                                                    @auth
                                                        @if(Auth::id() === $reply->user_id)
                                                            <div class="collapse my-2" id="edit-reply-{{ $reply->id }}">
                                                                <form action="{{ route('comments.update', $reply->id) }}" method="POST">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="input-group input-group-sm">
                                                                        <input 
                                                                            type="text" 
                                                                            name="body" 
                                                                            value="{{ $reply->body }}" 
                                                                            class="form-control" 
                                                                            required
                                                                        >
                                                                        <button class="btn btn-dark" type="submit">Save</button>
                                                                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#edit-reply-{{ $reply->id }}">Cancel</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        @endif
                                                    @endauth

                                                    <!-- Reply button for nested reply -->
                                                    <div>
                                                        @auth
                                                            <button 
                                                                type="button" 
                                                                class="btn btn-sm btn-link text-decoration-none text-muted p-0 d-inline-flex align-items-center gap-1" 
                                                                style="font-size: 0.72rem;" 
                                                                data-bs-toggle="collapse" 
                                                                data-bs-target="#reply-form-{{ $comment->id }}"
                                                                onclick="const input = document.querySelector('#reply-form-{{ $comment->id }} input[name=body]'); if(input) { input.value = '@' + '{{ $reply->user->name ?? 'User' }} '; input.focus(); }"
                                                            >
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="currentColor" viewBox="0 0 16 16">
                                                                    <path d="M5.921 11.9 1.353 8.62a.72.72 0 0 1 0-1.238L5.921 4.1A.716.716 0 0 1 7 4.619V6c2.827.167 4.878 1.178 6.179 2.979-2.038-.112-4.341.529-6.179 2.13v1.172c0 .58-.654.949-1.079.619"/>
                                                                </svg>
                                                                <span>Reply</span>
                                                            </button>
                                                        @else
                                                            <a href="{{ route('login') }}" class="text-muted text-decoration-none d-inline-flex align-items-center gap-1" style="font-size: 0.72rem;">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="currentColor" viewBox="0 0 16 16">
                                                                    <path d="M5.921 11.9 1.353 8.62a.72.72 0 0 1 0-1.238L5.921 4.1A.716.716 0 0 1 7 4.619V6c2.827.167 4.878 1.178 6.179 2.979-2.038-.112-4.341.529-6.179 2.13v1.172c0 .58-.654.949-1.079.619"/>
                                                                </svg>
                                                                <span>Reply</span>
                                                            </a>
                                                        @endauth
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <p class="text-muted small fst-italic mb-2">No comments yet.</p>
                            @endforelse
                        </div>

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

    <!-- Right Column: Trending Posts (Pure image + title, suspenseful, no view counts) -->
    <div class="col-12 col-lg-3 col-xl-3" style="max-width: 360px;">
        <div class="sidebar-reactive-scroll" style="position: sticky; top: 76px; max-height: calc(100vh - 90px); overflow-y: auto; overscroll-behavior: contain;">
            <!-- Header -->
            <div class="d-flex align-items-center mb-3 pb-2 border-bottom bg-light pt-1" style="position: sticky; top: 0; z-index: 5;">
                <h5 class="fw-bold mb-0">
                    🔥 Trending
                </h5>
            </div>

            <!-- Small Boxed Posts (Only Image & Title) -->
            <div class="d-flex flex-column gap-3">
                @forelse($trendingPosts ?? [] as $trending)
                    <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 8px;">
                        <!-- Image or Video (Original ratio, full display without zooming/cropping) -->
                        @if($trending->media_path && $trending->media_type === 'image')
                            <a href="{{ route('posts.show', $trending->slug ?? $trending->id) }}" class="d-block text-center bg-light overflow-hidden">
                                <img 
                                    src="{{ asset('storage/' . $trending->media_path) }}" 
                                    class="img-fluid w-100" 
                                    alt="{{ $trending->title }}" 
                                    style="max-height: 220px; object-fit: contain; background-color: #f8f9fa;"
                                >
                            </a>
                        @elseif($trending->media_path && $trending->media_type === 'video')
                            <div class="w-100 bg-dark text-center">
                                <video 
                                    controls 
                                    preload="metadata" 
                                    class="w-100" 
                                    style="max-height: 220px; object-fit: contain; background-color: #000;"
                                >
                                    <source src="{{ asset('storage/' . $trending->media_path) }}">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        @endif

                        <!-- Title Only -->
                        <div class="card-body p-3">
                            <h6 class="card-title fw-bold mb-0">
                                <a href="{{ route('posts.show', $trending->slug ?? $trending->id) }}" class="text-dark text-decoration-none" style="font-size: 0.95rem; line-height: 1.35;">
                                    {{ $trending->title }}
                                </a>
                            </h6>
                        </div>
                    </div>
                @empty
                    <div class="card border-0 shadow-sm p-3 text-center text-muted small">
                        No trending articles yet.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
    /* Independent reactive scrollbars */
    .sidebar-reactive-scroll {
        scrollbar-width: thin;
        scrollbar-color: rgba(148, 163, 184, 0.4) transparent;
        padding-right: 4px;
        scroll-behavior: smooth;
    }
    .sidebar-reactive-scroll::-webkit-scrollbar {
        width: 5px;
    }
    .sidebar-reactive-scroll::-webkit-scrollbar-track {
        background: transparent;
    }
    .sidebar-reactive-scroll::-webkit-scrollbar-thumb {
        background: rgba(148, 163, 184, 0.3);
        border-radius: 10px;
    }
    .sidebar-reactive-scroll:hover::-webkit-scrollbar-thumb {
        background: rgba(100, 116, 139, 0.6);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Enhance independent scroll behavior when cursor is inside sidebars
        const sidebars = document.querySelectorAll('.sidebar-reactive-scroll');
        sidebars.forEach(sidebar => {
            sidebar.addEventListener('wheel', function (e) {
                const canScroll = sidebar.scrollHeight > sidebar.clientHeight;
                if (canScroll) {
                    const atTop = sidebar.scrollTop === 0 && e.deltaY < 0;
                    const atBottom = Math.ceil(sidebar.scrollTop + sidebar.clientHeight) >= sidebar.scrollHeight && e.deltaY > 0;
                    // Keep scroll trapped inside this sidebar unless already at extreme edge
                    if (!atTop && !atBottom) {
                        e.stopPropagation();
                    }
                }
            }, { passive: true });
        });
    });
</script>
@endsection