@extends('admin.base')

@section('content')
    <section class="pc-container">
        <div class="pc-content">

            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <x-breadcrumb :breadcrumbs="[
                            [
                                'name' => 'Dashboard',
                                'route' => 'admin.dashboard.index',
                            ],
                            [
                                'name' => 'Logs',
                                'route' => 'admin.log.index',
                            ],
                            [
                                'name' => 'Log #' . $Log->id,
                            ],
                        ]" />

                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">{{ 'Log #' . $Log->id }}</h2>
                            </div>
                        </div>
                    </div>
                </div><!-- page-block -->
            </div><!-- page-header -->

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h4 class="mt-2">
                                <span class="align-middle">{{ ucfirst($Log->model_type) }}</span>
                                <i class="ti ti-chevrons-right text-primary position-relative" style="top: 3px"></i>
                                <span class="align-middle">{{ $Log->model_name }} (#{{ $Log->model_id }})</span>
                            </h4>

                            <div>
                                @can('log.index')
                                    <x-button componentType="a" icon="ti ti-clipboard-list" href="{{ route('admin.log.index') }}">
                                        Listar logs
                                    </x-button>
                                @endcan
                            </div>
                        </div><!-- card-header -->

                        <div class="card-body">
                            <x-alerts.success class="mb-4" />
                            <x-alerts.errors class="mb-4" />

                            <div class="row px-3">
                                <div class="col-12">
                                    <div class="row mb-3 py-2 border-bottom align-items-center">
                                        <a class="px-3 nav-link text-uppercase text-primary">{{ $Log->items->first()->label }}</a>
                                    </div>

                                    <div class="row mb-3 border-bottom align-items-center">
                                        <p class="col-2 fw-bold">Usuário que executou</p>
                                        <p class="col-10">{{ $Log->items->first()->user->name }}</p>
                                    </div>

                                    <div class="row mb-3 border-bottom align-items-center">
                                        <p class="col-2 fw-bold">Data e hora de execução</p>
                                        <p class="col-10">{{ $Log->items->first()->created_at->format('d/m/Y H:i:s') }}</p>
                                    </div>

                                    <div class="row mb-3 border-bottom align-items-center">
                                        <p class="col-2 fw-bold">Ação</p>
                                        <div class="col-10 pb-3">{!! $Log->items->first()->action_as_string() !!}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row px-3">
                                <div class="col-12 ">
                                    <div id="secondary" style="display: none;">
                                        <div class="row mb-3 border-bottom align-items-center">
                                            <p class="col-2 fw-bold">Id do módulo</p>
                                            <p class="col-10">{{ $Log->model_id }}</p>
                                        </div>

                                        <div class="row mb-3 border-bottom align-items-center">
                                            <p class="col-2 fw-bold">Nome do módulo</p>
                                            <p class="col-10">{{ $Log->model_name }}</p>
                                        </div>

                                        <div class="row mb-3 border-bottom align-items-center">
                                            <p class="col-2 fw-bold">Tipo do módulo</p>
                                            <p class="col-10">{{ $Log->model_type }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3" style="height: 0;">
                                    <button type="button" id="show-secondary" class="btn btn-icon btn-light-primary position-relative" style="left: 50%; top: -30px; transform: translateX(-50%); width: 30px; height: 30px;">
                                        <i class="ti ti-chevron-down"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="log-items row my-3">
                                <div class="log-items-header col-12">
                                    <ul class="nav nav-tabs text-nowrap flex-nowrap p-0 m-0" id="myTab" role="tablist">
                                        @foreach ($Log->items as $LogItem)
                                            @if (!empty($LogItem->label) && $LogItem->type !== 'create')
                                                <li class="nav-item">
                                                    <a class="nav-link text-uppercase" id="{{ mb_strtolower(str_replace(' ', '', $LogItem->label)) . $LogItem->id }}-tab" data-bs-toggle="tab" href="#{{ mb_strtolower(str_replace(' ', '', $LogItem->label)) . $LogItem->id }}" role="tab" aria-controls="{{ mb_strtolower(str_replace(' ', '', $LogItem->label)) . $LogItem->id }}" aria-selected="true">{{ $LogItem->label }}</a>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            <div class="row px-3">
                                <div class="col-12">
                                    <div class="tab-content" id="myTabContent">
                                        @foreach ($Log->items as $LogItem)
                                            @if (!empty($LogItem->label))
                                                <div class="tab-pane fade" id="{{ mb_strtolower(str_replace(' ', '', $LogItem->label)) . $LogItem->id }}" role="tabpanel" aria-labelledby="{{ mb_strtolower(str_replace(' ', '', $LogItem->label)) . $LogItem->id }}-tab">

                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="row mb-3 border-bottom align-items-center">
                                                                <p class="col-2 fw-bold">Usuário que executou</p>
                                                                <p class="col-10">{{ $LogItem->user->name }}</p>
                                                            </div>

                                                            <div class="row mb-3 border-bottom align-items-center">
                                                                <p class="col-2 fw-bold">Data e hora de execução</p>
                                                                <p class="col-10">{{ $LogItem->created_at->format('d/m/Y H:i:s') }}</p>
                                                            </div>

                                                            <div class="row mb-3 border-bottom align-items-center">
                                                                <p class="col-2 fw-bold">Ação</p>
                                                                <div class="col-10 pb-3">{!! $LogItem->action_as_string() !!}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- card-body -->
                </div><!-- card -->
            </div>
        </div>
        </div><!-- pc-content -->
    </section><!-- pc-container -->
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#show-secondary').on('click', function() {
                let i = $(this).find('i');

                if (i.hasClass('ti-chevron-down')) {
                    i.removeClass('ti-chevron-down');
                    i.addClass('ti-chevron-up');
                    $('#secondary').slideDown();

                } else {
                    i.addClass('ti-chevron-down');
                    i.removeClass('ti-chevron-up');
                    $('#secondary').slideUp();
                }
            })
        });
    </script>
@endpush
