<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubcategoryRequest;
use App\Http\Requests\UpdateSubcategoryRequest;
use App\Models\Category;
use App\Models\Company;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SubcategoryController extends Controller
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
                'name' => 'Subcategorias',
            ],
        ];

        $data_filter = [
            [
                'type' => 'text',
                'label' => 'Subcategoria',
                'input_name' => 'name',
                'placeholder' => 'Informe o nome da subcategoria'
            ],
            [
                'type' => 'select',
                'label' => 'Categoria',
                'input_name' => 'category_id',
                'data' => Category::orderBy('name')->get(),
            ],
            [
                'type' => 'select',
                'label' => 'Empresa',
                'input_name' => 'company_id',
                'data' => Company::orderBy('name')->get(),
            ],
        ];

        $gates = [
            'create' => Gate::allows('subcategory.create'),
            'edit' => Gate::allows('subcategory.edit'),
            'destroy' => Gate::allows('subcategory.destroy'),
            'restore' => Gate::allows('subcategory.restore'),
            'log_show' => Gate::allows('log.show'),
        ];

        $Subcategories = Subcategory::with('log', 'category.company')
        ->when($request->name, function($query) use ($request) {
            $query->where('name', 'LIKE', "%{$request->name}%");
        })
        ->when($request->category_id, function($query) use ($request) {
            $query->where('category_id', $request->category_id);
        })
        ->when($request->company_id, function($query) use ($request) {
            $query->whereHas('category', function ($query) use ($request) {
                $query->where('company_id', $request->company_id);
            });
        })
        ->when($gates['restore'], function($query) {
            $query->withTrashed();
        })
        ->orderBy('name', 'ASC')
        ->get();

        return view('admin.subcategory.index', [
            'data_breadcrumbs' => $data_breadcrumbs,
            'data_filter' => $data_filter,
            'gates' => $gates,
            'Subcategories' =>  $Subcategories,
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
                'name' => 'Subcategorias',
                'route' => 'admin.subcategory.index',
            ],
            [
                'name' => 'Cadastrar',
            ],
        ];

        return view('admin.subcategory.create', [
            'data_breadcrumbs' => $data_breadcrumbs,
            'Categories' => Category::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSubcategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSubcategoryRequest $request)
    {
        $data = $request->validated();

        $Equals = Subcategory::where('name', $data['name'])->where('category_id', $data['category_id'])->withTrashed()->get();

        if(!$Equals->isEmpty()) {
            return back()->withErrors(['name' => "Já existe para essa categoria uma subcategoria cadastrada com esse nome, porém ela está com status 'deletado'. Entre em contato com um administrador para restaurar essa subcategoria!"])->withInput();
        }

        Subcategory::create($data);

        return back()->with('success', 'Categoria cadastrada com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subcategory  $Subcategory
     * @return \Illuminate\Http\Response
     */
    public function show(Subcategory $Subcategory)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Subcategory  $Subcategory
     * @return \Illuminate\Http\Response
     */
    public function edit(Subcategory $Subcategory)
    {
        $data_breadcrumbs = [
            [
                'name' => 'Subcategorias',
                'route' => 'admin.subcategory.index',
            ],
            [
                'name' => 'Editar',
            ],
        ];

        return view('admin.subcategory.edit', [
            'data_breadcrumbs' => $data_breadcrumbs,
            'Subcategory' => $Subcategory,
            'Categories' => Category::orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSubcategoryRequest  $request
     * @param  \App\Models\Subcategory  $Subcategory
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSubcategoryRequest $request, Subcategory $Subcategory)
    {
        $data = $request->validated();

        $Equals = Subcategory::where('id', '!=', $Subcategory->id)->where('category_id', $data['category_id'])->where('name', $data['name'])->withTrashed()->get();

        if(!$Equals->isEmpty()) {
            return back()->withErrors(['name' => "Já existe para essa categoria uma subcategoria cadastrada com esse nome, porém ela está com status 'deletado'. Entre em contato com um administrador para restaurar essa subcategoria!"])->withInput();
        }

        $Subcategory->update($data);

        return back()->with('success', 'Categoria atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subcategory  $Subcategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subcategory $Subcategory)
    {
        $Subcategory->delete();

        return back()->with('success', 'Categoria deletada com sucesso!');
    }

    /**
     * Restore the specified resource to storage
     *
     * @param  int $Subcategory;
     * @return \Illuminate\Http\Response
     */
    public function restore(int $Subcategory)
    {
        $Subcategory = Subcategory::where('id', $Subcategory)->withTrashed()->first();

        if ($Subcategory->trashed()) {
            $Subcategory->restore();
        }

        return back()->with('success', 'Categoria restaurada com sucesso!');
    }
}
