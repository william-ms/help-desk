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
                                'name' => 'Menus',
                            ],
                        ]" />

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
                    <div class="card">
                        <div class="card-header d-flex justify-content-between gap-1">
                            <h4 class="mt-2">Listagem de menus</h4>

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

                        <div class="card-body">
                            <x-filter :data="$data_filter" />

                            <x-alerts.success class="mb-4" />
                            <x-alerts.errors class="mb-4" />

                            <form action="{{ route('admin.menu.order') }}" method="POST" id="order">
                                @csrf

                                <table id="table" class="table table-sm table-striped table-bordered nowrap">
                                    <thead>
                                        <tr>
                                            <th style="width: 10%">#</th>
                                            <th class="text-center" style="width: 10%">Ícone</th>
                                            <th style="width: 30%">Menu</th>
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
                                                    <input type="number" name="order[{{ $Menu->menu_category_id }}][{{ $Menu->id }}]" class="form-control p-1 text-end" value="{{ $Menu->order }}" style="min-width: 0;">
                                                </td>

                                                <td class="text-center">
                                                    @if (empty($Menu->deleted_at))
                                                        @can('menu.edit')
                                                            <x-button-icon icon="ti ti-edit" componentType="a" color="info" style="light" href="{{ route('admin.menu.edit', $Menu->id) }}" />
                                                        @endcan

                                                        @can('menu.destroy')
                                                            <x-button-icon type="button" icon="ti ti-trash" color="danger" style="light" class="destroy" data-link="{{ route('admin.menu.destroy', $Menu->id) }}" />
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
@endpush

@push('scripts')
    <!-- Data Tables JS -->
    <script src="{{ asset('assets/js/plugins/dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/dataTables.bootstrap5.min.js') }}"></script>

    <!-- Sweet Alerts JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $(".destroy").on("click", function() {
                var link = $(this).attr('data-link');

                Swal.fire({
                    title: 'Oops!!',
                    text: 'Deseja realmente deletar esse menu?',
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
                    bFilter: false,
                    language: {
                        url: "{{ asset('assets/js/plugins/dataTables.pt_BR.json') }}",
                    },
                });
            @endif
        });
    </script>
@endpush
