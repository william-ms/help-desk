<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subcategory extends Model
{
    use HasFactory, SoftDeletes;

    public string $type = "subcategoria";

    public $fields = [
        'category_id' => 'categoria',
        'name' => 'nome',
        'automatic_response' => 'resposta automática',
    ];

    protected $fillable = [
        'category_id',
        'name',
        'automatic_response',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function category() 
    {
        return $this->belongsTo(Category::class);
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

            if(!empty($changes['category_id'])) {
                $Category = Category::find($originals['category_id']);
                $data['category_id'] = ['value' => "Alterou a categoria de <b>{$Category->name}</b> para <b>{$this->category->name}</b>"];
            }
        } else {
            $data['category_id'] = ['value' => "Categoria <b>{$this->category->name}</b>"];  
        }

        return $data;
    }
}
