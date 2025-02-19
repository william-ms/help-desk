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
                                <h2 class="mb-0">Subcategorias</h2>
                            </div>
                        </div>
                    </div>
                </div><!-- page-block -->
            </div><!-- page-header -->

            <div class="row">
                <div class="col-md-12">
                    <div class="card py-3">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h4 class="m-0">Listagem de subcategorias</h4>

                            <div>
                                @if ($gates['create'])
                                    <x-button componentType="a" icon="ti ti-plus" href="{{ route('admin.subcategory.create') }}" title="Cadastrar subcategoria">
                                        <span class="d-none d-lg-inline">Cadastrar subcategoria</span>
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

                            <table id="table" class="table table-sm table-striped table-bordered nowrap responsive">
                                <thead>
                                    <tr>
                                        <th style="width: 10%">#</th>
                                        <th style="width: 20%">Subcategoria</th>
                                        <th style="width: 20%">Categoria</th>
                                        <th style="width: 20%">Departamento</th>
                                        <th style="width: 20%">Empresa</th>
                                        <th class="text-center"style="width: 10%">Ações</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($Subcategories as $Subcategory)
                                        <tr>
                                            <td>{{ $Subcategory->id }}</td>

                                            <td>{{ $Subcategory->name }}</td>

                                            <td>{{ $Subcategory->category->name }}</td>

                                            <td>{{ $Subcategory->departament->name }}</td>

                                            <td>{{ $Subcategory->company->name }}</td>

                                            <td class="text-center">
                                                @if (empty($Subcategory->deleted_at))
                                                    @if ($gates['edit'])
                                                        <x-button-icon icon="ti ti-edit" componentType="a" color="info" style="light" href="{{ route('admin.subcategory.edit', $Subcategory->id) }}" />
                                                    @endif

                                                    @if ($gates['destroy'])
                                                        <x-button-icon type="button" icon="ti ti-trash" color="danger" style="light" class="destroy" data-link="{{ route('admin.subcategory.destroy', $Subcategory->id) }}" />
                                                    @endif
                                                @else
                                                    @if ($gates['restore'])
                                                        <x-button-icon type="button" icon="ti ti-upload" color="secondary" style="light" class="restore" data-link="{{ route('admin.subcategory.restore', $Subcategory->id) }}" />
                                                    @endif
                                                @endif

                                                @if ($gates['log_show'])
                                                    <x-button-icon icon="ti ti-notes" color="warning" style="light" componentType="a" href="{{ route('admin.log.show', $Subcategory->log->id) }}" />
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        @if (empty(request()->all()))
                                            <tr id="emptyTable">
                                                <td colspan="7" class="text-center">
                                                    <h3>Nenhuma subcategoria cadastrada no momento!</h3>
                                                </td>
                                            </tr>
                                        @else
                                            <tr id="emptyTable">
                                                <td colspan="7" class="text-center table-warning">
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

    <style>
        table.dataTable tr.dtrg-group.dtrg-level-0 th {
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
                var link = $(this).attr('data-link');

                Swal.fire({
                    title: 'Oops!!',
                    text: 'Deseja realmente deletar essa subcategoria?',
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
                    text: 'Deseja realmente restaurar essa subcategoria?',
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

            @if (!$Subcategories->isEmpty())
                var table = $('#table').DataTable({
                    order: [
                        [2, 'asc']
                    ],
                    language: {
                        url: "{{ asset('assets/js/plugins/dataTables.pt_BR.json') }}",
                    },
                    rowGroup: {
                        dataSrc: 2
                    },
                    columnDefs: [{
                        target: 2,
                        visible: false,
                    }]
                });
            @endif
        });
    </script>
@endpush
