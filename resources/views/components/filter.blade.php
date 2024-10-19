@props(['data', 'route' => null])

<div id="filter" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLiveLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form action="{{ route($route ?? Route::currentRouteName()) }}" method="GET" id="form-filter">
                    @foreach ($data as $menu)
                        @if ($menu['type'] == 'group')
                            <x-filter-group :menu="$menu" />
                        @else
                            <x-filter-input :menu="$menu" />
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
