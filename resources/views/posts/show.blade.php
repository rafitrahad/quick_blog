@extends('layouts.app')

@section('content')
<div class="container py-2" style="max-width: 800px;">
    <!-- Back Button -->
    <div class="mb-3">
        <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary">
            &larr; Back to Articles
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Single Post Article -->
    <article class="card shadow-sm border-0 overflow-hidden mb-4">
        <!-- Post Header -->
        <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-2">
            <div class="d-flex justify-content-between align-items-center text-muted small mb-2">
                <span>By <strong>{{ $post->author->name ?? $post->author_name ?? 'Admin' }}</strong></span>
                <span>{{ $post->created_at ? $post->created_at->format('M d, Y') : 'Recent' }}</span>
            </div>
            <h1 class="h2 fw-bold text-dark mb-2">{{ $post->title }}</h1>
            @if($post->category)
                <span class="badge bg-secondary mb-2">{{ $post->category }}</span>
            @endif
        </div>

        <!-- Featured Media -->
        @if($post->media_path)
            <div class="w-100 bg-light text-center">
                @if($post->media_type === 'video')
                    <video controls class="w-100" style="max-height: 500px;">
                        <source src="{{ asset('storage/' . $post->media_path) }}">
                        Your browser does not support the video tag.
                    </video>
                @else
                    <img 
                        src="{{ asset('storage/' . $post->media_path) }}" 
                        class="img-fluid w-100" 
                        style="max-height: 550px; object-fit: contain; background-color: #f8f9fa;" 
                        alt="{{ $post->title }}"
                    >
                @endif
            </div>
        @endif

        <!-- Post Content -->
        <div class="card-body px-4 py-3">
            @if($post->quote)
                <blockquote class="blockquote border-start border-4 border-dark ps-3 py-1 my-3 bg-light rounded-end">
                    <p class="fst-italic mb-0 text-secondary">"{{ $post->quote }}"</p>
                </blockquote>
            @endif

            <div class="post-content lh-lg text-dark mb-4">
                {!! nl2br(e($post->content)) !!}
            </div>

            <!-- Tags -->
            @if(!empty($post->tags) && is_array($post->tags))
                <div class="d-flex flex-wrap gap-1 mb-3">
                    @foreach($post->tags as $tag)
                        <span class="badge bg-light text-dark border">#{{ trim($tag) }}</span>
                    @endforeach
                </div>
            @endif

            <hr class="my-4">

            <!-- Comments & Replies Section -->
            <div class="comments-section">
                <h5 class="fw-bold mb-3">
                    Comments ({{ $post->comments_count ?? $post->comments->count() }})
                </h5>

                <!-- Comment Submission Form -->
                @auth
                    <form action="{{ route('comments.store', $post->id) }}" method="POST" class="mb-4">
                        @csrf
                        <div class="input-group">
                            <input 
                                type="text" 
                                name="body" 
                                class="form-control" 
                                placeholder="Write a comment..." 
                                required
                            >
                            <button class="btn btn-dark" type="submit">Post Comment</button>
                        </div>
                    </form>
                @else
                    <div class="alert alert-light border small text-center mb-4 py-2">
                        Please <a href="{{ route('login') }}" class="fw-bold text-decoration-none">login</a> to leave a comment.
                    </div>
                @endauth

                <!-- Comments List -->
                <div class="comment-list d-flex flex-column gap-3">
                    @forelse($post->comments as $comment)
                        @if($comment->isHiddenFor(Auth::id()))
                            @continue
                        @endif

                        <div class="bg-light rounded p-3 border-start border-3 border-dark">
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
                                                    <li>
                                                        <button type="button" class="dropdown-item py-1" data-bs-toggle="collapse" data-bs-target="#edit-comment-{{ $comment->id }}">
                                                            Edit
                                                        </button>
                                                    </li>
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

                            <p class="mb-2 text-secondary" style="font-size: 0.9rem;">
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
                            <div class="d-flex align-items-center gap-2">
                                @auth
                                    <button 
                                        type="button" 
                                        class="btn btn-sm btn-link text-decoration-none text-muted p-0 d-inline-flex align-items-center gap-1" 
                                        style="font-size: 0.8rem;" 
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
                                    <a href="{{ route('login') }}" class="text-muted text-decoration-none d-inline-flex align-items-center gap-1" style="font-size: 0.8rem;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M5.921 11.9 1.353 8.62a.72.72 0 0 1 0-1.238L5.921 4.1A.716.716 0 0 1 7 4.619V6c2.827.167 4.878 1.178 6.179 2.979-2.038-.112-4.341.529-6.179 2.13v1.172c0 .58-.654.949-1.079.619"/>
                                        </svg>
                                        <span>Reply</span>
                                    </a>
                                @endauth

                                @if($comment->replies && $comment->replies->count() > 0)
                                    <span class="text-muted" style="font-size: 0.75rem;">&bull;</span>
                                    <span class="text-muted" style="font-size: 0.8rem;">
                                        {{ $comment->replies->count() }} {{ \Illuminate\Support\Str::plural('reply', $comment->replies->count()) }}
                                    </span>
                                @endif
                            </div>

                            <!-- Reply Form (Collapsible) -->
                            @auth
                                <div class="collapse mt-2" id="reply-form-{{ $comment->id }}">
                                    <form action="{{ route('comments.store', $post->id) }}" method="POST">
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
                                <div class="mt-3 ps-3 border-start border-2 border-secondary-subtle d-flex flex-column gap-2">
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

                                            <p class="mb-1 text-secondary" style="font-size: 0.85rem;">
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
                                                        style="font-size: 0.75rem;" 
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
                                                    <a href="{{ route('login') }}" class="text-muted text-decoration-none d-inline-flex align-items-center gap-1" style="font-size: 0.75rem;">
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
                        <p class="text-muted small fst-italic mb-2">No comments yet. Be the first to start the discussion!</p>
                    @endforelse
                </div>
            </div>
        </div>
    </article>
</div>
@endsection

