<?php
// ... (omitted previous auth routes)

use App\Http\Controllers\Api\ThreadController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;

// --- Protected Routes (Requires Sanctum Token) ---
Route::middleware(['auth:sanctum'])->group(function () {

    // Auth Routes
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('/user', function (Request $request) { return $request->user(); });

    // Forum Routes
    Route::apiResource('threads', ThreadController::class);

    // Posts/Replies (Nested Resource)
    Route::post('threads/{thread}/posts', [PostController::class, 'store']);
    Route::put('posts/{post}', [PostController::class, 'update']);
    Route::delete('posts/{post}', [PostController::class, 'destroy']);

    // Subscriptions
    Route::post('threads/{thread}/subscribe', [SubscriptionController::class, 'store']);
    Route::delete('threads/{thread}/subscribe', [SubscriptionController::class, 'destroy']);
});