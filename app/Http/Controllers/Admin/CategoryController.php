<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Models\Company;
use App\Models\Departament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
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
                'name' => 'Categorias',
            ],
        ];

        $data_filter = [
            [
                'type' => 'text',
                'label' => 'Categoria',
                'input_name' => 'name',
                'placeholder' => 'Informe o nome da categoria'
            ],
            [
                'type' => 'select',
                'label' => 'Departamento',
                'input_name' => 'departament',
                'data' => Departament::whereHas('categories')->get(),
            ],
            [
                'type' => 'select',
                'label' => 'Empresa',
                'input_name' => 'company',
                'data' => Company::whereHas('categories')->get(),
            ],
        ];

        $gates = [
            'create' => Gate::allows('category.create'),
            'edit' => Gate::allows('category.edit'),
            'destroy' => Gate::allows('category.destroy'),
            'restore' => Gate::allows('category.restore'),
            'companies' => Gate::allows('category.companies'),
            'departaments' => Gate::allows('category.departaments'),
            'log_show' => Gate::allows('log.show'),
        ];

        $Categories = Category::with(['log', 'departament', 'company'])
        ->when($request->name, function($query) use ($request) {
            $query->where('name', 'LIKE', "%{$request->name}%");
        })
        ->when($request->departament, function($query) use ($request) {
            $query->where('departament_id', $request->departament);
        })
        ->when($request->company, function($query) use ($request) {
            $query->where('company_id', $request->company);
        })
        ->when($gates['restore'], function($query) {
            $query->withTrashed();
        })
        ->when(!$gates['companies'], function($query) {
            $query->whereHas('company.users', function($query) {
                $query->where('users.id', auth()->id());
            });
        })
        ->when(!$gates['departaments'], function($query) {
            $query->whereHas('departament.users', function($query) {
                $query->where('users.id', auth()->id());
            });
        })
        ->orderBy('name', 'ASC')
        ->get();

        return view('admin.category.index', [
            'data_breadcrumbs' => $data_breadcrumbs,
            'data_filter' => $data_filter,
            'gates' => $gates,
            'Categories' =>  $Categories,
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

        $Companies = Company::when(!auth()->user()->can('category.companies'), function($query) {
            $query->whereHas('users', function($query) {
                $query->where('id', auth()->id());
            });
        })
        ->get();
        
        $Departaments = Departament::when(!auth()->user()->can('category.departaments'), function($query) {
            $query->whereHas('users', function($query) {
                $query->where('id', auth()->id());
            });
        })
        ->get();

        return view('admin.category.create', [
            'data_breadcrumbs' => $data_breadcrumbs,
            'Companies' => $Companies,
            'Departaments' => $Departaments,
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

        $Equals = Category::where('name', $data['name'])->where('company_id', $data['company_id'])->where('departament_id', $data['departament_id'])->withTrashed()->get();

        if(!$Equals->isEmpty()) {
            return back()->withErrors(['name' => "Já existe para esse departamento e empresa uma categoria cadastrada com esse nome, porém ela está com status 'deletado'. Entre em contato com um administrador para restaurar essa categoria!"])->withInput();
        }

        if(!empty($data['automatic_response'])) {
            $data['automatic_response'] = $this->format_response($data['automatic_response']);
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
        $Companies = Company::when(!auth()->user()->can('category.companies'), function($query) {
            $query->whereHas('users', function($query) {
                $query->where('id', auth()->id());
            });
        })
        ->get();

        $Departaments = Departament::when(!auth()->user()->can('category.departaments'), function($query) {
            $query->whereHas('users', function($query) {
                $query->where('id', auth()->id());
            });
        })
        ->get();

        if(!auth()->user()->can('category.companies') && !$Companies->contains('id', $Category->company_id)) {
            return abort(403);
        }

        if(!auth()->user()->can('category.departaments') && !$Departaments->contains('id', $Category->departament_id)) {
            return abort(403);
        }

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
            'data_breadcrumbs' => $data_breadcrumbs,
            'Category' => $Category,
            'Companies' => $Companies,
            'Departaments' => $Departaments,
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

        $Equals = Category::where('id', '!=', $Category->id)->where('company_id', $data['company_id'])->where('departament_id', $data['departament_id'])->where('name', $data['name'])->withTrashed()->get();

        if(!$Equals->isEmpty()) {
            return back()->withErrors(['name' => "Já existe para esse departamento e empresa uma categoria cadastrada com esse nome, porém ela está com status 'deletado'. Entre em contato com um administrador para restaurar essa categoria!"])->withInput();
        }

        if(!empty($data['automatic_response'])) {
            $data['automatic_response'] = $this->format_response($data['automatic_response']);
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

    private function format_response(string $response)
    {
        if (preg_match('/(<img[^>]+>)/i', $response)) {
            $response = preg_replace('/<img/i', '<img class="response-image"', $response);
        }

        return $response;
    }
}
