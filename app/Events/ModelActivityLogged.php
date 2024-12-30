<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ModelActivityLogged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $model;
    public $action;
    public $description;

    /**
     * Create a new event instance.
     */
    public function __construct($model, $action, $description)
    {
        $this->model = $model;
        $this->action = $action;
        $this->description = $description;
    }
}
