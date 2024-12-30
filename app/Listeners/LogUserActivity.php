<?php

namespace App\Listeners;

use App\Events\UserActivityLogged;
use App\Models\UserActivity;

class LogUserActivity
{
    /**
     * Handle the event.
     */
    public function handle(UserActivityLogged $event): void
    {
        // Pastikan aktivitas hanya dicatat sekali
        if (!UserActivity::where([
            ['user_id', '=', $event->user->id],
            ['activity', '=', $event->action],
            ['description', '=', $event->description],
        ])->exists()) {
            UserActivity::create([
                'user_id' => $event->user->id,
                'activity' => $event->action,
                'type' => $event->action,
                'description' => $event->description,
                'activity_date' => now(),
            ]);
        }
    }
}
