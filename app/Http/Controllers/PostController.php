<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index(Request $request)
    {
        // Only show published articles on the public feed
        $query = Post::where('status', 'published')
            ->with([
                'author',
                'comments' => fn($q) => $q->whereNull('parent_id')->with(['user', 'replies.user'])->latest(),
            ])
            ->withCount('comments')
            ->latest();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $posts = $query->paginate(6)->withQueryString();

        // Trending / Most Viewed posts for sidebar (Top 5 most trending)
        $trendingPosts = Post::where('status', 'published')
            ->orderByDesc('views')
            ->latest()
            ->take(5)
            ->get();

        return view('index', compact('posts', 'trendingPosts'));
    }

    public function show($post)
    {
        $postModel = $post instanceof Post
            ? $post->load([
                'author',
                'comments' => fn($q) => $q->whereNull('parent_id')->with(['user', 'replies.user'])->latest(),
            ])->loadCount('comments')
            : Post::with([
                'author',
                'comments' => fn($q) => $q->whereNull('parent_id')->with(['user', 'replies.user'])->latest(),
            ])
            ->withCount('comments')
            ->where(function ($q) use ($post) {
                $q->where('slug', $post);
                if (is_numeric($post)) {
                    $q->orWhere('id', $post);
                }
            })
            ->firstOrFail();

        // If the post is a draft, only logged-in admins can view it
        if ($postModel->status === 'draft') {
            /** @var \App\Models\User|null $user */
            $user = Auth::user();
            if (!$user || !$user->isAdmin()) {
                abort(404);
            }
        } else {
            // Increment view count for published posts (counted once per user session)
            $viewedKey = 'viewed_post_' . $postModel->id;
            if (!session()->has($viewedKey)) {
                $postModel->increment('views');
                session()->put($viewedKey, true);
            }
        }

        return view('posts.show', ['post' => $postModel]);
    }

    public function create()
    {
        return view('post');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:500'],
            'content'     => ['required', 'string'],
            'quote'       => ['nullable', 'string', 'max:500'],
            'category'    => ['required', 'string', 'max:100'],
            'tags'        => ['nullable', 'string'],
            'media'       => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,mp4,mov,avi', 'max:90480'],
            'status'      => ['required', 'in:draft,published'],
        ]);

        $tagsArray = !empty($validated['tags'])
            ? array_map('trim', explode(',', $validated['tags']))
            : null;

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

        $baseSlug = Str::slug($validated['title']);
        $slug = $baseSlug;
        $counter = 1;
        while (Post::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        /** @var \App\Models\User|null $currentUser */
        $currentUser = Auth::user();

        Post::create([
            'user_id'      => Auth::id(),
            'author_name'  => $currentUser ? $currentUser->name : 'Admin',
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

    public function edit(Post $post)
    {
        return view('post', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:500'],
            'content'     => ['required', 'string'],
            'quote'       => ['nullable', 'string', 'max:500'],
            'category'    => ['required', 'string', 'max:100'],
            'tags'        => ['nullable', 'string'],
            'media'       => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,mp4,mov,avi', 'max:90480'],
            'status'      => ['required', 'in:draft,published'],
        ]);

        $tagsArray = !empty($validated['tags'])
            ? array_map('trim', explode(',', $validated['tags']))
            : null;

        // Update media if a new file is uploaded
        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $mimeType = $file->getMimeType();

            if (str_starts_with($mimeType, 'image/')) {
                $post->media_type = 'image';
                $post->media_path = $file->store('posts/images', 'public');
            } elseif (str_starts_with($mimeType, 'video/')) {
                $post->media_type = 'video';
                $post->media_path = $file->store('posts/videos', 'public');
            }
        }

        // Update slug if title was modified
        if ($post->title !== $validated['title']) {
            $baseSlug = Str::slug($validated['title']);
            $slug = $baseSlug;
            $counter = 1;
            while (Post::where('slug', $slug)->where('id', '!=', $post->id)->exists()) {
                $slug = "{$baseSlug}-{$counter}";
                $counter++;
            }
            $post->slug = $slug;
        }

        // Set published_at timestamp if published for the first time
        if ($validated['status'] === 'published' && !$post->published_at) {
            $post->published_at = now();
        }

        $post->title       = $validated['title'];
        $post->description = $validated['description'];
        $post->content     = $validated['content'];
        $post->quote       = $validated['quote'] ?? null;
        $post->category    = $validated['category'];
        $post->tags        = $tagsArray;
        $post->status      = $validated['status'];
        $post->save();

        return redirect()->route('admin.dashboard')->with('status', 'Post updated successfully!');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('admin.dashboard')->with('status', 'Post deleted successfully!');
    }
}
