<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Thread;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    /**
     * Store a new reply (Post) in the specified thread.
     */
    public function store(Request $request, Thread $thread): JsonResponse
    {
        $data = $request->validate([
            'body' => 'required|string',
        ]);

        $post = $thread->posts()->create([
            'user_id' => $request->user()->id,
            'body' => $data['body'],
        ]);

        // Note: You would add notification logic here for thread subscribers

        return response()->json(['post' => $post->load('user:id,name')], 201);
    }

    /**
     * Update the specified post.
     */
    public function update(Request $request, Post $post): JsonResponse
    {
        // Authorization: Only the post owner can update
        if ($request->user()->id !== $post->user_id) {
            return response()->json(['message' => 'Unauthorized action.'], 403);
        }

        $data = $request->validate([
            'body' => 'required|string',
        ]);

        $post->update($data);

        return response()->json(['post' => $post]);
    }

    /**
     * Remove the specified post.
     */
    public function destroy(Request $request, Post $post): JsonResponse
    {
        // Authorization: Only the post owner can delete (or perhaps a moderator/thread owner)
        // For now, restrict to post owner:
        if ($request->user()->id !== $post->user_id) {
            return response()->json(['message' => 'Unauthorized action.'], 403);
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully.'], 200);
    }
}