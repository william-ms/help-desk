<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $Logs = Log::with(['items'])
        ->when($request->model_type, function($query) use ($request) {
            $query->where('model_type', $request->model_type);
        })
        ->when($request->model_id, function($query) use ($request) {
            $query->where('model_id', $request->model_id);
        })
        ->when($request->model_name, function($query) use ($request) {
            $query->where('model_name', 'LIKE', "%{$request->model_name}%");
        })
        ->latest()
        ->get();

        $data_filter = [
            [
                'type' => 'select',
                'label' => 'Módulo',
                'input_name' => 'model_type',
                'data' => Log::select('model_type')->distinct()->get(),
                'field_key' => 'model_type',
                'field_value' => 'model_type'
            ],
            [
                'type' => 'text',
                'label' => 'ID do módulo',
                'input_name' => 'model_id',
                'placeholder' => 'Informe o id do módulo'
            ],
            [
                'type' => 'text',
                'label' => 'Nome do módulo',
                'input_name' => 'model_name',
                'placeholder' => 'Informe o nome do módulo'
            ],
        ];

        $data_breadcrumbs = [
            [
                'name' => 'Logs',
            ],
        ];

        return view('admin.log.index', [
            'Logs' => $Logs,
            'data_filter' => $data_filter,
            'data_breadcrumbs' => $data_breadcrumbs
        ]);
    }

        /**
     * Display the specified resource.
     *
     * @param  \App\Models\Log  $Log
     * @return \Illuminate\Http\Response
     */
    public function show(Log $Log)
    {
        $Log->load('items');

        $data_breadcrumbs = [
            [
                'name' => 'Logs',
                'route' => 'admin.log.index',
            ],
            [
                'name' => 'Log',
            ],
        ];

        return view('admin.log.show', [
            'Log' => $Log,
            'data_breadcrumbs' => $data_breadcrumbs
        ]);
    }
}
