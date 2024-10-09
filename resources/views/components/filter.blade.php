@props(['data', 'route' => null])




<div id="filter" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLiveLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                {{-- <h5 class="modal-title">Filtro</h5> --}}
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form action="{{ route($route ?? Route::currentRouteName()) }}" method="GET" id="form-filter">
                    @foreach ($data as $menu)
                        @if ($menu['type'] == 'text')
                            <div class="col-12 mb-3">
                                <label class="ps-1" for="{{ $menu['input_name'] }}">{{ $menu['label'] }}:</label>

                                <input class="form-control" name="{{ $menu['input_name'] }}" id="{{ $menu['input_name'] }}" placeholder="{{ $menu['placeholder'] ?? '' }}" value="{{ !empty(request()->get($menu['input_name'])) ? request()->get($menu['input_name']) : '' }}">
                            </div>
                        @elseif ($menu['type'] == 'select')
                            <div class="col-12 mb-3">
                                <label class="ps-1" for="{{ $menu['input_name'] }}">{{ $menu['label'] }}:</label>

                                <select name="{{ $menu['input_name'] }}" id="{{ $menu['input_name'] }}" class="form-control" data-live-search="true">
                                    <option value="">Todos (as)</option>

                                    @foreach ($menu['data'] as $k => $v)
                                        <option value="{{ $v[$menu['field_key'] ?? 'id'] }}" {{ !empty(request()->get($menu['input_name'])) ? ($v[$menu['field_key'] ?? 'id'] == request()->get($menu['input_name']) ? 'selected' : '') : '' }}>
                                            {{ $v[$menu['field_value'] ?? 'name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    @endforeach
                </form>
            </div>

            <div class="modal-footer">
                <x-button class="" componentType="a" href="{{ route($route ?? Route::currentRouteName()) }}" icon="ti ti-x" color="warning">
                    Limpar filtro
                </x-button>

                <x-button class="" icon="ti ti-list-search" color="success" form="form-filter">
                    Filtrar
                </x-button>
            </div>
        </div>
    </div>
</div>

















{{-- @props(['data', 'route' => null])

@php
    $rows = count($data) <= 4 ? 12 / count($data) : 4;
@endphp

<div class="row mb-5">
    <div class="col-lg-12">
        <h4>Filtro</h4>

        <form action="{{ route($route ?? Route::currentRouteName()) }}" method="GET">
            <div class="row mb-2">
                @foreach ($data as $menu)
                    @if ($menu['type'] == 'text')
                        <div class="col-{{ $rows }}">
                            <label class="ps-1" for="{{ $menu['input_name'] }}">{{ $menu['label'] }}:</label>

                            <input class="form-control" name="{{ $menu['input_name'] }}" id="{{ $menu['input_name'] }}" value=" {{ !empty(request()->get($menu['input_name'])) ? request()->get($menu['input_name']) : '' }}">
                        </div>
                    @elseif ($menu['type'] == 'select')
                        <div class="col-{{ $rows }}">
                            <label class="ps-1" for="{{ $menu['input_name'] }}">{{ $menu['label'] }}:</label>

                            <select name="{{ $menu['input_name'] }}" id="{{ $menu['input_name'] }}" class="form-control" data-live-search="true">
                                <option value="">Todos (as)</option>

                                @foreach ($menu['data'] as $k => $v)
                                    <option value="{{ $v['id'] }}" {{ !empty(request()->get($menu['input_name'])) ? ($v['id'] == request()->get($menu['input_name']) ? 'selected' : '') : '' }}>
                                        {{ $v[$menu['indice']] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                @endforeach
            </div>

            <div class="col-12">
                <x-button class="" icon="ti ti-search" color="success">
                    Pesquisar
                </x-button>

                <x-button class="" componentType="a" href="{{ route($route ?? Route::currentRouteName()) }}" icon="ti ti-x" color="warning">
                    Limpar pesquisa
                </x-button>
            </div>
        </form>
    </div>
</div>

@push('css')
    <!-- SELECT 2 -->
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/bootstrap-select.css') }}">

    <style>
        .form-control:not(.dropdown) {
            padding: 6px 15px !important;
        }

        .form-control button {
            background-color: #ffffff !important;
            border: 1px solid #DBE0E5;
            padding: 6px 15px !important;
        }

        .form-control button[aria-expanded="true"] {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 2px rgba(var(--bs-primary-rgb), 0.2);
            outline: 0;
        }

        .form-control button .filter-option {
            color: #5B6B79;
        }

        .form-control .dropdown-menu .bs-searchbox {
            padding: 0 !important;
        }

        .form-control .dropdown-menu input {
            padding: 6px 15px !important;
        }

        .form-control .dropdown-menu li a {
            padding: 6px 15px !important;
            margin: 4px 0;
        }
    </style>
@endpush

@push('scripts')
    <!-- SELECT 2 -->
    <script src="{{ asset('assets/js/plugins/bootstrap-select.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('select').selectpicker();
        });
    </script>
@endpush --}}
