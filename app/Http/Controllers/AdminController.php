<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $currentTab    = $request->query('tab', 'posts'); // 'posts', 'ads', 'users'
        $currentFilter = $request->query('status'); // 'published', 'draft', 'trash', or null (all)
        $search        = $request->query('search');

        // If viewing the Recycle Bin, only query soft-deleted posts
        if ($currentFilter === 'trash') {
            $query = Post::onlyTrashed()->latest();
        } else {
            $query = Post::latest();
            if ($currentFilter && in_array($currentFilter, ['published', 'draft'])) {
                $query->where('status', $currentFilter);
            }
        }

        // Search by title, content, or category
        if ($search && $currentTab === 'posts') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $posts          = $query->paginate(10)->withQueryString();
        $totalPosts     = Post::count();
        $publishedCount = Post::where('status', 'published')->count();
        $draftsCount    = Post::where('status', 'draft')->count();
        $trashCount     = Post::onlyTrashed()->count();

        // Users Query
        $usersQuery = User::latest();
        if ($search && $currentTab === 'users') {
            $usersQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('role', 'like', "%{$search}%");
            });
        }
        $users      = $usersQuery->paginate(10, ['*'], 'users_page')->withQueryString();
        $totalUsers = User::count();
        $adminCount = User::where('role', 'admin')->count();

        return view('adminDashboard', compact(
            'posts',
            'totalPosts',
            'publishedCount',
            'draftsCount',
            'trashCount',
            'currentFilter',
            'search',
            'currentTab',
            'users',
            'totalUsers',
            'adminCount'
        ));
    }

    public function restore($id)
    {
        $post = Post::onlyTrashed()->findOrFail($id);
        $post->restore();

        return back()->with('status', "Post '{$post->title}' restored successfully!");
    }

    public function forceDelete($id)
    {
        $post = Post::onlyTrashed()->findOrFail($id);

        // Delete uploaded media file if it exists
        if ($post->media_path && Storage::disk('public')->exists($post->media_path)) {
            Storage::disk('public')->delete($post->media_path);
        }

        $title = $post->title;
        $post->forceDelete();

        return back()->with('status', "Post '{$title}' permanently deleted!");
    }

    public function emptyTrash()
    {
        $trashedPosts = Post::onlyTrashed()->get();

        foreach ($trashedPosts as $post) {
            if ($post->media_path && Storage::disk('public')->exists($post->media_path)) {
                Storage::disk('public')->delete($post->media_path);
            }
            $post->forceDelete();
        }

        return back()->with('status', 'Recycle bin emptied successfully!');
    }
}
