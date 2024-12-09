@extends('auth.base')

@section('content')
    <div class="text-center">
        <img src="{{ asset('assets/images/authentication/img-auth-login.png') }}" alt="images" class="img-fluid mb-3">

        <h4 class="f-w-500 mb-4">Faça login com seu e-mail</h4>
    </div>

    {{-- [flash-message] Status da sessão --}}
    <x-auth-session-status class="mb-4" :status="session('status')" />

    {{-- [flash-message] Erros de validação --}}
    <x-alerts.errors class="mb-4" :errors="$errors" />

    {{-- [buttons] - Usuários de teste --}}
    @env('local')
    <div class="d-flex my-2">
        <x-login-link class="btn btn-danger me-1 py-1" email="admin@helpdesk.com.br" label="Administrador" />
        <x-login-link class="btn btn-info me-1 py-1" email="technical@helpdesk.com.br" label="Técnico" />
        <x-login-link class="btn btn-secondary me-1 py-1" email="user@helpdesk.com.br" label="Usuário" />
    </div>
    @endenv

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- [input] Endereço de email --}}
        <div class="mb-3">
            <input class="form-control" type="email" name="email" id="floatingInput" placeholder="Email" :value="old('email')" required autofocus>
        </div>

        {{-- [input] Senha --}}
        <div class="mb-3 position-relative auth-pass-inputgroup">
            <input class="form-control password-input" type="password" name="password" id="floatingInput1" placeholder="Senha" required autocomplete="current-password">

            <button class="btn btn-link position-absolute end-0 text-decoration-none text-muted password-addon" type="button" id="password-reveal" style="top:5px">
                <i class="ti ti-eye"></i>
            </button>
        </div>

        <div class="d-flex mt-1 justify-content-between align-items-center">

            {{-- [input] Lembrar email e senha --}}
            <div class="form-check">
                <input class="form-check-input input-primary" type="checkbox" name="remember" id="customCheckc1" checked="">
                <label class="form-check-label text-muted" for="customCheckc1">Lembra de mim?</label>
            </div>

            {{-- [button] Esqueceu a senha --}}
            <a href="{{ route('password.request') }}" class="link-primary ms-1">Esqueceu a senha?</a>
        </div>

        {{-- [button] Relaizar login --}}
        <div class="d-grid mt-4">
            <x-button icon="ti ti-checks">
                Entrar
            </x-button>
        </div>
    </form>
@endsection

@push('js')
    <!-- password-addon init -->
    <script src="{{ asset('assets/js/pages/password-addon.js') }}"></script>
@endpush
