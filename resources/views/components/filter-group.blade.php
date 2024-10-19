@props(['menu'])

<div class="col-12 mb-3">
    <label class="ps-1">{{ $menu['label'] }}</label>

    <div class="input-group">
        @foreach ($menu['inputs'] as $input)
            @if ($input['type'] == 'span')
                <span class="input-group-text py-0 px-2">{{ $input['text'] }}</span>
            @elseif ($input['type'] == 'select')
                <select name="{{ $input['input_name'] }}" id="{{ $input['input_name'] }}" class="form-control" data-live-search="true">
                    <option value="">Todos (as)</option>

                    @foreach ($input['data'] as $k => $v)
                        <option value="{{ $v[$input['field_key'] ?? 'id'] }}" {{ !empty(request()->get($input['input_name'])) ? ($v[$input['field_key'] ?? 'id'] == request()->get($input['input_name']) ? 'selected' : '') : '' }}>
                            {{ $v[$input['field_value'] ?? 'name'] }}
                        </option>
                    @endforeach
                </select>
            @else
                <input class="form-control" type="{{ $input['type'] }}" name="{{ $input['input_name'] }}" id="{{ $input['input_name'] }}" placeholder="{{ $input['placeholder'] ?? '' }}" value="{{ !empty(request()->get($input['input_name'])) ? request()->get($input['input_name']) : '' }}" />
            @endif
        @endforeach
    </div>
</div>
