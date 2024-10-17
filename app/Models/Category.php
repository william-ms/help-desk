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
        'company_id' => 'empresa',
        'name' => 'nome',
        'automatic_response' => 'resposta automática',
        'resolution_time' => 'tempo de resolução',
    ];

    protected $fillable = [
        'company_id',
        'name',
        'automatic_response',
        'resolution_time',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function company() 
    {
        return $this->belongsTo(Company::class);
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

            if(!empty($changes['company_id'])) {
                $Company = Company::find($originals['company_id']);
                $data['company_id'] = ['value' => "Alterou a empresa de <b>{$Company->name}</b> para <b>{$this->company->name}</b>"];
            }
        } else {
            $data['company_id'] = ['value' => "Empresa <b>{$this->company->name}</b>"];  
        }

        return $data;
    }
}
