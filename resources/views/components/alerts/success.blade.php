@if (session()->has('success'))
    <div class="alert alert-success" role="alert">
        <div class="d-flex">
            <i class="ti ti-checks f-22"></i>
            <strong class="f-18">Sucesso</strong>
        </div>

        <div>
            <span>{{ session('success') }}</span>
        </div>
    </div>
@endif
