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

    public function dashboard($Permissions)
    {
        $Menu = $this->create([
            'menu_category_id' => 1,
            'name' => 'Dashboard',
            'icon' => 'ph-duotone ph-house-simple',
            'route' => 'dashboard',
            'order' => 1
        ]);

        $Menu->permissions()->sync($Permissions);

        return $Menu;
    }

    public function menu_categories($Permissions)
    {
        $Menu = $this->create([
            'menu_category_id' => 2,
            'name' => 'Categorias de menu',
            'icon' => 'ph-duotone ph-list-bullets',
            'route' => 'menu_category',
            'order' => 1
        ]);

        $Menu->permissions()->sync($Permissions);

        return $Menu;
    }

    public function menus($Permissions)
    {
        $Menu = $this->create([
            'menu_category_id' => 2,
            'name' => 'Menus',
            'icon' => 'ph-duotone ph-list-bullets',
            'route' => 'menu',
            'order' => 2
        ]);

        $Menu->permissions()->sync($Permissions);

        return $Menu;
    }

    public function permissions($Permissions)
    {
        $Menu = $this->create([
            'menu_category_id' => 3,
            'name' => 'Permissões',
            'icon' => 'ph-duotone ph-notepad',
            'route' => 'permission',
            'order' => 1
        ]);

        $Menu->permissions()->sync($Permissions);

        return $Menu;
    }

    public function roles($Permissions)
    {
        $Menu = $this->create([
            'menu_category_id' => 3,
            'name' => 'Funções',
            'icon' => 'ph-duotone ph-notepad',
            'route' => 'role',
            'order' => 2
        ]);

        $Menu->permissions()->sync($Permissions);

        return $Menu;
    }

    public function users($Permissions)
    {
        $Menu = $this->create([
            'menu_category_id' => 4,
            'name' => 'Usuários',
            'icon' => 'ph-duotone ph-users-three',
            'route' => 'user',
            'order' => 1
        ]);

        $Menu->permissions()->sync($Permissions);

        return $Menu;
    }

    public function companies($Permissions)
    {
        $Menu = $this->create([
            'menu_category_id' => 4,
            'name' => 'Empresas',
            'icon' => 'ph-duotone ph-buildings',
            'route' => 'company',
            'order' => 2
        ]);

        $Menu->permissions()->sync($Permissions);

        return $Menu;
    }

    public function departaments($Permissions)
    {
        $Menu = $this->create([
            'menu_category_id' => 4,
            'name' => 'Departamentos',
            'icon' => 'ph-duotone ph-archive',
            'route' => 'departament',
            'order' => 3
        ]);

        $Menu->permissions()->sync($Permissions);

        return $Menu;
    }

    public function categories($Permissions)
    {
        $Menu = $this->create([
            'menu_category_id' => 4,
            'name' => 'Categorias',
            'icon' => 'ph-duotone ph-list',
            'route' => 'category',
            'order' => 4
        ]);

        $Menu->permissions()->sync($Permissions);

        return $Menu;
    }

    public function subcategories($Permissions)
    {
        $Menu = $this->create([
            'menu_category_id' => 4,
            'name' => 'Subcategorias',
            'icon' => 'ph-duotone ph-list',
            'route' => 'subcategory',
            'order' => 5
        ]);

        $Menu->permissions()->sync($Permissions);

        return $Menu;
    }

    public function logs($Permissions)
    {
        $Menu = $this->create([
            'menu_category_id' => 4,
            'name' => 'Logs',
            'icon' => 'ph-duotone ph-notepad',
            'route' => 'log',
            'order' => 6
        ]);

        $Menu->permissions()->sync($Permissions);

        return $Menu;
    }
}
