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
                                'name' => 'Permissões',
                                'route' => 'admin.permission.index',
                            ],
                            [
                                'name' => 'Editar',
                            ],
                        ]" />

                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">Editar</h2>
                            </div>
                        </div>
                    </div>
                </div><!-- page-block -->
            </div><!-- page-header -->

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between gap-1">
                            <h4 class="mt-2">
                                Editar permissão
                                <i class="ti ti-chevrons-right text-primary position-relative" style="top: 2px"></i>
                                {{ $Permission->name }}
                            </h4>

                            <div>
                                @can(auth()->user()->can('permission.index'))
                                    <x-button componentType="a" icon="ti ti-clipboard-list" href="{{ route('admin.permission.index') }}">
                                        Listar permissões
                                    </x-button>
                                @endcan
                            </div>
                        </div><!-- card-header -->

                        <div class="card-body">
                            <x-alerts.success class="mb-4" />
                            <x-alerts.errors class="mb-4" :errors="$errors" />

                            <form method="POST" action="{{ route('admin.permission.update', $Permission->id) }}" id="form-edit">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-12">
                                        <!-- [input] - Prefixo da rota -->
                                        <div class="row mb-3">
                                            <label class="col-2 col-form-label required" for="route_prefix">Prefixo da rota:</label>

                                            <div class="col-10">
                                                <input type="text" class="form-control" id="route_prefix" name="route_prefix" value="{{ $Permission->prefix }}" placeholder="Informe o prefixo da rota" required />
                                                <p>Informe o prefixo da rota para a permissão que você deseja criar. ex: se a rota
                                                    principal for <code>categoria.index</code> informe apenas
                                                    <code>categoria</code>.
                                                </p>
                                            </div>
                                        </div>

                                        <!-- [input] - Método da rota -->
                                        <div class="row mb-3">
                                            <label class="col-2 col-form-label required" for="route_method">Método da rota:</label>
                                            <div class="col-10">
                                                <input type="text" class="form-control" id="route_method" name="route_method" value="{{ $Permission->method }}" placeholder="Informe o método da rota" required />
                                                <p>Informe o método da rota para a permissão que você deseja criar. ex: se a rota
                                                    principal for <code>categoria.index</code> informe apenas
                                                    <code>index</code>.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div><!-- card-body -->

                        <div class="card-footer">
                            <x-button icon="ti ti-check" form="form-edit">
                                Salvar
                            </x-button>

                            <div class="col-md-12 mb-2">
                                <small>
                                    <b class="text-danger">*</b>
                                    <em>Campos obrigatórios.</em>
                                </small>
                            </div>
                        </div><!-- card-footer -->
                    </div><!-- card -->
                </div>
            </div>
        </div><!-- pc-content -->
    </section><!-- pc-container -->
@endsection
