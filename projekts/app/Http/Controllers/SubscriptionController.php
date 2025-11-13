<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    // Parāda visus lietotāja abonementus
    public function index(Request $request)
    {
        $subscriptions = $request->user()->subscriptions()->with('user')->get();
        return response()->json($subscriptions);
    }

    // Pierakstās uz thread
    public function store(Request $request, Thread $thread)
    {
        $request->user()->subscriptions()->attach($thread->id);
        return response()->json(['message' => 'Subscribed to thread']);
    }

    // Atceļ pierakstu
    public function destroy(Request $request, Thread $thread)
    {
        $request->user()->subscriptions()->detach($thread->id);
        return response()->json(['message' => 'Unsubscribed from thread']);
    }
}
