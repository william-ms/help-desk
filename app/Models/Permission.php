<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use HasFactory;

    public string $type = "permissão";

    public $fields = [
        'name' => 'nome',
        'guard_name' => 'nome do guarda',
    ];

    public function menus()
    {
        return $this->belongsToMany(Menu::class);
    }

    public function log()
    {
        return $this->hasOne(Log::class, 'model_id', 'id')->where('model_type', $this->type);
    }

    public function log_values()
    {
        return [];
    }
}
