@extends('layouts.app')

@section('content')
<div class="row g-4">
    <!-- Left Sidebar: Admin Navigation -->
    <div class="col-12 col-md-3 col-xl-2">
        <div class="card border-0 shadow-sm p-3 bg-white" style="position: sticky; top: 76px;">
            <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                <div class="bg-primary text-white rounded p-1 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                    <i class="bi bi-speedometer2 small"></i>
                </div>
                <span class="fw-bold text-dark small text-uppercase">Admin Menu</span>
            </div>

            <!-- Navigation Buttons -->
            <div class="nav flex-column nav-pills gap-1">
                <!-- Posts Button -->
                <a href="{{ route('admin.dashboard', ['tab' => 'posts']) }}" 
                   class="nav-link d-flex align-items-center justify-content-between py-2 px-3 rounded {{ ($currentTab ?? 'posts') === 'posts' ? 'active bg-primary text-white fw-semibold' : 'text-dark bg-light' }}">
                    <span class="d-flex align-items-center gap-2">
                        <i class="bi bi-file-earmark-text"></i>
                        <span>Posts</span>
                    </span>
                    <span class="badge {{ ($currentTab ?? 'posts') === 'posts' ? 'bg-white text-primary' : 'bg-secondary-subtle text-secondary' }}">
                        {{ $totalPosts ?? 0 }}
                    </span>
                </a>

                <!-- Ads Button -->
                <a href="{{ route('admin.dashboard', ['tab' => 'ads']) }}" 
                   class="nav-link d-flex align-items-center justify-content-between py-2 px-3 rounded {{ ($currentTab ?? 'posts') === 'ads' ? 'active bg-primary text-white fw-semibold' : 'text-dark bg-light' }}">
                    <span class="d-flex align-items-center gap-2">
                        <i class="bi bi-badge-ad"></i>
                        <span>Billboard for Ads</span>
                    </span>
                </a>

                <!-- Users Button -->
                <a href="{{ route('admin.dashboard', ['tab' => 'users']) }}" 
                   class="nav-link d-flex align-items-center justify-content-between py-2 px-3 rounded {{ ($currentTab ?? 'posts') === 'users' ? 'active bg-primary text-white fw-semibold' : 'text-dark bg-light' }}">
                    <span class="d-flex align-items-center gap-2">
                        <i class="bi bi-people-fill"></i>
                        <span>Users</span>
                    </span>
                    <span class="badge {{ ($currentTab ?? 'posts') === 'users' ? 'bg-white text-primary' : 'bg-secondary-subtle text-secondary' }}">
                        {{ $totalUsers ?? 0 }}
                    </span>
                </a>
            </div>

            <hr class="my-3 text-muted">

            <!-- Quick Action Links -->
            <div class="d-flex flex-column gap-2">
                <a href="{{ route('admin.posts.create') }}" class="btn btn-primary btn-sm d-flex align-items-center justify-content-center gap-1 shadow-sm">
                    <i class="bi bi-plus-lg"></i>
                    <span>+ New Post</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Right Column: Main Content Area -->
    <div class="col-12 col-md-9 col-xl-10">
        
        {{-- ========================================================================= --}}
        {{-- TAB 1: POSTS MANAGEMENT --}}
        {{-- ========================================================================= --}}
        @if(($currentTab ?? 'posts') === 'posts')
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center border-bottom pb-3 mb-4 gap-2">
                <div>
                    <h1 class="h3 fw-bold mb-1">Posts Management</h1>
                    <p class="text-muted small mb-0">Manage all articles, drafts, recycle bin, and view analytics.</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.posts.create') }}" class="btn btn-primary btn-sm px-3">
                        + Create Post
                    </a>
                </div>
            </div>

            <!-- Metrics Cards / Quick Filters -->
            <div class="row g-3 mb-4">
                <!-- Total Posts -->
                <div class="col-6 col-md-3">
                    <a href="{{ route('admin.dashboard', ['tab' => 'posts']) }}" class="text-decoration-none text-dark">
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
                <div class="col-6 col-md-3">
                    <a href="{{ route('admin.dashboard', ['tab' => 'posts', 'status' => 'published']) }}" class="text-decoration-none text-dark">
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
                <div class="col-6 col-md-3">
                    <a href="{{ route('admin.dashboard', ['tab' => 'posts', 'status' => 'draft']) }}" class="text-decoration-none text-dark">
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

                <!-- Recycle Bin -->
                <div class="col-6 col-md-3">
                    <a href="{{ route('admin.dashboard', ['tab' => 'posts', 'status' => 'trash']) }}" class="text-decoration-none text-dark">
                        <div class="card border-0 shadow-sm p-3 h-100 {{ $currentFilter === 'trash' ? 'border-start border-4 border-danger bg-white' : 'bg-light' }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted small text-uppercase fw-semibold d-flex align-items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                        <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                                    </svg>
                                    Recycle Bin
                                </span>
                                @if($currentFilter === 'trash')
                                    <span class="badge bg-danger-subtle text-danger small">Active</span>
                                @endif
                            </div>
                            <span class="h3 fw-bold mt-2 mb-0 text-danger">{{ $trashCount ?? 0 }}</span>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Search Bar & Actions Toolbar -->
            <div class="card border-0 shadow-sm p-3 mb-4 bg-white">
                <form action="{{ route('admin.dashboard') }}" method="GET" class="row g-2 align-items-center">
                    <input type="hidden" name="tab" value="posts">
                    @if(!empty($currentFilter))
                        <input type="hidden" name="status" value="{{ $currentFilter }}">
                    @endif
                    <div class="col-md-9 col-sm-8">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                                </svg>
                            </span>
                            <input 
                                type="text" 
                                name="search" 
                                value="{{ $search ?? '' }}" 
                                class="form-control border-start-0 ps-0" 
                                placeholder="Search posts by title, category, or content..."
                            >
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-4 d-flex gap-2">
                        <button type="submit" class="btn btn-dark w-100 btn-sm">Search</button>
                        @if(!empty($search) || !empty($currentFilter))
                            <a href="{{ route('admin.dashboard', ['tab' => 'posts']) }}" class="btn btn-outline-secondary btn-sm" title="Clear search and filter">Reset</a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Active Filter / Recycle Bin Banner -->
            @if($currentFilter === 'trash')
                <div class="alert alert-danger d-flex justify-content-between align-items-center mb-3 py-2">
                    <span class="small">
                        <i class="bi bi-trash me-1"></i> You are viewing the <strong>Recycle Bin</strong> ({{ $trashCount }} deleted {{ \Illuminate\Support\Str::plural('post', $trashCount) }}). You can recover or permanently delete them.
                    </span>
                    @if(($trashCount ?? 0) > 0)
                        <form action="{{ route('admin.posts.emptyTrash') }}" method="POST" onsubmit="return confirm('Empty the entire Recycle Bin? All deleted posts will be permanently lost!');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm py-0">
                                Empty Recycle Bin
                            </button>
                        </form>
                    @endif
                </div>
            @endif

            <!-- Posts Table -->
            <div class="card border-0 shadow-sm overflow-hidden bg-white">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-muted small text-uppercase">
                            <tr>
                                <th scope="col" class="ps-3">Title</th>
                                <th scope="col">Author</th>
                                <th scope="col">Category</th>
                                <th scope="col">Status</th>
                                <th scope="col">Views</th>
                                <th scope="col">Date</th>
                                <th scope="col" class="text-end pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($posts ?? [] as $item)
                                <tr class="{{ $currentFilter === 'trash' ? 'table-danger bg-opacity-25' : '' }}">
                                    <td class="ps-3">
                                        <div class="fw-semibold text-dark text-truncate" style="max-width: 250px;">
                                            @if($currentFilter === 'trash')
                                                {{ $item->title }}
                                            @else
                                                <a href="{{ route('posts.show', $item->slug ?? $item->id) }}" class="text-dark text-decoration-none">
                                                    {{ $item->title }}
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-muted small">
                                        {{ $item->author->name ?? $item->author_name ?? 'Admin' }}
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-secondary border">
                                            {{ $item->category ?? 'General' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($currentFilter === 'trash')
                                            <span class="badge bg-danger">Deleted</span>
                                        @elseif($item->status === 'published')
                                            <span class="badge bg-success-subtle text-success">Published</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning">Draft</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info-subtle text-dark border border-info-subtle d-inline-flex align-items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                                                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                                            </svg>
                                            {{ number_format($item->views ?? 0) }}
                                        </span>
                                    </td>
                                    <td class="text-muted small">
                                        {{ $item->created_at ? $item->created_at->format('M d, Y') : 'N/A' }}
                                    </td>
                                    <td class="text-end pe-3">
                                        @if($currentFilter === 'trash')
                                            <!-- Restore Post from Recycle Bin -->
                                            <form action="{{ route('admin.posts.restore', $item->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-outline-success me-1 d-inline-flex align-items-center gap-1" title="Recover post from Recycle Bin">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2z"/>
                                                        <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466"/>
                                                    </svg>
                                                    Restore
                                                </button>
                                            </form>

                                            <!-- Permanently Force Delete Post -->
                                            <form action="{{ route('admin.posts.forceDelete', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to permanently delete this post? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Permanently Delete">
                                                    Delete Forever
                                                </button>
                                            </form>
                                        @else
                                            <!-- Normal Edit & Soft Delete Actions -->
                                            <a href="{{ route('admin.posts.edit', $item->id) }}" class="btn btn-sm btn-outline-primary me-1">Edit</a>
                                            
                                            <form action="{{ route('admin.posts.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Move this post to the Recycle Bin? (You can restore it later)');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        @if($currentFilter === 'trash')
                                            Recycle Bin is empty. No deleted posts.
                                        @elseif(!empty($search))
                                            No posts found matching "{{ e($search) }}".
                                        @else
                                            No {{ $currentFilter ?? '' }} posts found.
                                        @endif
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

        {{-- ========================================================================= --}}
        {{-- TAB 2: ADS (Billboard for Ads Placeholder) --}}
        {{-- ========================================================================= --}}
        @elseif($currentTab === 'ads')
            <div class="border-bottom pb-3 mb-4">
                <h1 class="h3 fw-bold mb-1">Billboard for Ads</h1>
                <p class="text-muted small mb-0">Space reserved for future advertisements.</p>
            </div>

            <div class="card border-0 shadow-sm p-5 text-center bg-white" style="border-radius: 12px; border: 2px dashed #cbd5e1 !important;">
                <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mx-auto mb-3" style="width: 64px; height: 64px;">
                    <i class="bi bi-badge-ad text-muted fs-2"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">Billboard for Ads</h5>
                <p class="text-muted small mb-0">
                    Space reserved for advertisements. Currently no active ads are configured.
                </p>
            </div>

        {{-- ========================================================================= --}}
        {{-- TAB 3: USERS MANAGEMENT --}}
        {{-- ========================================================================= --}}
        @elseif($currentTab === 'users')
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center border-bottom pb-3 mb-4 gap-2">
                <div>
                    <h1 class="h3 fw-bold mb-1">User Management</h1>
                    <p class="text-muted small mb-0">View registered users, administrative roles, and accounts.</p>
                </div>
            </div>

            <!-- Users Metrics -->
            <div class="row g-3 mb-4">
                <div class="col-sm-4">
                    <div class="card border-0 shadow-sm p-3 h-100 border-start border-4 border-primary bg-white">
                        <span class="text-muted small text-uppercase fw-semibold">Total Registered Users</span>
                        <span class="h3 fw-bold mt-2 mb-0">{{ $totalUsers ?? 0 }}</span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card border-0 shadow-sm p-3 h-100 border-start border-4 border-danger bg-white">
                        <span class="text-muted small text-uppercase fw-semibold">Administrators</span>
                        <span class="h3 fw-bold mt-2 mb-0 text-danger">{{ $adminCount ?? 0 }}</span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card border-0 shadow-sm p-3 h-100 border-start border-4 border-secondary bg-white">
                        <span class="text-muted small text-uppercase fw-semibold">Regular Members</span>
                        <span class="h3 fw-bold mt-2 mb-0 text-secondary">{{ max(0, ($totalUsers ?? 0) - ($adminCount ?? 0)) }}</span>
                    </div>
                </div>
            </div>

            <!-- Users Search Toolbar -->
            <div class="card border-0 shadow-sm p-3 mb-4 bg-white">
                <form action="{{ route('admin.dashboard') }}" method="GET" class="row g-2 align-items-center">
                    <input type="hidden" name="tab" value="users">
                    <div class="col-md-9 col-sm-8">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted">
                                <i class="bi bi-search"></i>
                            </span>
                            <input 
                                type="text" 
                                name="search" 
                                value="{{ $search ?? '' }}" 
                                class="form-control border-start-0 ps-0" 
                                placeholder="Search users by name, email, or role..."
                            >
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-4 d-flex gap-2">
                        <button type="submit" class="btn btn-dark w-100 btn-sm">Search</button>
                        @if(!empty($search))
                            <a href="{{ route('admin.dashboard', ['tab' => 'users']) }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Users Table -->
            <div class="card border-0 shadow-sm overflow-hidden bg-white">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-muted small text-uppercase">
                            <tr>
                                <th scope="col" class="ps-3"># ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Role</th>
                                <th scope="col">Registered Date</th>
                                <th scope="col" class="text-end pe-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users ?? [] as $user)
                                <tr>
                                    <td class="ps-3 text-muted small">#{{ $user->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="bg-secondary bg-opacity-10 text-secondary rounded-circle d-flex align-items-center justify-content-center fw-bold small" style="width: 32px; height: 32px;">
                                                {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                            </div>
                                            <div class="fw-semibold text-dark">{{ $user->name }}</div>
                                        </div>
                                    </td>
                                    <td class="text-muted small">{{ $user->email }}</td>
                                    <td>
                                        @if(($user->role ?? null) === 'admin')
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                                <i class="bi bi-shield-fill-check me-1"></i> Admin
                                            </span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">
                                                User
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-muted small">
                                        {{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}
                                    </td>
                                    <td class="text-end pe-3">
                                        <span class="badge bg-success-subtle text-success small">
                                            Active
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        No users found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(method_exists($users, 'links'))
                    <div class="d-flex justify-content-center p-3 border-top">
                        {{ $users->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        @endif

    </div>
</div>
@endsection
