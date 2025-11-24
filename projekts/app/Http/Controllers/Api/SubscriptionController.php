<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SubscriptionController extends Controller
{
    /**
     * Subscribe the authenticated user to the given thread.
     */
    public function store(Request $request, Thread $thread): JsonResponse
    {
        $thread->subscribers()->attach($request->user()->id);

        return response()->json(['message' => 'Subscribed successfully.'], 200);
    }

    /**
     * Unsubscribe the authenticated user from the given thread.
     */
    public function destroy(Request $request, Thread $thread): JsonResponse
    {
        $thread->subscribers()->detach($request->user()->id);

        return response()->json(['message' => 'Unsubscribed successfully.'], 200);
    }
}