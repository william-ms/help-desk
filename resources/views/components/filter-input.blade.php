@props(['menu'])

@if ($menu['type'] == 'select')
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
@else
    <div class="col-12 mb-3">
        <label class="ps-1" for="{{ $menu['input_name'] }}">{{ $menu['label'] }}:</label>

        <input class="form-control" type="{{ $menu['type'] }}" name="{{ $menu['input_name'] }}" id="{{ $menu['input_name'] }}" placeholder="{{ $menu['placeholder'] ?? '' }}" value="{{ !empty(request()->get($menu['input_name'])) ? request()->get($menu['input_name']) : '' }}">
    </div>
@endif
