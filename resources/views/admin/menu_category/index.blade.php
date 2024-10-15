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
                                <h2 class="mb-0">Categorias de menu</h2>
                            </div>
                        </div>
                    </div>
                </div><!-- page-block -->
            </div><!-- page-header -->

            <div class="row">
                <div class="col-md-12">
                    <div class="card py-3">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h4 class="m-0">Listagem de categorias de menu</h4>

                            <div>
                                @if (empty(request()->all()))
                                    @can('menu_category.order')
                                        <x-button form="order" icon="ti ti-arrows-down-up" title="Atualizar ordem das categorias de menu">
                                            Atualizar ordem
                                        </x-button>
                                    @endcan
                                @endif

                                @can('menu_category.create')
                                    <x-button componentType="a" icon="ti ti-plus" href="{{ route('admin.menu_category.create') }}" title="Cadastrar categoria de menu">
                                        Cadastrar categoria de menu
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

                            <form action="{{ route('admin.menu_category.order') }}" method="POST" id="order">
                                @csrf

                                <table id="table" class="table table-sm table-striped table-bordered nowrap">
                                    <thead>
                                        <tr>
                                            <th class="text-end" style="width: 10%">#</th>
                                            <th style="width: 70%">Categoria de menu</th>
                                            <th class="text-end" style="width: 10%">Ordem</th>
                                            <th class="text-center" style="width: 10%">Ações</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse ($MenuCategories as $MenuCategory)
                                            <tr>
                                                <td class="text-end">{{ $MenuCategory->id }}</td>

                                                <td>{{ $MenuCategory->name }}</td>

                                                <td class="text-end">
                                                    <input type="number" name="order[{{ $MenuCategory->id }}]" class="form-control p-1 text-end" value="{{ $MenuCategory->order }}" style="min-width: 0;">
                                                </td>

                                                <td class="text-center">
                                                    @if (empty($MenuCategory->deleted_at))
                                                        @can('menu_category.edit')
                                                            <x-button-icon icon="ti ti-edit" color="info" style="light" componentType="a" href="{{ route('admin.menu_category.edit', $MenuCategory->id) }}" />
                                                        @endcan

                                                        @can('menu_category.destroy')
                                                            <x-button-icon type="button" icon="ti ti-trash" color="danger" style="light" class="destroy" data-menus="{{ json_encode($MenuCategory->menus->pluck('name')->toArray()) }}" data-link="{{ route('admin.menu_category.destroy', $MenuCategory->id) }}" />
                                                        @endcan
                                                    @else
                                                        @can('menu_category.restore')
                                                            <x-button-icon type="button" icon="ti ti-upload" color="secondary" style="light" class="restore" data-link="{{ route('admin.menu_category.restore', $MenuCategory->id) }}" />
                                                        @endcan
                                                    @endif

                                                    @can('log.show')
                                                        <x-button-icon icon="ti ti-notes" color="warning" style="light" componentType="a" href="{{ route('admin.log.show', $MenuCategory->log->id) }}" />
                                                    @endcan
                                                </td>
                                            </tr>
                                        @empty
                                            @if (empty(request()->all()))
                                                <tr id="emptyTable">
                                                    <td colspan="4" class="text-center">
                                                        <h3>Nenhuma categoria de menu cadastrada no momento!</h3>
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

                let menus = $(this).data('menus');

                if (menus.length > 0) {
                    let list_menus = '<ul class="list-group">';
                    menus.forEach(function(menu, key) {
                        list_menus += `<li class="list-group-item f-14 p-2">${menu}</li>`
                    });
                    list_menus += '</ul>';

                    Swal.fire({
                        title: 'Oops!!',
                        html: `
                            <p class="f-16">Antes de deletar essa categoria, desvincule os seguintes menus que estão vinculados a essa categoria:</p>
                            ${list_menus}
                        `,
                        icon: 'error',
                        confirmButtonText: '<i class="ti ti-checks"></i> Ok',
                    });
                } else {
                    Swal.fire({
                        text: 'Deseja realmente deletar essa categoria de menu?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: '<i class="ti ti-trash"></i> Deletar',
                        cancelButtonColor: '#f06548',
                        cancelButtonText: '<i class="ti ti-x"></i> Cancelar',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $(".delete").attr('action', $(this).attr('data-link')).submit();
                        }
                    })
                }
            });

            $(".restore").on("click", function() {
                Swal.fire({
                    title: 'Oops!!',
                    text: 'Deseja realmente restaurar essa categoria de menu?',
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

            @if (!$MenuCategories->isEmpty())
                var table = $('#table').DataTable({
                    order: [
                        [2, 'asc']
                    ],
                    language: {
                        url: "{{ asset('assets/js/plugins/dataTables.pt_BR.json') }}",
                    },
                });
            @endif
        });
    </script>
@endpush
