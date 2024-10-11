<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMenuCategoryRequest;
use App\Http\Requests\UpdateMenuCategoryRequest;
use App\Models\MenuCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class MenuCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $MenuCategories = MenuCategory::with('menus', 'log')
        ->when($request->name, function($query) use ($request) {
            $query->where('name', 'LIKE', "%{$request->name}%");
        })
        ->when(auth()->user()->can('menu_category.restore'), function($query) {
            $query->withTrashed();
        })
        ->orderBy('order', 'ASC')
        ->get();

        $data_filter = [
            [
                'type' => 'text',
                'label' => 'Categoria de menu',
                'input_name' => 'name',
                'placeholder' => 'Informe o nome da categoria de menu'
            ],
        ];

        $data_breadcrumbs = [
            [
                'name' => 'Categorias de menu',
            ],
        ];

        return view('admin.menu_category.index', [
            'MenuCategories' =>  $MenuCategories,
            'data_filter' => $data_filter,
            'data_breadcrumbs' => $data_breadcrumbs,
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
                'name' => 'Categorias de menu',
                'route' => 'admin.menu_category.index',
            ],
            [
                'name' => 'Cadastrar',
            ],
        ];

        return view('admin.menu_category.create', [
            'data_breadcrumbs' => $data_breadcrumbs,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreMenuCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMenuCategoryRequest $request)
    {
        $MenuCategories = MenuCategory::withTrashed()->get();

        $data = $request->validated();
        $data['order'] = $MenuCategories->count() + 1;

        $Equals = $MenuCategories->where('name', $data['name']);

        if(!$Equals->isEmpty()) {
            return back()->withErrors(['name' => "Já existe uma categoria de menu cadastrada com esse nome, porém ela está com status 'deletado'. Entre em contato com um administrador para restuarar essa categoria!"])->withInput();
        }

        MenuCategory::create($data);

        return redirect()->route('admin.menu_category.create')->with('success', 'Categoria de menu cadastrada com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MenuCategory  $MenuCategory
     * @return \Illuminate\Http\Response
     */
    public function show(MenuCategory $MenuCategory)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MenuCategory  $MenuCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(MenuCategory $MenuCategory)
    {
        $data_breadcrumbs = [
            [
                'name' => 'Categorias de menu',
                'route' => 'admin.menu_category.index',
            ],
            [
                'name' => 'Editar',
            ],
        ];

        return view('admin.menu_category.edit', [
            'MenuCategory' => $MenuCategory,
            'data_breadcrumbs' => $data_breadcrumbs
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCategoryRequest  $request
     * @param  \App\Models\MenuCategory  $MenuCategory
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMenuCategoryRequest $request, MenuCategory $MenuCategory)
    {
        $data = $request->validated();

        $Equals = MenuCategory::where('id', '!=', $MenuCategory->id)->where('name', $data['name'])->withTrashed()->get();

        if(!$Equals->isEmpty()) {
            return back()->withErrors(['name' => "Já existe uma categoria de menu cadastrada com esse nome, porém ela está com status 'deletado'. Entre em contato com um administrador para restuarar essa categoria!"])->withInput();
        }

        $MenuCategory->update($data);

        return back()->with('success', 'Categoria de menu atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MenuCategory  $MenuCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(MenuCategory $MenuCategory)
    {
        $MenuCategory->delete();

        return back()->with('success', 'Categoria de menu deletada com sucesso!');
    }

    /**
     * Restore the specified resource to storage
     *
     * @param  int $MenuCategory;
     * @return \Illuminate\Http\Response
     */
    public function restore(int $MenuCategory)
    {
        $MenuCategory = MenuCategory::withTrashed()->where('id', $MenuCategory)->firstOrFail();

        if (MenuCategory::where('id', '!=', $MenuCategory->id)->where('name', $MenuCategory->name)->first()) {
            return back()->withErrors(['name' => 'Não é possível restaurar essa categoria de menu pois já existe uma nova categoria de menu utilizando o mesmo nome.']);
        }

        if ($MenuCategory->trashed()) {
            $MenuCategory->restore();
        }

        if ($MenuCategory->order != MenuCategory::max('order')) {
            $MenuCategory->update(['order' => MenuCategory::max('order') + 1]);
        }

        return back()->with('success', 'Categoria de menu restaurada com sucesso!');
    }

    /**
     * Updates the order of resources
     *
     * @param  \Illuminate\Http\Request $request;
     * @return \Illuminate\Http\Response
     */
    public function order(Request $request)
    {
        if (count(array_unique($request->order)) != count($request->order)) {
            throw ValidationException::withMessages([
                'order' => 'A ordem das categorias não pode ser duplicada!'
            ]);
        }

        $MenuCategories = MenuCategory::select('id', 'name', 'order')->get();

        foreach ($MenuCategories as $MenuCategory) {
            if ($MenuCategory->order != $request->order[$MenuCategory->id]) {
                $MenuCategory->update(['order' => $request->order[$MenuCategory->id]]);
            }
        }

        return back()->with('success', 'Ordem das categorias de menu alterada com sucesso!');
    }
}
