<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubcategoryRequest;
use App\Http\Requests\UpdateSubcategoryRequest;
use App\Models\Category;
use App\Models\Company;
use App\Models\Departament;
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
                'input_name' => 'category',
                'data' => Category::select('name')->distinct()->get(),
                'field_key' => 'name',
            ],
            [
                'type' => 'select',
                'label' => 'Departamento',
                'input_name' => 'departament',
                'data' => Departament::get(),
            ],
            [
                'type' => 'select',
                'label' => 'Empresa',
                'input_name' => 'company',
                'data' => Company::get(),
            ],
        ];

        $gates = [
            'create' => Gate::allows('subcategory.create'),
            'edit' => Gate::allows('subcategory.edit'),
            'destroy' => Gate::allows('subcategory.destroy'),
            'restore' => Gate::allows('subcategory.restore'),
            'companies' => Gate::allows('subcategory.companies'),
            'departaments' => Gate::allows('subcategory.departaments'),
            'log_show' => Gate::allows('log.show'),
        ];

        $Subcategories = Subcategory::with(['log', 'category' => function($query) {
            $query->with('departament', 'company');
        }])
        ->when($request->name, function($query) use ($request) {
            $query->where('name', 'LIKE', "%{$request->name}%");
        })
        ->when($request->category, function($query) use ($request) {
            $query->whereHas('category', function($query) use ($request) {
                $query->where('name', $request->category);
            });
        })
        ->when($request->departament, function($query) use ($request) {
            $query->whereHas('category.departament', function ($query) use ($request) {
                $query->where('id', $request->departament);
            });
        })
        ->when($request->company, function($query) use ($request) {
            $query->whereHas('category.company', function ($query) use ($request) {
                $query->where('id', $request->company);
            });
        })
        ->when(!$gates['companies'], function($query) {
            $query->whereHas('category.company.users', function($query) {
                $query->where('id', auth()->id());
            });
        })
        ->when(!$gates['departaments'], function($query) {
            $query->whereHas('category.departament.users', function($query) {
                $query->where('id', auth()->id());
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

        $Categories = Category::whereIn('company_id', $Companies->pluck('id'))->whereIn('departament_id', $Departaments->pluck('id'))->get();
        
        return view('admin.subcategory.create', [
            'data_breadcrumbs' => $data_breadcrumbs,
            'Companies' => $Companies,
            'Departaments' => $Departaments,
            'Categories' => $Categories,
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

        if(!empty($data['automatic_response'])) {
            $data['automatic_response'] = $this->format_response($data['automatic_response']);
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

        $Categories = Category::whereIn('company_id', $Companies->pluck('id'))->whereIn('departament_id', $Departaments->pluck('id'))->get();

        return view('admin.subcategory.edit', [
            'data_breadcrumbs' => $data_breadcrumbs,
            'Subcategory' => $Subcategory,
            'Companies' => $Companies,
            'Departaments' => $Departaments,
            'Categories' => $Categories,
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

        if(!empty($data['automatic_response'])) {
            $data['automatic_response'] = $this->format_response($data['automatic_response']);
        }

        $Subcategory->update($data);

        return back()->with('success', 'Subcategoria atualizada com sucesso!');
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

        return back()->with('success', 'Subcategoria deletada com sucesso!');
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

        return back()->with('success', 'Subcategoria restaurada com sucesso!');
    }

    private function format_response(string $response) {

        if (preg_match('/(<img[^>]+>)/i', $response)) {
            $response = preg_replace('/<img/i', '<img class="response-image"', $response);
        }

        return $response;
    }
}
