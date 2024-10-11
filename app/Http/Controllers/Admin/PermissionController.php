<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $Permissions = Permission::get();

        foreach ($Permissions as $Permission) {
            [$prefix, $method] = explode('.', $Permission->name);

            $Prefixs[] = ['id' => $prefix, 'prefix' => $prefix];
            $Methods[] = ['id' => $method, 'method' => $method];
        }

        $Permissions = Permission::query()
        ->when($request->prefix, function($query) use ($request) {
            $query->where('name', 'LIKE', "{$request->prefix}.%");
        })
        ->when($request->method, function($query) use ($request) {
            $query->where('name', 'LIKE', "%.{$request->method}");
        })
        ->get();

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

        $data_breadcrumbs = [
            [
                'name' => 'Permissões',
            ],
        ];

        return view('admin.permission.index', [
            'Permissions' => $Permissions,
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
        $data['name'] = $data['route_prefix'] . '.' . $data['route_method'];
        $data['guard_name'] = 'web';
        unset($data['route_prefix'], $data['route_method']);

        if (Permission::where('name', $data['name'])->first()) {
            return back()->withErrors(['name' => 'Já existe uma permissão cadastrada com esse prefixo e método!']);
        }

        Permission::create($data);

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
            'Permission' => $Permission,
            'data_breadcrumbs' => $data_breadcrumbs
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
        $data['name'] = $data['route_prefix'] . '.' . $data['route_method'];
        unset($data['route_prefix'], $data['route_method']);

        if (Permission::where('name', $data['name'])->where('id', '!=', $Permission->id)->first()) {
            return back()->withErrors(['name' => 'Já existe uma permissão cadastrada com esse prefixo e método!']);
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
        $Permission->load('users', 'roles');

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
