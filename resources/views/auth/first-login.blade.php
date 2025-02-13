@extends('auth.base')

@section('content')
    <div class="text-center">
        <div class="w-50 mx-auto mb-4">
            <img src="{{ asset('assets/images/logo-dark-full.png') }}" alt="images" class="img-fluid">
        </div>
        
        <h4 class="f-w-500 mb-4">Crie uma nova senha</h4>
    </div>

    {{-- [flash-message] Status da sessão --}}
    <x-auth-session-status class="mb-4" :status="session('status')" />

    {{-- [flash-message] Erros de validação --}}
    <x-alerts.errors class="mb-4" :errors="$errors" />

    <form method="POST" class="update-password" action="{{ route('first_login') }}">
        @csrf

        {{-- [button] Nova senha --}}
        <div class="mb-3">
        </div>

        {{-- [button] Confirmar senha --}}
        <div class="mb-3">
        </div>


        {{-- [button] Nova senha --}}
        <div class="mb-3 position-relative auth-pass-inputgroup">
            <input type="password" class="form-control password-input" placeholder="Senha" name="password" id="password" autocomplete="new-password" required>

            <button class="btn btn-link position-absolute end-0 text-decoration-none text-muted password-addon" type="button" id="password-reveal" style="top:5px">
                <i class="ti ti-eye"></i>
            </button>
        </div>

        {{-- [button] Confirmar senha --}}
        <div class="mb-3 position-relative auth-pass-inputgroup">
            <input type="password" class="form-control password-input" name="password_confirmation" id="password-confirmation" placeholder="Confirmar a nova senha" required>

            <button class="btn btn-link position-absolute end-0 text-decoration-none text-muted password-addon" type="button" id="password-reveal" style="top:5px">
                <i class="ti ti-eye"></i>
            </button>
        </div>

        <div id="password-equal" class="p-3 bg-light mb-2 rounded d-none">
            <p class="text-danger fs-12 mb-2">As senhas não conferem, as senhas precisam ser iguais!</p>
        </div>

        <div id="password-contain" class="p-3 bg-light mb-2 rounded">
            <h5 class="fs-13">A senha deverá conter:</h5>
            <p id="password-length" class="invalid fs-12 mb-2">Mínimo de <b>8 caracteres</b></p>
            <p id="password-lower" class="invalid fs-12 mb-2">Uma letra <b>minúscula</b> (a-z)</p>
            <p id="password-upper" class="invalid fs-12 mb-2">Uma letra <b>maiscúla</b> (A-Z)</p>
            <p id="password-number" class="invalid fs-12 mb-2">Um <b>número</b> (0-9)</p>
            <p id="password-symbol" class="invalid fs-12 mb-0">Um <b>caractere especial</b></p>
        </div>

        {{-- [button] Salvar senha --}}
        <div class="d-grid mt-4">
            <x-button icon="ti ti-checks">
                Salvar senha
            </x-button>
        </div>
    </form>
@endsection


@push('css')
    <style>
        #password-contain p.valid {
            color: #0ab39c;
        }

        #password-contain p.valid::before {
            position: relative;
            left: -8px;
            content: "✔";
        }

        #password-contain p.invalid {
            color: #f06548;
        }

        #password-contain p.invalid::before {
            position: relative;
            left: -8px;
            content: "✖";
        }
    </style>
@endpush

@push('js')
    <script src="{{ asset('assets/js/pages/password-addon.js') }}"></script>
    <script src="{{ asset('assets/js/pages/password-verify.js') }}"></script>
@endpush
