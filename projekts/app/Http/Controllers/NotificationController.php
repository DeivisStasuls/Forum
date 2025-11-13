<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // Parāda visus lietotāja paziņojumus
    public function index(Request $request)
    {
        $notifications = $request->user()->notifications()->latest()->get();
        return response()->json($notifications);
    }

    // Parāda konkrētu paziņojumu
    public function show(Notification $notification)
    {
        $this->authorize('view', $notification);
        return response()->json($notification);
    }

    // Atzīmē kā nolasītu
    public function update(Notification $notification)
    {
        $this->authorize('update', $notification);
        $notification->update(['read_at' => now()]);

        return response()->json(['message' => 'Notification marked as read']);
    }

    // Dzēš paziņojumu
    public function destroy(Notification $notification)
    {
        $this->authorize('delete', $notification);
        $notification->delete();

        return response()->json(['message' => 'Notification deleted']);
    }
}
