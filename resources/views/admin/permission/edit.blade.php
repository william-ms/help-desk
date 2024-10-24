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
                                Editar permissão
                                <i class="ti ti-chevrons-right text-primary position-relative" style="top: 2px"></i>
                                {{ $Permission->name }}
                            </h4>

                            <div>
                                @can('permission.index')
                                    <x-button componentType="a" icon="ti ti-clipboard-list" href="{{ route('admin.permission.index') }}">
                                        Listar permissões
                                    </x-button>
                                @endcan
                            </div>
                        </div><!-- card-header -->

                        <div class="card-body py-3">
                            <x-alerts.success class="mb-4" />
                            <x-alerts.errors class="mb-4" :errors="$errors" />

                            <form method="POST" action="{{ route('admin.permission.update', $Permission->id) }}" id="form-edit">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-12">
                                        {{-- [input] - Menu --}}
                                        <div class="row my-3">
                                            <label class="col-2 col-form-label" for="menu_id">Menu :</label>
                                            <div class="col-10 d-flex align-items-center">
                                                <select class="form-control" id="menu_id" name="menu_id" data-live-search="true">
                                                    <option value="">Selecione um menu</option>

                                                    @foreach ($Menus as $Menu)
                                                        <option value="{{ $Menu->id }}" data-route="{{ $Menu->route }}" {{ !empty(old('menu_id')) && old('menu_id') == $Menu->id ? 'selected' : ($Menu->route == explode('.', $Permission->name)[0] ? 'selected' : '') }}>{{ $Menu->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        {{-- [input] - Prefixo da rota --}}
                                        <div class="row my-3 d-none">
                                            <label class="col-2 col-form-label required" for="route-prefix">Prefixo da rota:</label>

                                            <div class="col-10  d-flex align-items-center">
                                                <input type="text" class="form-control" id="route-prefix" name="route_prefix" value="{{ $Permission->prefix }}" placeholder="Informe o prefixo da rota" required />
                                            </div>

                                            <div class="col-2"></div>
                                            <div class="col-10">
                                                <p class="ms-2 mb-0 f-12">Informe o prefixo da rota que você deseja criar o menu. ex: se a rota principal for <code>categoria.index</code> informe apenas <code>categoria</code>.</p>
                                                <p class="ms-2 mb-0 f-12">O prefixo da rota deve possuir apenas letras, use <code>_</code> caso precise separar as palavras.</p>
                                            </div>
                                        </div>

                                        {{-- [input] - Método da rota --}}
                                        <div class="row my-3">
                                            <label class="col-2 col-form-label required" for="route-method">Método da rota:</label>
                                            <div class="col-10 d-flex align-items-center">
                                                <input type="text" class="form-control" id="route-method" name="route_method" value="{{ $Permission->method }}" placeholder="Informe o método da rota" required />
                                            </div>

                                            <div class="col-2"></div>
                                            <div class="col-10">
                                                <p class="ms-2 mb-0 f-12">Informe o método da rota que você deseja criar o menu. ex: se a rota principal for <code>categoria.index</code> informe apenas <code>index</code>.</p>
                                                <p class="ms-2 mb-0 f-12">O método da rota deve possuir apenas letras, use <code>_</code> caso precise separar as palavras.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div><!-- card-body -->

                        <div class="card-footer py-3">
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

    <script type="text/javascript">
        $(document).ready(function() {
            $('select').selectpicker();

            //:::::::::::::::::::::::::::::::::::::::: ALTERAR PREFIXO DA ROTA ::::::::::::::::::::::::::::::::::::::://
            let route = '';
            let input_route_prefix = $('#route-prefix');
            let wrapper_route_prefix = input_route_prefix.parent().parent();

            function change_route_prefix(route) {
                if (route) {
                    input_route_prefix.val(route);
                    wrapper_route_prefix.addClass('d-none');
                } else {
                    input_route_prefix.val('');
                    wrapper_route_prefix.removeClass('d-none');
                }
            }

            //:::::::::: AO CARREGAR A PÁGINA :::::::::://
            change_route_prefix($('#menu_id').find(':selected').data('route'));

            //:::::::::::: AO ALTERAR O MENU ::::::::::://
            $('#menu_id').on('change', function() {
                change_route_prefix($(this).find(':selected').data('route'));
            });
        });
    </script>
@endpush
