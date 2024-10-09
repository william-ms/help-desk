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
                                'name' => 'Categorias de menu',
                                'route' => 'admin.menu_category.index',
                            ],
                            [
                                'name' => 'Cadastrar',
                            ],
                        ]" />

                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">Cadastrar</h2>
                            </div>
                        </div>
                    </div>
                </div><!-- page-block -->
            </div><!-- page-header -->

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between gap-1">
                            <h4 class="mt-2">Cadastrar categoria de menu</h4>

                            <div>
                                @can('menu_category.index')
                                    <x-button componentType="a" icon="ti ti-clipboard-list" href="{{ route('admin.menu_category.index') }}">
                                        Listar categorias
                                    </x-button>
                                @endcan
                            </div>
                        </div><!-- card-header -->

                        <div class="card-body">
                            <x-alerts.success class="mb-4" />
                            <x-alerts.errors class="mb-4" />

                            <form method="POST" action="{{ route('admin.menu_category.store') }}" id="form-create">
                                @csrf

                                <div class="row">
                                    <div class="col-12">
                                        <div class="row mb-3">
                                            <label class="col-2 col-form-label required" for="name">Categoria :</label>
                                            <div class="col-10">
                                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" aria-describedby="Categoria de menu a ser criada" placeholder="Informe o nome da categoria de menu" required />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div><!-- card-footer -->

                        <div class="card-footer">
                            <x-button icon="ti ti-check" form="form-create">
                                Enviar
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
