@extends('admin.base')

@section('content')
    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <x-breadcrumb :breadcrumbs="$data_breadcrumbs" />

                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">Perfil</h2>
                            </div>
                        </div>
                    </div>
                </div><!-- page-block -->
            </div><!-- page-header -->

            <!-- [ Main Content ] start -->
            <div class="row">
                <!-- [ sample-page ] start -->
                <div class="col-sm-12">
                    {{-- <div class="card bg-primary">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 me-3">
                                    <h3 class="text-white">Verificação de e-mail</h3>
                                    <p class="text-white text-opacity-75 text-opa mb-0">Seu e-mail não foi confirmado. Por favor, verifique sua caixa de entrada.
                                        <a href="#" class="link-light"><u>Re-enviar confirmação</u></a>
                                    </p>
                                </div>
                                <div class="flex-shrink-0">
                                    <img src="../assets/images/application/img-accout-alert.png" alt="img" class="img-fluid wid-80" />
                                </div>
                            </div>
                        </div><!-- card-body -->
                    </div><!-- card --> --}}

                    <div class="row">
                        <div class="col-lg-5 col-xxl-3">
                            <div class="card overflow-hidden">
                                <div class="card-body position-relative">
                                    <div class="text-center mt-3">
                                        <div class="chat-avtar d-inline-flex mx-auto">
                                            <img class="rounded-circle img-fluid wid-90 img-thumbnail" src="{{ $User->profile_image }}" alt="User image" />
                                            <i class="chat-badge bg-success me-2 mb-2"></i>
                                        </div>
                                        <h5 class="mb-0">{{ $User->name }}</h5>

                                        @if (!$User->hasRole(2))
                                            <p class="text-muted text-sm">{{ $User->roles()->first()->name }}</p>
                                        @endif
                                    </div>
                                </div>

                                {{-- Barra de navegação do perfil --}}
                                <div class="nav flex-column nav-pills list-group list-group-flush account-pills mb-0" id="user-set-tab" role="tablist" aria-orientation="vertical">
                                    <a class="nav-link list-group-item list-group-item-action {{ request('tab') == 'info' ? 'active' : '' }}" id="profile-info-tab" data-bs-toggle="pill" href="#profile-info" role="tab" aria-controls="profile-info" aria-selected="true">
                                        <span class="f-w-500"><i class="ph-duotone ph-user-circle m-r-10"></i>Informações do perfil</span>
                                    </a>

                                    <a class="nav-link list-group-item list-group-item-action {{ request('tab') == 'edit-info' ? 'active' : '' }}" id="profile-edit-info-tab" data-bs-toggle="pill" href="#profile-edit-info" role="tab" aria-controls="profile-edit-info" aria-selected="false">
                                        <span class="f-w-500"><i class="ph-duotone ph-clipboard-text m-r-10"></i>Alterar informações pessoais</span>
                                    </a>

                                    <a class="nav-link list-group-item list-group-item-action {{ request('tab') == 'edit-image' ? 'active' : '' }}" id="profile-edit-image-tab" data-bs-toggle="pill" href="#profile-edit-image" role="tab" aria-controls="profile-edit-image" aria-selected="false">
                                        <span class="f-w-500"><i class="ph-duotone ph-notebook m-r-10"></i>Alterar imagem de perfil</span>
                                    </a>

                                    <a class="nav-link list-group-item list-group-item-action {{ request('tab') == 'edit-password' ? 'active' : '' }}" id="profile-edit-password-tab" data-bs-toggle="pill" href="#profile-edit-password" role="tab" aria-controls="profile-edit-password" aria-selected="false">
                                        <span class="f-w-500"><i class="ph-duotone ph-key m-r-10"></i>Alterar senha</span>
                                    </a>
                                </div>
                            </div>

                            {{-- TODO: Adicionar os times --}}
                            {{-- <div class="card">
                                <div class="card-header">
                                    <h5>Personal information</h5>
                                </div>
                                <div class="card-body position-relative">
                                    <div class="d-inline-flex align-items-center justify-content-between w-100 mb-3">
                                        <p class="mb-0 text-muted me-1">Email</p>
                                        <p class="mb-0">anshan@gmail.com</p>
                                    </div>
                                    <div class="d-inline-flex align-items-center justify-content-between w-100 mb-3">
                                        <p class="mb-0 text-muted me-1">Phone</p>
                                        <p class="mb-0">(+1-876) 8654 239 581</p>
                                    </div>
                                    <div class="d-inline-flex align-items-center justify-content-between w-100">
                                        <p class="mb-0 text-muted me-1">Location</p>
                                        <p class="mb-0">New York</p>
                                    </div>
                                </div>
                            </div> --}}
                        </div>

                        <div class="col-lg-7 col-xxl-9">
                            <x-alerts.success />
                            <x-alerts.errors />


                            <form method="POST" action="{{ route('admin.profile.update', ['tab' => ':tab']) }}" id="form-profile">
                                @csrf
                                @method('PUT')

                                <div class="tab-content" id="user-set-tabContent">
                                    {{-- Informações do perfil --}}
                                    <div class="tab-pane fade show {{ request('tab') == 'info' ? 'active show' : '' }}" id="profile-info" role="tabpanel" aria-labelledby="profile-info-tab">

                                        {{-- Informações pessoais --}}
                                        <div class="card">
                                            <div class="card-header">
                                                <h5>Informações pessoais</h5>
                                            </div>

                                            <div class="card-body pt-0">
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item">
                                                        <div class="row">
                                                            <p class="col-md-2 my-0 text-muted">Nome</p>
                                                            <p class="col-md-10 my-0">{{ $User->name }}</p>
                                                        </div>
                                                    </li>

                                                    @if (!$User->hasRole(2))
                                                        <li class="list-group-item">
                                                            <div class="row">
                                                                <p class="col-md-2 my-0 text-muted">Cargo</p>
                                                                <p class="col-md-10 my-0">{{ $User->roles()->first()->name }}</p>
                                                            </div>
                                                        </li>
                                                    @endif

                                                    <li class="list-group-item">
                                                        <div class="row">
                                                            <p class="col-md-2 my-0 text-muted">Email</p>
                                                            <p class="col-md-10 my-0">{{ $User->email }}</p>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        {{-- Informações profissionais --}}
                                        <div class="card">
                                            <div class="card-header">
                                                <h5>Informações profissionais</h5>
                                            </div>

                                            <div class="card-body pt-0">
                                                <ul class="list-group list-group-flush ">
                                                    <li class="list-group-item">
                                                        <div class="row align-items-center">
                                                            <p class="col-md-2 my-0 text-muted">Empresas</p>

                                                            <div class="col-md-10">
                                                                @foreach ($User->companies as $Company)
                                                                    <p class="my-0">{{ $Company->name }}</p>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </li>

                                                    <li class="list-group-item">
                                                        <div class="row align-items-center">
                                                            <p class="col-md-2 my-0 text-muted">Departamentos</p>

                                                            <div class="col-md-10">
                                                                @foreach ($User->departaments as $Departament)
                                                                    <p class="my-0">{{ $Departament->name }}</p>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Alterar informações pessoais --}}
                                    <div class="tab-pane fade {{ request('tab') == 'edit-info' ? 'active show' : '' }}" id="profile-edit-info" role="tabpanel" aria-labelledby="profile-edit-info-tab">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5>Alterar informações pessoais</h5>
                                            </div>

                                            <div class="card-body">
                                                <!-- [input] - Nome -->
                                                <div class="row my-3">
                                                    <label class="col-12 col-md-3 col-xxl-2 col-form-label required" for="name">Nome:</label>
                                                    <div class="col-12 col-md-9 col-xxl-10 d-flex align-items-center">
                                                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') ?? $User->name }}" placeholder="Informe o seu nome" />
                                                    </div><!-- col-10 -->
                                                </div><!-- row -->

                                                <!-- [input] - Email -->
                                                <div class="row my-3">
                                                    <label class="col-12 col-md-3 col-xxl-2 col-form-label required" for="email">Email:</label>
                                                    <div class="col-12 col-md-9 col-xxl-10 d-flex align-items-center">
                                                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') ?? $User->email }}" placeholder="Informe o seu email" />
                                                    </div><!-- col-10 -->
                                                </div><!-- row -->
                                            </div>
                                        </div>

                                        <div class="text-end btn-page">
                                            <x-button class="btn-profile-form" type="button" icon="ti ti-checks" data-tab="edit-info">Salvar</x-button>
                                        </div>
                                    </div>

                                    {{-- Alterar imagem de perfil --}}
                                    <div class="tab-pane fade {{ request('tab') == 'edit-image' ? 'active show' : '' }}" id="profile-edit-image" role="tabpanel" aria-labelledby="profile-edit-image-tab">
                                        {{-- <div class="card">
                                            <div class="card-header">
                                                <h5>Alterar imagem</h5>
                                            </div>

                                            <div class="card-body">
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item px-0 pt-0">
                                                        <div class="row mb-0">
                                                            <label class="col-form-label col-md-4 col-sm-12 text-md-end">Username <span class="text-danger">*</span></label>
                                                            <div class="col-md-8 col-sm-12">
                                                                <input type="text" class="form-control" value="Ashoka_Tano_16" />
                                                                <div class="form-text">
                                                                    Your Profile URL: <a href="#" class="link-primary">https://pc.com/Ashoka_Tano_16</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class="list-group-item px-0">
                                                        <div class="row mb-0">
                                                            <label class="col-form-label col-md-4 col-sm-12 text-md-end">Account Email <span class="text-danger">*</span></label>
                                                            <div class="col-md-8 col-sm-12">
                                                                <input type="text" class="form-control" value="demo@sample.com" />
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class="list-group-item px-0">
                                                        <div class="row mb-0">
                                                            <label class="col-form-label col-md-4 col-sm-12 text-md-end">Language</label>
                                                            <div class="col-md-8 col-sm-12">
                                                                <select class="form-control">
                                                                    <option>Washington</option>
                                                                    <option>India</option>
                                                                    <option>Africa</option>
                                                                    <option>New York</option>
                                                                    <option>Malaysia</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class="list-group-item px-0 pb-0">
                                                        <div class="row mb-0">
                                                            <label class="col-form-label col-md-4 col-sm-12 text-md-end">Sign in Using <span class="text-danger">*</span></label>
                                                            <div class="col-md-8 col-sm-12">
                                                                <select class="form-control">
                                                                    <option>Password</option>
                                                                    <option>Face Recognition</option>
                                                                    <option>Thumb Impression</option>
                                                                    <option>Key</option>
                                                                    <option>Pin</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div> --}}

                                        <div class="card">
                                            <div class="card-header">
                                                <h5>Alterar avatar</h5>
                                            </div>

                                            <div class="card-body">
                                                <div class="row">
                                                    @for ($i = 1; $i <= 10; $i++)
                                                        <div class="col-4 col-sm-3 col-lg-4 col-xl-3 col-xxl-2 d-flex justify-content-center form-check m-0 mb-3">
                                                            <label class="form-check-label" for='{{ "avatar-{$i}.jpg" }}'>
                                                                <div class="chat-avtar d-inline-flex mx-auto">
                                                                    <img class="avatar rounded-circle img-fluid wid-90 img-thumbnail {{ "avatar-{$i}.jpg" == auth()->user()->avatar ? 'border-primary border-2' : '' }}" src="{{ asset("assets/images/user/avatar-".$i.".jpg") }}" alt="User image" />
                                                                </div>
                                                            </label>

                                                            <input class="form-check-input input-primary d-none" type="radio" id='{{ "avatar-{$i}.jpg" }}' value='{{ "avatar-{$i}" }}' name="avatar" {{ "avatar-{$i}.jpg" == auth()->user()->avatar ? 'checked' : '' }} />
                                                        </div>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-end btn-page">
                                            <x-button class="btn-profile-form" type="button" icon="ti ti-checks" data-tab="edit-image">Salvar</x-button>
                                        </div>
                                    </div>

                                    {{-- Alterar senha --}}
                                    <div class="tab-pane fade {{ request('tab') == 'edit-password' ? 'active show' : '' }}" id="profile-edit-password" role="tabpanel" aria-labelledby="profile-edit-password-tab">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5>Change Password</h5>
                                            </div>

                                            <div class="card-body">
                                                <!-- [input] - Senha atual -->
                                                <div class="row my-3 align-items-center">
                                                    <label class="col-12 col-md-3 col-xxl-2 col-form-label" for="password_actual">Senha atual:</label>
                                                    <div class="col-12 col-md-9 col-xxl-10 position-relative auth-pass-inputgroup">
                                                        <input type="password" class="form-control password-input" placeholder="Informe a senha atual" name="password_actual" id="password_actual" autocomplete="new-password" required>

                                                        <button class="btn btn-link position-absolute end-0 text-decoration-none text-muted password-addon" type="button" id="password-reveal" style="top:1px">
                                                            <i class="ti ti-eye"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <!-- [input] - Nova senha -->
                                                <div class="row my-3 align-items-center">
                                                    <label class="col-12 col-md-3 col-xxl-2 col-form-label" for="password">Nova senha :</label>
                                                    <div class="col-12 col-md-9 col-xxl-10 position-relative auth-pass-inputgroup">
                                                        <input type="password" class="form-control password-input" placeholder="Informe uma nova senha" name="password" id="password" autocomplete="new-password" required>

                                                        <button class="btn btn-link position-absolute end-0 text-decoration-none text-muted password-addon" type="button" id="password-reveal" style="top:1px">
                                                            <i class="ti ti-eye"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <!-- [input] - Confirmar senha -->
                                                <div class="row my-3 align-items-center">
                                                    <label class="col-12 col-md-3 col-xxl-2 col-form-label" for="password_confirmation">Confirmar senha :</label>
                                                    <div class="col-12 col-md-9 col-xxl-10 position-relative auth-pass-inputgroup">
                                                        <input type="password" class="form-control password-input" name="password_confirmation" id="password-confirmation" placeholder="Confirmar a nova senha" required>

                                                        <button class="btn btn-link position-absolute end-0 text-decoration-none text-muted password-addon" type="button" id="password-reveal" style="top:1px">
                                                            <i class="ti ti-eye"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div id="password-equal" class="p-3 bg-light mb-2 rounded d-none">
                                                    <p class="text-danger fs-12 mb-2">As senhas não conferem, as senhas precisam ser iguais!</p>
                                                </div>

                                                <div id="password-contain" class="p-3 bg-light mb-2 rounded">
                                                    <h5 class="fs-13">A nova senha deverá conter:</h5>
                                                    <p id="password-length" class="invalid fs-12 mb-2">Mínimo de <b>8 caracteres</b></p>
                                                    <p id="password-lower" class="invalid fs-12 mb-2">Uma letra <b>minúscula</b> (a-z)</p>
                                                    <p id="password-upper" class="invalid fs-12 mb-2">Uma letra <b>maiscúla</b> (A-Z)</p>
                                                    <p id="password-number" class="invalid fs-12 mb-2">Um <b>número</b> (0-9)</p>
                                                    <p id="password-symbol" class="invalid fs-12 mb-0">Um <b>caractere especial</b></p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-end btn-page">
                                            <x-button class="btn-profile-form" type="button" icon="ti ti-checks" data-tab="edit-password">Salvar</x-button>
                                        </div>

                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
                <!-- [ sample-page ] end -->
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>
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

@push('scripts')
    <script src="{{ asset('assets/js/pages/password-addon.js') }}"></script>
    <script src="{{ asset('assets/js/pages/password-verify.js') }}"></script>

    <script>
        $(document).ready(function() {
            const form_profile = $('#form-profile');
            const form_profile_action = form_profile.attr('action');
            let tab = null;

            //:::::::::::::::::::::::::::::::::::: APLICAR BORDA AO ALTERAR AVATAR ::::::::::::::::::::::::::::::::::://
            $('.avatar').on('click', function() {
                $('.avatar.border-primary').removeClass('border-primary').removeClass('border-2');
                $(this).addClass('border-primary').addClass('border-2');
            })


            $('.btn-profile-form').on('click', function() {
                tab = $(this).data('tab');
                form_profile.attr('action', form_profile_action.replace(':tab', tab))
                form_profile.submit();
            })
        });
    </script>
@endpush
