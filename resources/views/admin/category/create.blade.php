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
                            <h4 class="m-0">Cadastrar categoria</h4>

                            <div>
                                @can('category.index')
                                    <x-button componentType="a" icon="ti ti-clipboard-list" href="{{ route('admin.category.index') }}">
                                        Listar categorias
                                    </x-button>
                                @endcan
                            </div>
                        </div><!-- card-header -->

                        <div class="card-body py-3">
                            <x-alerts.success class="mb-4" />
                            <x-alerts.errors class="mb-4" />

                            <form method="POST" action="{{ route('admin.category.store') }}" id="form-create">
                                @csrf

                                {{-- [select] - Empresa --}}
                                <div class="row my-3 align-items-center">
                                    <label class="col-2 col-form-label required" for="company_id">Empresa :</label>
                                    <div class="col-10">
                                        <select class="form-control" id="company_id" name="company_id" data-live-search="true" required>
                                            <option value="">Selecione uma empresa</option>

                                            @foreach ($Companies as $Company)
                                                <option value="{{ $Company->id }}" {{ collect(old('company_id'))->contains($Company->id) ? 'selected' : '' }}>
                                                    {{ $Company->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- [input] - Categoria --}}
                                <div class="row my-3 align-items-center">
                                    <label class="col-2 col-form-label required" for="name">Categoria :</label>
                                    <div class="col-10">
                                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Informe o nome da categoria" required />
                                    </div>
                                </div>

                                {{-- [input] -  Resposta automática --}}
                                <div class="row my-3 align-items-center">
                                    <label class="col-2 col-form-label required" for="automatic_response">Resposta automática :</label>
                                    <div class="col-10">
                                        <textarea class="form-control" id="automatic_response" rows="10" name="automatic_response">{{ old('automatic_response') }}</textarea>
                                    </div>
                                </div>


                                {{-- [input] -  Tempo de resolução --}}
                                <div class="row my-3 align-items-center">
                                    <label class="col-2 col-form-label required" for="automatic_response">Tempo de resolução :</label>
                                    <div class="col-10">
                                        <input type="time" step="2" class="form-control" id="resolution_time" name="resolution_time" value="{{ old('resolution_time') }}" placeholder="Informe o tempo de resoulção" required />
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

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/bootstrap-select.css') }}">

    <style>
        .form-control button {
            background-color: #ffffff !important;
            border: 1px solid #DBE0E5;
        }

        .form-control button[aria-expanded="true"] {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 2px rgba(var(--bs-primary-rgb), 0.2);
            outline: 0;
        }

        .form-control button .filter-option {
            color: #5B6B79;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/plugins/bootstrap-select.js') }}"></script>

    <x-tinymce-config textarea="#automatic_response" />

    <script type="text/javascript">
        $(document).ready(function() {
            $('select').selectpicker();
        });
    </script>
@endpush
