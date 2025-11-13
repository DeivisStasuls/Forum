<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    // Show all threads
    public function index()
    {
        $threads = Thread::with('user', 'posts')->latest()->get();
        return response()->json($threads);
    }

    // Show one thread
    public function show(Thread $thread)
    {
        $thread->load('user', 'posts');
        return response()->json($thread);
    }

    // Create new thread
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $thread = $request->user()->threads()->create($validated);

        return response()->json($thread, 201);
    }

    // Update thread
    public function update(Request $request, Thread $thread)
    {
        $this->authorize('update', $thread); // optional if using policies

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'body' => 'sometimes|string',
        ]);

        $thread->update($validated);

        return response()->json($thread);
    }

    // Delete thread
    public function destroy(Thread $thread)
    {
        $this->authorize('delete', $thread); // optional
        $thread->delete();

        return response()->json(['message' => 'Thread deleted']);
    }
}
