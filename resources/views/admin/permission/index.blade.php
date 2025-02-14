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
                                <h2 class="mb-0">Permissões</h2>
                            </div>
                        </div>
                    </div>
                </div><!-- page-block -->
            </div><!-- page-header -->

            <div class="row">
                <div class="col-md-12">
                    <div class="card py-3">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h4 class="m-0">Listagem de permissões</h4>

                            <div>
                                @if ($gates['create'])
                                    <x-button componentType="a" icon="ti ti-plus" href="{{ route('admin.permission.create') }}" title="Cadastrar permissão">
                                        <span class="d-none d-lg-inline">Cadastrar permissão</span>
                                    </x-button>
                                @endif
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

                            <table id="dom-jqry" class="table table-sm table-striped table-bordered nowrap responsive">
                                <thead>
                                    <tr>
                                        <th style="width: 10%">#</th>
                                        <th>Predixo da rota</th>
                                        <th style="width: 80%">Método da rota</th>
                                        <th class="text-center" style="width: 10%">Ações</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($Permissions as $Permission)
                                        <tr>
                                            <td>{{ $Permission->id }}</td>

                                            <td>{{ $Permission->menu->name ?? explode('.', $Permission->name)[0] }}</td>
                                            <td>{{ explode('.', $Permission->name)[1] }}</td>

                                            <td class="text-center">
                                                @if (empty($Permission->deleted_at))
                                                    @if ($gates['edit'])
                                                        <x-button-icon icon="ti ti-edit" color="info" style="light" componentType="a" href="{{ route('admin.permission.edit', $Permission->id) }}" />
                                                    @endif

                                                    @if ($gates['destroy'])
                                                        <x-button-icon type="button" icon="ti ti-trash" color="danger" style="light" class="destroy" data-link="{{ route('admin.permission.destroy', $Permission->id) }}" />
                                                    @endif
                                                @else
                                                    @if ($gates['restore'])
                                                        <x-button-icon type="button" icon="ti ti-upload" color="secondary" style="light" class="restore" data-link="{{ route('admin.permission.restore', $Permission->id) }}" />
                                                    @endif
                                                @endif

                                                @if ($gates['log_show'])
                                                    <x-button-icon icon="ti ti-notes" color="warning" style="light" componentType="a" href="{{ route('admin.log.show', $Permission->log->id) }}" />
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        @if (empty(request()->all()))
                                            <tr id="emptyTable">
                                                <td colspan="4" class="text-center">
                                                    <h3>Nenhuma permissão cadastrada no momento!</h3>
                                                </td>
                                            </tr>
                                        @else
                                            <tr id="emptyTable">
                                                <td colspan="4" class="text-center table-warning">
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

    <form action="" class="delete" method="post">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/dataTables.bootstrap5.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/responsive.bootstrap5.min.css') }}" />
    <link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.5.0/css/rowGroup.bootstrap5.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.5.0/css/rowGroup.bootstrap5.css" />

    <style>
        table.dataTable tr.dtrg-group.dtrg-level-0 th {
            /* padding-left: 11%; */
            text-align: center;
            text-transform: uppercase;
        }
    </style>
@endpush

@push('scripts')
    <!-- Data Tables JS -->
    <script src="{{ asset('assets/js/plugins/dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/dataTables.responsive.min.js') }}"></script>
    <script src="https://cdn.datatables.net/rowgroup/1.5.0/js/dataTables.rowGroup.js"></script>
    <script src="https://cdn.datatables.net/rowgroup/1.5.0/js/rowGroup.bootstrap5.js"></script>

    <!-- Sweet Alerts JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $(".destroy").on("click", function() {

                Swal.fire({
                    title: 'Oops!!',
                    text: 'Deseja realmente deletar essa permissão?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    confirmButtonText: '<i class="ti ti-trash"></i> Deletar',
                    cancelButtonColor: '#f06548',
                    cancelButtonText: '<i class="ti ti-x"></i> Cancelar',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(".delete").attr('action', $(this).attr('data-link')).submit();
                    }
                })
            });

            $(".restore").on("click", function() {
                Swal.fire({
                    title: 'Oops!!',
                    text: 'Deseja realmente restaurar essa permissão?',
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

            @if (!$Permissions->isEmpty())
                var table = $('#dom-jqry').DataTable({
                    order: [
                        [1, 'asc']
                    ],
                    language: {
                        url: "{{ asset('assets/js/plugins/dataTables.pt_BR.json') }}",
                    },
                    rowGroup: {
                        dataSrc: 1
                    },
                    columnDefs: [{
                        target: 1,
                        visible: false,
                        // searchable: false
                    }, ]
                });
            @endif
        });
    </script>
@endpush
