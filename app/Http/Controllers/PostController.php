<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{



    public function index(Request $request)
    {
        // Eager-load author and comments with comment authors
        $query = Post::with(['author', 'comments.user'])->latest();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $posts = $query->paginate(6)->withQueryString();

        return view('index', compact('posts'));
    }

    public function show(string $slug)
    {
        $post = Post::with(['author', 'comments.user'])
            ->where('slug', $slug)
            ->orWhere('id', $slug)
            ->firstOrFail();

        return view('posts.show', compact('post'));
    }

    // Show form to create a new post
    public function create()
    {
        return view('post');
    }

    // Store new post
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:500'],
            'content'     => ['required', 'string'],
            'quote'       => ['nullable', 'string', 'max:500'],
            'category'    => ['required', 'string', 'max:100'],
            'tags'        => ['nullable', 'string'], // Comma-separated: "tech, laravel, coding"
            'media'       => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,mp4,mov,avi', 'max:20480'], // max 20MB
            'status'      => ['required', 'in:draft,published'],
        ]);

        // 1. Process Tags (split string by comma and trim)
        $tagsArray = null;
        if (!empty($validated['tags'])) {
            $tagsArray = array_map('trim', explode(',', $validated['tags']));
        }

        // 2. Handle Image or Video Upload
        $mediaPath = null;
        $mediaType = 'none';

        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $mimeType = $file->getMimeType();

            if (str_starts_with($mimeType, 'image/')) {
                $mediaType = 'image';
                $mediaPath = $file->store('posts/images', 'public');
            } elseif (str_starts_with($mimeType, 'video/')) {
                $mediaType = 'video';
                $mediaPath = $file->store('posts/videos', 'public');
            }
        }

        // 3. Generate unique slug
        $baseSlug = Str::slug($validated['title']);
        $slug = $baseSlug;
        $counter = 1;
        while (Post::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        // 4. Save to Database
        Post::create([
            'user_id'      => Auth::id(),
            'author_name'  => Auth::user()->name,
            'title'        => $validated['title'],
            'slug'         => $slug,
            'description'  => $validated['description'],
            'content'      => $validated['content'],
            'quote'        => $validated['quote'] ?? null,
            'category'     => $validated['category'],
            'tags'         => $tagsArray,
            'media_path'   => $mediaPath,
            'media_type'   => $mediaType,
            'status'       => $validated['status'],
            'published_at' => $validated['status'] === 'published' ? now() : null,
        ]);

        return redirect()->route('admin.dashboard')->with('status', 'Post created successfully!');
    }
}
