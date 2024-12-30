<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserActivityLogged
{
    use Dispatchable, SerializesModels;

    public $user;
    public $action;
    public $description;

    /**
     * Create a new event instance.
     */
    public function __construct($user, $action, $description)
    {
        $this->user = $user;
        $this->action = $action;
        $this->description = $description;
    }
}
