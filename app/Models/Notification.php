<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public string $type = "notification";

    public $fields = [
        'notified_id' => 'notificado',
        'notifier_id' => 'notificante',
        'status' => 'status',
        'model_type' => 'tipo do modelo',
        'model_id' => 'id do modelo',
        'message' => 'mensagem',
        'icon' => 'ícone',
    ];

    protected $fillable = [
        'notified_id',
        'notifier_id',
        'status',
        'model_type',
        'model_id',
        'message',
        'icon',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function notified() 
    {
        return $this->belongsTo(User::class, 'notified_id');
    }

    public function notifier() 
    {
        return $this->belongsTo(User::class, 'notifier_id');
    }

    public function model()
    {
        return $this->morphTo();
    }
}
