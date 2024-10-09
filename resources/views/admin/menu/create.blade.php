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
                                'name' => 'Menus',
                                'route' => 'admin.menu.index',
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
                            <h4 class="mt-2">Cadastrar menu</h4>

                            <div>
                                @can('menu.index')
                                    <x-button componentType="a" icon="ti ti-clipboard-list" href="{{ route('admin.menu.index') }}">
                                        Listar menus
                                    </x-button>
                                @endcan
                            </div>
                        </div><!-- card-header -->

                        <div class="card-body">
                            <x-alerts.success class="mb-4" />
                            <x-alerts.errors class="mb-4" />

                            <form method="POST" action="{{ route('admin.menu.store') }}" id="form-create">
                                @csrf

                                <div class="row">
                                    <div class="col-12">
                                        <!-- [input] - Categoria do menu -->
                                        <div class="row mb-3">
                                            <label class="col-2 col-form-label required" for="menu_category_id">Categoria :</label>
                                            <div class="col-10">
                                                <select class="form-control" id="menu_category_id" name="menu_category_id" aria-describedby="Categoria a qual o menu pertence" placeholder="Selecione a categoria na lista abaixo." required>
                                                    <option value="">Selecione uma categoria</option>

                                                    @foreach ($MenuCategories as $MenuCategory)
                                                        <option value="{{ $MenuCategory->id }}" @if (old('menu_category_id') == $MenuCategory->id) selected @endif>{{ $MenuCategory->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- [input] - Nome do menu -->
                                        <div class="row mb-3">
                                            <label class="col-2 col-form-label required" for="name">Menu :</label>

                                            <div class="col-10">
                                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" aria-describedby="Menu a ser criado" placeholder="Informe o nome do menu." required />
                                            </div>
                                        </div>

                                        <!-- [input] - Ícone do menu -->
                                        <div class="row mb-3">
                                            <label class="col-2 col-form-label required" for="icon">Ícone :</label>
                                            <div class="col-10">
                                                <select name="icon" id="icon" class="form-control" data-live-search="true" aria-describedby="Ícone para o menu" placeholder="Selecione o ícone na lista abaixo." required>
                                                    <option value="">Selecione um ícone</option>

                                                    @foreach ($Icons as $Icon)
                                                        <option value="ph-duotone {{ $Icon }}" data-icon="ph-duotone {{ $Icon }}" @if (old('icon') == 'ph-duotone ' . $Icon) selected @endif>{{ $Icon }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- [input] - Prefixo da rota -->
                                        <div class="row mb-3">
                                            <label class="col-2 col-form-label required" for="route">Prefixo da rota :</label>

                                            <div class="col-10">
                                                <input type="text" class="form-control" id="route" name="route" value="{{ old('route') }}" aria-describedby="Rota para o menu" placeholder="Informe o prefixo da rota." required />
                                                <p class="m-0 ms-2 f-12">Informe o prefixo da rota que você deseja criar o menu. ex: se a rota principal for <code>categoria.index</code> informe apenas <code>categoria</code>.</p>
                                                <p class="m-0 ms-2 f-12">Use apenas letras, se necessário, use <code>_</code> para separar as palavras.</p>
                                            </div>
                                        </div>

                                        <!-- [input] - Permissões do menu -->
                                        <div class="row mb-2 align-items-center">
                                            <label class="col-2 col-form-label" for="permission">Permissões :</label>

                                            <div class="col-10">
                                                <select name="permission" id="permission" class="form-control">
                                                    <option value="1">Não</option>
                                                    <option value="2">Sim</option>
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
                                                                    <input type="checkbox" name="permissions[]" value="{{ $permission['name'] }}" class="form-check-input input-{{ $permission['color'] }}" />
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
