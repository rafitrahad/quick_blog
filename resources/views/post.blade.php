@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card shadow-sm border-0 p-4">
            <h2 class="h4 fw-bold mb-4">Create New Post</h2>

            <form action="{{ route('post.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Post Title -->
                <div class="mb-3">
                    <label for="title" class="form-label fw-semibold">Title</label>
                    <input 
                        type="text" 
                        id="title" 
                        name="title" 
                        value="{{ old('title') }}" 
                        placeholder="Enter post title" 
                        required 
                        class="form-control @error('title') is-invalid @enderror"
                    >
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Short Description / Summary -->
                <div class="mb-3">
                    <label for="description" class="form-label fw-semibold">Short Description / Excerpt</label>
                    <textarea 
                        id="description" 
                        name="description" 
                        rows="2" 
                        placeholder="A short summary of what this article is about..." 
                        required 
                        class="form-control @error('description') is-invalid @enderror"
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Quotation / Key Quote -->
                <div class="mb-3">
                    <label for="quote" class="form-label fw-semibold">Featured Quote / Quotation <span class="text-muted fw-normal">(Optional)</span></label>
                    <input 
                        type="text" 
                        id="quote" 
                        name="quote" 
                        value="{{ old('quote') }}" 
                        placeholder="e.g. 'Code is like humor. When you have to explain it, it’s bad.'" 
                        class="form-control @error('quote') is-invalid @enderror"
                    >
                    @error('quote')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row mb-3">
                    <!-- Category -->
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label for="category" class="form-label fw-semibold">Category</label>
                        <select id="category" name="category" class="form-select @error('category') is-invalid @enderror">
                            <option value="Technology" {{ old('category') == 'Technology' ? 'selected' : '' }}>Technology</option>
                            <option value="Programming" {{ old('category') == 'Programming' ? 'selected' : '' }}>Programming</option>
                            <option value="Lifestyle" {{ old('category') == 'Lifestyle' ? 'selected' : '' }}>Lifestyle</option>
                            <option value="Design" {{ old('category') == 'Design' ? 'selected' : '' }}>Design</option>
                            <option value="News" {{ old('category') == 'News' ? 'selected' : '' }}>News</option>
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="col-md-6">
                        <label for="status" class="form-label fw-semibold">Status</label>
                        <select id="status" name="status" class="form-select">
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Publish Immediately</option>
                        </select>
                    </div>
                </div>

                <!-- Tags Input -->
                <div class="mb-3">
                    <label for="tags" class="form-label fw-semibold">Tags <span class="text-muted fw-normal">(Comma separated)</span></label>
                    <input 
                        type="text" 
                        id="tags" 
                        name="tags" 
                        value="{{ old('tags') }}" 
                        placeholder="laravel, php, web development, bootstrap" 
                        class="form-control @error('tags') is-invalid @enderror"
                    >
                    @error('tags')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Media Upload (Image or Video) -->
                <div class="mb-3">
                    <label for="media" class="form-label fw-semibold">Upload Image or Video</label>
                    <input 
                        type="file" 
                        id="media" 
                        name="media" 
                        accept="image/*,video/mp4,video/quicktime,video/avi" 
                        class="form-control @error('media') is-invalid @enderror"
                    >
                    <div class="form-text">Supported formats: JPG, PNG, WEBP, MP4, MOV (Max 20MB).</div>
                    @error('media')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Main Content Body -->
                <div class="mb-4">
                    <label for="content" class="form-label fw-semibold">Article Content</label>
                    <textarea 
                        id="content" 
                        name="content" 
                        rows="8" 
                        required 
                        placeholder="Write your article body here..." 
                        class="form-control @error('content') is-invalid @enderror"
                    >{{ old('content') }}</textarea>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="d-flex justify-content-end gap-2 border-top pt-3">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4 fw-semibold">Save Post</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection