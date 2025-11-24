<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class ThreadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        // Paginate threads and load related user and category data
        $threads = Thread::with(['user', 'category'])
            ->latest()
            ->paginate(20);

        return response()->json($threads);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $thread = Thread::create([
            'user_id' => $request->user()->id,
            'category_id' => $data['category_id'],
            'title' => $data['title'],
            'body' => $data['body'],
            'slug' => Str::slug($data['title']),
        ]);

        return response()->json(['thread' => $thread], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Thread $thread): JsonResponse
    {
        // Load the thread with its posts, and the user for both
        $thread->load([
            'user:id,name',
            'category:id,title,slug',
            'posts.user:id,name'
        ]);

        return response()->json($thread);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Thread $thread): JsonResponse
    {
        // Authorization: Only the thread owner can update
        if ($request->user()->id !== $thread->user_id) {
            return response()->json(['message' => 'Unauthorized action.'], 403);
        }

        $data = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'body' => 'sometimes|required|string',
            'category_id' => 'sometimes|required|exists:categories,id',
        ]);

        // Update slug if title is changed
        if (isset($data['title'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $thread->update($data);

        return response()->json(['thread' => $thread]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Thread $thread): JsonResponse
    {
        // Authorization: Only the thread owner can delete
        if ($request->user()->id !== $thread->user_id) {
            return response()->json(['message' => 'Unauthorized action.'], 403);
        }

        $thread->delete();

        return response()->json(['message' => 'Thread deleted successfully.'], 200);
    }
}