<?php

namespace App\Http\Controllers\Ajax;

use App\Events\NewTicketResponse;
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

        broadcast(new NewTicketResponse($TicketResponse));

        $new_response = view('ajax.ticket_response.new_response', [
            'TicketResponse' => $TicketResponse, 
        ])->render();

        return response()->json([
            'message' => 'Ticket respondido com sucesso!',
            'new_response' => $new_response,
        ]);
    }

    public function check_new_response(Request $request, TicketResponse $TicketResponse)
    {        
        $new_response = view('ajax.ticket_response.new_response', [
            'TicketResponse' => $TicketResponse, 
        ])->render();

        return response()->json([
            'message' => 'Ticket respondido com sucesso!',
            'new_response' => $new_response,
        ]);
    }
}
