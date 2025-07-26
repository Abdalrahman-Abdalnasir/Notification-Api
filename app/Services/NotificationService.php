<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\GeneralNotification;
use Illuminate\Support\Facades\Cache;

class NotificationService
{
    public function sendNotification(int $userId, string $message)
    {
        $user = $this->getUserWithCache($userId);

        return $user->notify(new GeneralNotification($message));
    }

    protected function getUserWithCache(int $userId): User
    {
        return Cache::remember(
            "user.{$userId}",
            now()->addMinutes(10),
            fn() => User::findOrFail($userId)
        );
    }
}
