<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasFactory;

    public string $type = "função";

    public $fields = [
        'name' => 'nome',
        'guard_name' => 'nome do guarda',
    ];

    
    public function log()
    {
        return $this->hasOne(Log::class, 'model_id', 'id')->where('model_type', $this->type);
    }

    public function log_values() {
        return [];
    }
}
