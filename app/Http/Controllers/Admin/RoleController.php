<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Menu;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $Roles = Role::query()
        ->when($request->name, function($query) use ($request) {
            $query->where('name', 'LIKE', "%{$request->name}%");
        })
        ->get();

        $data_filter = [
            [
                'type' => 'text',
                'label' => 'Função',
                'input_name' => 'name',
                'placeholder' => 'Informe o nome da função'
            ],
        ];

        $data_breadcrumbs = [
            [
                'name' => 'Funções',
            ],
        ];

        return view('admin.role.index', [
            'Roles' =>  $Roles,
            'data_filter' => $data_filter,
            'data_breadcrumbs' => $data_breadcrumbs
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $PermissionsGroupByName = Permission::get()->groupBy(function($Permission) {
            return explode('.', $Permission->name)[0];
        });

        $Menus = Menu::select('name', 'route')->get();

        foreach ($Menus as $Menu) {
            if(!empty($PermissionsGroupByName[$Menu->route])) {
                $PermissionsGroupByName[$Menu->name] = $PermissionsGroupByName[$Menu->route];

                unset($PermissionsGroupByName[$Menu->route]);
            }
        }

        $data_breadcrumbs = [
            [
                'name' => 'Funções',
                'route' => 'admin.role.index',
            ],
            [
                'name' => 'Cadastrar',
            ],
        ];

        return view('admin.role.create', [
            'PermissionsGroupByName' => $PermissionsGroupByName,
            'data_breadcrumbs' => $data_breadcrumbs
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRoleRequest $request)
    {
        $data = $request->validated();
        $data['guard_name'] = 'web';

        $Role = Role::create($data);

        $Role->syncPermissions(!empty($request->permissions) ? array_map('intval',$request->permissions) : []);

        $permissions = $Role->permissions->pluck('name');

        if(!empty($permissions)) {
            register_log($Role, 'update', 200, [
                'permissions' => ['values' => $permissions, 'title' => 'Atribuiu à função as permissões'],
            ]);
        }

        return back()->with('success', 'Função cadastrada com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Spatie\Permission\Models\Role  $Role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $Role)
    {
        $Permissions = Permission::get();
        $Menus = Menu::get();

        foreach ($Permissions as $Permission) {
            $PermissionsGroupByName[preg_replace('/\..*/', '', $Permission->name)][] = $Permission;
        }

        foreach ($Menus as $Menu) {
            if(!empty($PermissionsGroupByName[$Menu->route])) {
                $PermissionsGroupByName[$Menu->name] = $PermissionsGroupByName[$Menu->route];

                unset($PermissionsGroupByName[$Menu->route]);
            }
        }

        $data_breadcrumbs = [
            [
                'name' => 'Funções',
                'route' => 'admin.role.index',
            ],
            [
                'name' => 'Editar',
            ],
        ];

        return view('admin.role.edit', [
            'Role' => $Role,
            'PermissionsGroupByName' => $PermissionsGroupByName,
            'data_breadcrumbs' => $data_breadcrumbs
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Spatie\Permission\Models\Role  $Role
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRoleRequest $request, Role $Role)
    {
        $data = $request->validated();

        $Role->update($data);

        $Permissions = $Role->permissions;

        $Role->syncPermissions(!empty($request->permissions) ? array_map('intval',$request->permissions) : []);

        $assigned = $Role->permissions->diff($Permissions)->pluck('name')->toArray();
        $revoked = $Permissions->diff($Role->permissions)->pluck('name')->toArray();

        if(!empty($assigned) || !empty($revoked)) {
            register_log($Role, 'update', 200, [
                'assigned' => ['values' => $assigned, 'title' => 'Atribuiu à função as permissões'],
                'revoked' => ['values' => $revoked, 'title' => 'Revogou da função as permissões'],
            ]);
        }

        return back()->with('success', 'Função atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Spatie\Permission\Models\Role  $Role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $Role)
    {
        $Role->load('users', 'permissions');
        $data = $Role->only('id', 'name');
        $data['permissions'] = $Role->permissions->pluck('name');
        $data['users'] = $Role->users->pluck('name');

        // Se existe algum usuário associado a essa função:
        if ($Role->users->count() > 0) {
            // Define uma nova função para o usuário
            $newRole = Role::findByName('Usuário');

            // Associa o usuário à nova função
            foreach ($Role->users as $User) {
                $User->syncRoles($newRole);
            }
        }

        $Role->delete();

        return back()->with('success', 'Função deletada com sucesso!');
    }
}
