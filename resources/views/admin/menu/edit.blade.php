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
                                Editar menu
                                <i class="ti ti-chevrons-right text-primary position-relative" style="top: 2px"></i>
                                {{ $Menu->name }}
                            </h4>

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
                            <x-alerts.errors class="mb-4" :errors="$errors" />

                            <form method="POST" action="{{ route('admin.menu.update', $Menu->id) }}" id="form-edit">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-12">
                                        <div class="row my-3">
                                            <label class="col-2 col-form-label required" for="menu_category_id">Categoria :</label>
                                            <div class="col-10 d-flex align-items-center">
                                                <select class="form-control" id="menu_category_id" name="menu_category_id" data-live-search="true" required>
                                                    <option value="">Selecione uma categoria</option>

                                                    @foreach ($MenuCategories as $MenuCategory)
                                                        <option value="{{ $MenuCategory->id }}" {{ !empty(old('menu_category_id')) && old('menu_category_id') == $MenuCategory->id ? 'selected' : ($Menu->menu_category_id == $MenuCategory->id ? 'selected' : '') }}>
                                                            {{ $MenuCategory->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row my-3">
                                            <label class="col-2 col-form-label required" for="name">Menu :</label>
                                            <div class="col-10 d-flex align-items-center">
                                                <input type="text" class="form-control" id="name" name="name" value="{{ !empty(old('name')) ? old('name') : $Menu->name }}" placeholder="Informe o nome do menu." required />
                                            </div>
                                        </div>

                                        <div class="row my-3">
                                            <label class="col-2 col-form-label required" for="icon">Ícone :</label>
                                            <div class="col-10 d-flex align-items-center">
                                                <select name="icon" id="icon" class="form-control" data-live-search="true" required>
                                                    <option value="">Selecione um ícone</option>
                                                    @foreach ($Icons as $Icon)
                                                        <option value="ph-duotone {{ $Icon }}" data-icon="ph-duotone {{ $Icon }}" {{ !empty(old('icon')) && old('icon') == "$Icon" ? 'selected' : ($Menu->icon == "ph-duotone $Icon" ? 'selected' : '') }}>
                                                            {{ $Icon }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row my-3">
                                            <label class="col-2 col-form-label required" for="icon">Prefixo da rota :</label>
                                            <div class="col-10 d-flex align-items-center">
                                                <input type="text" class="form-control" id="route" name="route" value="{{ !empty(old('route')) ? old('route') : $Menu->route }}" placeholder="Informe o prefixo da rota." required />
                                            </div>

                                            <div class="col-2"></div>
                                            <div class="col-10">
                                                <p class="ms-2 mb-0 f-12">Informe o prefixo da rota que você deseja criar o menu. ex: se a rota principal for <code>categoria.index</code> informe apenas <code>categoria</code>.</p>
                                                <p class="ms-2 mb-0 f-12">A rota deve possuir apenas letras, use <code>_</code> caso precise separar as palavras.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div><!-- card-body -->

                        <div class="card-footer m-0 py-3">
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
        });
    </script>
@endpush
