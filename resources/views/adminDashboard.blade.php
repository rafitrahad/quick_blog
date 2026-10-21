@extends('layouts.app')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center border-bottom pb-3 mb-4 gap-2">
    <div>
        <h1 class="h3 fw-bold mb-1">Admin Dashboard</h1>
        <p class="text-muted small mb-0">Manage all articles, drafts, and edits.</p>
    </div>
    <a href="{{ route('admin.posts.create') }}" class="btn btn-primary btn-sm px-3">
        + Create Post
    </a>
</div>

<!-- Metrics Cards / Quick Filters -->
<div class="row g-3 mb-4">
    <!-- Total Posts -->
    <div class="col-sm-4">
        <a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-dark">
            <div class="card border-0 shadow-sm p-3 h-100 {{ empty($currentFilter) ? 'border-start border-4 border-primary bg-white' : 'bg-light' }}">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted small text-uppercase fw-semibold">Total Posts</span>
                    @if(empty($currentFilter))
                        <span class="badge bg-primary-subtle text-primary small">All</span>
                    @endif
                </div>
                <span class="h3 fw-bold mt-2 mb-0">{{ $totalPosts ?? 0 }}</span>
            </div>
        </a>
    </div>

    <!-- Published -->
    <div class="col-sm-4">
        <a href="{{ route('admin.dashboard', ['status' => 'published']) }}" class="text-decoration-none text-dark">
            <div class="card border-0 shadow-sm p-3 h-100 {{ $currentFilter === 'published' ? 'border-start border-4 border-success bg-white' : 'bg-light' }}">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted small text-uppercase fw-semibold">Published</span>
                    @if($currentFilter === 'published')
                        <span class="badge bg-success-subtle text-success small">Filtered</span>
                    @endif
                </div>
                <span class="h3 fw-bold mt-2 mb-0 text-success">{{ $publishedCount ?? 0 }}</span>
            </div>
        </a>
    </div>

    <!-- Drafts -->
    <div class="col-sm-4">
        <a href="{{ route('admin.dashboard', ['status' => 'draft']) }}" class="text-decoration-none text-dark">
            <div class="card border-0 shadow-sm p-3 h-100 {{ $currentFilter === 'draft' ? 'border-start border-4 border-warning bg-white' : 'bg-light' }}">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted small text-uppercase fw-semibold">Drafts</span>
                    @if($currentFilter === 'draft')
                        <span class="badge bg-warning-subtle text-warning small">Filtered</span>
                    @endif
                </div>
                <span class="h3 fw-bold mt-2 mb-0 text-warning">{{ $draftsCount ?? 0 }}</span>
            </div>
        </a>
    </div>
</div>

<!-- Active Filter Indicator -->
@if(!empty($currentFilter))
    <div class="d-flex justify-content-between align-items-center mb-3">
        <span class="text-muted small">
            Showing only <strong>{{ ucfirst($currentFilter) }}</strong> posts ({{ $posts->total() }})
        </span>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm py-0">
            Clear Filter (Show All)
        </a>
    </div>
@endif

<!-- Posts Table -->
<div class="card border-0 shadow-sm overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th scope="col" class="py-3 ps-3">Title</th>
                    <th scope="col" class="py-3">Category</th>
                    <th scope="col" class="py-3">Status</th>
                    <th scope="col" class="py-3">Created</th>
                    <th scope="col" class="py-3 pe-3 text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($posts ?? [] as $item)
                    <tr>
                        <td class="ps-3 fw-semibold">
                            <a href="{{ route('posts.show', $item->slug ?? $item->id) }}" class="text-dark text-decoration-none" target="_blank">
                                {{ $item->title }}
                            </a>
                        </td>
                        <td class="text-muted">{{ $item->category ?? 'General' }}</td>
                        <td>
                            @if(($item->status ?? 'draft') === 'published')
                                <span class="badge bg-success-subtle text-success border border-success-subtle">Published</span>
                            @else
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle">Draft</span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ $item->created_at ? $item->created_at->format('Y-m-d') : 'N/A' }}</td>
                        <td class="pe-3 text-end">
                            <a href="{{ route('admin.posts.edit', $item->id) }}" class="btn btn-sm btn-outline-primary me-1">Edit</a>
                            
                            <form action="{{ route('admin.posts.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            No {{ $currentFilter ?? '' }} posts found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($posts, 'links'))
        <div class="d-flex justify-content-center p-3 border-top">
            {{ $posts->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection

