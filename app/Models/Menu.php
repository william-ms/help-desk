<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Permission;

class Menu extends Model
{
    use HasFactory, SoftDeletes;

    public string $type = "menu";

    public $fields = [
        'name' => 'nome',
        'icon' => 'ícone',
        'menu_category' => 'categoria de menu',
        'route' => 'prefixo da rota',
        'order' => 'ordem',
        'permissions' => 'permissões'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'menu_category_id',
        'name',
        'icon',
        'route',
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

    public function menu_category()
    {
        return $this->belongsTo(MenuCategory::class);
    }

    public function log()
    {
        return $this->hasOne(Log::class, 'model_id', 'id')->where('model_type', $this->type);
    }

    public function log_values() {

        $data = [];
        $changes = $this->getChanges();

        if(!empty($changes) && !array_key_exists('deleted_at', $changes)) {

            $originals = $this->getOriginal();

            if(!empty($changes['icon'])) {
                $data['icon'] = ['value' => "Alterou ícone de <i class='{$originals['icon']}'></i> para <i class='{$this->icon}'></i>"];
            }

            if(!empty($changes['menu_category_id'])) {

                $MenuCategory = MenuCategory::find($originals['menu_category_id']);

                $data['menu_category'] = ['value' => "Alterou categoria de menu de <b>{$MenuCategory->name}</b> para <b>{$this->menu_category->name}</b>"];
                $data['menu_category_id'] = '';
            }
        } else {

            if($this->wasRecentlyCreated) {
                $menu_permissions = [
                    $this->route . '.index',
                    $this->route . '.show',
                    $this->route . '.create',
                    $this->route . '.edit',
                    $this->route . '.destroy',
                    $this->route . '.restore',
                ];

                $permissions = Permission::whereIn('name', $menu_permissions)->get()->pluck('name')->toArray();
            }

            $data = [
                'icon' => ['value' => "Ícone <i class='{$this->icon}'></i>"],
                'menu_category' => ['value' => "Categoria de menu <b>{$this->menu_category->name}</b>"],
                'menu_category_id' => '',
            ];

            if(!empty($permissions)) {
                $data['permissions'] = ['values' => $permissions, 'title' => 'Com as permissões'];
            }
        }

        return $data;
    }
}
