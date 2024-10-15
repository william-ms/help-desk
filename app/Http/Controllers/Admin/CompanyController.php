<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $Companies = Company::with('log')
        ->when($request->name, function($query) use ($request) {
            $query->where('name', 'LIKE', "%{$request->name}%");
        })
        ->when(auth()->user()->can('company.restore'), function($query) {
            $query->withTrashed();
        })
        ->orderBy('name', 'ASC')
        ->get();

        $data_filter = [
            [
                'type' => 'text',
                'label' => 'Empresa',
                'input_name' => 'name',
                'placeholder' => 'Informe o nome da empresa'
            ],
        ];

        $data_breadcrumbs = [
            [
                'name' => 'Empresas',
            ],
        ];

        return view('admin.company.index', [
            'Companies' =>  $Companies,
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
                'name' => 'Empresas',
                'route' => 'admin.company.index',
            ],
            [
                'name' => 'Cadastrar',
            ],
        ];

        return view('admin.company.create', [
            'data_breadcrumbs' => $data_breadcrumbs,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCompanyRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCompanyRequest $request)
    {
        $data = $request->validated();

        $Equals = Company::where('name', $data['name'])->withTrashed()->get();

        if(!$Equals->isEmpty()) {
            return back()->withErrors(['name' => "Já existe uma empresa cadastrada com esse nome, porém ela está com status 'deletado'. Entre em contato com um administrador para restaurar essa empresa!"])->withInput();
        }

        Company::create($data);

        return redirect()->route('admin.company.create')->with('success', 'Empresa cadastrada com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company  $Company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $Company)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Company  $Company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $Company)
    {
        $data_breadcrumbs = [
            [
                'name' => 'Empresas',
                'route' => 'admin.company.index',
            ],
            [
                'name' => 'Editar',
            ],
        ];

        return view('admin.company.edit', [
            'Company' => $Company,
            'data_breadcrumbs' => $data_breadcrumbs
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCompanyRequest  $request
     * @param  \App\Models\Company  $Company
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCompanyRequest $request, Company $Company)
    {
        $data = $request->validated();

        $Equals = Company::where('id', '!=', $Company->id)->where('name', $data['name'])->withTrashed()->get();

        if(!$Equals->isEmpty()) {
            return back()->withErrors(['name' => "Já existe uma empresa cadastrada com esse nome, porém ela está com status 'deletado'. Entre em contato com um administrador para restaurar essa empresa!"])->withInput();
        }

        $Company->update($data);

        return back()->with('success', 'Empresa atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $Company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $Company)
    {
        $Company->delete();

        return back()->with('success', 'Empresa deletada com sucesso!');
    }

    /**
     * Restore the specified resource to storage
     *
     * @param  int $Company;
     * @return \Illuminate\Http\Response
     */
    public function restore(int $Company)
    {
        $Company = Company::where('id', $Company)->withTrashed()->first();

        if ($Company->trashed()) {
            $Company->restore();
        }

        return back()->with('success', 'Empresa restaurada com sucesso!');
    }
}
