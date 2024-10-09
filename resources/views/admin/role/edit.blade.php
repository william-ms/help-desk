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
                                'name' => 'Funções',
                                'route' => 'admin.role.index',
                            ],
                            [
                                'name' => 'Editar',
                            ],
                        ]" />

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
                    <div class="card">
                        <div class="card-header d-flex justify-content-between gap-1">
                            <h4 class="mt-2">
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

                        <div class="card-body">
                            <x-alerts.success class="mb-4" />
                            <x-alerts.errors class="mb-4" :errors="$errors" />

                            <form method="POST" action="{{ route('admin.role.update', $Role->id) }}" id="form-edit">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-12">
                                        {{-- [input] - Nome da função --}}
                                        <div class="row mb-3">
                                            <label class="col-2 col-form-label required" for="name">Nome da função:</label>
                                            <div class="col-10">
                                                <input type="text" class="form-control" id="name" name="name" value="{{ !empty(old('name')) ? old('name') : $Role->name }}" aria-describedby="Nome da função a ser criada" placeholder="Informe o nome da função." required />
                                            </div>
                                        </div>

                                        {{-- [input] - Guarda da função --}}
                                        {{-- <div class="row mb-3">
                                            <label class="col-2 col-form-label required" for="guard_name">Guarda da função:</label>
                                            <div class="col-10">
                                                <input type="text" class="form-control" id="guard_name" name="guard_name" value="{{ !empty(old('guard_name')) ? old('guard_name') : $Role->guard_name }}" aria-describedby="Guarda da função a ser criada" placeholder="Informe o guarda da função." required />
                                            </div>
                                        </div> --}}

                                        {{-- [input] - Permissões --}}
                                        <div class="row permissions">
                                            <div class="col-12">
                                                <h4>Permissões</h4>
                                                <hr />

                                                <div class="row">
                                                    @foreach ($PermissionsGroupByName as $PermissionGroup => $Permissions)
                                                        <div class="col-2 p-3 m-3 border border-info rounded permission-wrapper">
                                                            <h4 class="mt-2 mb-0">{{ $PermissionGroup }}</h4>
                                                            <hr />

                                                            @foreach ($Permissions as $Permission)
                                                                <div class="form-check mb-2">
                                                                    <input class="form-check-input input-primary" type="checkbox" name="permissions[]" @if ($Role->hasPermissionTo($Permission->name)) checked @endif id="{{ $Permission->name }}" value="{{ $Permission->id }}" />
                                                                    <label class="form-check-label" for="{{ $Permission->name }}">{{ $Permission->name }}</label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div><!-- permissions -->
                                    </div>
                                </div>
                            </form>
                        </div><!-- card-body -->

                        <div class="card-footer">
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
