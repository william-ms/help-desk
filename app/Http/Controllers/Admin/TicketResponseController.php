<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketResponseRequest;
use App\Models\Ticket;
use App\Models\TicketResponse;

class TicketResponseController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTicketRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTicketResponseRequest $request, Ticket $Ticket)
    {
        $data = $request->validated();

        $data['ticket_id'] = $Ticket->id;
        $data['user_id'] = auth()->id();
        $data['type'] = 1;

        TicketResponse::create($data);

        return back()->with('success', 'Ticket respondido com sucesso!');
    }
}
