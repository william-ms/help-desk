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
                                <h2 class="mb-0">Funções</h2>
                            </div>
                        </div>
                    </div>
                </div><!-- page-block -->
            </div><!-- page-header -->

            <div class="row">
                <div class="col-md-12">
                    <div class="card py-3">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h4 class="m-0">Listagem de funções</h4>

                            <div>
                                @if ($gates['create'])
                                    <x-button componentType="a" icon="ti ti-plus" href="{{ route('admin.role.create') }}" title="Cadastrar função">
                                        <span class="d-none d-lg-inline">Cadastrar função</span>
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
                                        <th style="width: 80%">Nome</th>
                                        <th class="text-center" style="width: 10%">Ações</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($Roles as $Role)
                                        <tr>
                                            <td>{{ $Role->id }}</td>
                                            <td>{{ $Role->name }}</td>

                                            <td class="text-center">
                                                @if (empty($Role->deleted_at))
                                                    @if ($gates['edit'])
                                                        <x-button-icon icon="ti ti-edit" color="info" style="light" componentType="a" href="{{ route('admin.role.edit', $Role->id) }}" />
                                                    @endif

                                                    @if ($Role->id != 1)
                                                        @if ($gates['destroy'])
                                                            <x-button-icon type="button" icon="ti ti-trash" color="danger" style="light" class="destroy" data-link="{{ route('admin.role.destroy', $Role->id) }}" data-users="{{ json_encode($Role->users->pluck('name')->toArray()) }}" />
                                                        @endif
                                                    @endif
                                                @else
                                                    @if ($gates['restore'])
                                                        <x-button-icon type="button" icon="ti ti-upload" color="secondary" style="light" class="restore" data-link="{{ route('admin.role.restore', $Role->id) }}" />
                                                    @endif
                                                @endif

                                                @if ($gates['log_show'])
                                                    <x-button-icon icon="ti ti-notes" color="warning" style="light" componentType="a" href="{{ route('admin.log.show', $Role->log->id) }}" />
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        @if (empty(request()->all()))
                                            <tr id="emptyTable">
                                                <td colspan="4" class="text-center">
                                                    <h3>Nenhuma função cadastrada no momento!</h3>
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
@endpush

@push('scripts')
    <!-- Data Tables JS -->
    <script src="{{ asset('assets/js/plugins/dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/dataTables.responsive.min.js') }}"></script>

    <!-- Sweet Alerts JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            let users = [];
            let html = '';
            let list_users = '';

            $(".destroy").on("click", function() {
                users = $(this).data('users');
                html = '';

                if (users.length > 0) {
                    list_users = '<ul class="list-group">';
                    users.forEach(function(user, key) {
                        list_users += `<li class="list-group-item f-14 p-2">${user}</li>`
                    });
                    list_users += '</ul>';

                    Swal.fire({
                        title: 'Oops!!',
                        html: `
                            <p class="f-16">Antes de deletar essa função, desvincule os seguintes usuários que estão vinculados a essa função:</p>
                            ${list_users}
                        `,
                        icon: 'error',
                        confirmButtonText: '<i class="ti ti-checks"></i> Ok',
                    });
                } else {
                    Swal.fire({
                        title: 'Oops!!',
                        text: 'Deseja realmente deletar essa função?',
                        html: html,
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
                }
            });

            @if (!$Roles->isEmpty())
                var table = $('#table').DataTable({
                    order: [
                        [0, 'asc']
                    ],
                    language: {
                        url: "{{ asset('assets/js/plugins/dataTables.pt_BR.json') }}",
                    },
                });
            @endif
        });
    </script>
@endpush
