<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use App\Models\MenuCategory;
use App\Models\Menu;
use App\Traits\PhosphorDuotoneTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class MenuController extends Controller
{
    use PhosphorDuotoneTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $Menus = Menu::with('menu_category')
        ->when($request->name, function($query) use ($request) {
            $query->where('name', 'LIKE', "%{$request->name}%");
        })
        ->when($request->menu_category_id, function($query) use ($request) {
            $query->where('menu_category_id', $request->menu_category_id);
        })
        ->when(auth()->user()->can('menu.restore'), function($query) {
            $query->withTrashed();
        })
        ->orderBy('menu_category_id')->orderBy('order')
        ->get();

        $data_filter = [
            [
                'type' => 'text',
                'label' => 'Menu',
                'input_name' => 'name',
                'placeholder' => 'Informe o nome do menu'
            ],
            [
                'type' => 'select',
                'label' => 'Categoria',
                'input_name' => 'menu_category_id',
                'data' => MenuCategory::get(),
            ],
        ];

        $data_breadcrumbs = [
            [
                'name' => 'Menus',
            ],
        ];

        return view('admin.menu.index', [
            'Menus' => $Menus,
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
        $MenuCategories = MenuCategory::get();

        $preset_permissions = [
            ['name' => 'index', 'label' => 'Listar', 'color' => 'info'],
            ['name' => 'show', 'label' => 'Ver', 'color' => 'info'],
            ['name' => 'create', 'label' => 'Criar', 'color' => 'warning'],
            ['name' => 'edit', 'label' => 'Editar', 'color' => 'danger'],
            ['name' => 'destroy', 'label' => 'Deletar', 'color' => 'danger'],
            ['name' => 'restore', 'label' => 'Restaurar', 'color' => 'warning'],
        ];

        $data_breadcrumbs = [
            [
                'name' => 'Menus',
                'route' => 'admin.menu.index',
            ],
            [
                'name' => 'Cadastrar',
            ],
        ];

        return view('admin.menu.create', [
            'MenuCategories' => $MenuCategories,
            'Icons' => $this->PhosphorDuotoneIcons,
            'preset_permissions' => $preset_permissions,
            'data_breadcrumbs' => $data_breadcrumbs
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreMenuRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMenuRequest $request)
    {
        $data = $request->validated();

        $Equals = Menu::where('name', $data['name'])->where('menu_category_id', $data['menu_category_id'])->withTrashed()->get();

        if(!$Equals->isEmpty()) {
            return back()->withErrors(['name' => "Já existe um menu cadastrado com esse nome, porém ele está com status 'deletado'. Entre em contato com um administrador para restaurar esse menu!"])->withInput();
        }

        $data['order'] = Menu::where('menu_category_id', '=', $data['menu_category_id'])->max('order') + 1;
        $data['route'] = Str::lower($data['route']);

        $Permissions = collect([]);

        if (!empty($request->permissions)) {
            foreach ($request->permissions as $permission) {
                $Permissions[] = Permission::firstOrCreate(['name' => $data['route'] . '.' . $permission], [
                    'guard_name' => 'web'
                ]);
            }
        }

        $Menu = Menu::create($data);
        $Menu->permissions()->sync($Permissions->pluck('id')->toArray());

        return back()->with('success', 'Menu cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Menu  $Menu
     * @return \Illuminate\Http\Response
     */
    public function show(Menu $Menu)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Menu  $Menu
     * @return \Illuminate\Http\Response
     */
    public function edit(Menu $Menu)
    {
        $MenuCategories = MenuCategory::get();

        $data_breadcrumbs = [
            [
                'name' => 'Menus',
                'route' => 'admin.menu.index',
            ],
            [
                'name' => 'Editar',
            ],
        ];

        return view('admin.menu.edit', [
            'Menu' => $Menu,
            'MenuCategories' => $MenuCategories,
            'Icons' => $this->PhosphorDuotoneIcons,
            'data_breadcrumbs' => $data_breadcrumbs
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\Menu $Menu
     * @param  \App\Http\Requests\UpdateMenuRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMenuRequest $request, Menu $Menu)
    {
        $data = $request->validated();

        $Equals = Menu::where('id', '!=', $Menu->id)->where('name', $data['name'])->where('menu_category_id', $data['menu_category_id'])->withTrashed()->get();

        if(!$Equals->isEmpty()) {
            return back()->withErrors(['name' => "Já existe um menu cadastrado com esse nome, porém ele está com status 'deletado'. Entre em contato com um administrador para restaurar esse menu!"])->withInput();
        }

        if($data['menu_category_id'] != $Menu->menu_category_id) {
            $data['order'] = Menu::where('menu_category_id', $data['menu_category_id'])->max('order') + 1;
        }

        $Menu->update($data);

        return back()->with('success', 'Menu atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Menu  $Menu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Menu $Menu)
    {
        $Menu->delete();
        Permission::whereIn('name', $Menu->permissions->pluck('name'))->delete();
        $Menu->permissions()->sync([]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
        
        return back()->with('success', 'Menu deletado com sucesso!');
    }

        /**
     * Restore the specified resource to storage
     *
     * @param  int $Menu;
     * @return \Illuminate\Http\Response
     */
    public function restore(int $Menu)
    {    
        $Menu = Menu::where('id', $Menu)->withTrashed()->first();

        if ($Menu->trashed()) {
            $Menu->restore();
        }

        return back()->with('success', 'Menu restaurado com sucesso!');
    }

    /**
     * Updates the order of resources
     *
     * @param  \Illuminate\Http\Request $request;
     * @return \Illuminate\Http\Response
     */
    public function order(Request $request)
    {
        foreach ($request->order  as $order) {
            if (count(array_unique($order)) != count($order)) {
                throw ValidationException::withMessages([
                    'order' => 'A ordem dos menus não pode ser duplicada!'
                ]);
            }
        }

        $Menus = Menu::select('id', 'menu_category_id', 'name', 'order')->get();

        foreach ($Menus as $Menu) {
            if ($Menu->order != $request->order[$Menu->menu_category_id][$Menu->id]) {
                $Menu->update(['order' => $request->order[$Menu->menu_category_id][$Menu->id]]);
            }
        }

        return back()->with('success', 'Ordem dos menus alterada com sucesso!');
    }
}
