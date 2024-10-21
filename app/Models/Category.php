<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    public string $type = "categoria";

    public $fields = [
        'departament_id' => 'departamento',
        'name' => 'nome',
        'automatic_response' => 'resposta automática',
        'resolution_time' => 'tempo de resolução',
    ];

    protected $fillable = [
        'departament_id',
        'name',
        'automatic_response',
        'resolution_time',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function departament() 
    {
        return $this->belongsTo(Departament::class);
    }

    public function log()
    {
        return $this->hasOne(Log::class, 'model_id', 'id')->where('model_type', $this->type);
    }

    public function log_values()
    {
        $data = [];
        $data['automatic_response'] = '';
        $changes = $this->getChanges();

        if(!empty($changes) && !array_key_exists('deleted_at', $changes)) {
            
            $originals = $this->getOriginal();

            if(!empty($changes['departament_id'])) {
                $Departament = Departament::find($originals['departament_id']);
                $data['departament_id'] = ['value' => "Alterou o departamento de <b>{$Departament->name}</b> para <b>{$this->departament->name}</b>"];
            }
        } else {
            $data['departament_id'] = ['value' => "Departamento <b>{$this->departament->name}</b>"];  
        }

        return $data;
    }
}
