<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\UserActivityLogged;
use App\Listeners\LogUserActivity;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UserActivityLogged::class => [
            LogUserActivity::class,
        ],
    ];

    public function boot(): void
    {
        parent::boot();
    }
}
