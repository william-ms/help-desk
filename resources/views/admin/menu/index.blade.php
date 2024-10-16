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
                                <h2 class="mb-0">Menus</h2>
                            </div>
                        </div>
                    </div>
                </div><!-- page-block -->
            </div><!-- page-header -->

            <div class="row">
                <div class="col-md-12">
                    <div class="card py-3">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h4 class="m-0">Listagem de menus</h4>

                            <div>
                                @if (empty(request()->all()))
                                    @can('menu.order')
                                        <x-button form="order" icon="ti ti-arrows-down-up">
                                            Atualizar ordem
                                        </x-button>
                                    @endcan
                                @endif

                                @can('menu.create')
                                    <x-button componentType="a" icon="ti ti-plus" href="{{ route('admin.menu.create') }}">
                                        Cadastrar menu
                                    </x-button>
                                @endcan
                            </div>
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

                            <form action="{{ route('admin.menu.order') }}" method="POST" id="order">
                                @csrf

                                <table id="table" class="table table-sm table-striped table-bordered nowrap">
                                    <thead>
                                        <tr>
                                            <th style="width: 10%">#</th>
                                            <th class="text-center" style="width: 10%">Ícone</th>
                                            <th style="width: 60%">Menu</th>
                                            <th style="width: 30%">Categoria</th>
                                            <th class="text-end" style="width: 10%">Ordem</th>
                                            <th class="text-center"style="width: 10%">Ações</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse ($Menus as $Menu)
                                            <tr>
                                                <td>{{ $Menu->id }}</td>

                                                <td class="text-center"><i class="{{ $Menu->icon }} f-18"></i></td>

                                                <td>{{ $Menu->name }}</td>

                                                <td>{{ $Menu->menu_category->name }}</td>

                                                <td class="text-end">
                                                    <input type="number" name="order[{{ $Menu->menu_category_id }}][{{ $Menu->id }}]" class="form-control text-end" value="{{ $Menu->order }}">
                                                </td>

                                                <td class="text-center">
                                                    @if (empty($Menu->deleted_at))
                                                        @can('menu.edit')
                                                            <x-button-icon icon="ti ti-edit" componentType="a" color="info" style="light" href="{{ route('admin.menu.edit', $Menu->id) }}" />
                                                        @endcan

                                                        @can('menu.destroy')
                                                            <x-button-icon type="button" icon="ti ti-trash" color="danger" style="light" class="destroy" data-link="{{ route('admin.menu.destroy', $Menu->id) }}" data-permissions="{{ json_encode($Menu->permissions->pluck('name')->toArray()) }}" />
                                                        @endcan
                                                    @else
                                                        @can('menu.restore')
                                                            <x-button-icon type="button" icon="ti ti-upload" color="secondary" style="light" class="restore" data-link="{{ route('admin.menu.restore', $Menu->id) }}" />
                                                        @endcan
                                                    @endif

                                                    @can('log.show')
                                                        <x-button-icon icon="ti ti-notes" color="warning" style="light" componentType="a" href="{{ route('admin.log.show', $Menu->log->id) }}" />
                                                    @endcan
                                                </td>
                                            </tr>
                                        @empty
                                            @if (empty(request()->all()))
                                                <tr id="emptyTable">
                                                    <td colspan="6" class="text-center">
                                                        <h3>Nenhum menu cadastrado no momento!</h3>
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
                            </form>
                        </div><!-- card-body -->
                    </div><!-- card -->
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
    <link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.5.0/css/rowGroup.bootstrap5.css" />

    <style>
        table.dataTable tr.dtrg-group.dtrg-level-0 th {
            /* padding-left: 11%; */
            text-align: center;
            text-transform: uppercase;
        }

        .form-control:not(.dropdown) {
            padding: 4px 9px !important;
        }
    </style>
@endpush

@push('scripts')
    <!-- Data Tables JS -->
    <script src="{{ asset('assets/js/plugins/dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/dataTables.bootstrap5.min.js') }}"></script>
    <script src="https://cdn.datatables.net/rowgroup/1.5.0/js/dataTables.rowGroup.js"></script>
    <script src="https://cdn.datatables.net/rowgroup/1.5.0/js/rowGroup.bootstrap5.js"></script>

    <!-- Sweet Alerts JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $(".destroy").on("click", function() {
                let link = $(this).attr('data-link');
                let permissions = $(this).data('permissions');
                let html = '';

                if (permissions.length > 0) {
                    html += `
                            <p class="f-18">Deseja realmente deletar esse menu?</p>
                            <p class="f-16 m-0 mb-1">As seguintes permissões também serão deletadas:</p>
                            <ul class="list-group">
                        `;

                    permissions.forEach(function(menu, key) {
                        html += `<li class="list-group-item f-14 p-2">${menu}</li>`
                    });
                    html += '</ul>';
                }

                Swal.fire({
                    title: 'Oops!!',
                    text: 'Deseja realmente deletar esse menu?',
                    html: html,
                    icon: 'warning',
                    input: false,
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    confirmButtonText: '<i class="ti ti-trash"></i> Deletar',
                    cancelButtonColor: '#f06548',
                    cancelButtonText: '<i class="ti ti-x"></i> Cancelar',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(".delete").attr('action', link).submit();
                    }
                })
            });

            $(".restore").on("click", function() {
                Swal.fire({
                    title: 'Oops!!',
                    text: 'Deseja realmente restaurar esse menu?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    confirmButtonText: '<i class="ti ti-upload"></i> Restaurar',
                    cancelButtonColor: '#f06548',
                    cancelButtonText: '<i class="ti ti-x"></i> Cancelar',
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.location.href = $(this).attr('data-link');
                    }
                })
            });

            @if (!$Menus->isEmpty())
                var table = $('#table').DataTable({
                    order: 3,
                    language: {
                        url: "{{ asset('assets/js/plugins/dataTables.pt_BR.json') }}",
                    },
                    rowGroup: {
                        dataSrc: 3
                    },
                    columnDefs: [{
                        target: 3,
                        visible: false,
                    }]
                });
            @endif
        });
    </script>
@endpush
