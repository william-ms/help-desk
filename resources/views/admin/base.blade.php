<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Sistema de fechamento de médicos da MedMais Consulta" />
    <meta name="author" content="MedMais Consulta" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ config('app.name') }}</title>

    {{-- [Favicon] icon --}}
    <link rel="icon" href="{{ asset('assets/images/favicon.ico') }}" type="image/x-icon" />

    {{-- [Google Font : Public Sans] icon --}}
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&amp;display=swap" rel="stylesheet">

    {{-- [Tabler Icons] https://tablericons.com --}}
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    {{-- [Feather Icons] https://feathericons.com --}}
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
    {{-- [Font Awesome Icons] https://fontawesome.com/icons --}}
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
    {{-- [Material Icons] https://fonts.google.com/icons --}}
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">
    {{-- [Template CSS Files] --}}
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
    <link rel="stylesheet" href="{{ asset('assets/css/style-preset.css') }}">

    <style>
        .required::after {
            content: ' *';
            font-weight: bold;
            color: red !important;
        }

        .form-control:not(.dropdown) {
            padding: 9px 15px !important;
        }

        .pc-navbar .pc-caption:has(+ .pc-caption) {
            display: none;
        }

        /*:::::::::::::::::::::::::::::::::::::::::::::::: SWEET ALERT :::::::::::::::::::::::::::::::::::::::::::::::*/
        .swal2-modal ul {
            max-height: 30vh !important;
            overflow-y: scroll !important;
        }

        /*:::::::::::::::::::::::::::::::::::::::::::::::::: TICKETS :::::::::::::::::::::::::::::::::::::::::::::::::*/
        .response-image {
            cursor: pointer;
        }
    </style>

    @vite('resources/css/app.css')
    @stack('css')
</head>

<body data-pc-preset="{{ $UserSettings['theme-preset'] ?? 'preset-1' }}" data-pc-sidebar-theme="{{ $UserSettings['theme-sidebar'] ?? 'light' }}" data-pc-sidebar-caption="true" data-pc-direction="ltr" data-pc-theme="{{ $UserSettings['theme'] ?? 'light' }}">

    {{-- LOADER --}}
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div><!-- loader-bg -->

    <nav class="pc-sidebar">
        <div class="navbar-wrapper">
            <div class="m-header">
                <a href="{{ route('admin.dashboard.index') }}" class="b-brand text-primary">
                    <img src="{{ asset('assets/images/logo-dark.png') }}" alt="logo image" class="w-100 p-3" />
                </a>
            </div>

            {{-- MENU DA SIDEBAR --}}
            <div class="navbar-content">
                <ul class="pc-navbar">
                    @foreach ($CategoriesAndMenusForSidebar as $Category)
                        @if (!$Category->menus->isEmpty())
                            <li class="pc-item pc-caption">
                                <label>{{ $Category->name }}</label>
                            </li>

                            @foreach ($Category->menus as $Menu)
                                <li class="pc-item pc-not-caption {{ request()->routeIs($Menu->route . '.*') ? 'active' : '' }}">
                                    <a href="{{ Route::has('admin.' . $Menu->route . '.index') ? route('admin.' . $Menu->route . '.index') : '' }}" class="pc-link {{ request()->routeIs($Menu->route . '.*') ? 'active' : '' }}">
                                        <span class="pc-micon"><i class="{{ $Menu->icon }}"></i></span>
                                        <span class="pc-mtext">{{ $Menu->name }}</span>
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    @endforeach
                </ul>
            </div>

            {{-- MENU DO USUÁRIO NA SIDEBAR --}}
            <div class="card pc-user-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <img src="{{ !empty(auth()->user()->photo) ? asset('storage/photo/' . auth()->user()->photo) : (!empty(auth()->user()->avatar) ? asset('assets/images/user/' . auth()->user()->avatar) : asset('assets/images/user/avatar-1.jpg')) }}" alt="user-image" class="user-avtar wid-45 rounded-circle" style="aspect-ratio: 1/1" />
                        </div>

                        <div class="flex-grow-1 ms-3 me-2">
                            <h6 class="mb-0">{{ auth()->user()->name }}</h6>
                            @if (!empty(auth()->user()->is_admin))
                                <small>Administrador</small>
                            @endif
                        </div>

                        <div class="dropdown">
                            <a href="#" class="btn btn-icon btn-link-secondary avtar arrow-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" data-bs-offset="0,20">
                                <i class="ph-duotone ph-windows-logo"></i>
                            </a>

                            <div class="dropdown-menu">
                                <ul>
                                    {{-- MEU PERFIL --}}
                                    <li>
                                        <a href="{{ route('admin.profile.index') }}" class="pc-user-links">
                                            <i class="ph-duotone ph-user-circle"></i>
                                            <span>Meu perfil</span>
                                        </a>
                                    </li>

                                    {{-- CONFIGURAÇÕES --}}
                                    <li>
                                        <a class="pc-user-links" href="#" data-bs-toggle="offcanvas" data-bs-target="#offcanvas_pc_layout">
                                            <i class="ph-duotone ph-gear-six"></i>
                                            <span>Configurações</span>
                                        </a>
                                    </li>

                                    {{-- SAIR --}}
                                    <li>
                                        <form action="{{ route('logout') }}" method="post" id="logout">
                                            @csrf
                                        </form>

                                        <a href="javascript:document.getElementById('logout').submit()" class="pc-user-links">
                                            <i class="ph-duotone ph-power"></i>
                                            <span>Sair</span>
                                        </a>
                                    </li>
                                </ul>
                            </div><!-- dropdown-menu -->
                        </div><!-- dropdown -->
                    </div>
                </div><!-- card-body -->
            </div><!-- card -->
        </div><!-- navbar-wrapper -->
    </nav>

    <header class="pc-header">
        <div class="header-wrapper">
            <div class="me-auto pc-mob-drp">
                <ul class="list-unstyled">

                    <li class="pc-h-item pc-sidebar-collapse">
                        <a href="#" class="pc-head-link ms-0" id="sidebar-hide">
                            <i class="ti ti-menu-2"></i>
                        </a>
                    </li>

                    <li class="pc-h-item pc-sidebar-popup">
                        <a href="#" class="pc-head-link ms-0" id="mobile-collapse">
                            <i class="ti ti-menu-2"></i>
                        </a>
                    </li>

                    {{-- HEADER - SEARCH --}}
                    <li class="dropdown pc-h-item d-inline-flex d-md-none">
                        <a class="pc-head-link dropdown-toggle arrow-none m-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <i class="ph-duotone ph-magnifying-glass"></i>
                        </a>
                        <div class="dropdown-menu pc-h-dropdown drp-search">
                            <form class="px-3">
                                <div class="mb-0 d-flex align-items-center">
                                    <input type="search" class="form-control border-0 shadow-none" placeholder="Search..." />
                                    <button class="btn btn-light-secondary btn-search">Search</button>
                                </div>
                            </form>
                        </div>
                    </li>

                    <li class="pc-h-item d-none d-md-inline-flex">
                        <form class="form-search">
                            <i class="ph-duotone ph-magnifying-glass icon-search"></i>
                            <input type="search" class="form-control" placeholder="Search..." />

                            {{-- <button class="btn btn-search" style="padding: 0"><kbd>ctrl+k</kbd></button> --}}
                        </form>
                    </li>
                </ul>
            </div>

            {{-- HEADER - OPÇÕES --}}
            <div class="ms-auto">
                <ul class="list-unstyled">

                    {{-- [button] - ALTERNAR TEMA ESCURO/CLARO --}}
                    <li class="dropdown pc-h-item">
                        <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <i class="ph-duotone ph-sun-dim"></i>
                        </a>

                        <div class="dropdown-menu dropdown-menu-end pc-h-dropdown">
                            <a href="#!" class="dropdown-item" onclick="layout_change('dark')">
                                <i class="ph-duotone ph-moon"></i>
                                <span>Escuro</span>
                            </a>

                            <a href="#!" class="dropdown-item" onclick="layout_change('light')">
                                <i class="ph-duotone ph-sun-dim"></i>
                                <span>Claro</span>
                            </a>

                            <a href="#!" class="dropdown-item" onclick="layout_change_default()">
                                <i class="ph-duotone ph-cpu"></i>
                                <span>Padrão</span>
                            </a>
                        </div>
                    </li>

                    {{-- CONFIGURAÇÕES --}}
                    <li class="pc-h-item">
                        <a class="pc-head-link pct-c-btn" href="#" data-bs-toggle="offcanvas" data-bs-target="#offcanvas_pc_layout">
                            <i class="ph-duotone ph-gear-six"></i>
                        </a>
                    </li>

                    {{-- NOTIFICAÇÕES  --}}
                    <li class="dropdown pc-h-item">
                        <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <i class="ph-duotone ph-bell"></i>
                            <span class="badge bg-success pc-h-badge">3</span>
                        </a>

                        {{-- <div class="dropdown-menu dropdown-notification dropdown-menu-end pc-h-dropdown">
                            <div class="dropdown-header d-flex align-items-center justify-content-between">
                                <h5 class="m-0">Notifications</h5>
                                <ul class="list-inline ms-auto mb-0">
                                    <li class="list-inline-item">
                                        <a href="../application/mail.html" class="avtar avtar-s btn-link-hover-primary">
                                            <i class="ti ti-link f-18"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="dropdown-body text-wrap header-notification-scroll position-relative" style="max-height: calc(100vh - 235px)">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <p class="text-span">Today</p>
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <img src="../assets/images/user/avatar-2.jpg" alt="user-image" class="user-avtar avtar avtar-s" />
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1 me-3 position-relative">
                                                        <h6 class="mb-0 text-truncate">Keefe Bond added new tags to 💪 Design system</h6>
                                                    </div>
                                                    <div class="flex-shrink-0">
                                                        <span class="text-sm">2 min ago</span>
                                                    </div>
                                                </div>
                                                <p class="position-relative mt-1 mb-2"><br /><span class="text-truncate">Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</span></p>
                                                <span class="badge bg-light-primary border border-primary me-1 mt-1">web design</span>
                                                <span class="badge bg-light-warning border border-warning me-1 mt-1">Dashobard</span>
                                                <span class="badge bg-light-success border border-success me-1 mt-1">Design System</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <div class="avtar avtar-s bg-light-primary">
                                                    <i class="ph-duotone ph-chats-teardrop f-18"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1 me-3 position-relative">
                                                        <h6 class="mb-0 text-truncate">Message</h6>
                                                    </div>
                                                    <div class="flex-shrink-0">
                                                        <span class="text-sm">1 hour ago</span>
                                                    </div>
                                                </div>
                                                <p class="position-relative mt-1 mb-2"><br /><span class="text-truncate">Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</span></p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <p class="text-span">Yesterday</p>
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <div class="avtar avtar-s bg-light-danger">
                                                    <i class="ph-duotone ph-user f-18"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1 me-3 position-relative">
                                                        <h6 class="mb-0 text-truncate">Challenge invitation</h6>
                                                    </div>
                                                    <div class="flex-shrink-0">
                                                        <span class="text-sm">12 hour ago</span>
                                                    </div>
                                                </div>
                                                <p class="position-relative mt-1 mb-2"><br /><span class="text-truncate"><strong> Jonny aber </strong> invites to join the challenge</span></p>
                                                <button class="btn btn-sm rounded-pill btn-outline-secondary me-2">Decline</button>
                                                <button class="btn btn-sm rounded-pill btn-primary">Accept</button>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <div class="avtar avtar-s bg-light-info">
                                                    <i class="ph-duotone ph-notebook f-18"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1 me-3 position-relative">
                                                        <h6 class="mb-0 text-truncate">Forms</h6>
                                                    </div>
                                                    <div class="flex-shrink-0">
                                                        <span class="text-sm">2 hour ago</span>
                                                    </div>
                                                </div>
                                                <p class="position-relative mt-1 mb-2">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard
                                                    dummy text ever since the 1500s.</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <img src="../assets/images/user/avatar-2.jpg" alt="user-image" class="user-avtar avtar avtar-s" />
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1 me-3 position-relative">
                                                        <h6 class="mb-0 text-truncate">Keefe Bond added new tags to 💪 Design system</h6>
                                                    </div>
                                                    <div class="flex-shrink-0">
                                                        <span class="text-sm">2 min ago</span>
                                                    </div>
                                                </div>
                                                <p class="position-relative mt-1 mb-2"><br /><span class="text-truncate">Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</span></p>
                                                <button class="btn btn-sm rounded-pill btn-outline-secondary me-2">Decline</button>
                                                <button class="btn btn-sm rounded-pill btn-primary">Accept</button>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <div class="avtar avtar-s bg-light-success">
                                                    <i class="ph-duotone ph-shield-checkered f-18"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1 me-3 position-relative">
                                                        <h6 class="mb-0 text-truncate">Security</h6>
                                                    </div>
                                                    <div class="flex-shrink-0">
                                                        <span class="text-sm">5 hour ago</span>
                                                    </div>
                                                </div>
                                                <p class="position-relative mt-1 mb-2">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard
                                                    dummy text ever since the 1500s.</p>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="dropdown-footer">
                                <div class="row g-3">
                                    <div class="col-6">
                                        <div class="d-grid"><button class="btn btn-primary">Archive all</button></div>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-grid"><button class="btn btn-outline-secondary">Mark all as read</button></div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    </li>

                    {{-- PERFIL --}}
                    <li class="dropdown pc-h-item header-user-profile">

                        {{-- [perfil] - FOTO OU AVATAR --}}
                        <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" data-bs-auto-close="outside" aria-expanded="false">
                            <img src="{{ !empty(auth()->user()->photo) ? asset('storage/photo/' . auth()->user()->photo) : (!empty(auth()->user()->avatar) ? asset('assets/images/user/' . auth()->user()->avatar) : asset('assets/images/user/avatar-1.jpg')) }}" alt="user-image" class="user-avtar" style="aspect-ratio: 1/1" />
                        </a>

                        <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
                            <div class="dropdown-body">
                                <div class="profile-notification-scroll position-relative" style="max-height: calc(100vh - 225px)">
                                    <ul class="list-group list-group-flush w-100">

                                        {{-- [perfil] DADOS DO PERFIL --}}
                                        <li class="list-group-item">
                                            <div class="d-flex align-items-center">
                                                {{-- [perfil] FOTO OU AVATAR --}}
                                                <div class="flex-shrink-0">
                                                    <img src="{{ !empty(auth()->user()->photo) ? asset('storage/photo/' . auth()->user()->photo) : (!empty(auth()->user()->avatar) ? asset('assets/images/user/' . auth()->user()->avatar) : asset('assets/images/user/avatar-1.jpg')) }}" alt="user-image" class="wid-50 rounded-circle" style="aspect-ratio: 1/1" />
                                                </div>

                                                {{-- [perfil] NOME E EMAIL --}}
                                                <div class="flex-grow-1 mx-3">
                                                    <h5 class="mb-0">{{ auth()->user()->name }}</h5>

                                                    @if (!empty(auth()->user()->is_admin))
                                                        <small>Administrador</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </li>


                                        <li class="list-group-item">
                                            {{-- [perfil] MEU PERFIL --}}
                                            <a href="{{ route('admin.profile.index') }}" class="dropdown-item">
                                                <span class="d-flex align-items-center">
                                                    <i class="ph-duotone ph-user-circle"></i>
                                                    <span>Meu perfil</span>
                                                </span>
                                            </a>

                                            {{-- [perfil] ALTERAR SENHA --}}
                                            <a href="{{ route('admin.profile.index', ['password' => 'password']) }}" class="dropdown-item">
                                                <span class="d-flex align-items-center">
                                                    <i class="ph-duotone ph-key"></i>
                                                    <span>Alterar senha</span>
                                                </span>
                                            </a>

                                            {{-- [perfil] CONFIGURAÇÕES --}}
                                            <a href="#" class="dropdown-item" data-bs-toggle="offcanvas" data-bs-target="#offcanvas_pc_layout">
                                                <span class="d-flex align-items-center">
                                                    <i class="ph-duotone ph-gear-six"></i>
                                                    <span>Configurações</span>
                                                </span>
                                            </a>
                                        </li>

                                        {{-- [perfil] SAIR --}}
                                        <li class="list-group-item">
                                            <form action="{{ route('logout') }}" method="post" id="logout">
                                                @csrf
                                            </form>

                                            <a href="javascript:document.getElementById('logout').submit()" class="dropdown-item">
                                                <span class="d-flex align-items-center">
                                                    <i class="ph-duotone ph-power"></i>
                                                    <span>Sair</span>
                                                </span>
                                            </a>
                                        </li>
                                    </ul>
                                </div><!-- profile-notification-scroll -->
                            </div><!-- dropdown-body -->
                        </div><!-- dropdown-user-profile -->
                    </li>
                </ul>
            </div>
        </div><!-- header-wrapper -->
    </header>

    {{-- CONTENT --}}
    @yield('content')
    {{-- --------- --}}

    {{-- FOOTER --}}
    <footer class="pc-footer">
        <div class="footer-wrapper container-fluid">
            <div class="row">
                <div class="col my-1 text-center">
                    &copy
                    <script>
                        document.write(new Date().getFullYear())
                    </script> MedMais Consulta. <i class="ph-duotone ph-heart text-danger"></i>
                </div>
            </div>
        </div>
    </footer>
    {{-- -------- --}}

    {{-- CONFIGURAÇÕES --}}
    <div class="offcanvas border-0 pct-offcanvas offcanvas-end" tabindex="-1" id="offcanvas_pc_layout">
        <div class="offcanvas-header justify-content-between">
            <h5 class="offcanvas-title">Configurações</h5>
            <button type="button" class="btn btn-icon btn-link-danger" data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="ti ti-x"></i>
            </button>
        </div>

        <div class="pct-body customizer-body">
            <div class="offcanvas-body py-0">
                <ul class="list-group list-group-flush">

                    {{-- #----- TEMA PRINCIPAL -----# --}}
                    <li class="list-group-item">
                        <div class="pc-dark">
                            <h6 class="mb-1">Tema principal</h6>
                            <p class="text-muted text-sm">Escolha o tema principal</p>
                            <div class="row theme-color theme-layout">

                                {{-- TEMA CLARO --}}
                                <div class="col-6">
                                    <div class="d-grid">
                                        <button class="preset-btn btn theme-btn active" data-value="true" data-type="theme" data-theme="light" onclick="layout_change('light');">
                                            <span class="btn-label">Claro</span>
                                            <span class="pc-lay-icon"><span></span><span></span><span></span><span></span></span>
                                        </button>
                                    </div>
                                </div>

                                {{-- TEMA ESCURO --}}
                                <div class="col-6">
                                    <div class="d-grid">
                                        <button class="preset-btn btn theme-btn" data-value="false" data-type="theme" data-theme="dark" onclick="layout_change('dark');">
                                            <span class="btn-label">Escuro</span>
                                            <span class="pc-lay-icon"><span></span><span></span><span></span><span></span></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    {{-- #----- TEMA DA SIDEBAR -----# --}}
                    <li class="list-group-item">
                        <h6 class="mb-1">Tema da barra lateral</h6>

                        <p class="text-muted text-sm">Escolha o tema da barra lateral</p>

                        <div class="row theme-color theme-sidebar-color">
                            {{-- TEMA ESCURO --}}
                            <div class="col-6">
                                <div class="d-grid">
                                    <button class="preset-btn btn theme-btn" data-value="true" data-type="theme-sidebar" data-theme="dark" onclick="layout_sidebar_change('dark');">
                                        <span class="btn-label">Escuro</span>
                                        <span class="pc-lay-icon"><span></span><span></span><span></span><span></span></span>
                                    </button>
                                </div>
                            </div>

                            {{-- TEMA CLARO --}}
                            <div class="col-6">
                                <div class="d-grid">
                                    <button class="preset-btn btn theme-btn active" data-value="false" data-type="theme-sidebar" data-theme="light" onclick="layout_sidebar_change('light');">
                                        <span class="btn-label">Claro</span>
                                        <span class="pc-lay-icon"><span></span><span></span><span></span><span></span></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </li>

                    {{-- #----- COR PRINCPAL -----# --}}
                    <li class="list-group-item">
                        <h6 class="mb-1">Cor de destaque</h6>

                        <p class="text-muted text-sm">Escolha a cor principal do seu tema</p>

                        <div class="theme-color preset-color">
                            <a href="#!" class="theme-btn active" data-value="preset-1" data-type="theme-preset" data-theme="preset-1"><i class="ti ti-check"></i></a>
                            <a href="#!" class="theme-btn" data-value="preset-2" data-type="theme-preset" data-theme="preset-2"><i class="ti ti-check"></i></a>
                            <a href="#!" class="theme-btn" data-value="preset-3" data-type="theme-preset" data-theme="preset-3"><i class="ti ti-check"></i></a>
                            <a href="#!" class="theme-btn" data-value="preset-4" data-type="theme-preset" data-theme="preset-4"><i class="ti ti-check"></i></a>
                            <a href="#!" class="theme-btn" data-value="preset-5" data-type="theme-preset" data-theme="preset-5"><i class="ti ti-check"></i></a>
                            <a href="#!" class="theme-btn" data-value="preset-6" data-type="theme-preset" data-theme="preset-6"><i class="ti ti-check"></i></a>
                            <a href="#!" class="theme-btn" data-value="preset-7" data-type="theme-preset" data-theme="preset-7"><i class="ti ti-check"></i></a>
                            <a href="#!" class="theme-btn" data-value="preset-8" data-type="theme-preset" data-theme="preset-8"><i class="ti ti-check"></i></a>
                            <a href="#!" class="theme-btn" data-value="preset-9" data-type="theme-preset" data-theme="preset-9"><i class="ti ti-check"></i></a>
                            <a href="#!" class="theme-btn" data-value="preset-10" data-type="theme-preset" data-theme="preset-10"><i class="ti ti-check"></i></a>
                        </div>
                    </li>

                    {{-- #----- LARGURA DO LAYOUT -----# --}}
                    <li class="list-group-item pc-box-width">
                        <div class="pc-container-width">
                            <h6 class="mb-1">Largura do layout</h6>

                            <p class="text-muted text-sm">Escolha a largura do layout</p>

                            <div class="row theme-color theme-container">
                                {{-- LARGURA CHEIA --}}
                                <div class="col-6">
                                    <div class="d-grid">
                                        <button class="preset-btn btn theme-btn active" data-value="false" data-type="theme-container" data-theme="false" onclick="change_box_container('false')">
                                            <span class="btn-label">Largura cheia</span>
                                            <span class="pc-lay-icon"><span></span><span></span><span></span><span><span></span></span></span>
                                        </button>
                                    </div>
                                </div>

                                {{-- LARGURA FIXA --}}
                                <div class="col-6">
                                    <div class="d-grid">
                                        <button class="preset-btn btn theme-btn" data-value="true" data-type="theme-container" data-theme="true" onclick="change_box_container('true')">
                                            <span class="btn-label">Largura fixa</span>
                                            <span class="pc-lay-icon"><span></span><span></span><span></span><span><span></span></span></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="d-grid">
                            <button class="btn btn-light-danger" id="theme-reset">
                                Resetar mudanças
                            </button>
                        </div>
                    </li>
                </ul>
            </div><!-- offcanvas-body -->
        </div><!-- customizer-body -->
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/fonts/custom-font.js') }}"></script>
    <script src="{{ asset('assets/js/pcoded.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>

    <script>
        layout_change("{{ $UserSettings['theme'] ?? 'light' }}");
        layout_sidebar_change("{{ $UserSettings['theme-sidebar'] ?? 'light' }}");
        change_box_container("{{ $UserSettings['theme-container'] ?? 'false' }}");
        preset_change("{{ $UserSettings['theme-preset'] ?? 'preset-1' }}");
        // layout_caption_change('true');
        // layout_rtl_change('false');

        $(document).ready(function() {
            //:::::::::::::::::::::::::::::::::::::::::::::::::::::::://
            // Evento definido para salvar as configurações do layout //
            $('.theme-btn').on('click', function() {
                var type = $(this).data('type');
                var value = $(this).data('theme');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var posting = $.post("{{ route('ajax.setting.theme') }}", {
                    type,
                    value
                }, "json");

                posting.fail(function(data) {
                    Swal.fire('Oops',
                        'Erro ao atualizar as configurações, tente novamente mais tarde!',
                        'danger');
                });
            })

            //::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
            // Evento definido para resetar as configurações do layout //
            $('#theme-reset').on('click', function() {
                layout_change('light');
                layout_sidebar_change('light');
                change_box_container('false');
                preset_change('preset-1');

                let theme_reset = true;

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var posting = $.post("{{ route('ajax.setting.theme') }}", {
                    theme_reset
                }, "json");

                posting.fail(function(data) {
                    Swal.fire('Oops',
                        'Erro ao atualizar as configurações, tente novamente mais tarde!',
                        'danger');
                });
            });
        });
    </script>

    @vite('resources/js/app.js')
    @stack('scripts')
</body>

</html>
