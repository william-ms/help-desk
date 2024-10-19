<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Departament;
use App\Models\Menu;
use App\Models\MenuCategory;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
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
        MenuCategory::factory()->navigation();
        MenuCategory::factory()->menus();
        MenuCategory::factory()->permissions();
        MenuCategory::factory()->admin();

        $dashboard = Menu::factory()->dashboard();
        $menu_category = Menu::factory()->menu_categories();
        $menu = Menu::factory()->menus();
        $permission = Menu::factory()->permissions();
        $role = Menu::factory()->roles();
        $user = Menu::factory()->users();
        $company = Menu::factory()->companies();
        $departament = Menu::factory()->departaments();
        $category = Menu::factory()->categories();
        $subcategory = Menu::factory()->subcategories();
        $log = Menu::factory()->logs();

        $Permissions['menu'][] = Permission::create(['name' => 'menu.index', 'guard_name' => 'web'])->id;
        $Permissions['menu'][] = Permission::create(['name' => 'menu.create', 'guard_name' => 'web'])->id;
        $Permissions['menu'][] = Permission::create(['name' => 'menu.edit', 'guard_name' => 'web'])->id;
        $Permissions['menu'][] = Permission::create(['name' => 'menu.destroy', 'guard_name' => 'web'])->id;
        $Permissions['menu'][] = Permission::create(['name' => 'menu.restore', 'guard_name' => 'web'])->id;
        $Permissions['menu'][] = Permission::create(['name' => 'menu.order', 'guard_name' => 'web'])->id;

        $Permissions['menu_category'][] = Permission::create(['name' => 'menu_category.index', 'guard_name' => 'web'])->id;
        $Permissions['menu_category'][] = Permission::create(['name' => 'menu_category.create', 'guard_name' => 'web'])->id;
        $Permissions['menu_category'][] = Permission::create(['name' => 'menu_category.edit', 'guard_name' => 'web'])->id;
        $Permissions['menu_category'][] = Permission::create(['name' => 'menu_category.destroy', 'guard_name' => 'web'])->id;
        $Permissions['menu_category'][] = Permission::create(['name' => 'menu_category.restore', 'guard_name' => 'web'])->id;
        $Permissions['menu_category'][] = Permission::create(['name' => 'menu_category.order', 'guard_name' => 'web'])->id;

        $Permissions['permission'][] = Permission::create(['name' => 'permission.index', 'guard_name' => 'web'])->id;
        $Permissions['permission'][] = Permission::create(['name' => 'permission.create', 'guard_name' => 'web'])->id;
        $Permissions['permission'][] = Permission::create(['name' => 'permission.edit', 'guard_name' => 'web'])->id;
        $Permissions['permission'][] = Permission::create(['name' => 'permission.destroy', 'guard_name' => 'web'])->id;

        $Permissions['role'][] = Permission::create(['name' => 'role.index', 'guard_name' => 'web'])->id;
        $Permissions['role'][] = Permission::create(['name' => 'role.create', 'guard_name' => 'web'])->id;
        $Permissions['role'][] = Permission::create(['name' => 'role.edit', 'guard_name' => 'web'])->id;
        $Permissions['role'][] = Permission::create(['name' => 'role.destroy', 'guard_name' => 'web'])->id;

        $Permissions['user'][] = Permission::create(['name' => 'user.index', 'guard_name' => 'web'])->id;
        $Permissions['user'][] = Permission::create(['name' => 'user.create', 'guard_name' => 'web'])->id;
        $Permissions['user'][] = Permission::create(['name' => 'user.edit', 'guard_name' => 'web'])->id;
        $Permissions['user'][] = Permission::create(['name' => 'user.destroy', 'guard_name' => 'web'])->id;
        $Permissions['user'][] = Permission::create(['name' => 'user.restore', 'guard_name' => 'web'])->id;
        $Permissions['user'][] = Permission::create(['name' => 'user.permissions', 'guard_name' => 'web'])->id;

        $Permissions['company'][] = Permission::create(['name' => 'company.index', 'guard_name' => 'web'])->id;
        $Permissions['company'][] = Permission::create(['name' => 'company.create', 'guard_name' => 'web'])->id;
        $Permissions['company'][] = Permission::create(['name' => 'company.edit', 'guard_name' => 'web'])->id;
        $Permissions['company'][] = Permission::create(['name' => 'company.destroy', 'guard_name' => 'web'])->id;
        $Permissions['company'][] = Permission::create(['name' => 'company.restore', 'guard_name' => 'web'])->id;

        $Permissions['departament'][] = Permission::create(['name' => 'departament.index', 'guard_name' => 'web'])->id;
        $Permissions['departament'][] = Permission::create(['name' => 'departament.create', 'guard_name' => 'web'])->id;
        $Permissions['departament'][] = Permission::create(['name' => 'departament.edit', 'guard_name' => 'web'])->id;
        $Permissions['departament'][] = Permission::create(['name' => 'departament.destroy', 'guard_name' => 'web'])->id;
        $Permissions['departament'][] = Permission::create(['name' => 'departament.restore', 'guard_name' => 'web'])->id;

        $Permissions['category'][] = Permission::create(['name' => 'category.index', 'guard_name' => 'web'])->id;
        $Permissions['category'][] = Permission::create(['name' => 'category.create', 'guard_name' => 'web'])->id;
        $Permissions['category'][] = Permission::create(['name' => 'category.edit', 'guard_name' => 'web'])->id;
        $Permissions['category'][] = Permission::create(['name' => 'category.destroy', 'guard_name' => 'web'])->id;
        $Permissions['category'][] = Permission::create(['name' => 'category.restore', 'guard_name' => 'web'])->id;

        $Permissions['subcategory'][] = Permission::create(['name' => 'subcategory.index', 'guard_name' => 'web'])->id;
        $Permissions['subcategory'][] = Permission::create(['name' => 'subcategory.create', 'guard_name' => 'web'])->id;
        $Permissions['subcategory'][] = Permission::create(['name' => 'subcategory.edit', 'guard_name' => 'web'])->id;
        $Permissions['subcategory'][] = Permission::create(['name' => 'subcategory.destroy', 'guard_name' => 'web'])->id;
        $Permissions['subcategory'][] = Permission::create(['name' => 'subcategory.restore', 'guard_name' => 'web'])->id;

        $Permissions['log'][] = Permission::create(['name' => 'log.index', 'guard_name' => 'web'])->id;
        $Permissions['log'][] = Permission::create(['name' => 'log.show', 'guard_name' => 'web'])->id;

        foreach($Permissions as $Menu => $Group) {
            $$Menu->permissions()->sync($Group);
        }

        $RoleAdmin = Role::create(['name' => 'Administrador', 'guard_name' => 'web']); 
        $RoleUser = Role::create(['name' => 'Usuário', 'guard_name' => 'web']); 

        $Company_1 = Company::create(['name' => 'MedMais - Centro']);
        $Company_2 = Company::create(['name' => 'MedMais - Benfica']);

        $Departament_1 = Departament::create(['name' => 'TI']);
        $Departament_2 = Departament::create(['name' => 'Recepção']);

        $Admin = User::factory()->admin();
        $Admin->companies()->sync([$Company_1->id, $Company_2->id]);
        $Admin->departaments()->sync([$Departament_1->id, $Departament_2->id]);
        $Admin->assignRole($RoleAdmin->id);
        $log_admin_data['companies'] = ['values' => [$Company_1->name, $Company_2->name],'title' => "Atribuiu o usuário as <b>empresas</b>"];
        $log_admin_data['departaments'] = ['values' => [$Departament_1->name, $Departament_2->name],'title' => "Atribuiu o usuário aos <b>departamentos</b>"];
        $log_admin_data['role'] = ['value' => "Atribuiu o usuário à função de <b>{$RoleAdmin->name}</b>"];
        register_log($Admin, 'update', 200, $log_admin_data);

        $User = User::factory()->user();
        $User->companies()->sync([$Company_1->id]);
        $User->departaments()->sync([$Departament_1->id]);
        $User->assignRole($RoleUser->id);
        $log_user_data['companies'] = ['values' => [$Company_1->name],'title' => "Atribuiu o usuário as <b>empresas</b>"];
        $log_user_data['departaments'] = ['values' => [$Departament_1->name],'title' => "Atribuiu o usuário aos <b>departamentos</b>"];
        $log_user_data['role'] = ['value' => "Atribuiu o usuário à função de <b>{$RoleUser->name}</b>"];
        register_log($User, 'update', 200, $log_user_data);
    }
}
