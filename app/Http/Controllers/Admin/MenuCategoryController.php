<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMenuCategoryRequest;
use App\Http\Requests\UpdateMenuCategoryRequest;
use App\Models\Log;
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
        $MenuCategories = MenuCategory::with('log')
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

        return view('admin.menu_category.index', [
            'MenuCategories' =>  $MenuCategories,
            'data_filter' => $data_filter
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.menu_category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreMenuCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMenuCategoryRequest $request)
    {
        $data = $request->validated();
        $data['order'] = MenuCategory::max('order') + 1;

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
        return view('admin.menu_category.edit', [
            'MenuCategory' => $MenuCategory
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

        Log::create([
            'user_id' => auth()->id(),
            'model_type' => 'MenuCategory',
            'model_id' => $MenuCategory->id,
            'model_name' => $MenuCategory->name,
            'status' => 200,
            'action' => 'Deletou a categoria de menu de id "' . $MenuCategory->id . '", nome "' . $MenuCategory->name . '"',
            'body' => json_encode($MenuCategory->toArray())
        ]);

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

        Log::create([
            'user_id' => auth()->id(),
            'model_type' => 'MenuCategory',
            'model_id' => $MenuCategory->id,
            'model_name' => $MenuCategory->name,
            'status' => 200,
            'action' => 'Restaurou a categoria de menu de id "' . $MenuCategory->id . '", nome "' . $MenuCategory->name . '"',
            'body' => json_encode($MenuCategory->toArray())
        ]);

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

        $old_data = [];
        $new_data = [];

        foreach ($MenuCategories as $MenuCategory) {
            if ($MenuCategory->order != $request->order[$MenuCategory->id]) {
                $old_data[$MenuCategory->name] = $MenuCategory->order;
                $MenuCategory->update(['order' => $request->order[$MenuCategory->id]]);
                $new_data[$MenuCategory->name] = $MenuCategory->order;
            }
        }

        if ($old_data != $new_data) {
            Log::create([
                'user_id' => auth()->id(),
                'model_type' => 'MenuCategory',
                'status' => 200,
                'action' => 'Atualizou a ordem das categorias de menu',
                'body' => json_encode(['old_data' => $old_data, 'new_data' => $new_data]),
            ]);
        }

        return back()->with('success', 'Ordem das categorias de menu alterada com sucesso!');
    }
}
