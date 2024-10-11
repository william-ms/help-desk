<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;

class LogObserver
{
    public function created(Model $Model)
    {
        register_log($Model, "create", 201, $Model->log_values());
    }

    public function updated(Model $Model)
    {
        $type = (array_key_exists('deleted_at', $Model->getDirty())) ? 'restore' : 'update';

        register_log($Model, $type, 200, $Model->log_values());
    }

    public function deleted(Model $Model)
    {
        register_log($Model, "delete", 200, $Model->log_values());
    }
}
