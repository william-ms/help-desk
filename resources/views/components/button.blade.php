@props(['icon', 'componentType' => 'button', 'color' => 'primary', 'title' => null])

@if ($componentType == 'button')
    <button {{ $attributes->merge(['class' => "btn btn-{$color} rounded", 'type' => 'submit']) }} data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $title ?? $slot }}" style="padding: 6px 15px;">
        <i class="{{ $icon }} position-relative" style="top: 2px;"></i>
        <span>{{ $slot }}</span>
    </button>
@else
    <a {{ $attributes->merge(['class' => "btn btn-{$color}"]) }} data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $title ?? $slot }}" style="padding: 6px 15px;">
        <i class="{{ $icon }} position-relative" style="top: 2px;"></i>
        <span>{{ $slot }}</span>
    </a>
@endif
