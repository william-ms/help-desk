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
                            <h4 class="m-0">Cadastrar usuário</h4>

                            <div>
                                @can('menu.index')
                                    <x-button componentType="a" icon="ti ti-clipboard-list" href="{{ route('admin.user.index') }}" title="Listar usuários">
                                        <span class="d-none d-lg-inline">Listar usuários</span>
                                    </x-button>
                                @endcan
                            </div>
                        </div><!-- card-header -->

                        <div class="card-body py-3">
                            <x-alerts.success class="mb-4" />
                            <x-alerts.errors class="mb-4" />

                            <form method="POST" action="{{ route('admin.user.store') }}" id="form-create">
                                @csrf

                                <div class="row">
                                    <div class="col-12">
                                        <!-- [input] - Nome -->
                                        <div class="row my-3 align-items-center">
                                            <label class="col-12 col-md-3 col-xxl-2 col-form-label required" for="name">Nome :</label>
                                            <div class="col-12 col-md-9 col-xxl-10">
                                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Informe o nome do usuário." required />
                                            </div>
                                        </div>

                                        <!-- [input] - Email -->
                                        <div class="row my-3 align-items-center">
                                            <label class="col-12 col-md-3 col-xxl-2 col-form-label required" for="email">Email :</label>
                                            <div class="col-12 col-md-9 col-xxl-10">
                                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Informe o email do usuário." required />
                                            </div>
                                        </div>

                                        <!-- [input] - Senha -->
                                        <div class="row my-3 align-items-center">
                                            <label class="col-12 col-md-3 col-xxl-2 col-form-label required" for="password">Senha :</label>
                                            <div class="col-12 col-md-9 col-xxl-10">
                                                <input type="password" class="form-control" id="password" name="password" value="{{ old('password') }}" placeholder="Informe a senha do usuário." required />
                                            </div>
                                        </div>

                                        {{-- [select] - Empresas --}}
                                        <div class="row mb-3 align-items-center">
                                            <label class="col-12 col-md-3 col-xxl-2 col-form-label required" for="companies">Empresas :</label>
                                            <div class="col-12 col-md-9 col-xxl-10">
                                                <select class="form-control" id="companies" name="companies[]" data-live-search="true" multiple required>
                                                    @foreach ($Companies as $Company)
                                                        <option value="{{ $Company->id }}" {{ collect(old('companies'))->contains($Company->id) ? 'selected' : '' }}>
                                                            {{ $Company->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        {{-- [select] - Departamentos --}}
                                        <div class="row mb-3 align-items-center">
                                            <label class="col-12 col-md-3 col-xxl-2 col-form-label required" for="departaments">Departamentos :</label>
                                            <div class="col-12 col-md-9 col-xxl-10">
                                                <select class="form-control" id="departaments" name="departaments[]" data-live-search="true" multiple required>
                                                    @foreach ($Departaments as $Departament)
                                                        <option value="{{ $Departament->id }}" {{ collect(old('departaments'))->contains($Departament->id) ? 'selected' : '' }}>
                                                            {{ $Departament->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        {{-- [select] - Função --}}
                                        <div class="row my-3 align-items-center">
                                            <label class="col-12 col-md-3 col-xxl-2 col-form-label required" for="role">Função do usuário:</label>
                                            <div class="col-12 col-md-9 col-xxl-10">
                                                <select class="form-control" id="role" name="role" data-live-search="true" required>
                                                    <option value="">Selecione uma função</option>

                                                    @foreach ($Roles as $Role)
                                                        @if (!auth()->user()->hasRole(1) && $Role->id == 1)
                                                            @continue
                                                        @endif

                                                        <option class="role-{{ $Role->id }}" value="{{ $Role->id }}" data-permissions="{{ json_encode($Role->permissions->pluck('name')->toArray()) }}" @if (!empty(old('role')) && old('role') == $Role->id) selected @endif>{{ $Role->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        {{-- [input] - Permissões --}}
                                        @if (auth()->user()->can('user.permissions'))
                                            <div class="row" id="permissions">
                                                <div class="col-12">
                                                    <h4 class="border-bottom pb-3 mb-3">Permissões</h4>

                                                    <div class="form-check ms-4">
                                                        <input type="checkbox" class="form-check-input input-warning disabled" checked />
                                                        <span>Pertence à função</span>
                                                    </div>

                                                    <div class="form-check ms-4">
                                                        <input type="checkbox" class="form-check-input input-info disabled" checked />
                                                        <span>Não pertence à função</span>
                                                    </div>

                                                    <div class="row m-0">
                                                        @foreach ($PermissionsGroupByName as $PermissionGroup => $Permissions)
                                                            <div class="col-12 col-md-6 col-xl-4 p-2 d-flex align-content-stretch">
                                                                <div class="p-4 flex-fill border border-info rounded">
                                                                    <div class="form-check p-0 pb-3 mb-3 d-flex align-items-center justify-content-between border-bottom">
                                                                        <h4 class="m-0">{{ $PermissionGroup }}</h4>
                                                                        <input class="form-check-input input-info all-check {{ explode('.', $Permissions[0]->name)[0] }}" data-group="{{ explode('.', $Permissions[0]->name)[0] }}" type="checkbox" value="" />
                                                                    </div>

                                                                    @foreach ($Permissions as $Permission)
                                                                        <div class="form-check mb-2">
                                                                            <input class="form-check-input input-info single-check {{ explode('.', $Permission->name)[0] }}" type="checkbox" name="permissions[{{ $Permission->id }}]" value="{{ $Permission->name }}" data-value="{{ $Permission->name }}" data-group="{{ explode('.', $Permission->name)[0] }}" {{ !empty(old('permissions')) && in_array($Permission->name, old('permissions')) ? 'checked' : '' }} />
                                                                            <label class="form-check-label" for="{{ $Permission->name }}">{{ $Permission->name }}</label>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div><!-- permissions -->

                                            <div id="admin-alert" class="d-none">
                                                <x-alerts.custom status="warning" icon="ti ti-alert-triangle" title="Atenção">
                                                    <span>O usuário com a função de <b>{{ $Roles->find(1)->name }}</b> possui todas as permissões do sistema!</span>
                                                </x-alerts.custom>
                                            </div>
                                        @endif
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
    <script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            //=========================== SELECT PICKER ==========================//
            $('#role').selectpicker();

            new Choices('#companies', {
                removeItemButton: true,
                placeholderValue: 'Selecione uma empresa',
            });

            new Choices('#departaments', {
                removeItemButton: true,
                placeholderValue: 'Selecione um departamento',
            });

            //========================== ALTERAR FUNÇÃO ==========================//
            let permissions_checked = $('.single-check:checked');
            let role = $('#role');
            let role_selected = role.find('.role-' + role.val());
            let role_permissions = role_selected.data('permissions');

            if (role.val() == 1) {
                $('#permissions').slideUp();
                $('#admin-alert').removeClass('d-none');
            }

            if (role.val() != 1) {
                permissions_checked.each(function(key, permission) {
                    if (role_permissions.includes($(permission).data('value'))) {
                        $(permission).removeClass('input-info');
                        $(permission).addClass('input-warning');
                        $(permission).addClass('disabled');
                    }
                })
            }

            $('#role').on('change', function() {
                let role_value = $(this).val();
                let all_permissions = $(".permissions-wrapper input[type='checkbox']");

                all_permissions.prop('checked', false).removeClass('disabled').removeClass('input-warning').addClass('input-info');

                if (role_value == 1) {
                    $('#permissions').slideUp();
                    $('#admin-alert').removeClass('d-none');
                } else {
                    $('#permissions').slideDown();
                    $('#admin-alert').addClass('d-none');

                    if (role_value == '') {
                        return;
                    }

                    let role = $('#role').find('.role-' + role_value);

                    if (role.length > 0) {
                        let permissions = role.data('permissions');
                        let single_check;

                        permissions.forEach(function(permission, key) {
                            single_check = $(`.single-check[data-value="${permission}"]`);

                            if (!single_check.prop('checked')) {
                                single_check.click();
                                single_check.removeClass('input-info');
                                single_check.addClass('input-warning');
                                single_check.addClass('disabled');
                            }
                        })
                    }
                }
            })

            //======================= SELECIONAR PERMISSÕES ======================//
            let all_checked, is_checked, group, prefix_route;
            let permissions = @json($PermissionsGroupByName);

            for (group in permissions) {

                prefix_route = permissions[group][0].name.split('.')[0];
                all_checked = true;

                for (key in permissions[group]) {
                    if (!$(`.single-check[data-value="${permissions[group][key].name}"]`).prop('checked')) {
                        all_checked = false;
                    }
                }

                $('.all-check.' + prefix_route).prop('checked', all_checked);
            }

            $('.all-check').on('change', function() {
                is_checked = $(this).prop('checked');

                group = '.single-check.' + $(this).data('group');

                $(group).each(function(key, permission) {
                    if (!$(permission).hasClass('disabled')) {
                        $(permission).prop('checked', is_checked);
                    }
                })
            });

            $('.single-check').on('click', function(e) {
                if ($(this).hasClass('disabled')) {
                    e.preventDefault();
                }
            });

            $('.disabled').on('click', function(e) {
                e.preventDefault();
            });

            $('.single-check').on('change', function(e) {
                group = '.' + $(this).data('group');
                all_checked = true;

                $('.single-check' + group).each(function(key, item) {
                    if (!$(item).prop('checked')) {
                        all_checked = false;
                    }
                })

                $('.all-check' + group).prop('checked', all_checked);
            })
        });
    </script>
@endpush
