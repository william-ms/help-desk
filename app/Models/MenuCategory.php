<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuCategory extends Model
{
    use HasFactory, SoftDeletes;

    public string $type = "categoria de menu";

    public $fields = [
        'name' => 'nome',
        'order' => 'ordem',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'order'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    public function log()
    {
        return $this->hasOne(Log::class, 'model_id', 'id')->where('model_type', $this->type);
    }

    public function log_values() {
        return [];
    }
}
