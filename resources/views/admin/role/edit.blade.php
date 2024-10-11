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
                                Editar função
                                <i class="ti ti-chevrons-right text-primary position-relative" style="top: 2px"></i>
                                {{ $Role->name }}
                            </h4>

                            <div>
                                @can('role.index')
                                    <x-button componentType="a" icon="ti ti-clipboard-list" href="{{ route('admin.role.index') }}">
                                        Listar funções
                                    </x-button>
                                @endcan
                            </div>
                        </div><!-- card-header -->

                        <div class="card-body py-3">
                            <x-alerts.success class="mb-4" />
                            <x-alerts.errors class="mb-4" :errors="$errors" />

                            <form method="POST" action="{{ route('admin.role.update', $Role->id) }}" id="form-edit">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-12">
                                        {{-- [input] - Nome da função --}}
                                        <div class="row my-3">
                                            <label class="col-2 col-form-label required" for="name">Nome da função:</label>
                                            <div class="col-10 d-flex align-items-center">
                                                <input type="text" class="form-control" id="name" name="name" value="{{ !empty(old('name')) ? old('name') : $Role->name }}" aria-describedby="Nome da função a ser criada" placeholder="Informe o nome da função." />
                                            </div>
                                        </div>

                                        {{-- [input] - Guarda da função --}}
                                        {{-- <div class="row my-3">
                                            <label class="col-2 col-form-label required" for="guard_name">Guarda da função:</label>
                                            <div class="col-10 d-flex align-items-center">
                                                <input type="text" class="form-control" id="guard_name" name="guard_name" value="{{ !empty(old('guard_name')) ? old('guard_name') : $Role->guard_name }}" aria-describedby="Guarda da função a ser criada" placeholder="Informe o guarda da função." required />
                                            </div>
                                        </div> --}}

                                        {{-- [input] - Permissões --}}
                                        <div class="row permissions">
                                            <div class="col-12">
                                                <h4 class="border-bottom pb-3 mb-3">Permissões</h4>

                                                <div class="row m-0">
                                                    @foreach ($PermissionsGroupByName as $PermissionGroup => $Permissions)
                                                        <div class="permission-wrapper d-flex align-content-stretch">
                                                            <div class="p-4 mx-2 my-3 flex-fill border border-info rounded">
                                                                <div class="pb-3 mb-3 d-flex align-content-center justify-content-between border-bottom">
                                                                    <h4 class="m-0">{{ $PermissionGroup }}</h4>
                                                                    <input class="form-check-input input-primary all-check {{ explode('.', $Permissions[0]->name)[0] }}" data-group="{{ explode('.', $Permissions[0]->name)[0] }}" type="checkbox" value="" />
                                                                </div>

                                                                @foreach ($Permissions as $Permission)
                                                                    <div class="form-check mb-2">
                                                                        <input class="form-check-input input-primary single-check {{ explode('.', $Permission->name)[0] }}" type="checkbox" name="permissions[]" value="{{ $Permission->id }}" data-value="{{ $Permission->name }}" data-group="{{ explode('.', $Permission->name)[0] }}" {{ !empty(old('permissions')) ? (in_array($Permission->id, old('permissions')) ? 'checked' : '') : ($Role->hasPermissionTo($Permission->name) ? 'checked' : '') }} />
                                                                        <label class="form-check-label" for="{{ $Permission->name }}">{{ $Permission->name }}</label>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div><!-- permissions -->
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
    <style>
        .permission-wrapper {
            width: calc(100% / 5);
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {

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

                $(group).prop('checked', is_checked);
            });

            $('.single-check').on('change', function() {
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
