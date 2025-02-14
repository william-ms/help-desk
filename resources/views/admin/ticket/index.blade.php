@extends('admin.base')

@section('content')
    <section class="pc-container">
        <div class="pc-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <x-breadcrumb :breadcrumbs="$data_breadcrumbs" />

                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">Tickets</h2>
                            </div>
                        </div>
                    </div>
                </div><!-- page-block -->
            </div><!-- page-header -->

            <div class="row">
                <div class="col-xl-8 col-lg-12 help-main">
                    <div class="card">
                        <div class="card-body">
                            <nav class="navbar justify-content-between p-0 align-items-center">
                                <div>
                                    @if ($gates['create'])
                                        <x-button componentType="a" icon="ti ti-plus" href="{{ route('admin.ticket.create') }}">
                                            Cadastrar ticket
                                        </x-button>
                                    @endif
                                </div>

                                <div class="btn-group btn-group-sm help-filter" role="group" aria-label="button groups sm">
                                    <a class="btn btn-light-secondary" data-filter="sm"><i class="feather icon-align-justify m-0"></i></a>
                                    <a class="btn btn-light-secondary" data-filter="md"><i class="feather icon-menu m-0"></i></a>
                                    <a class="btn btn-light-secondary active" data-filter="large"><i class="feather icon-grid m-0"></i></a>
                                </div>
                            </nav>
                        </div><!-- card-body -->
                    </div>

                    @foreach ($Tickets as $Ticket)
                        <div class="card ticket-card ticket-border" style="border-left: 3px solid var(--bs-{{ $TicketStatus[$Ticket->status]['color'] }})">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-auto mb-3 mb-sm-0">
                                        <div class="d-sm-inline-block d-flex align-items-center">
                                            <img class="media-object wid-60 img-radius" src="{{ $Ticket->requester->profile_image }}" />
                                            {{-- <div class="ms-3 ms-sm-0">
                                                <ul class="text-sm-center list-unstyled mt-2 mb-0 d-inline-block">
                                                    <li class="list-unstyled-item"><a href="#" class="link-secondary">1 Ticket</a></li>
                                                    <li class="list-unstyled-item"><a href="#" class="link-danger"><i class="fas fa-heart"></i> 3</a></li>
                                                </ul>
                                            </div> --}}
                                        </div>
                                    </div>

                                    <div class="col">
                                        <div class="h5 font-weight-bold">
                                            {{ $Ticket->requester->name }}
                                            <small class="badge bg-light-{{ $TicketStatus[$Ticket->status]['color'] }} ms-2">{{ $TicketStatus[$Ticket->status]['text'] }}</small>
                                        </div>

                                        <div class="help-sm-hidden">
                                            <div class="d-flex flex-wrap text-nowrap">
                                                {{-- Empresa --}}
                                                <span class="me-3">
                                                    <i class="ph-duotone ph-buildings me-1"></i>{{ $Ticket->company->name }}
                                                </span>

                                                {{-- Departamento --}}
                                                <span class="me-3">
                                                    <i class="ph-duotone ph-archive me-1"></i>{{ $Ticket->departament->name }}
                                                </span>

                                                {{-- Quem aceitou o ticket --}}
                                                @if (!empty($Ticket->assignee))
                                                    <span class="me-3">
                                                        <img src="{{ $Ticket->assignee->profile_image }}" alt="" class="wid-20 rounded me-1 img-fluid" />
                                                        Aceito por <b>{{ $Ticket->assignee->name }}</b>
                                                    </span>
                                                @endif

                                                {{-- Data e hora da última atualização --}}
                                                <span class="me-3">
                                                    <i class="ph-duotone ph-calendar-blank"></i>Atualizado a 22 horas atrás
                                                </span>

                                                {{-- Número de mensagens --}}
                                                <span class="me-3">
                                                    <i class="ph-duotone ph-chats"></i>9
                                                </span>
                                            </div>
                                        </div>

                                        {{-- Assunto --}}
                                        <div class="mt-3">
                                            <h5><i class="ph-duotone ph-bookmark-simple"></i> {{ $Ticket->subject }}</h5>
                                        </div>

                                        <div class="help-md-hidden">
                                            <div class="bg-body mb-3 p-3">
                                                <h6>
                                                    <img src="{{ $Ticket->last_user_response->user->profile_image }}" alt="" class="wid-20 avatar me-2 rounded" />
                                                    Último comentário de
                                                    <a href="#" class="link-secondary">{{ $Ticket->last_user_response->user->name }}:</a>
                                                </h6>

                                                <div class="last-response">
                                                    {!! $Ticket->last_user_response->response !!}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-2">
                                            <x-button icon="ti ti-eye" componentType="a" color="light-primary" href="{{ route('admin.ticket.show', $Ticket->id) }}">
                                                Ver ticket
                                            </x-button>
                                        </div>
                                    </div>
                                </div><!-- row -->
                            </div><!-- card-body -->
                        </div><!-- ticket-card -->
                    @endforeach

                    {{ $Tickets->onEachSide(5)->links() }}
                </div>

                {{-- TODO: Atualizar ao adicionar os times --}}
                <div class="col-xl-4 col-lg-12">
                    <div class="right-side">
                        {{-- Times --}}
                        {{-- <div class="card mb-3">
                            <div class="card-header">
                                <h5>Ticket Categories</h5>
                            </div>

                            <ul class="list-group list-group-flush pb-2">
                                <li class="list-group-item">
                                    <div class="d-inline-block">
                                        <img src="../assets/images/admin/p1.jpg" alt="" class="wid-20 rounded me-1 img-fluid" />
                                        <a href="#" class="link-secondary">Piaf able</a>
                                    </div>
                                    <div class="float-end">
                                        <a href="#" class="badge bg-light-danger rounded-circle me-1" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-original-title="tooltip on top">1</a>
                                        <a href="#" class="badge bg-light-secondary rounded-circle me-0" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-original-title="tooltip on top">3</a>
                                    </div>
                                </li>

                                <li class="list-group-item">
                                    <div class="d-inline-block">
                                        <img src="../assets/images/admin/p2.jpg" alt="" class="wid-20 rounded me-1 img-fluid" />
                                        <a href="#" class="link-secondary">Pro able</a>
                                    </div>
                                    <div class="float-end">
                                        <a href="#" class="badge bg-light-secondary rounded-circle me-0" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-original-title="tooltip on top">3</a>
                                    </div>
                                </li>

                                <li class="list-group-item">
                                    <div class="d-inline-block">
                                        <img src="../assets/images/admin/p3.jpg" alt="" class="wid-20 rounded me-1 img-fluid" />
                                        <a href="#" class="link-secondary">CRM admin</a>
                                    </div>
                                    <div class="float-end">
                                        <a href="#" class="badge bg-light-danger rounded-circle me-1" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-original-title="tooltip on top">1</a>
                                        <a href="#" class="badge bg-light-secondary rounded-circle me-0" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-original-title="tooltip on top">3</a>
                                    </div>
                                </li>

                                <li class="list-group-item">
                                    <div class="d-inline-block">
                                        <img src="../assets/images/admin/p4.jpg" alt="" class="wid-20 rounded me-1 img-fluid" />
                                        <a href="#" class="link-secondary">Alpha pro</a>
                                    </div>
                                    <div class="float-end">
                                        <a href="#" class="badge bg-light-secondary rounded-circle me-0" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-original-title="tooltip on top">3</a>
                                    </div>
                                </li>

                                <li class="list-group-item">
                                    <div class="d-inline-block">
                                        <img src="../assets/images/admin/p5.jpg" alt="" class="wid-20 rounded me-1 img-fluid" />
                                        <a href="#" class="link-secondary">Carbon able</a>
                                    </div>
                                    <div class="float-end">
                                        <a href="#" class="badge bg-light-secondary rounded-circle me-0" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-original-title="tooltip on top">3</a>
                                    </div>
                                </li>
                            </ul>
                        </div> --}}

                        {{-- Integrantes do time --}}
                        {{-- <div class="card">
                            <div class="card-header pt-4 pb-4">
                                <h5>Support Aggent</h5>
                            </div>
                            <ul class="list-group list-group-flush pb-2">
                                <li class="list-group-item">
                                    <div class="d-inline-block">
                                        <img src="../assets/images/user/avatar-5.jpg" alt="" class="wid-20 rounded me-1 img-fluid" />
                                        <a href="#" class="link-secondary">Tom Cook</a>
                                    </div>
                                    <div class="float-end">
                                        <a href="#" class="badge bg-light-danger rounded-circle me-1" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-original-title="tooltip on top">1</a>
                                        <a href="#" class="badge bg-light-secondary rounded-circle me-0" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-original-title="tooltip on top">3</a>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="d-inline-block">
                                        <img src="../assets/images/user/avatar-4.jpg" alt="" class="wid-20 rounded me-1 img-fluid" />
                                        <a href="#" class="link-secondary">Brad Larry</a>
                                    </div>
                                    <div class="float-end">
                                        <a href="#" class="badge bg-light-danger rounded-circle me-1" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-original-title="tooltip on top">1</a>
                                        <a href="#" class="badge bg-light-secondary rounded-circle me-0" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-original-title="tooltip on top">3</a>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="d-inline-block">
                                        <img src="../assets/images/user/avatar-3.jpg" alt="" class="wid-20 rounded me-1 img-fluid" />
                                        <a href="#" class="link-secondary">Jhon White</a>
                                    </div>
                                    <div class="float-end">
                                        <a href="#" class="badge bg-light-secondary rounded-circle me-0" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-original-title="tooltip on top">3</a>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="d-inline-block">
                                        <img src="../assets/images/user/avatar-2.jpg" alt="" class="wid-20 rounded me-1 img-fluid" />
                                        <a href="#" class="link-secondary">Mark Jobs</a>
                                    </div>
                                    <div class="float-end">
                                        <a href="#" class="badge bg-light-secondary rounded-circle me-0" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-original-title="tooltip on top">3</a>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="d-inline-block">
                                        <img src="../assets/images/user/avatar-1.jpg" alt="" class="wid-20 rounded me-1 img-fluid" />
                                        <a href="#" class="link-secondary">Able Pro</a>
                                    </div>
                                    <div class="float-end">
                                        <a href="#" class="badge bg-light-secondary rounded-circle me-0" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-original-title="tooltip on top">3</a>
                                    </div>
                                </li>
                            </ul>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div><!-- pc-content -->
    </section><!-- pc-container -->

    <form action="" class="delete" method="post">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/dataTables.bootstrap5.min.css') }}" />

    <style>
        .last-response img {
            max-width: 200px;
        }

        .pagination {
            justify-content: end;
        }
    </style>
@endpush

@push('scripts')
    <!-- Data Tables JS -->
    <script src="{{ asset('assets/js/plugins/dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/dataTables.bootstrap5.min.js') }}"></script>

    <!-- Sweet Alerts JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            const div_help_main = $('.help-main');
            var size = '';

            $('.help-filter a').on('click', function() {
                size = $(this).data('filter');

                $('.help-filter a').removeClass('active');
                $(this).addClass('active');

                div_help_main.removeClass(['sm-view', 'md-view', 'large-view']).addClass(size + '-view');
            });
        });
    </script>
@endpush
