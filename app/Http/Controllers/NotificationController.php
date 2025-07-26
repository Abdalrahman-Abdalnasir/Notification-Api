<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Notifications\CustomNotification;

class NotificationController extends Controller
{
    public function send(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string|max:255',
        ]);

        // Cache user for 10 minutes
        $user = Cache::remember("user_{$validated['user_id']}", 600, function () use ($validated) {
            return User::findOrFail($validated['user_id']);
        });

        // Send notification
        $user->notify(new CustomNotification($validated['message']));

        return response()->json([
            'message' => 'Notification sent'
        ]);
    }
}
