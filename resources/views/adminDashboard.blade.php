@extends('layouts.app')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center border-bottom pb-3 mb-4 gap-2">
    <div>
        <h1 class="h3 fw-bold mb-1">Admin Dashboard</h1>
        <p class="text-muted small mb-0">Manage all articles, drafts, and edits.</p>
    </div>
    <a href={{route('post.create')}} class="btn btn-primary btn-sm px-3">
        + Create Post
    </a>
</div>

<!-- Metrics Cards -->
<div class="row g-3 mb-4">
    <div class="col-sm-4">
        <div class="card border-0 shadow-sm p-3">
            <span class="text-muted small text-uppercase fw-semibold">Total Posts</span>
            <span class="h3 fw-bold mt-2 mb-0">{{ $totalPosts ?? 0 }}</span>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card border-0 shadow-sm p-3">
            <span class="text-muted small text-uppercase fw-semibold">Published</span>
            <span class="h3 fw-bold mt-2 mb-0 text-success">{{ $publishedCount ?? 0 }}</span>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card border-0 shadow-sm p-3">
            <span class="text-muted small text-uppercase fw-semibold">Drafts</span>
            <span class="h3 fw-bold mt-2 mb-0 text-warning">{{ $draftsCount ?? 0 }}</span>
        </div>
    </div>
</div>

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
                        <td class="ps-3 fw-semibold">{{ $item->title }}</td>
                        <td class="text-muted">{{ $item->category->name ?? 'Uncategorized' }}</td>
                        <td>
                            @if(($item->status ?? 'draft') === 'published')
                                <span class="badge bg-success-subtle text-success border border-success-subtle">Published</span>
                            @else
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle">Draft</span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ $item->created_at ? $item->created_at->format('Y-m-d') : 'N/A' }}</td>
                        <td class="pe-3 text-end">
                            <a href="{{ route('post.edit', $item->id) }}" class="btn btn-sm btn-outline-primary me-1">Edit</a>
                            
                            <form action="{{ route('post.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No posts found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection