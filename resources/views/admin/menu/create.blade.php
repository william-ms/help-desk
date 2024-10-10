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
                            <h4 class="m-0">Cadastrar menu</h4>

                            <div>
                                @can('menu.index')
                                    <x-button componentType="a" icon="ti ti-clipboard-list" href="{{ route('admin.menu.index') }}">
                                        Listar menus
                                    </x-button>
                                @endcan
                            </div>
                        </div><!-- card-header -->

                        <div class="card-body py-3">
                            <x-alerts.success class="mb-4" />
                            <x-alerts.errors class="mb-4" />

                            <form method="POST" action="{{ route('admin.menu.store') }}" id="form-create">
                                @csrf

                                <div class="row">
                                    <div class="col-12">
                                        <!-- [input] - Categoria do menu -->
                                        <div class="row my-3">
                                            <label class="col-2 col-form-label required" for="menu_category_id">Categoria :</label>
                                            <div class="col-10 d-flex align-items-center">
                                                <select class="form-control" id="menu_category_id" name="menu_category_id" required>
                                                    <option value="">Selecione uma categoria</option>

                                                    @foreach ($MenuCategories as $MenuCategory)
                                                        <option value="{{ $MenuCategory->id }}" @if (old('menu_category_id') == $MenuCategory->id) selected @endif>{{ $MenuCategory->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- [input] - Nome do menu -->
                                        <div class="row my-3">
                                            <label class="col-2 col-form-label required" for="name">Menu :</label>
                                            <div class="col-10 d-flex align-items-center">
                                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Informe o nome do menu." required />
                                            </div>
                                        </div>

                                        <!-- [input] - Ícone do menu -->
                                        <div class="row my-3">
                                            <label class="col-2 col-form-label required" for="icon">Ícone :</label>
                                            <div class="col-10 d-flex align-items-center">
                                                <select name="icon" id="icon" class="form-control" data-live-search="true" required>
                                                    <option value="">Selecione um ícone</option>

                                                    @foreach ($Icons as $Icon)
                                                        <option value="ph-duotone {{ $Icon }}" data-icon="ph-duotone {{ $Icon }}" @if (old('icon') == 'ph-duotone ' . $Icon) selected @endif>{{ $Icon }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- [input] - Prefixo da rota -->
                                        <div class="row my-3">
                                            <label class="col-2 col-form-label required" for="route">Prefixo da rota :</label>
                                            <div class="col-10 d-flex align-items-center">
                                                <input type="text" class="form-control" id="route" name="route" value="{{ old('route') }}" placeholder="Informe o prefixo da rota." required />
                                            </div>

                                            <div class="col-2"></div>
                                            <div class="col-10">
                                                <p class="ms-2 mb-0 f-12">Informe o prefixo da rota que você deseja criar o menu. ex: se a rota principal for <code>categoria.index</code> informe apenas <code>categoria</code>.</p>
                                                <p class="ms-2 mb-0 f-12">A rota deve possuir apenas letras, use <code>_</code> caso precise separar as palavras.</p>
                                            </div>
                                        </div>

                                        <!-- [input] - Permissões do menu -->
                                        <div class="row my-3">
                                            <label class="col-2 col-form-label" for="permission">Permissões :</label>
                                            <div class="col-10 d-flex align-items-center">
                                                <select name="permission" id="permission" class="form-control">
                                                    <option value="1">Não</option>
                                                    <option value="2" @if (!empty(old('permissions'))) selected @endif>Sim</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div id="permissions-wrapper" style="display: none">
                                            <hr class="my-4">

                                            <p class="text-primary mb-1">Selecione as permissões que deseja criar, ao criar o menu.</p>

                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th width="20%">Permissão</th>
                                                        <th><i class="ti ti-settings f-18"></i></th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @foreach ($preset_permissions as $permission)
                                                        <tr>
                                                            <td>{{ $permission['label'] }}</td>
                                                            <td>
                                                                <div class="form-check">
                                                                    <input type="checkbox" name="permissions[]" value="{{ $permission['name'] }}" class="form-check-input input-{{ $permission['color'] }}" @if (!empty(old('permissions')) && in_array($permission['name'], old('permissions'))) checked @endif>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>

                                                <tfoot>
                                                    <tr>
                                                        <td colspan="2">
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input input-info disable" checked />
                                                                <span>Não pode causar danos ao sistema</span>
                                                            </div>

                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input input-warning disable" checked />
                                                                <span>Talvez cause danos ao sistema</span>
                                                            </div>

                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input input-danger disable" checked />
                                                                <span>Pode causar danos ao sistema</span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div><!-- card-footer -->

                        <div class="card-footer py-3">
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

            if ($('#permission').is(":checked")) {
                $('#permissions-wrapper').slideDown();
            }

            $('#permission').on('change', function() {
                if ($('#permission').val() == 2) {
                    $('#permissions-wrapper').slideDown();
                } else {
                    $('#permissions-wrapper').slideUp();
                }
            });
            if ($('#permission').val() == 2) {
                $('#permissions-wrapper').slideDown();
            } else {
                $('#permissions-wrapper').slideUp();
            }

            $(".disable").on("click", (e) => {
                e.preventDefault();
                return false
            })
        });
    </script>
@endpush
