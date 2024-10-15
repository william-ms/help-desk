<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MenuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
        ];
    }

    public function dashboard()
    {
        return $this->create([
            'menu_category_id' => 1,
            'name' => 'Dashboard',
            'icon' => 'ph-duotone ph-house-simple',
            'route' => 'dashboard',
            'order' => 1
        ]);
    }

    public function menu_categories()
    {
        return $this->create([
            'menu_category_id' => 2,
            'name' => 'Categorias de menu',
            'icon' => 'ph-duotone ph-list-bullets',
            'route' => 'menu_category',
            'order' => 1
        ]);
    }

    public function menus()
    {
        return $this->create([
            'menu_category_id' => 2,
            'name' => 'Menus',
            'icon' => 'ph-duotone ph-list-bullets',
            'route' => 'menu',
            'order' => 2
        ]);
    }

    public function permissions()
    {
        return $this->create([
            'menu_category_id' => 3,
            'name' => 'Permissões',
            'icon' => 'ph-duotone ph-notepad',
            'route' => 'permission',
            'order' => 1
        ]);
    }

    public function roles()
    {
        return $this->create([
            'menu_category_id' => 3,
            'name' => 'Funções',
            'icon' => 'ph-duotone ph-notepad',
            'route' => 'role',
            'order' => 2
        ]);
    }

    public function users()
    {
        return $this->create([
            'menu_category_id' => 4,
            'name' => 'Usuários',
            'icon' => 'ph-duotone ph-users-three',
            'route' => 'user',
            'order' => 1
        ]);
    }

    public function companies()
    {
        return $this->create([
            'menu_category_id' => 4,
            'name' => 'Empresas',
            'icon' => 'ph-duotone ph-buildings',
            'route' => 'company',
            'order' => 2
        ]);
    }

    public function departaments()
    {
        return $this->create([
            'menu_category_id' => 4,
            'name' => 'Departamentos',
            'icon' => 'ph-duotone ph-archive',
            'route' => 'departament',
            'order' => 3
        ]);
    }

    public function logs()
    {
        return $this->create([
            'menu_category_id' => 4,
            'name' => 'Logs',
            'icon' => 'ph-duotone ph-notepad',
            'route' => 'log',
            'order' => 4
        ]);
    }
}
