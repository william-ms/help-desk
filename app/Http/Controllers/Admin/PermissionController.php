<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Models\Menu;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data_breadcrumbs = [
            [
                'name' => 'Permissões',
            ],
        ];

        $Permissions = Permission::get();

        foreach ($Permissions as $Permission) {
            [$prefix, $method] = explode('.', $Permission->name);

            $Prefixs[] = ['id' => $prefix, 'prefix' => $prefix];
            $Methods[] = ['id' => $method, 'method' => $method];
        }
        
        $data_filter = [
            [
                'type' => 'select',
                'label' => 'Prefixo da rota',
                'input_name' => 'prefix',
                'data' => collect($Prefixs)->unique('id'),
                'field_value' => 'prefix',
            ],
            [
                'type' => 'select',
                'label' => 'Método',
                'input_name' => 'method',
                'data' => collect($Methods)->unique('id'),
                'field_value' => 'method',
            ],
        ];

        $gates = [
            'create' => Gate::allows('permission.create'),
            'edit' => Gate::allows('permission.edit'),
            'destroy' => Gate::allows('permission.destroy'),
            'log_show' => Gate::allows('log.show'),
        ];

        $Permissions = Permission::with('log')
        ->when($request->prefix, function($query) use ($request) {
            $query->where('name', 'LIKE', "{$request->prefix}.%");
        })
        ->when($request->method, function($query) use ($request) {
            $query->where('name', 'LIKE', "%.{$request->method}");
        })
        ->get();

        return view('admin.permission.index', [
            'data_breadcrumbs' => $data_breadcrumbs,
            'data_filter' => $data_filter,
            'gates' => $gates,
            'Permissions' => $Permissions,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $data_breadcrumbs = [
            [
                'name' => 'Permissões',
                'route' => 'admin.permission.index',
            ],
            [
                'name' => 'Cadastrar',
            ],
        ];

        return view('admin.permission.create', [
            'data_breadcrumbs' => $data_breadcrumbs,
            'Menus' => Menu::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePermissionRequest $request)
    {
        $data = $request->validated();

        if(!empty($data['menu_id'])) {
            $Menu = Menu::find($data['menu_id']);
            $data['name'] = $Menu->route . '.' . $data['route_method'];
        } else {
            $data['name'] = $data['route_prefix'] . '.' . $data['route_method'];
        }

        if (Permission::where('name', $data['name'])->first()) {
            return back()->withErrors(['name' => 'Já existe uma permissão cadastrada com esse prefixo e método!']);
        }

        $Permission = Permission::create([
            'name' => $data['name'],
            'guard_name' => 'web',
        ]);

        if(!empty($data['menu_id'])) {
            $Menu->permissions()->attach($Permission->id);
            register_log($Menu, 'update', 201, ['attached' => ['value' => "Associou ao menu a permissão <b>{$Permission->name}</b>"]]);
        }

        return back()->with('success', 'Permissão cadastrada com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \Spatie\Permission\Models\Permission  $Permission
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $Permission)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Spatie\Permission\Models\Permission  $Permission
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $Permission)
    {
        //Separa o nome da permissão em prefixo e método
        [$Permission->prefix, $Permission->method] = explode('.', $Permission->name);

        $data_breadcrumbs = [
            [
                'name' => 'Permissões',
                'route' => 'admin.permission.index',
            ],
            [
                'name' => 'Editar',
            ],
        ];


        return view('admin.permission.edit', [
            'data_breadcrumbs' => $data_breadcrumbs,
            'Permission' => $Permission,
            'Menus' => Menu::orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\UpdatePermissionRequest  $request
     * @param  \Spatie\Permission\Models\Permission  $Permission
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePermissionRequest $request, Permission $Permission)
    {
        $data = $request->validated();

        if(!empty($data['menu_id'])) {
            $Menu = Menu::find($data['menu_id']);
            $data['name'] = $Menu->route . '.' . $data['route_method'];
        } else {
            $Menu = null;
            $data['name'] = $data['route_prefix'] . '.' . $data['route_method'];
        }

        if (Permission::where('name', $data['name'])->where('id', '!=', $Permission->id)->first()) {
            return back()->withErrors(['name' => 'Já existe uma permissão cadastrada com esse prefixo e método!']);
        }

        $ActualMenu = $Permission->menu;

        if($ActualMenu?->id != $Menu?->id) {
            if(!empty($ActualMenu)) {
                $ActualMenu->permissions()->detach($Permission->id);
                register_log($ActualMenu, 'update', 201, ['detached' => ['value' => 'Desassociou do menu a permissão <b>'. $ActualMenu->route . '.' . $data['route_method'] . '</b>']]);
            }

            if(!empty($Menu)) {
                $Menu->permissions()->attach($Permission->id);
                register_log($Menu, 'update', 201, ['attached' => ['value' => 'Associou ao menu a permissão <b>'. $Menu->route . '.' . $data['route_method'] . '</b>']]);
            }
        }

        $Permission->update($data);

        return back()->with('success', 'Permissão atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Spatie\Permission\Models\Permission  $Permission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $Permission)
    {
        $Permission->load('users', 'roles', 'menu');

        $Menu = $Permission->menu;
        $Menu->permissions()->detach($Permission->id);
        register_log($Menu, 'update', 201, ['detached' => ['value' => "Desassociou do menu a permissão <b>{$Permission->name}</b>"]]);

        // Se a permissão pertence a alguma função, ela é removida da função
        if (count($Permission->roles) > 0) {
            foreach ($Permission->roles as $Role) {
                $Role->revokePermissionTo($Permission);
            }
        }

        // Se a permissão pertence a algum usuário, ela é removida do usuário
        if (count($Permission->users) > 0) {
            foreach ($Permission->users as $User) {
                $User->revokePermissionTo($Permission);
            }
        }

        $Permission->delete();

        return back()->with('success', 'Permissão deletada com sucesso!');
    }
}
