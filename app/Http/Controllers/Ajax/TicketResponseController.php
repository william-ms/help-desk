<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketResponseRequest;
use App\Models\TicketResponse;
use Illuminate\Http\Request;

class TicketResponseController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTicketRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTicketResponseRequest $request)
    {
        $data = $request->validated();

        $data['user_id'] = auth()->id();
        $data['type'] = 1;

        $TicketResponse = TicketResponse::create($data);

        $new_response = view('ajax.ticket_response.new_response', [
            'TicketResponse' => $TicketResponse, 
        ])->render();

        return response()->json([
            'message' => 'Ticket respondido com sucesso!',
            'new_response' => $new_response,
        ]);
    }
}
