<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;

class LogObserver
{
    public function created(Model $Model)
    {
        create_log_item($Model, "create", 201, $Model->log_values());
    }

    public function updated(Model $Model)
    {
        $type = (array_key_exists('deleted_at', $Model->getDirty())) ? 'restore' : 'update';

        create_log_item($Model, $type, 200, $Model->log_values());
    }

    public function deleted(Model $Model)
    {
        create_log_item($Model, "delete", 200, $Model->log_values());
    }
}
