<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDepartamentRequest;
use App\Http\Requests\UpdateDepartamentRequest;
use App\Models\Company;
use App\Models\Departament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DepartamentController extends Controller
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
                'name' => 'Departamentos',
            ],
        ];

        $gates = [
            'create' => Gate::allows('departament.create'),
            'edit' => Gate::allows('departament.edit'),
            'destroy' => Gate::allows('departament.destroy'),
            'restore' => Gate::allows('departament.restore'),
            'companies' => Gate::allows('departament.companies'),
            'log_show' => Gate::allows('log.show'),
        ];

        $Companies = Company::when(!$gates['companies'], function($query) {
            $query->whereHas('users', function($query) {
                $query->where('id', auth()->id());
            });
        })->orderBy('name')->get();

        $data_filter = [
            [
                'type' => 'text',
                'label' => 'Departamento',
                'input_name' => 'name',
                'placeholder' => 'Informe o nome do departamento'
            ],
            [
                'type' => 'select',
                'label' => 'Empresa',
                'input_name' => 'company_id',
                'data' => $Companies,
            ],
        ];

        $Departaments = Departament::with('log', 'company')
        ->when($request->name, function($query) use ($request) {
            $query->where('name', 'LIKE', "%{$request->name}%");
        })
        ->when($request->company_id, function($query) use ($request) {
            $query->where('company_id', $request->company_id);
        })
        ->when($gates['restore'], function($query) {
            $query->withTrashed();
        })
        ->when(!$gates['companies'], function($query) {
            $query->whereHas('company.users', function($query) {
                $query->where('id', auth()->id());
            });
        })
        ->orderBy('name', 'ASC')
        ->get();

        return view('admin.departament.index', [
            'data_breadcrumbs' => $data_breadcrumbs,
            'data_filter' => $data_filter,
            'gates' => $gates,
            'Departaments' =>  $Departaments,
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
                'name' => 'Departamentos',
                'route' => 'admin.departament.index',
            ],
            [
                'name' => 'Cadastrar',
            ],
        ];

        $Companies = Company::when(!auth()->user()->can('departament.companies'), function($query) {
            $query->whereHas('users', function($query) {
                $query->where('id', auth()->id());
            });
        })->orderBy('name')->get();

        return view('admin.departament.create', [
            'data_breadcrumbs' => $data_breadcrumbs,
            'Companies' => $Companies,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreDepartamentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDepartamentRequest $request)
    {
        $data = $request->validated();

        if(!auth()->user()->can('departament.companies')) {
            if(!auth()->user()->companies->contains('id', $data['company_id'])) {
                return back()->withErrors(['name' => "Ocorreu um erro ao cadastrar o departamento. Tente novamente, se o erro persistir entre em contato com um administrador!"])->withInput();
            }
        }

        $Equals = Departament::where('name', $data['name'])->where('company_id', $data['company_id'])->withTrashed()->get();

        if(!$Equals->isEmpty()) {
            return back()->withErrors(['name' => "Já existe para essa empresa um departamento cadastrado com esse nome, porém ele está com status 'deletado'. Entre em contato com um administrador para restuarar esse departamento!"])->withInput();
        }

        Departament::create($data);

        return redirect()->route('admin.departament.create')->with('success', 'Departamento cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Departament  $Departament
     * @return \Illuminate\Http\Response
     */
    public function show(Departament $Departament)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Departament  $Departament
     * @return \Illuminate\Http\Response
     */
    public function edit(Departament $Departament)
    {
        $data_breadcrumbs = [
            [
                'name' => 'Departamentos',
                'route' => 'admin.departament.index',
            ],
            [
                'name' => 'Editar',
            ],
        ];

        $Companies = Company::when(!auth()->user()->can('departament.companies'), function($query) {
            $query->whereHas('users', function($query) {
                $query->where('id', auth()->id());
            });
        })->orderBy('name')->get();

        return view('admin.departament.edit', [
            'data_breadcrumbs' => $data_breadcrumbs,
            'Departament' => $Departament,
            'Companies' => $Companies,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDepartamentRequest  $request
     * @param  \App\Models\Departament  $Departament
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDepartamentRequest $request, Departament $Departament)
    {
        $data = $request->validated();

        if(!auth()->user()->can('departament.companies')) {
            if(!auth()->user()->companies->contains('id', $data['company_id'])) {
                return back()->withErrors(['name' => "Ocorreu um erro ao cadastrar o departamento. Tente novamente, se o erro persistir entre em contato com um administrador!"])->withInput();
            }
        }

        $Equals = Departament::where('id', '!=', $Departament->id)->where('company_id', $data['company_id'])->where('name', $data['name'])->withTrashed()->get();

        if(!$Equals->isEmpty()) {
            return back()->withErrors(['name' => "Já existe para essa empresa um departamento cadastrado com esse nome, porém ele está com status 'deletado'. Entre em contato com um administrador para restaurar esse departamento!"])->withInput();
        }

        $Departament->update($data);

        return back()->with('success', 'Departamento atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Departament  $Departament
     * @return \Illuminate\Http\Response
     */
    public function destroy(Departament $Departament)
    {
        $Departament->delete();

        return back()->with('success', 'Departamento deletado com sucesso!');
    }

    /**
     * Restore the specified resource to storage
     *
     * @param  int $Departament;
     * @return \Illuminate\Http\Response
     */
    public function restore(int $Departament)
    {
        $Departament = Departament::where('id', $Departament)->withTrashed()->first();

        if ($Departament->trashed()) {
            $Departament->restore();
        }

        return back()->with('success', 'Departamento restaurado com sucesso!');
    }
}
