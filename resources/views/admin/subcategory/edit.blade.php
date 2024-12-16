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
                                <h2 class="mb-0">Editar</h2>
                            </div>
                        </div>
                    </div>
                </div><!-- page-block -->
            </div><!-- page-header -->

            <div class="row">
                <div class="col-md-12">
                    <div class="card py-3">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h4 class="m-0">
                                Editar subcategoria
                                <i class="ti ti-chevrons-right text-primary position-relative" style="top: 2px"></i>
                                {{ $Subcategory->name }}
                            </h4>

                            <div>
                                @can('subcategory.index')
                                    <x-button componentType="a" icon="ti ti-clipboard-list" href="{{ route('admin.subcategory.index') }}">
                                        Listar subcategorias
                                    </x-button>
                                @endcan
                            </div>
                        </div><!-- card-header -->

                        <div class="card-body py-3">
                            <x-alerts.success class="mb-4" />
                            <x-alerts.errors class="mb-4" :errors="$errors" />

                            <form method="POST" action="{{ route('admin.subcategory.update', $Subcategory->id) }}" id="form-edit">
                                @csrf
                                @method('PUT')

                                @if ($Companies->count() > 1)
                                    {{-- [select] - Empresa --}}
                                    <div class="row my-3 align-items-center">
                                        <label class="col-2 col-form-label required" for="company_id">Empresa :</label>
                                        <div class="col-10">
                                            <select class="form-control" id="company_id" name="company_id" data-live-search="true" required>
                                                <option value="">Selecione uma empresa</option>

                                                @foreach ($Companies as $Company)
                                                    <option value="{{ $Company->id }}" {{ !empty(old('company_id')) ? (old('company_id') == $Company->id ? 'selected' : '') : ($Subcategory->category->company_id == $Company->id ? 'selected' : '') }}>
                                                        {{ $Company->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @else
                                    <input type="number" value="{{ $Companies->first()->id }}" name="company_id" id="company_id" hidden />
                                @endif

                                @if ($Departaments->count() > 1)
                                    {{-- [select] - Departamento --}}
                                    <div class="row my-3 align-items-center">
                                        <label class="col-2 col-form-label required" for="departament_id">Departamento :</label>
                                        <div class="col-10">
                                            <select class="form-control" id="departament_id" name="departament_id" data-live-search="true" required>
                                                <option value="">Selecione um departamento</option>

                                                @foreach ($Departaments as $Departament)
                                                    <option value="{{ $Departament->id }}" {{ !empty(old('departament_id')) ? (old('departament_id') == $Departament->id ? 'selected' : '') : ($Subcategory->category->departament_id == $Departament->id ? 'selected' : '') }}>
                                                        {{ $Departament->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @else
                                    <input type="number" value="{{ $Departaments->first()->id }}" name="departament_id" id="departament_id" hidden />
                                @endif

                                {{-- [select] - Categoria --}}
                                <div class="row my-3 align-items-center">
                                    <label class="col-2 col-form-label required" for="category_id">Categoria :</label>
                                    <div class="col-10">
                                        <select class="form-control" id="category_id" name="category_id" data-live-search="true" required>
                                            <option value="">Selecione uma categoria</option>

                                            @foreach ($Categories as $Category)
                                                <option value="{{ $Category->id }}" class="{{ 'tag-' . $Category->company_id . '-' . $Category->departament_id }}" {{ !empty(old('category_id')) ? (old('category_id') == $Category->id ? 'selected' : '') : ($Subcategory->category_id == $Category->id ? 'selected' : '') }}>
                                                    {{ $Category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- [input] - Subcategoria --}}
                                <div class="row my-3 align-items-center">
                                    <label class="col-2 col-form-label required" for="name">Subcategoria :</label>
                                    <div class="col-10">
                                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') ?? ($Subcategory->name ?? '') }}" placeholder="Informe o nome da subcategoria" required />
                                    </div>
                                </div>

                                {{-- [input] -  Resposta automática --}}
                                <div class="row my-3 align-items-center">
                                    <label class="col-2 col-form-label required" for="automatic_response">Resposta automática :</label>
                                    <div class="col-10">
                                        <textarea class="form-control" id="automatic_response" rows="10" name="automatic_response">{{ old('automatic_response') ?? ($Subcategory->automatic_response ?? '') }}</textarea>
                                    </div>
                                </div>
                            </form>
                        </div><!-- card-body -->

                        <div class="card-footer py-3">
                            <x-button icon="ti ti-checks" form="form-edit">
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

            i_company = $('#company_id');
            i_departament = $('#departament_id');
            i_category = $('#category_id');

            //:::::::::::::::::::::::::::::::::::::::: ALTERAR CATEGORIAS :::::::::::::::::::::::::::::::::::::::://
            function change_categories(company_id, departament_id) {
                $('#category_id option:not(":first-child")').prop('hidden', true);
                $('.tag-' + company_id + '-' + departament_id).prop('hidden', false);

                if (!company_id || !departament_id) {
                    i_category.val('')
                        .attr('disabled', true)
                        .find('option:first-child').text('Selecione uma empresa e um departamento para ver as categorias');
                } else {
                    i_category.attr('disabled', false)
                        .find('option:first-child').text('Selecione uma categoria');
                }

                $('#category_id').selectpicker('refresh');
            }

            //:::::::::: AO CARREGAR A PÁGINA :::::::::://
            change_categories(i_company.val(), i_departament.val());

            //:::::::::: AO ALTERAR A EMPRESA :::::::::://
            i_company.on('change', function() {
                change_categories($(this).val(), i_departament.val());
            });

            //:::::::::: AO ALTERAR O DEPARTAMENTO :::::::::://
            i_departament.on('change', function() {
                change_categories(i_company.val(), $(this).val());
            });
        });
    </script>
@endpush
