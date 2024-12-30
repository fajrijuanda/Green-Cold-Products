<?php

namespace App\Listeners;

use App\Events\ModelActivityLogged;
use App\Models\UserActivity;
use Illuminate\Support\Facades\Auth;

class LogModelActivity
{
    /**
     * Handle the event.
     */
    public function handle(ModelActivityLogged $event): void
    {
        UserActivity::create([
            'user_id' => Auth::check() ? Auth::id() : null,
            'activity' => "{$event->action} {$event->model->getTable()}",
            'type' => $event->action,
            'description' => $event->description,
            'activity_date' => now(),
        ]);
    }
}
