<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\User;
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
        $Logs = Log::with(['items'])->latest();


        // if (!empty($request->name)) {
        //     $Logs->where('user_id', $request->name);
        // }

        // if (!empty($request->model_type)) {
        //     $Logs->where('model_type', 'LIKE', '%'. $request->model_type .'%');
        // }

        // if (!empty($request->action)) {
        //     $Logs->where('action', 'LIKE', '%'. $request->action .'%');
        // }

        $Logs = $Logs->get();

        $data_filter = [
            [
                'type' => 'select',
                'label' => 'Executante',
                'input_name' => 'user_id',
                'data' => User::get(),
                'indice' => 'name',
            ],
            [
                'type' => 'text',
                'label' => 'Módulo',
                'input_name' => 'model_type',
            ],
            [
                'type' => 'text',
                'label' => 'Ação',
                'input_name' => 'action',
            ],
        ];

        return view('admin.log.index', [
            'Logs' => $Logs,
            'data_filter' => $data_filter
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

        return view('admin.log.show', [
            'Log' => $Log
        ]);
    }
}
