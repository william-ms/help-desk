@extends('auth.base')

@section('content')
    <div class="text-center">
        <img src="{{ asset('assets/images/authentication/img-auth-fporgot-password.png') }}" alt="images" class="img-fluid mb-3">

        <h4 class="f-w-500 mb-4">Esqueci minha senha</h4>
    </div>

    {{-- [flash-message] Status da sessão --}}
    <x-auth-session-status class="mb-4" :status="session('status')" />

    {{-- [flash-message] Erros de validação --}}
    <x-alerts.errors class="mb-4" :errors="$errors" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        {{-- [input] Endereço de email --}}
        <div class="mb-1">
            <input class="form-control" type="email" name="email" id="floatingInput" placeholder="Email" :value="old('email')" required autofocus>
        </div>

        {{-- [button] Voltar para login --}}
        <p class="mb-3">
            <i class="ti ti-arrow-back"></i><a href="{{ route('login') }}" class="link-primary ms-1">Voltar para login</a>
        </p>

        {{-- [button] Enviar email --}}
        <div class="d-grid mt-3">
            <x-button icon="ti ti-mail-forward">
                Enviar email
            </x-button>
        </div>
    </form>
@endsection
