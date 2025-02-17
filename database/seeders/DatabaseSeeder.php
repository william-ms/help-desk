<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Company;
use App\Models\Departament;
use App\Models\Menu;
use App\Models\MenuCategory;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Subcategory;
use App\Models\Ticket;
use App\Models\TicketResponse;
use App\Models\User;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $Permissions = [];
        $PermissionsGroupByRoute = [
            'menu'              => ['index', 'create', 'edit', 'destroy', 'restore', 'order'],
            'menu_category'     => ['index', 'create', 'edit', 'destroy', 'restore', 'order'],
            'permission'        => ['index', 'create', 'edit', 'destroy'],
            'role'              => ['index', 'create', 'edit', 'destroy'],
            'user'              => ['index', 'create', 'edit', 'destroy', 'restore', 'permissions'],
            'company'           => ['index', 'create', 'edit', 'destroy', 'restore'],
            'departament'       => ['index', 'create', 'edit', 'destroy', 'restore'],
            'category'          => ['index', 'create', 'edit', 'destroy', 'restore', 'companies', 'departaments'],
            'subcategory'       => ['index', 'create', 'edit', 'destroy', 'restore', 'companies', 'departaments'],
            'ticket'            => ['index', 'create', 'edit', 'show'],
            'log'               => ['index', 'show'],
        ];

        foreach($PermissionsGroupByRoute as $Route => $permissions) {
            foreach($permissions as $Permission) {
                $Permissions[$Route][] = Permission::create(['name' => "{$Route}.{$Permission}", 'guard_name' => 'web'])->id;
            }
        }

        MenuCategory::factory()->navigation();
        MenuCategory::factory()->menus();
        MenuCategory::factory()->permissions();
        MenuCategory::factory()->admin();

        Menu::factory()->dashboard([]);
        Menu::factory()->tickets($Permissions['ticket']);
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
        $RoleUser = Role::factory()->user($Permissions);
        $RoleTechnical = Role::factory()->technical($Permissions);

        $Company_1 = Company::create(['name' => 'HelpDesk - Central']);
        $Company_2 = Company::create(['name' => 'HelpDesk - Polo']);

        $Departament_1 = Departament::create(['name' => 'TI']);
        $Departament_2 = Departament::create(['name' => 'Recepção']);

        $Category_1 = Category::create(['company_id' => $Company_1->id, 'departament_id' => $Departament_1->id, 'name' => 'Impressora', 'resolution_time' => '02:00:00']);
        $Category_2 = Category::create(['company_id' => $Company_1->id, 'departament_id' => $Departament_1->id, 'name' => 'Computador', 'resolution_time' => '02:00:00']);
        $Category_3 = Category::create(['company_id' => $Company_1->id, 'departament_id' => $Departament_2->id, 'name' => 'TEF', 'resolution_time' => '02:00:00']);
        $Category_5 = Category::create(['company_id' => $Company_2->id, 'departament_id' => $Departament_1->id, 'name' => 'Impressora', 'resolution_time' => '02:00:00']);
        $Category_6 = Category::create(['company_id' => $Company_2->id, 'departament_id' => $Departament_2->id, 'name' => 'Computador', 'resolution_time' => '02:00:00']);

        $Subcategory_1 = Subcategory::create(['category_id' => $Category_1->id, 'name' => 'Não liga']);
        $Subcategory_2 = Subcategory::create(['category_id' => $Category_1->id, 'name' => 'Não imprime']);
        $Subcategory_3 = Subcategory::create(['category_id' => $Category_5->id, 'name' => 'Não liga']);
        $Subcategory_4 = Subcategory::create(['category_id' => $Category_2->id, 'name' => 'Não liga']);
        $Subcategory_5 = Subcategory::create(['category_id' => $Category_2->id, 'name' => 'Sem internet']);
        $Subcategory_6 = Subcategory::create(['category_id' => $Category_6->id, 'name' => 'Não liga']);

        $Admin = User::factory()->admin(
            collect([$Company_1, $Company_2]),
            collect([$Departament_1, $Departament_2]),
            $RoleAdmin
        );

        $Technical = User::factory()->technical(
            collect([$Company_1, $Company_2]),
            collect([$Departament_1, $Departament_2]),
            $RoleTechnical
        );

        $User = User::factory()->user(
            collect([$Company_1]),
            collect([$Departament_1]),
            $RoleUser
        );

        $Ticket = Ticket::factory()->create([
            'uuid' => (string) Uuid::uuid4(),
            'company_id' => $Company_1->id,
            'departament_id' => $Departament_1->id,
            'category_id' => $Category_1->id,
            'subcategory_id' => $Subcategory_2->id,
            'requester_id' => $User->id,
            'assignee_id' => $Technical->id,
            'subject' => 'A impressora da recepção parou de imprimir',
            'status' => 1,
            'action' => 1,
        ]);

        TicketResponse::factory()->create([
            'ticket_id' => $Ticket->id,
            'user_id' => $User->id,
            'type' => 1,
            'response' => '<p>A impressora estava imprimindo normal antes do almo&ccedil;o, agora de tarde nenhum dos computadores est&aacute; conseguindo imprimir nela.</p>',
        ]);

        TicketResponse::factory()->create([
            'ticket_id' => $Ticket->id,
            'user_id' => $Technical->id,
            'type' => 1,
            'response' => "<p>Boa tarde. Primeiro, na barra de pesquisa do computador, digite 'impressoras', clica na op&ccedil;&atilde;o que aparecer, vai abrir uma lista das impressoras conectadas no computador. Confere se o nome da impressora est&aacute; nessa lista.</p>",
        ]);

        TicketResponse::factory()->create([
            'ticket_id' => $Ticket->id,
            'user_id' => $User->id,
            'type' => 1,
            'response' => '<p>Consegui abrir a lista, o nome dela est&aacute; aqui sim.</p>',
        ]);

        TicketResponse::factory()->create([
            'ticket_id' => $Ticket->id,
            'user_id' => $Technical->id,
            'type' => 1,
            'response' => '<p>Abre as op&ccedil;&otilde;es da impressora e limpa a fila de impress&atilde;o, depois reinicie o computador. Isso deve funcionar</p>',
        ]);
    }
}
