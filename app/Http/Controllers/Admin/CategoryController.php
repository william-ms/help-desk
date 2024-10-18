<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Models\Company;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $Categories = Category::with('log', 'company')
        ->when($request->name, function($query) use ($request) {
            $query->where('name', 'LIKE', "%{$request->name}%");
        })
        ->when($request->company_id, function($query) use ($request) {
            $query->where('company_id', $request->company_id);
        })
        ->when(auth()->user()->can('category.restore'), function($query) {
            $query->withTrashed();
        })
        ->orderBy('name', 'ASC')
        ->get();

        $data_filter = [
            [
                'type' => 'text',
                'label' => 'Categoria',
                'input_name' => 'name',
                'placeholder' => 'Informe o nome da categoria'
            ],
            [
                'type' => 'select',
                'label' => 'Empresa',
                'input_name' => 'company_id',
                'data' => Company::orderBy('name')->get(),
            ],
        ];

        $data_breadcrumbs = [
            [
                'name' => 'Categorias',
            ],
        ];

        return view('admin.category.index', [
            'Categories' =>  $Categories,
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
                'name' => 'Categorias',
                'route' => 'admin.category.index',
            ],
            [
                'name' => 'Cadastrar',
            ],
        ];

        return view('admin.category.create', [
            'Companies' => Company::orderBy('name')->get(),
            'data_breadcrumbs' => $data_breadcrumbs,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();

        $Equals = Category::where('name', $data['name'])->where('company_id', $data['company_id'])->withTrashed()->get();

        if(!$Equals->isEmpty()) {
            return back()->withErrors(['name' => "Já existe para essa empresa uma categoria cadastrada com esse nome, porém ela está com status 'deletado'. Entre em contato com um administrador para restaurar essa categoria!"])->withInput();
        }

        Category::create($data);

        return back()->with('success', 'Categoria cadastrada com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $Category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $Category)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $Category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $Category)
    {
        $data_breadcrumbs = [
            [
                'name' => 'Categorias',
                'route' => 'admin.category.index',
            ],
            [
                'name' => 'Editar',
            ],
        ];

        return view('admin.category.edit', [
            'Category' => $Category,
            'Companies' => Company::orderBy('name')->get(),
            'data_breadcrumbs' => $data_breadcrumbs
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCategoryRequest  $request
     * @param  \App\Models\Category  $Category
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, Category $Category)
    {
        $data = $request->validated();

        $Equals = Category::where('id', '!=', $Category->id)->where('company_id', $data['company_id'])->where('name', $data['name'])->withTrashed()->get();

        if(!$Equals->isEmpty()) {
            return back()->withErrors(['name' => "Já existe para essa empresa uma categoria cadastrada com esse nome, porém ela está com status 'deletado'. Entre em contato com um administrador para restaurar essa categoria!"])->withInput();
        }

        $Category->update($data);

        return back()->with('success', 'Categoria atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $Category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $Category)
    {
        $Category->delete();

        return back()->with('success', 'Categoria deletada com sucesso!');
    }

    /**
     * Restore the specified resource to storage
     *
     * @param  int $Category;
     * @return \Illuminate\Http\Response
     */
    public function restore(int $Category)
    {
        $Category = Category::where('id', $Category)->withTrashed()->first();

        if ($Category->trashed()) {
            $Category->restore();
        }

        return back()->with('success', 'Categoria restaurada com sucesso!');
    }
}
