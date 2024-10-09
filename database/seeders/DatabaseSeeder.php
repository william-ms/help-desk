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
        \App\Models\Menu::factory()->logs();

        \Spatie\Permission\Models\Permission::create(['name' => 'menu.index', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'menu.create', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'menu.edit', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'menu.destroy', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'menu.order', 'guard_name' => 'web']);

        \Spatie\Permission\Models\Permission::create(['name' => 'menu_category.index', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'menu_category.create', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'menu_category.edit', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'menu_category.destroy', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'menu_category.order', 'guard_name' => 'web']);

        \Spatie\Permission\Models\Permission::create(['name' => 'permission.index', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'permission.create', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'permission.edit', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'permission.destroy', 'guard_name' => 'web']);

        \Spatie\Permission\Models\Permission::create(['name' => 'user.index', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'user.create', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'user.edit', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'user.destroy', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'user.seedeleted', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::create(['name' => 'user.restore', 'guard_name' => 'web']);

        \App\Models\User::factory()->admin();
    }
}
