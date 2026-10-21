<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentHistory;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'body'      => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $parentId = $validated['parent_id'] ?? null;

        // If parent_id is supplied, verify it belongs to this post
        if ($parentId) {
            $parentComment = Comment::where('id', $parentId)
                ->where('post_id', $post->id)
                ->firstOrFail();
            $parentId = $parentComment->id;
        }

        $post->comments()->create([
            'user_id'   => Auth::id(),
            'parent_id' => $parentId,
            'body'      => $validated['body'],
        ]);

        $message = $parentId ? 'Reply posted successfully!' : 'Comment posted successfully!';

        return back()->with('success', $message);
    }

    public function update(Request $request, Comment $comment)
    {
        // Edit is strictly only for the user who made the comment
        if (Auth::id() !== $comment->user_id) {
            abort(403, 'Unauthorized. You can only edit your own comments.');
        }

        $validated = $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        // If text was modified, save the previous version into the comment_histories table
        if ($comment->body !== $validated['body']) {
            CommentHistory::create([
                'comment_id' => $comment->id,
                'user_id'    => Auth::id(),
                'old_body'   => $comment->body,
            ]);

            $comment->body      = $validated['body'];
            $comment->edited_at = now();
            $comment->save();
        }

        return back()->with('success', 'Comment edited successfully!');
    }

    public function destroy(Comment $comment)
    {
        // Author or Admin can delete
        if (Auth::id() !== $comment->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized. You can only delete your own comments.');
        }

        // Soft delete: removed for everyone publicly, stays in database for security
        $comment->delete();

        return back()->with('success', 'Comment deleted successfully.');
    }

    public function hide(Comment $comment)
    {
        $userId = Auth::id();
        $hiddenBy = $comment->hidden_by ?? [];

        // For other users: hides the comment from their personal view only
        if (!in_array($userId, $hiddenBy)) {
            $hiddenBy[] = $userId;
            $comment->hidden_by = $hiddenBy;
            $comment->save();
        }

        return back()->with('success', 'Comment removed from your view.');
    }
}
