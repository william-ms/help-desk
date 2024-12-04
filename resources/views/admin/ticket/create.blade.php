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
                            <h4 class="m-0">Cadastrar ticket</h4>

                            <div>
                                @can('ticket.index')
                                    <x-button componentType="a" icon="ti ti-clipboard-list" href="{{ route('admin.ticket.index') }}">
                                        Listar tickets
                                    </x-button>
                                @endcan
                            </div>
                        </div><!-- card-header -->

                        <div class="card-body py-3">
                            <x-alerts.success class="mb-4" />
                            <x-alerts.errors class="mb-4" />

                            <form method="POST" action="{{ route('admin.ticket.store') }}" id="form-create">
                                @csrf

                                @if ($Companies->count() > 1)
                                    {{-- [select] - Empresa --}}
                                    <div class="row my-3 align-items-center">
                                        <label class="col-2 col-form-label required" for="company_id">Empresa :</label>
                                        <div class="col-10">
                                            <select class="form-control" id="company_id" name="company_id" data-live-search="true" required>
                                                <option value="">Selecione uma empresa</option>

                                                @foreach ($Companies as $Company)
                                                    <option value="{{ $Company->id }}" {{ !empty(old('company_id')) && old('company_id') == $Company->id ? 'selected' : '' }}>
                                                        {{ $Company->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif

                                @if ($Departaments->count() > 1)
                                    {{-- [select] - Departamento --}}
                                    <div class="row my-3 align-items-center">
                                        <label class="col-2 col-form-label required" for="departament_id">Departamento :</label>
                                        <div class="col-10">
                                            <select class="form-control" id="departament_id" name="departament_id" data-live-search="true" required>
                                                <option value="">Selecione um departamento</option>

                                                @foreach ($Departaments as $Departament)
                                                    <option value="{{ $Departament->id }}" class="company-{{ $Departament->company_id }}" {{ !empty(old('departament_id')) && old('departament_id') == $Departament->id ? 'selected' : '' }}>
                                                        {{ $Departament->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif

                                {{-- [select] - Categoria --}}
                                <div class="row my-3 align-items-center">
                                    <label class="col-2 col-form-label required" for="category_id">Categoria :</label>
                                    <div class="col-10">
                                        <select class="form-control" id="category_id" name="category_id" data-live-search="true" required>
                                            <option value="">Selecione uma categoria</option>

                                            @foreach ($Categories as $Category)
                                                <option value="{{ $Category->id }}" class="departament-{{ $Category->departament_id }}" {{ !empty(old('category_id')) && old('category_id') == $Category->id ? 'selected' : '' }}>
                                                    {{ $Category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- [select] - Subcategoria --}}
                                <div class="row my-3 align-items-center">
                                    <label class="col-2 col-form-label" for="subcategory_id">Subcategoria :</label>
                                    <div class="col-10">
                                        <select class="form-control" id="subcategory_id" name="subcategory_id" data-live-search="true">
                                            <option value="">Selecione uma subcategoria</option>

                                            @foreach ($Subcategories as $Subcategory)
                                                <option value="{{ $Subcategory->id }}" class="category-{{ $Subcategory->category_id }}" {{ !empty(old('subcategory_id')) && old('subcategory_id') == $Subcategory->id ? 'selected' : '' }}>
                                                    {{ $Subcategory->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- [input] - Assunto --}}
                                <div class="row my-3 align-items-center">
                                    <label class="col-2 col-form-label required" for="subject">Assunto :</label>
                                    <div class="col-10">
                                        <input type="text" class="form-control" id="subject" name="subject" value="{{ old('subject') }}" placeholder="Informe um assunto" required />
                                    </div>
                                </div>

                                {{-- [input] - Descrição --}}
                                <div class="row my-3 align-items-center">
                                    <label class="col-2 col-form-label required" for="description">Descrição :</label>
                                    <div class="col-10">
                                        <textarea class="form-control" id="description" rows="10" name="description" placeholder="Informe uma descrição">{{ old('description') }}</textarea>
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

                    {{-- [modal] - Resposta Automática --}}
                    <div id="automatic-response" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="Resposta automática" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header d-block">
                                    <h3 id="automatic-response-title"></h3>
                                    <h5>Resposta automática</h5>
                                </div>

                                <div class="modal-body">
                                    <div id="automatic-response-content"></div>
                                </div>

                                <div class="modal-footer">
                                    <x-button id="btn-close-reply" icon="ti ti-x" color="info" data-bs-dismiss="modal">Não ajudou!</x-button>
                                    <x-button componentType="a" href="{{ route('admin.ticket.index') }}" icon="ti ti-checks" color="danger">Resolveu o problema (Sair)</x-button>
                                </div>
                            </div>
                        </div>
                    </div><!-- automatic-response -->

                    {{-- [modal] - Imagem da resposta --}}
                    <div id="show-response-image" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header justify-content-end">
                                    <x-button id="btn-close-reply" icon="ti ti-x" color="danger" data-bs-dismiss="modal"></x-button>
                                </div>

                                <div class="modal-body">
                                    <img src="" class="w-100" />
                                </div>
                            </div>
                        </div>
                    </div><!-- show-response-image -->
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

    <x-tinymce-config textarea="#description" />

    <script type="text/javascript">
        $(document).ready(function() {
            $('select').selectpicker();

            @if ($Companies->count() > 1)
                //:::::::::::::::::::: ADICIONAR DEPARTAMENTOS DE ACORDO COM A EMPRESA SELECIONADA ::::::::::::::::::://
                function show_departaments(company_id) {
                    $('#departament_id option:not(":first-child")').prop('hidden', true);
                    $('.company-' + company_id).prop('hidden', false);

                    show_categories($('#departament_id').val());
                    show_subcategories($('#category_id').val());

                    if (company_id) {
                        $('#departament_id').attr('disabled', false);
                        $('#departament_id option:first-child').text('Selecione um departamento');
                    } else {
                        $('#departament_id').attr('disabled', true);
                        $('#departament_id option:first-child').text('Selecione uma empresa para ver os departamentos');
                    }

                    $('#departament_id').selectpicker('refresh');
                }

                //:::::::::: AO CARREGAR A PÁGINA :::::::::://
                show_departaments($('#company_id').val());

                //:::::::::: AO ALTERAR A EMPRESA :::::::::://
                $('#company_id').on('change', function() {
                    $('#departament_id option:first-child').prop('selected', true);
                    show_departaments($(this).val());
                });
            @endif

            @if ($Departaments->count() > 1)
                //::::::::::::::::::: ADICIONAR CATEGORIAS DE ACORDO COM O DEPARTAMENTO SELECIONADO :::::::::::::::::://
                function show_categories(departament_id) {
                    $('#category_id option:not(":first-child")').prop('hidden', true);
                    $('.departament-' + departament_id).prop('hidden', false);

                    show_subcategories($('#category_id').val());

                    if (departament_id) {
                        $('#category_id').attr('disabled', false);
                        $('#category_id option:first-child').text('Selecione uma categoria');
                    } else {
                        $('#category_id').attr('disabled', true);
                        $('#category_id option:first-child').prop('selected', true);
                        $('#category_id option:first-child').text('Selecione um departamento para ver as categorias');
                    }

                    $('#category_id').selectpicker('refresh');
                }

                //:::::::::: AO CARREGAR A PÁGINA :::::::::://
                show_categories($('#departament_id').val());

                //:::::::::: AO ALTERAR A EMPRESA :::::::::://
                $('#departament_id').on('change', function() {
                    $('#category_id option:first-child').prop('selected', true);
                    show_categories($(this).val());
                });
            @endif

            //::::::::::::::::::::: ADICIONAR SUBCATEGORIAS DE ACORDO COM A CATEGORIA SELECIONADA :::::::::::::::::::://
            function show_subcategories(category_id) {
                $('#subcategory_id option:not(":first-child")').prop('hidden', true);
                $('.category-' + category_id).prop('hidden', false);

                if (category_id) {
                    $('#subcategory_id').attr('disabled', false);
                    $('#subcategory_id option:first-child').text('Selecione uma subcategoria');
                } else {
                    $('#subcategory_id').attr('disabled', true);
                    $('#subcategory_id option:first-child').prop('selected', true);
                    $('#subcategory_id option:first-child').text('Selecione uma categoria para ver as subcategorias');
                }

                $('#subcategory_id').selectpicker('refresh');
            }

            //:::::::::: AO CARREGAR A PÁGINA :::::::::://
            show_subcategories($('#category_id').val());

            //:::::::::: AO ALTERAR A EMPRESA :::::::::://
            $('#category_id').on('change', function() {
                $('#subcategory_id option:first-child').prop('selected', true);
                show_subcategories($(this).val());
            });

            //:::::::::::::::::::::::::::::::::::::: EXIBIR RESPOSTA AUTOMÁTICA :::::::::::::::::::::::::::::::::::::://
            function show_automatic_response(type, id) {

                if (id == '') {
                    return;
                }

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var posting = $.post("{{ route('ajax.ticket.get_automatic_response') }}", {
                    type,
                    id,
                }, "json");

                posting.done(function(data) {
                    if (data.automatic_response == null) {
                        return;
                    }

                    $('#automatic-response-title').html(data.title);
                    $('#automatic-response-content').html(data.automatic_response);
                    $('#automatic-response').modal('show');
                });
            }

            $('#category_id').on('change', function() {
                show_automatic_response('category', $('#category_id').val());
            });

            $('#subcategory_id').on('change', function() {
                show_automatic_response('subcategory', $('#subcategory_id').val());
            });

            //::::::::::::::::::::::::::::::::: EXIBIR IMAGEM DA RESPOSTA AUTOMÁTICA ::::::::::::::::::::::::::::::::://
            $(document).on("click", '.response-image', function() {
                $('#show-response-image img').attr('src', $(this).attr('src'));
                $('#show-response-image').modal('show');
            })
        });
    </script>
@endpush
