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
                                <h2 class="mb-0">Cadastrar</h2>
                            </div>
                        </div>
                    </div>
                </div><!-- page-block -->
            </div><!-- page-header -->

            <div class="row">
                <div class="col-md-12">
                    <div class="card py-3">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h4 class="m-0">Cadastrar departamento</h4>

                            <div>
                                @can('departament.index')
                                    <x-button componentType="a" icon="ti ti-clipboard-list" href="{{ route('admin.departament.index') }}" title="Listar departamentos">
                                        <span class="d-none d-lg-inline">Listar departamentos</span>
                                    </x-button>
                                @endcan
                            </div>
                        </div><!-- card-header -->

                        <div class="card-body py-3">
                            <x-alerts.success class="mb-4" />
                            <x-alerts.errors class="mb-4" />

                            <form method="POST" action="{{ route('admin.departament.store') }}" id="form-create">
                                @csrf

                                {{-- [input] - Departamento --}}
                                <div class="row">
                                    <div class="col-12">
                                        <div class="row my-3 align-items-center">
                                            <label class="col-12 col-md-3 col-xxl-2 col-form-label required" for="name">Departamento :</label>
                                            <div class="col-12 col-md-9 col-xxl-10">
                                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Informe o nome do departamento" required />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div><!-- card-body -->

                        <div class="card-footer py-3">
                            <x-button icon="ti ti-checks" form="form-create">
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
