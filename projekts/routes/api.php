<?php
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Auth\RegisteredUserController;

Route::post('/register', [RegisteredUserController::class, 'store']);
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('threads', ThreadController::class);
    Route::apiResource('posts', PostController::class);
    Route::get('notifications', [NotificationController::class, 'index']);
    Route::patch('notifications/{notification}', [NotificationController::class, 'update']);
    Route::delete('notifications/{notification}', [NotificationController::class, 'destroy']);

    Route::get('subscriptions', [SubscriptionController::class, 'index']);
    Route::post('threads/{thread}/subscribe', [SubscriptionController::class, 'store']);
    Route::delete('threads/{thread}/unsubscribe', [SubscriptionController::class, 'destroy']);
});
