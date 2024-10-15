<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\MenuCategory::factory()->navigation();
        \App\Models\MenuCategory::factory()->menus();
        \App\Models\MenuCategory::factory()->permissions();
        \App\Models\MenuCategory::factory()->admin();

        \App\Models\Menu::factory()->dashboard();
        \App\Models\Menu::factory()->menu_categories();
        \App\Models\Menu::factory()->menus();
        \App\Models\Menu::factory()->permissions();
        \App\Models\Menu::factory()->roles();
        \App\Models\Menu::factory()->users();
        \App\Models\Menu::factory()->companies();
        \App\Models\Menu::factory()->departaments();
        \App\Models\Menu::factory()->logs();

        \Spatie\Permission\Models\Permission::create(['name' => 'menu.index', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'menu.create', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'menu.edit', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'menu.destroy', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'menu.restore', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'menu.order', 'guard_name' => 'web']);

        \Spatie\Permission\Models\Permission::create(['name' => 'menu_category.index', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'menu_category.create', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'menu_category.edit', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'menu_category.destroy', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'menu_category.restore', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'menu_category.order', 'guard_name' => 'web']);

        \Spatie\Permission\Models\Permission::create(['name' => 'permission.index', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'permission.create', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'permission.edit', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'permission.destroy', 'guard_name' => 'web']);

        \Spatie\Permission\Models\Permission::create(['name' => 'role.index', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'role.create', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'role.edit', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'role.destroy', 'guard_name' => 'web']);

        \Spatie\Permission\Models\Permission::create(['name' => 'user.index', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'user.create', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'user.edit', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'user.destroy', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'user.restore', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'user.permissions', 'guard_name' => 'web']);

        \Spatie\Permission\Models\Permission::create(['name' => 'company.index', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'company.create', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'company.edit', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'company.destroy', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'company.restore', 'guard_name' => 'web']);

        \Spatie\Permission\Models\Permission::create(['name' => 'departament.index', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'departament.create', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'departament.edit', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'departament.destroy', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'departament.restore', 'guard_name' => 'web']);

        \Spatie\Permission\Models\Permission::create(['name' => 'log.index', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'log.show', 'guard_name' => 'web']);

        $RoleAdmin = \Spatie\Permission\Models\Role::create(['name' => 'Administrador', 'guard_name' => 'web']); 
        $RoleUser = \Spatie\Permission\Models\Role::create(['name' => 'Usuário', 'guard_name' => 'web']); 

        $Admin = \App\Models\User::factory()->admin();
        $Admin->assignRole($RoleAdmin->id);
        register_log($Admin, 'update', 200, [
            'role' => ['value' => "Atribuiu o usuário à função de <b>{$RoleAdmin->name}</b>"]
        ]);

        $User = \App\Models\User::factory()->user();
        $User->assignRole($RoleUser->id);
        register_log($User, 'update', 200, [
            'role' => ['value' => "Atribuiu o usuário à função de <b>{$RoleUser->name}</b>"]
        ]);
    }
}
