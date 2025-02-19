@props(['icon', 'componentType' => 'button', 'color' => 'primary', 'style' => null])

@php
    $style = !empty($style) ? '-' . $style : '';
@endphp

@if ($componentType == 'button')
    <button {{ $attributes->merge(['class' => "btn btn-icon btn{$style}-{$color} rounded border border-{$color}", 'type' => 'submit']) }} style="width: 30px; height: 30px;">
        <i class="{{ $icon }}"></i>
    </button>
@else
    <a {{ $attributes->merge(['class' => "btn btn-icon btn{$style}-{$color} rounded border border-{$color}"]) }} style="width: 30px; height: 30px;">
        <i class="{{ $icon }}"></i>
    </a>
@endif
