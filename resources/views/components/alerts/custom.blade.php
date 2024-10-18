@props(['status' => 'success', 'icon' => 'ti-checks', 'title' => 'Sucesso'])

<div class="alert alert-{{ $status }}" role="alert">
    <div class="d-flex align-items-center">
        <i class="{{ $icon }} f-20 me-1"></i>
        <strong class="f-18">{{ $title }}</strong>
    </div>

    <div>
        {{ $slot }}
    </div>
</div>
