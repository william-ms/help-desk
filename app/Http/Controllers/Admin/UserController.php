<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Company;
use App\Models\Departament;
use App\Models\Menu;
use App\Models\User;
use App\Traits\PhosphorDuotoneTrait;
use App\Traits\UserTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    use PhosphorDuotoneTrait;
    use UserTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $Users = User::with('log', 'departaments', 'companies', 'roles')
        ->when($request->name, function($query) use ($request) {
            $query->where('name', 'LIKE', "%{$request->name}%");
        })
        ->when($request->company, function($query) use ($request) {
            $query->whereHas('companies', function ($q) use ($request) {
                $q->where('companies.id', $request->company);
            });
        })
        ->when($request->departament, function($query) use ($request) {
            $query->whereHas('departaments', function ($q) use ($request) {
                $q->where('departaments.id', $request->departament);
            });
        })
        ->when($request->role, function($query) use ($request) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('roles.id', $request->role);
            });
        })
        ->when($request->status, function($query) use ($request) {
            $query->where('status', $request->status);
        })
        ->when(auth()->user()->can('user.restore'), function($query) {
            $query->withTrashed();
        })
        ->orderBy('name')
        ->get();

        $data_filter = [
            [
                'type' => 'text',
                'label' => 'Usuário',
                'input_name' => 'name',
                'placeholder' => 'Informe o nome do usuário'
            ],
            [
                'type' => 'select',
                'label' => 'Empresa',
                'input_name' => 'company',
                'data' => Company::orderBy('name')->get(),
            ],
            [
                'type' => 'select',
                'label' => 'Departamento',
                'input_name' => 'departament',
                'data' => Departament::orderBy('name')->get(),
            ],
            [
                'type' => 'select',
                'label' => 'Função',
                'input_name' => 'role',
                'data' => Role::orderBy('name')->get(),
            ],
            [
                'type' => 'select',
                'label' => 'Status',
                'input_name' => 'status',
                'data' => collect([['id' => 1, 'name' => 'Ativo'], ['id' => 2, 'name' => 'Inativo']]),
            ],
        ];

        $data_breadcrumbs = [
            [
                'name' => 'Usuários',
            ],
        ];

        return view('admin.user.index', [
            'Users' => $Users,
            'UserStatus' => $this->UserStatus,
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
        
        $Menus = Menu::get();

        foreach ($Menus as $Menu) {
            if (!empty($PermissionsGroupByName[$Menu->route])) {
                $PermissionsGroupByName[$Menu->name] = $PermissionsGroupByName[$Menu->route];

                unset($PermissionsGroupByName[$Menu->route]);
            }
        }

        $Roles = Role::with('permissions')->orderBy('name')->get();

        $data_breadcrumbs = [
            [
                'name' => 'Users',
                'route' => 'admin.user.index',
            ],
            [
                'name' => 'Cadastrar',
            ],
        ];

        return view('admin.user.create', [
            'Companies' => Company::get(),
            'Departaments' => Departament::get(),
            'Roles' => $Roles,
            'PermissionsGroupByName' => $PermissionsGroupByName,
            'data_breadcrumbs' => $data_breadcrumbs
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $log_data = [];

        if($data['role'] == 1 && !auth()->user()->hasRole(1)) {
            return back()->withErrors(['name' => "Parece que você está tentando burlar o sistema! Seu ip foi registrado em nossos dados para análise"])->withInput();
        }
        $Equals = User::where('email', $data['email'])->withTrashed()->get();
        
        if(!$Equals->isEmpty()) {
            return back()->withErrors(['name' => "Já existe um usuário cadastrado com esse email, porém ele está com status 'deletado'. Entre em contato com um administrador para restaurar esse usuário!"])->withInput();
        }
        
        $User = User::create($data);

        $User->companies()->sync($data['companies']);
        $log_data['companies'] = ['values' => $User->companies->pluck('name'), 'title' => "Atribuiu o usuário as <b>empresas</b>"];

        $User->departaments()->sync($data['departaments']);
        $log_data['departaments'] = ['values' => $User->departaments->pluck('name'), 'title' => "Atribuiu o usuário aos <b>departamentos</b>"];

        $User->assignRole((int) $data['role']);
        $Role = $User->roles->first();
        $log_data['role'] = ['value' => "Atribuiu o usuário à função de <b>{$Role->name}</b>"];

        // Se o novo usuário não for um administrador e existem permissões passadas pelo request
        if ($data['role'] != 1 && !empty($request->permissions)) {
            $permissions = array_diff($request->permissions, $Role->permissions->pluck('name')->toArray());

            if(!empty($permissions)) {
                $log_data['permissions'] = ['values' => $permissions, 'title' => 'Atribuiu ao usuário as <b>permissões</b>'];
            }

            $User->syncPermissions($permissions);
        }

        if(!empty($log_data)) {
            register_log($User, 'update', 200, $log_data);
        }

        return back()->with('success', 'Usuário cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $User
     * @return \Illuminate\Http\Response
     */
    public function show(User $User)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $User
     * @return \Illuminate\Http\Response
     */
    public function edit(User $User)
    {
        $PermissionsGroupByName = Permission::get()->groupBy(function($Permission) {
            return explode('.', $Permission->name)[0];
        });
        
        $Menus = Menu::get();

        foreach ($Menus as $Menu) {
            if (!empty($PermissionsGroupByName[$Menu->route])) {
                $PermissionsGroupByName[$Menu->name] = $PermissionsGroupByName[$Menu->route];

                unset($PermissionsGroupByName[$Menu->route]);
            }
        }

        $Roles = Role::with('permissions')->orderBy('name')->get();

        $data_breadcrumbs = [
            [
                'name' => 'Usuários',
                'route' => 'admin.user.index',
            ],
            [
                'name' => 'Editar',
            ],
        ];

        return view('admin.user.edit', [
            'User' => $User,
            'Companies' => Company::get(),
            'Departaments' => Departament::get(),
            'Roles' => $Roles,
            'PermissionsGroupByName' => $PermissionsGroupByName,
            'data_breadcrumbs' => $data_breadcrumbs
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\User $User
     * @param  \App\Http\Requests\UpdateUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $User)
    {
        $data = $request->validated();
        $log_data = [];

        if($data['role'] == 1 && !auth()->user()->hasRole(1)) {
            return back()->withErrors(['name' => "Parece que você está tentando burlar o sistema! Seu ip foi registrado em nossos dados para análise"])->withInput();
        }

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        $Equals = User::where('id', '!=', $User->id)->where('email', $data['email'])->withTrashed()->get();
        
        if(!$Equals->isEmpty()) {
            return back()->withErrors(['name' => "Já existe um usuário cadastrado com esse email, porém ele está com status 'deletado'. Entre em contato com um administrador para restaurar esse usuário!"])->withInput();
        }

        $User->update($data);
        
        $CompaniesSync = $User->companies()->sync($data['companies']);
        
        if(!empty($CompaniesSync['attached']) || !empty($CompaniesSync['detached'])) {
            if(!empty($CompaniesSync['attached'])) {
                $CompaniesAttached = Company::whereIn('id', $CompaniesSync['attached'])->get();
                $log_data['companies_attached'] =  ['values' => $CompaniesAttached->pluck('name'), 'title' => "Atribuiu o usuário as <b>empresas</b>"];
            }

            if(!empty($CompaniesSync['detached'])) {
                $CompaniesDetached = Company::whereIn('id', $CompaniesSync['detached'])->get();  
                $log_data['companies_detached'] =  ['values' => $CompaniesDetached->pluck('name'), 'title' => "Removeu o usuário das <b>empresas</b>"];
            }
        }

        $DepartamentsSync = $User->departaments()->sync($data['departaments']);

        if(!empty($DepartamentsSync['attached']) || !empty($DepartamentsSync['detached'])) {
            if(!empty($DepartamentsSync['attached'])) {
                $DepartamentsAttached = Company::whereIn('id', $DepartamentsSync['attached'])->get();
                $log_data['departaments_attached'] =  ['values' => $DepartamentsAttached->pluck('name'), 'title' => "Atribuiu o usuário aos <b>departamentos</b>"];
            }

            if(!empty($DepartamentsSync['detached'])) {
                $DepartamentsDetached = Company::whereIn('id', $DepartamentsSync['detached'])->get();  
                $log_data['departaments_detached'] =  ['values' => $DepartamentsDetached->pluck('name'), 'title' => "Removeu o usuário dos <b>departamentos</b>"];
            }
        }

        $Role = $User->roles->first();
        
        if($data['role'] != $Role->id) {
            $User->syncRoles((int) $data['role']);
            $OldRole = $Role;
            $Role = $User->roles->first();
            $log_data['role'] = ['value' => "Alterou a função do usuário de <b>{$OldRole->name}</b> para <b>{$Role->name}</b>"];
        }

        if ($User->hasRole(1)) {
            $User->syncPermissions([]);
        } else {
            if(empty($request->permissions)) {
                $request->permissions = [];
            }

            //Permissões selecionadas que não estão associadas a função
            $permissions = array_diff($request->permissions, $Role->permissions->pluck('name')->toArray());

            $assigned = array_diff($permissions, $User->permissions->pluck('name')->toArray());
            $revoked = array_diff($User->permissions->pluck('name')->toArray(), $permissions);

            if(!empty($assigned) || !empty($revoked)) {
                $data = [];

                if(!empty($assigned)) {
                    $log_data['assigned'] = ['values' => $assigned, 'title' => 'Atribuiu ao usuário as <b>permissões</b>'];
                }

                if(!empty($revoked)) {
                    $log_data['revoked'] = ['values' => $revoked, 'title' => 'Revogou do usuário as <b>permissões</b>'];
                }
            }

            $User->syncPermissions($permissions);
        } 

        if(!empty($log_data)) {
            register_log($User, 'update', 200, $log_data);
        }
        
        return back()->with('success', 'Usuário atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $User
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $User)
    {
        $User->delete();

        return back()->with('success', 'Usuário deletado com sucesso!');
    }

    /**
     * Restore the specified resource to storage
     *
     * @param  int $User;
     * @return \Illuminate\Http\Response
     */
    public function restore(int $User)
    {    
        $User = User::withTrashed()->where('id', $User)->firstOrFail();

        if ($User->trashed()) {
            $User->restore();
        }

        return back()->with('success', 'Usuário restaurado com sucesso!');
    }
}
