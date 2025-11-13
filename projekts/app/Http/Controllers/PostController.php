<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // Parāda visus postus
    public function index()
    {
        $posts = Post::with(['user', 'thread'])->latest()->get();
        return response()->json($posts);
    }

    // Parāda konkrētu postu
    public function show(Post $post)
    {
        $post->load(['user', 'thread']);
        return response()->json($post);
    }

    // Izveido jaunu postu
    public function store(Request $request)
    {
        $validated = $request->validate([
            'thread_id' => 'required|exists:threads,id',
            'body' => 'required|string',
        ]);

        $post = $request->user()->posts()->create($validated);

        return response()->json($post, 201);
    }

    // Atjaunina postu
    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post); // ja izmanto policy

        $validated = $request->validate([
            'body' => 'sometimes|string',
        ]);

        $post->update($validated);

        return response()->json($post);
    }

    // Dzēš postu
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        $post->delete();

        return response()->json(['message' => 'Post deleted']);
    }
}
