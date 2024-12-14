<?php

namespace App\Http\Controllers\Ajax;

use App\Events\NewNotification;
use App\Events\NewTicketResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketResponseRequest;
use App\Models\Notification;
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

        // Cadastrando notificação da nova resposta
        $Ticket = $TicketResponse->ticket;

        $data_notification = [
            'notifier_id' => auth()->id(),
            'model_type' => 'App\Models\Ticket',
            'model_id' => $Ticket->id,
            'message' => '<p><b>'. auth()->user()->name .'</b> respondeu o ticket #'. substr($Ticket->uuid, -6) .'</p><div class="bg-light-info p-2 rounded overflow-hidden" style="max-height: 100px;">'. $TicketResponse->response. '</div>',
        ];

        if(auth()->id() == $Ticket->requester_id && !empty($Ticket->assignee_id)) {
            $data_notification['notified_id'] = $Ticket->assignee_id;
            $Notification = Notification::create($data_notification);
        }

        if(auth()->id() == $Ticket->assignee_id) {
            $data_notification['notified_id'] = $Ticket->requester_id;
            $Notification = Notification::create($data_notification);
        }

        if(!empty($Notification)) {
            broadcast(new NewNotification($Notification));
        }

        // Criando a view da nova resposta
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
