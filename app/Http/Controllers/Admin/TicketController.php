<?php

namespace App\Http\Controllers\Admin;

use App\Events\NewNotification;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\Category;
use App\Models\Company;
use App\Models\Departament;
use App\Models\Notification;
use App\Models\Subcategory;
use App\Models\Ticket;
use App\Models\TicketResponse;
use App\Models\User;
use App\Traits\TicketTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Ramsey\Uuid\Uuid;

class TicketController extends Controller
{
    use TicketTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data_breadcrumbs = [
            [
                'name' => 'Tickets',
            ],
        ];

        $gates = [
            'create' => Gate::allows('ticket.create'),
            'show' => Gate::allows('ticket.show'),
            'log_show' => Gate::allows('log.show'),
        ];

        $RoleUser = auth()->user()->roles->first()->id;

        $Tickets = Ticket::with(['requester', 'assignee', 'departament', 'company', 'last_user_response' => function($query) {
            $query->with('user');
        }]);

        if($RoleUser !== 1) {
            $Tickets = $Tickets->where('status', '!=', 7);

            if($RoleUser === 2) {
                $Tickets = $Tickets->where('requester_id', auth()->id());
            } else {
                $Tickets = $Tickets->whereIn('company_id', auth()->user()->companies->pluck('id'))
                ->whereIn('departament_id', auth()->user()->departaments->pluck('id'))
                ->where(function($query) {
                    $query->where(function($query) {
                        $query->where('assignee_id', auth()->id())->orWhere('transfer_assignee_id', auth()->id());
                    })->orWhere(function($query) {
                        $query->where('status', 1)->whereNull('assignee_id');
                    });
                });
            }
        } 

        $Tickets = $Tickets->orderBy('updated_at', 'DESC')
        ->paginate(10);

        return view('admin.ticket.index', [
            'data_breadcrumbs' => $data_breadcrumbs,
            'gates' => $gates,
            'Tickets' =>  $Tickets,
            'TicketStatus' => $this->TicketStatus,
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
                'name' => 'Tickets',
                'route' => 'admin.ticket.index',
            ],
            [
                'name' => 'Cadastrar',
            ],
        ];

        $Companies = Company::when(!auth()->user()->can('ticket.companies'), function($query) {
            $query->whereHas('users', function($query) {
                $query->where('id', auth()->id());
            });
        })
        ->get();
        
        $Departaments = Departament::when(!auth()->user()->can('ticket.departaments'), function($query) {
            $query->whereHas('users', function($query) {
                $query->where('id', auth()->id());
            });
        })
        ->get();

        $Categories = Category::whereIn('company_id', $Companies->pluck('id'))->whereIn('departament_id', $Departaments->pluck('id'))->get();

        $Subcategories = Subcategory::whereIn('category_id', $Categories->pluck('id'))->get();

        return view('admin.ticket.create', [
            'data_breadcrumbs' => $data_breadcrumbs,
            'Companies' => $Companies,
            'Departaments' => $Departaments,
            'Categories' => $Categories,
            'Subcategories' => $Subcategories,
        ]);
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTicketRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTicketRequest $request)
    {
        //Cadastrando o ticket
        $data = $request->validated();
        $data['uuid'] = (string) Uuid::uuid4();
        $data['requester_id'] = auth()->id();
        $data['status'] = 1;    //Aberto
        $data['action'] = 1;    //Usuário cadastrou o ticket

        $Ticket = Ticket::create($data);

        // Cadastrando resposta automática da categoria
        if (!empty($Ticket->category->automatic_response)) {
            TicketResponse::create([
                'type' => 1,    //Mensagem
                'ticket_id' => $Ticket->id,
                'response' => $Ticket->category->automatic_response,
            ]);
        }

        // Cadastrando resposta automática da subcategoria
        if (!empty($Ticket->subcategory->automatic_response)) {
            TicketResponse::create([
                'type' => 1,    //Mensagem
                'ticket_id' => $Ticket->id,
                'response' => $Ticket->subcategory->automatic_response,
            ]);
        }

        // Cadastrando resposta do usuário
        TicketResponse::create([
            'type' => 1,    //Mensagem
            'ticket_id' => $Ticket->id,
            'user_id' => auth()->id(),
            'response' => $request->description,
        ]);

        return redirect()->route('admin.ticket.show', $Ticket->id)->with('success', 'Ticket cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ticket  $Ticket
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $Ticket)
    {
        session()->put(['ticket_id' => $Ticket->id]);

        $data_breadcrumbs = [
            [
                'name' => 'Tickets',
                'route' => 'admin.ticket.index',
            ],
            [
                'name' => 'Ticket',
            ],
        ];

        if($Ticket->requester_id !== auth()->id()) {
            $Users = User::where('id', '!=', auth()->id())
            ->where('id', '!=', $Ticket->requester_id)
            ->whereDoesntHave('roles', function($query) {
                $query->where('id', 2);
            })->whereHas('companies', function($query) use ($Ticket) {
                $query->where('id', $Ticket->company_id);
            })->whereHas('departaments', function($query) use ($Ticket) {
                $query->where('id', $Ticket->departament_id);
            })->get();
        }

        $Responses = $Ticket->responses()->with('user')->get();

        return view('admin.ticket.show', [
            'data_breadcrumbs' => $data_breadcrumbs,
            'Ticket' => $Ticket,
            'Responses' => $Responses,
            'Users' => $Users ?? null,
        ]);
    }

    public function update(UpdateTicketRequest $request, Ticket $Ticket)
    {
        $data = $request->validated();

        if ($data['type'] === 'accept') {
            $Ticket->update(['assignee_id' => auth()->id()]);
            $alert = 'Ticket aceito com sucesso!';

            $Notification = Notification::create([
                'notified_id' => $Ticket->requester_id,
                'notifier_id' => auth()->id(),
                'model_type' => 'App\Models\Ticket',
                'model_id' => $Ticket->id,
                'message' => '<p><b>'. auth()->user()->name .'</b> aceitou o ticket #'. substr($Ticket->uuid, -6) .'</p>',
            ]);

        } else if ($data['type'] === 'transfer') {
            $Ticket->update(['status' => 2, 'transfer_assignee_id' => $data['transfer_assignee_id']]);
            $alert = 'Solicitação de transferência realizada com sucesso!';

            $Notification = Notification::create([
                'notified_id' => $Ticket->transfer_assignee_id,
                'notifier_id' => auth()->id(),
                'model_type' => 'App\Models\Ticket',
                'model_id' => $Ticket->id,
                'message' => '<p><b>'. auth()->user()->name .'</b> solicitou a transferência do ticket #'. substr($Ticket->uuid, -6) .'</p>',
            ]);
        }else if ($data['type'] === 'cancel_transfer') {
            $Notification = Notification::create([
                'notified_id' => $Ticket->transfer_assignee_id,
                'notifier_id' => auth()->id(),
                'model_type' => 'App\Models\Ticket',
                'model_id' => $Ticket->id,
                'message' => '<p><b>'. auth()->user()->name .'</b> cancelou a transferência do ticket #'. substr($Ticket->uuid, -6) .'</p>',
            ]);

            $Ticket->update(['status' => 1, 'transfer_assignee_id' => null]);
            $alert = 'Transferência do ticket cancelada com sucesso!';
        } else if ($data['type'] === 'accept_transfer') {
            $Notification = Notification::create([
                'notified_id' => $Ticket->assignee_id,
                'notifier_id' => auth()->id(),
                'model_type' => 'App\Models\Ticket',
                'model_id' => $Ticket->id,
                'message' => '<p><b>'. auth()->user()->name .'</b> aceitou a transferência do ticket #'. substr($Ticket->uuid, -6) .'</p>',
            ]);

            $Notification = Notification::create([
                'notified_id' => $Ticket->requester_id,
                'notifier_id' => auth()->id(),
                'model_type' => 'App\Models\Ticket',
                'model_id' => $Ticket->id,
                'message' => '<p><b>'. auth()->user()->name .'</b> aceitou o ticket #'. substr($Ticket->uuid, -6) .'</p>',
            ]);

            $Ticket->update(['status' => 1, 'assignee_id' => auth()->id(), 'transfer_assignee_id' => null]);
            $alert = 'Transferência do ticket aceita com sucesso!';
        } else if($data['type'] === 'resolve') {
            $Ticket->update(['status' => 3]);
            $alert = 'Ticket resolvido com sucesso!';

            $Notification = Notification::create([
                'notified_id' => $Ticket->requester_id,
                'notifier_id' => auth()->id(),
                'model_type' => 'App\Models\Ticket',
                'model_id' => $Ticket->id,
                'message' => '<p><b>'. auth()->user()->name .'</b> resolveu o ticket #'. substr($Ticket->uuid, -6) .'</p>',
            ]);
        } else if($data['type'] === 'finish') {
            $Ticket->update(['status' => 4]);
            $alert = 'Ticket finalizado com sucesso!';

            $Notification = Notification::create([
                'notified_id' => $Ticket->assignee_id,
                'notifier_id' => auth()->id(),
                'model_type' => 'App\Models\Ticket',
                'model_id' => $Ticket->id,
                'message' => '<p><b>'. auth()->user()->name .'</b> finalizou o ticket #'. substr($Ticket->uuid, -6) .'</p>',
            ]);
        } else if ($data['type'] === 'cancel') {
            $Ticket->update(['status' => 7]);
            $alert = 'Ticket cancelado com sucesso!';
        }

        // Enviando notificação
        broadcast(new NewNotification($Notification));

        return redirect()->route('admin.ticket.show', $Ticket->id)->with('success', $alert);
    }

    public function get_collections() {

        $gates= [
            'companies' => auth()->user()->can('subcategory.companies'),
            'departaments' => auth()->user()->can('subcategory.departaments'),
        ];

        $Companies = Company::query();

        if(!$gates['companies'] && !$gates['departaments']) {
            $Companies->whereHas('users', function ($query) {
                $query->where('users.id', auth()->id());
            })->whereHas('departaments.users', function ($query) {
                $query->where('users.id', auth()->id());
            })->with(['departaments' => function($query) {
                $query->whereHas('users', function ($query) {
                    $query->where('users.id', auth()->id());
                })->whereHas('categories')->with('categories');
            }]);

        } else if($gates['companies'] && !$gates['departaments']) {
            $Companies->whereHas('departaments', function ($query) {
                $query->whereIn('name', auth()->user()->departaments->pluck('name'));
            })->with(['departaments' => function($query) {
                $query->whereIn('name', auth()->user()->departaments->pluck('name'))->with('categories');
            }]);

        } else if(!$gates['companies'] && $gates['departaments']) {
            $Companies->whereHas('users', function ($query) {
                $query->where('users.id', auth()->id());
            })->with(['departaments' => function($query) {
                $query->whereHas('categories')->with('categories');
            }]);

        } else {
            $Companies->with(['departaments' => function($query) {
                $query->whereHas('categories')->with('categories');
            }]);
        }

        $Companies = $Companies->get();

        $Departaments = $Companies->flatMap(function ($Company) {
            return $Company->departaments;
        });
        
        $Categories = $Departaments->flatMap(function ($Departament) {
            return $Departament->categories;
        });

        $Subcategories = $Categories->flatMap(function ($Category) {
            return $Category->subcategories;
        });

        return [$Companies, $Departaments, $Categories, $Subcategories];
    }
}
