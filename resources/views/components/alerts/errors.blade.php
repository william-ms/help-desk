@if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <div class="d-flex align-items-center">
            <i class="ti ti-alert-octagon f-20 me-1"></i>
            <strong class="f-18">{{ __('Whoops! Something went wrong.') }}</strong>
        </div>

        <div>
            @foreach ($errors->all() as $error)
                {{ $error }}<br />
            @endforeach
        </div>
    </div>
@endif
