<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Departament extends Model
{
    use HasFactory, SoftDeletes;

    public string $type = "departamento";

    public $fields = [
        'company_id' => 'empresa',
        'name' => 'nome',
    ];

    protected $fillable = [
        'company_id',
        'name',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function users() {
        return $this->belongsToMany(User::class, 'user_departament');
    }

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
