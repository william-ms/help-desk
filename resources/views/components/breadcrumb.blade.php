@props(['breadcrumbs' => []])

<div class="col-md-12">
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Início</a></li>

        @foreach ($breadcrumbs as $breadcrumb)
            @if (!empty($breadcrumb['route']))
                <li class="breadcrumb-item"><a href="{{ route($breadcrumb['route']) }}">{{ $breadcrumb['name'] }}</a></li>
            @else
                <li class="breadcrumb-item" aria-current="page">{{ $breadcrumb['name'] }}</li>
            @endif
        @endforeach
    </ul>
</div>
