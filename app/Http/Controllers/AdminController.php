<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $currentFilter = $request->query('status'); // 'published', 'draft', or null (all)

        $query = Post::latest();
        if ($currentFilter && in_array($currentFilter, ['published', 'draft'])) {
            $query->where('status', $currentFilter);
        }

        $posts = $query->paginate(10)->withQueryString();
        $totalPosts = Post::count();
        $publishedCount = Post::where('status', 'published')->count();
        $draftsCount = Post::where('status', 'draft')->count();

        return view('adminDashboard', compact('posts', 'totalPosts', 'publishedCount', 'draftsCount', 'currentFilter'));
    }
}
