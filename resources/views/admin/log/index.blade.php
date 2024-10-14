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
                                <h2 class="mb-0">Logs</h2>
                            </div>
                        </div>
                    </div>
                </div><!-- page-block -->
            </div><!-- page-header -->

            <div class="row">
                <div class="col-md-12">
                    <div class="card py-3">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h4 class="m-0">Listagem de logs</h4>
                        </div><!-- card-header -->

                        <div class="card-body py-3">
                            <x-alerts.success class="mb-4" />
                            <x-alerts.errors class="mb-4" />

                            <div class="d-flex justify-content-end">
                                <x-button icon="ti ti-list-search" class="mb-2" title="Filtrar" data-bs-toggle="modal" data-bs-target="#filter">
                                    Filtrar
                                </x-button>

                                <x-filter :data="$data_filter" />
                            </div>

                            <table id="dom-jqry" class="table table-sm table-striped table-bordered nowrap">
                                <thead>
                                    <tr>
                                        <th style="width: 10%">#</th>
                                        <th style="width: 25%">Módulo</th>
                                        <th style="width: 15%">Id do módulo</th>
                                        <th style="width: 25%">Nome do módulo</th>
                                        <th style="width: 15%">Última alteração</th>
                                        <th class="text-center" style="width: 10%">Ações</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($Logs as $Log)
                                        <tr>
                                            <td>{{ $Log->id }}</td>

                                            <td class="td-limit" title="{{ $Log->model_type }}" data-bs-toggle="tooltip">{{ $Log->model_type }}</td>

                                            <td class="td-limit" title="{{ $Log->model_id }}" data-bs-toggle="tooltip">{{ $Log->model_id }}</td>

                                            <td class="td-limit" title="{{ $Log->model_name }}" data-bs-toggle="tooltip">{{ $Log->model_name }}</td>

                                            <td>{{ $Log->items->last()->created_at->format('d/m/Y H:i:s') }}</td>

                                            <td class="text-center">
                                                @can('log.show')
                                                    <x-button-icon icon="ti ti-eye" color="info" style="light" componentType="a" href="{{ route('admin.log.show', $Log->id) }}" />
                                                @endcan
                                            </td>
                                        </tr>
                                    @empty
                                        @if (empty(request()->all()))
                                            <tr id="emptyTable">
                                                <td colspan="6" class="text-center">
                                                    <h3>Nenhum log cadastrada no momento!</h3>
                                                </td>
                                            </tr>
                                        @else
                                            <tr id="emptyTable">
                                                <td colspan="6" class="text-center table-warning">
                                                    <h3>Não encontramos nada usando os dados da sua pesquisa!</h3>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforelse
                                </tbody>
                            </table>
                        </div><!-- card-body -->
                    </div><!-- card -->
                </div>
            </div>
        </div><!-- pc-content -->
    </section><!-- pc-container -->
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/dataTables.bootstrap5.min.css') }}" />
@endpush

@push('scripts')
    <!-- Data Tables JS -->
    <script src="{{ asset('assets/js/plugins/dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/dataTables.bootstrap5.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            @if (!$Logs->isEmpty())
                var table = $('#dom-jqry').DataTable({
                    order: [
                        [4, 'desc']
                    ],
                    language: {
                        url: "{{ asset('assets/js/plugins/dataTables.pt_BR.json') }}",
                    },
                });
            @endif
        });
    </script>
@endpush
