<?php

namespace Database\Seeders;

use App\Models\Category;
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
        $Permissions['category'][] = Permission::create(['name' => 'category.companies', 'guard_name' => 'web'])->id;
        $Permissions['category'][] = Permission::create(['name' => 'category.departaments', 'guard_name' => 'web'])->id;

        $Permissions['subcategory'][] = Permission::create(['name' => 'subcategory.index', 'guard_name' => 'web'])->id;
        $Permissions['subcategory'][] = Permission::create(['name' => 'subcategory.create', 'guard_name' => 'web'])->id;
        $Permissions['subcategory'][] = Permission::create(['name' => 'subcategory.edit', 'guard_name' => 'web'])->id;
        $Permissions['subcategory'][] = Permission::create(['name' => 'subcategory.destroy', 'guard_name' => 'web'])->id;
        $Permissions['subcategory'][] = Permission::create(['name' => 'subcategory.restore', 'guard_name' => 'web'])->id;
        
        $Permissions['log'][] = Permission::create(['name' => 'log.index', 'guard_name' => 'web'])->id;
        $Permissions['log'][] = Permission::create(['name' => 'log.show', 'guard_name' => 'web'])->id;

        MenuCategory::factory()->navigation();
        MenuCategory::factory()->menus();
        MenuCategory::factory()->permissions();
        MenuCategory::factory()->admin();

        Menu::factory()->dashboard([]);
        Menu::factory()->menu_categories($Permissions['menu_category']);
        Menu::factory()->menus($Permissions['menu']);
        Menu::factory()->permissions($Permissions['permission']);
        Menu::factory()->roles($Permissions['role']);
        Menu::factory()->users($Permissions['user']);
        Menu::factory()->companies($Permissions['company']);
        Menu::factory()->departaments($Permissions['departament']);
        Menu::factory()->categories($Permissions['category']);
        Menu::factory()->subcategories($Permissions['subcategory']);
        Menu::factory()->logs($Permissions['log']);

        $RoleAdmin = Role::factory()->admin();
        $RoleUser = Role::factory()->user();
        $RoleTechnical = Role::factory()->technical($Permissions);

        $Company_1 = Company::create(['name' => 'MedMais - Centro']);
        $Company_2 = Company::create(['name' => 'MedMais - Benfica']);

        $Departament_1 = Departament::create(['name' => 'TI', 'company_id' => $Company_1->id]);
        $Departament_2 = Departament::create(['name' => 'Recepção', 'company_id' => $Company_1->id]);
        $Departament_3 = Departament::create(['name' => 'AcessaMed', 'company_id' => $Company_1->id]);
        $Departament_4 = Departament::create(['name' => 'TI', 'company_id' => $Company_2->id]);
        $Departament_5 = Departament::create(['name' => 'AcessaMed', 'company_id' => $Company_2->id]);

        $Category_1 = Category::create(['departament_id' => $Departament_1->id, 'name' => 'Impressora', 'automatic_response' => 'Resposta automática', 'resolution_time' => '02:00:00']);
        $Category_2 = Category::create(['departament_id' => $Departament_1->id, 'name' => 'Computador', 'automatic_response' => 'Resposta automática', 'resolution_time' => '02:00:00']);
        $Category_3 = Category::create(['departament_id' => $Departament_2->id, 'name' => 'TEF', 'automatic_response' => 'Resposta automática', 'resolution_time' => '02:00:00']);
        $Category_4 = Category::create(['departament_id' => $Departament_3->id, 'name' => 'Medicina do trabalho', 'automatic_response' => 'Resposta automática', 'resolution_time' => '02:00:00']);
        $Category_5 = Category::create(['departament_id' => $Departament_4->id, 'name' => 'Impressora', 'automatic_response' => 'Resposta automática', 'resolution_time' => '02:00:00']);
        $Category_6 = Category::create(['departament_id' => $Departament_4->id, 'name' => 'Computador', 'automatic_response' => 'Resposta automática', 'resolution_time' => '02:00:00']);

        User::factory()->admin(
            collect([$Company_1, $Company_2]),
            collect([$Departament_1, $Departament_2]),
            $RoleAdmin
        );

        User::factory()->technical(
            collect([$Company_1, $Company_2]),
            collect([$Departament_1, $Departament_2]),
            $RoleTechnical
        );
        
        User::factory()->user(
            collect([$Company_1]),
            collect([$Departament_1]),
            $RoleUser
        );
    }
}
