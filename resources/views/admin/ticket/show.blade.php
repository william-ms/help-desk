@extends('admin.base')

@section('content')
    <section class="pc-container">
        <div class="pc-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <x-breadcrumb :breadcrumbs="$data_breadcrumbs" />

                        {{-- <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">Ticket (#{{ substr($Ticket->uuid, -6) }})</h2>
                            </div>
                        </div> --}}
                    </div>
                </div><!-- page-block -->
            </div><!-- page-header -->

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5>
                                    <i class="ph-duotone ph-ticket"></i>
                                    Ticket #{{ substr($Ticket->uuid, -6) }}
                                </h5>

                                <div>
                                    @if (auth()->id() == $Ticket->requester_id)
                                        @if ($Ticket->status !== 4)
                                            {{-- [button] - Responder --}}
                                            <x-button icon="ti ti-file-plus" color="light-success" data-bs-toggle="modal" data-bs-target="#reply">Responder</x-button>
                                        @endif

                                        @if (empty($Ticket->assignee))
                                            {{-- [button] - Cancelar --}}
                                            <x-button id="btn-cancel" icon="ti ti-file-x" color="light-danger">Cancelar</x-button>
                                        @endif

                                        @if ($Ticket->status === 3)
                                            {{-- [button] - Finalizar --}}
                                            <x-button id="btn-finish" icon="ti ti-file-check" color="light-danger">Finalizar</x-button>
                                        @endif
                                    @else
                                        @if (empty($Ticket->assignee_id))
                                            {{-- [button] - Aceitar --}}
                                            <x-button id="btn-accept" icon="ti ti-file-download" color="light-info">Aceitar</x-button>
                                        @endif

                                        @if ($Ticket->status === 1 && $Ticket->assignee_id === auth()->id())
                                            {{-- [button] - Responder --}}
                                            <x-button icon="ti ti-file-plus" color="light-success" data-bs-toggle="modal" data-bs-target="#reply">Responder</x-button>

                                            {{-- [button] - Transferir --}}
                                            <x-button id="btn-transfer" icon="ti ti-file-export" color="light-warning">Transferir</x-button>

                                            {{-- [button] - Marcar como resolvido --}}
                                            <x-button id="btn-resolve" icon="ti ti-file-check" color="light-danger">Marcar como resolvido</x-button>
                                        @endif

                                        @if ($Ticket->status === 2 && auth()->id() === $Ticket->assignee_id)
                                            {{-- [button] - Cancelar transferência --}}
                                            <x-button id="btn-cancel-transfer" icon="ti ti-file-import" color="light-danger">Cancelar transferência</x-button>
                                        @endif

                                        @if ($Ticket->status === 2 && auth()->id() === $Ticket->transfer_assignee_id)
                                            {{-- [button] - Aceitar transferência --}}
                                            <x-button id="btn-accept-transfer" icon="ti ti-file-import" color="light-info">Aceitar transferência</x-button>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="card-body border-bottom py-2">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h4 class="d-inline-block mb-0">{{ $Ticket->subject }}</h4>
                                </div>
                            </div>
                        </div>

                        <div id="ticket-responses">
                            @foreach ($Responses as $Response)
                                @if (!empty($Response->user))
                                    {{-- Resposta do usuáio --}}
                                    <div class="border-bottom card-body {{ $loop->last ? 'last-response' : '' }}">
                                        <div class="row">
                                            <div class="col-sm-auto mb-3 mb-sm-0">
                                                <div class="d-sm-inline-block d-flex align-items-center">
                                                    <img class="wid-60 img-radius mb-2" src="{{ $Response->user->profile_image }}" alt="Generic placeholder image " />
                                                </div>
                                            </div>

                                            <div class="col">
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="">
                                                            <h4 class="d-inline-block">{{ $Response->user_id == auth()->id() ? 'Você' : $Response->user->name }}</h4>
                                                            <span class="badge bg-light-secondary">replied</span>
                                                            <p class="text-muted">
                                                                {{ calculate_elapsed_time($Response->created_at) . ($Response->created_at->dayOfWeek > 0 && $Response->created_at->dayOfWeek < 6 ? ', na ' : ', no ') . $Response->created_at->dayName . ' às ' . $Response->created_at->format('H:m') }}
                                                            </p>
                                                        </div>
                                                    </div>

                                                    {{-- @if ($Response->user_id == auth()->id())
                                                        <div class="col-auto">
                                                            <ul class="list-unstyled mb-0">
                                                                <li class="d-inline-block f-20 me-1">
                                                                    <a href="#" data-bs-toggle="tooltip" title="Edit">
                                                                        <i data-feather="edit" class="icon-svg-success wid-20"></i>
                                                                    </a>
                                                                </li>
                                                                <li class="d-inline-block f-20">
                                                                    <a href="#" data-bs-toggle="tooltip" title="Delete">
                                                                        <i data-feather="trash-2" class="icon-svg-danger wid-20"></i>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    @endif --}}
                                                </div>

                                                <div class="">
                                                    {!! $Response->response !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    {{-- Resposta automática --}}
                                    <div class="border-bottom card-body">
                                        <div class="row">
                                            <div class="col d-flex flex-column gap-2">
                                                <div class="bg-light-info p-2 rounded automatic-response overflow-hidden">
                                                    {!! $Response->response !!}
                                                </div>

                                                <x-button class="align-self-center btn-automatic-response d-none" icon="ti ti-chevrons-down" color="info"></x-button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div><!-- ticket-responses -->
                    </div><!-- card -->
                </div>

                {{-- [modal] - Responser --}}
                <div id="reply" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="row my-3 align-items-center">
                                    <div class="col-12">
                                        <textarea class="form-control" id="response" rows="10" name="response" placeholder="Informe uma resposta"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <x-button id="btn-close-reply" icon="ti ti-x" color="danger" data-bs-dismiss="modal">Cancelar</x-button>
                                <x-button id="btn-reply" icon="ti ti-checks" color="info">Enviar</x-button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- [modal] - Imagem da resposta --}}
                <div id="show-response-image" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header justify-content-end">
                                <x-button id="btn-close-reply" icon="ti ti-x" color="danger" data-bs-dismiss="modal"></x-button>
                            </div>

                            <div class="modal-body">
                                <img src="" class="w-100" />
                            </div>
                        </div>
                    </div>
                </div><!-- show-response-image -->

                {{-- Detalhes do ticket --}}
                <div class="col-lg-4 ">
                    <div class="card">
                        <div class="card-header">
                            <h5>Detalhes do ticket</h5>
                        </div>

                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <label class="mb-0 wid-100">Solicitante</label>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="mb-0"><img src="{{ $Ticket->requester->profile_image }}" alt="" class="wid-20 rounded me-1 img-fluid" /><a href="#" class="link-secondary">{{ $Ticket->requester->name }}</a></p>
                                    </div>
                                </div>
                            </li>

                            <li class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <label class="mb-0 wid-100">Contato</label>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="mb-0"><i class="feather icon-mail me-1"></i><a href="#" class="link-secondary">{{ $Ticket->requester->email }}</a></p>
                                    </div>
                                </div>
                            </li>

                            <li class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <label class="mb-0 wid-100">Empresa</label>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="mb-0"><i class="ph-duotone ph-buildings me-1"></i>{{ $Ticket->company->name }}</a></p>
                                    </div>
                                </div>
                            </li>

                            <li class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <label class="mb-0 wid-100">Departamento</label>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="mb-0"><i class="ph-duotone ph-archive me-1"></i>{{ $Ticket->departament->name }}</a></p>
                                    </div>
                                </div>
                            </li>

                            <li class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <label class="mb-0 wid-100">Categoria</label>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="mb-0"><i class="ph-duotone ph-list me-1"></i>{{ $Ticket->category->name }}</a></p>
                                    </div>
                                </div>
                            </li>

                            @if (!empty($Ticket->subcategory))
                                <li class="list-group-item">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <label class="mb-0 wid-100">Subcategoria</label>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <p class="mb-0"><i class="ph-duotone ph-list me-1"></i>{{ $Ticket->subcategory->name }}</a></p>
                                        </div>
                                    </div>
                                </li>
                            @endif

                            @if (!empty($Ticket->assigned))
                                <li class="list-group-item">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <label class="mb-0 wid-100">Aceito por</label>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <p class="mb-0"><img src="{{ $Ticket->assigned->profile_image }}" alt="" class="wid-20 rounded me-1 img-fluid" /><a href="#" class="link-secondary">{{ $Ticket->assigned->name }}</a></p>
                                        </div>
                                    </div>
                                </li>
                            @endif

                            <li class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <label class="mb-0 wid-100">Criado em</label>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="mb-0"><i class="feather icon-calendar me-1"></i><label class="mb-0">{{ $Ticket->created_at->format('d/m/Y') . ', às ' . $Ticket->created_at->format('H:m') }}</label></p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.ticket.update', $Ticket->id) }}" method="POST" id="form-update">
                @csrf
                @method('PUT')
            </form>
        </div><!-- pc-content -->
    </section><!-- pc-container -->
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/bootstrap-select.css') }}">

    <style>
        .form-control button {
            background-color: #ffffff !important;
            border: 1px solid #DBE0E5;
        }

        .form-control button[aria-expanded="true"] {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 2px rgba(var(--bs-primary-rgb), 0.2);
            outline: 0;
        }

        .form-control button .filter-option {
            color: #5B6B79;
        }

        .retract {
            max-height: 300px;
        }

        .error-toast .swal2-title {
            color: var(--bs-danger);
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/plugins/bootstrap-select.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/sweetalert2.all.min.js') }}"></script>

    <x-tinymce-config textarea="#response" height="300" />

    <script type="text/javascript">
        $(document).ready(function() {
            const ticket_id = {{ $Ticket->id }};
            var response = '';

            const form_update = $('#form-update');
            const div_ticket_responses = $('#ticket-responses');
            const btn_close_reply = $('#btn-close-reply');
            const btn_accept = $('#btn-accept');
            const btn_transfer = $('#btn-transfer');
            const btn_cancel_transfer = $('#btn-cancel-transfer');
            const btn_accept_transfer = $('#btn-accept-transfer');
            const btn_resolve = $('#btn-resolve');
            const btn_finish = $('#btn-finish');
            const btn_cancel = $('#btn-cancel');

            let i_status = null;
            let i_type = null;

            {{-- TODO: Passar para um componente --}}
            //::::::::::::::::::::::::::::::::::::::::::: POPPUP DE ALERTA ::::::::::::::::::::::::::::::::::::::::::://
            @if (session()->has('success'))
                Swal.fire({
                    toast: true,
                    backdrop: false,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    icon: 'success',
                    title: 'Sucesso',
                    text: "{{ session('success') }}",
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });
            @endif

            @if ($errors->any())
                Swal.fire({
                    toast: true,
                    backdrop: false,
                    position: 'top-end',
                    showConfirmButton: false,
                    timerProgressBar: true,
                    icon: 'error',
                    title: 'Erro',
                    html: `
                        <div>
                            @foreach ($errors->all() as $error)
                                {{ $error }}<br />
                            @endforeach
                        </div>
                    `,
                    customClass: {
                        popup: 'error-toast',
                    },
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });
            @endif

            //::::::::::::::::::::::::::::::::::::::::::: RESPONDER TICKET ::::::::::::::::::::::::::::::::::::::::::://
            $('#btn-reply').on('click', function() {
                tinyMCE.triggerSave();
                btn_close_reply.click();
                response = $('#response').val();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                let posting = $.post("{{ route('ajax.ticket_response.store') }}", {
                    ticket_id,
                    response,
                }, "json");

                posting.done(function(data) {
                    $('.last-response').removeClass('last-response');
                    div_ticket_responses.append(data.new_response);

                    tinymce.get('response').setContent('');

                    $('html, body').animate({
                        scrollTop: $('.last-response').offset().top
                    }, 'slow');
                });

                posting.fail(function(data) {
                    console.log(data, data.responseJSON.errors.ticket_id[0]);
                });
            });

            //:::::::::::::::::::::::::::::::::::::::::::: ACEITAR TICKET :::::::::::::::::::::::::::::::::::::::::::://
            btn_accept.on('click', function() {
                Swal.fire({
                    icon: 'warning',
                    title: 'Atenção!!',
                    text: 'Deseja aceitar esse ticket?',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    confirmButtonText: '<i class="ti ti-upload"></i> Sim',
                    cancelButtonColor: '#f06548',
                    cancelButtonText: '<i class="ti ti-x"></i> Não',
                }).then((result) => {
                    if (result.isConfirmed) {
                        i_type = $('<input type="text" name="type" value="accept">');
                        form_update.append(i_type).submit();
                    }
                })
            });

            //:::::::::::::::::::::::::::::::::::::::::: TRANSFERIR TICKET ::::::::::::::::::::::::::::::::::::::::::://
            @if ($Ticket->requester_id !== auth()->id())
                btn_transfer.on('click', function() {
                    let Users = @json(
                        $Users->map(function ($user) {
                            return [
                                'id' => $user->id,
                                'name' => $user->name,
                            ];
                        }));
                    let options = '';

                    Users.forEach(User => {
                        options += `<option value="${User.id}">${User.name}</option>`
                    })

                    Swal.fire({
                        icon: 'warning',
                        title: 'Transferir ticket!!',
                        html: `
                        <select class="form-control" id="transfer_assignee_id" data-live-search="true">
                            <option value="">Selecione um técnico</option>` + options + `
                        </select>
                        `,
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        confirmButtonText: '<i class="ti ti-upload"></i> Transferir',
                        cancelButtonColor: '#f06548',
                        cancelButtonText: '<i class="ti ti-x"></i> Cancelar',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            let transfer_assignee_id = $('#transfer_assignee_id').val();

                            if (transfer_assignee_id == '') {
                                return;
                            }

                            i_type = $('<input type="text" name="type" value="transfer" hidden>');
                            i_transfer_assignee_id = $(`<input type="number" name="transfer_assignee_id" value="${transfer_assignee_id}" hidden>`);
                            form_update.append(i_type).append(i_transfer_assignee_id).submit();
                        }
                    })
                });
            @endif

            //::::::::::::::::::::::::::::::::::: CANCELAR TRANSFERÊNCIA DO TICKET ::::::::::::::::::::::::::::::::::://
            btn_cancel_transfer.on('click', function() {
                Swal.fire({
                    icon: 'warning',
                    title: 'Atenção!!',
                    text: 'Deseja cancelar a tranferência desse ticket?',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    confirmButtonText: '<i class="ti ti-upload"></i> Sim',
                    cancelButtonColor: '#f06548',
                    cancelButtonText: '<i class="ti ti-x"></i> Não',
                }).then((result) => {
                    if (result.isConfirmed) {
                        i_type = $('<input type="text" name="type" value="cancel_transfer">');
                        form_update.append(i_type).submit();
                    }
                })
            });

            //::::::::::::::::::::::::::::::::::: ACEITAR TRANSFERÊNCIA DO TICKET :::::::::::::::::::::::::::::::::::://
            btn_accept_transfer.on('click', function() {
                Swal.fire({
                    icon: 'warning',
                    title: 'Atenção!!',
                    text: 'Deseja aceitar a transferência desse ticket?',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    confirmButtonText: '<i class="ti ti-upload"></i> Sim',
                    cancelButtonColor: '#f06548',
                    cancelButtonText: '<i class="ti ti-x"></i> Não',
                }).then((result) => {
                    if (result.isConfirmed) {
                        let i_status = $('<input type="text" name="type" value="accept_transfer">');
                        form_update.append(i_status).submit();
                    }
                })
            });

            //::::::::::::::::::::::::::::::::::::::::::: RESOLVER TICKET :::::::::::::::::::::::::::::::::::::::::::://
            btn_resolve.on('click', function() {
                Swal.fire({
                    icon: 'warning',
                    title: 'Atenção!!',
                    text: 'Deseja marcar esse ticket como resolvido?',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    confirmButtonText: '<i class="ti ti-checks"></i> Sim',
                    cancelButtonColor: '#f06548',
                    cancelButtonText: '<i class="ti ti-x"></i> Não',
                }).then((result) => {
                    if (result.isConfirmed) {
                        let i_status = $('<input type="text" name="type" value="resolve">');
                        form_update.append(i_status).submit();
                    }
                })
            });

            //::::::::::::::::::::::::::::::::::::::::::: FINALIZAR TICKET ::::::::::::::::::::::::::::::::::::::::::://
            btn_finish.on('click', function() {
                Swal.fire({
                    icon: 'warning',
                    title: 'Atenção!!',
                    text: 'Deseja marcar esse ticket como finalizado?',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    confirmButtonText: '<i class="ti ti-checks"></i> Sim',
                    cancelButtonColor: '#f06548',
                    cancelButtonText: '<i class="ti ti-x"></i> Não',
                }).then((result) => {
                    if (result.isConfirmed) {
                        let i_status = $('<input type="text" name="type" value="finish">');
                        form_update.append(i_status).submit();
                    }
                })
            });

            //::::::::::::::::::::::::::::::::::::::::::: CANCELAR TICKET :::::::::::::::::::::::::::::::::::::::::::://
            btn_cancel.on('click', function() {
                Swal.fire({
                    icon: 'warning',
                    title: 'Atenção!!',
                    text: 'Deseja cancelar esse ticket?',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    confirmButtonText: '<i class="ti ti-upload"></i> Sim',
                    cancelButtonColor: '#f06548',
                    cancelButtonText: '<i class="ti ti-x"></i> Não',
                }).then((result) => {
                    if (result.isConfirmed) {
                        let i_status = $('<input type="text" name="type" value="cancel">');
                        form_update.append(i_status).submit();
                    }
                })
            });

            //::::::::::::::::::::::::: EXIBIR/OCULTAR BOTÃO DE EXPANDIR RESPOSTA AUTOMÁTICA ::::::::::::::::::::::::://
            $('.automatic-response').each(function(key, item) {
                if ($(item).height() > 300) {
                    $(item).addClass('retract');
                    $(item).next().removeClass('d-none');
                }
            });

            //::::::::::::::::::::::::::::::::: EXPANDIR/CONTRAIR RESPOSTA AUTOMÁTICA :::::::::::::::::::::::::::::::://
            $('.btn-automatic-response').on('click', function() {
                let automatic_response = $(this).prev();

                if (automatic_response.hasClass('retract')) {
                    automatic_response.removeClass('retract');
                    $(this).html('<i class="ti ti-chevrons-up"></i>');
                } else {
                    automatic_response.addClass('retract');
                    $(this).html('<i class="ti ti-chevrons-down"></i>');
                }
            });

            //:::::::::::::::::::::::::::::::::::::: EXIBIR IMAGEM DA RESPOSTA ::::::::::::::::::::::::::::::::::::::://
            $(document).on("click", '.response-image', function() {
                $('#show-response-image img').attr('src', $(this).attr('src'));
                $('#show-response-image').modal('show');
            });

            //::::::::::::::::::::::::::::::::: EVENTO PARA OBSERVAR NOVAS MENSAGENS ::::::::::::::::::::::::::::::::://
            window.Echo.channel("ticket-response").listen("NewTicketResponse", (e) => {
                if (e.TicketResponse.user_id != {{ auth()->id() }} && e.TicketResponse.ticket_id === ticket_id) {
                    axios
                        .post("{{ route('ajax.ticket_response.check_new_response', ['ticket_response' => ':response']) }}".replace(':response', e.TicketResponse.id), {
                            data: {
                                TicketResponse: e.TicketResponse,
                            },
                        })
                        .then(function(response) {
                            $('.last-response').removeClass('last-response');
                            div_ticket_responses.append(response.data.new_response);
                            btn_close_reply.click();

                            tinymce.get('response').setContent('');

                            $('html, body').animate({
                                scrollTop: $('.last-response').offset().top
                            }, 'slow');
                        })
                        .catch(function(error) {
                            console.log(error);
                        });
                }
            });
        });
    </script>
@endpush
