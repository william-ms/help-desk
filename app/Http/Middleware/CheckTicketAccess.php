<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckTicketAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $User = auth()->user();
        $Ticket = $request->route('ticket');

        // Se o usuário logado é um super admin
        if($User->roles->contains('id', 1)) {
            return $next($request);
        }

        if($Ticket->status === 7) {
            return abort(403);
        }

        // Verifica se o usuário logado é o mesmo usuário que abriu o ticket
        if($Ticket->requester_id === $User->id) {
            return $next($request);
        }

        // Verifica se o usuário logado pertence a mesma empresa e departamento do ticket
        if(!$User->companies->contains('id', $Ticket->company_id) || !$User->departaments->contains('id', $Ticket->departament_id) ) {
            return abort(403);
        }

        // Verifica se o ticket está atribuido à um usuário e se esse usuário é diferente do usuádio logado
        if(!empty($Ticket->assignee_id) && $Ticket->assignee_id !== $User->id && $Ticket->transfer_assignee_id !== $User->id ) {
            return abort(403);
        }

        return $next($request);
    }
}
