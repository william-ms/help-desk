@if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <div class="col-12">
            <i class="ti ti-alert-triangle align-middle f-20"></i>
            <strong class="f-18 align-middle">{{ __('Whoops! Something went wrong.') }}</strong>
        </div>

        <div>
            @foreach ($errors->all() as $error)
                {{ $error }}<br />
            @endforeach
        </div>
    </div>
@endif
