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
                                <h2 class="mb-0">Usuários</h2>
                            </div>
                        </div>
                    </div>
                </div><!-- page-block -->
            </div><!-- page-header -->

            <div class="row">
                <div class="col-md-12">
                    <div class="card py-3">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h4 class="m-0">Listagem de usuários</h4>

                            <div>
                                @can('user.create')
                                    <x-button componentType="a" icon="ti ti-plus" href="{{ route('admin.user.create') }}">
                                        Cadastrar usuário
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

                            <table id="table" class="table table-sm table-striped table-bordered nowrap">
                                <thead>
                                    <tr>
                                        <th style="width: 10%">#</th>
                                        <th class="text-center" style="width: 35%">Nome</th>
                                        <th style="width: 35%">Função</th>
                                        <th style="width: 10%">Status</th>
                                        <th class="text-center"style="width: 10%">Ações</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($Users as $User)
                                        <tr>
                                            <td>{{ $User->id }}</td>

                                            <td>{{ $User->name }}</td>

                                            <td>{{ $User->roles->first()->name }}</td>

                                            <td class="text-center">
                                                <span class="badge bg-{{ $UserStatus[$User->status]['color'] }} p-2">{{ $UserStatus[$User->status]['status'] }}</span>
                                            </td>

                                            <td class="text-center">
                                                @if (empty($User->deleted_at))
                                                    @can('user.edit')
                                                        <x-button-icon icon="ti ti-edit" componentType="a" color="info" style="light" href="{{ route('admin.user.edit', $User->id) }}" />
                                                    @endcan

                                                    @if (auth()->user()->can('user.destroy') && auth()->id() != $User->id)
                                                        <x-button-icon type="button" icon="ti ti-trash" color="danger" style="light" class="destroy" data-link="{{ route('admin.user.destroy', $User->id) }}" />
                                                    @endif
                                                @else
                                                    @can('user.restore')
                                                        <x-button-icon type="button" icon="ti ti-upload" color="secondary" style="light" class="restore" data-link="{{ route('admin.user.restore', $User->id) }}" />
                                                    @endcan
                                                @endif

                                                @can('log.show')
                                                    <x-button-icon icon="ti ti-notes" color="warning" style="light" componentType="a" href="{{ route('admin.log.show', $User->log->id) }}" />
                                                @endcan
                                            </td>
                                        </tr>
                                    @empty
                                        @if (empty(request()->all()))
                                            <tr id="emptyTable">
                                                <td colspan="6" class="text-center">
                                                    <h3>Nenhum usuário cadastrado no momento!</h3>
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
                    text: 'Deseja realmente deletar esse usuário?',
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
                    text: 'Deseja realmente restaurar esse usuário?',
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

            @if (!$Users->isEmpty())
                var table = $('#table').DataTable({
                    language: {
                        url: "{{ asset('assets/js/plugins/dataTables.pt_BR.json') }}",
                    },
                });
            @endif
        });
    </script>
@endpush