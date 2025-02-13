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
    <link rel="stylesheet" href="{{ asset('assets/fonts/phosphor/duotone/style.css') }}" />

    {{-- [Template CSS Files] --}}
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
    <link rel="stylesheet" href="{{ asset('assets/css/style-preset.css') }}">

    @stack('css')
</head>

<body data-pc-preset="preset-1" data-pc-sidebar-theme="light" data-pc-sidebar-caption="true" data-pc-direction="ltr" data-pc-theme="light">

    {{-- LOADER --}}
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div><!-- loader-track -->
    </div><!-- loader-bg -->

    <div class="auth-main v1">
        <div class="auth-wrapper">

            {{-- CONTENT --}}
            <div class="auth-form">
                <div class="card my-5">
                    <div class="card-body">
                        @yield('content')
                    </div>
                </div>
            </div><!-- auth-form -->

            {{-- FOOTER --}}
            <div class="auth-sidefooter">
                <div class="row">
                    <div class="col my-1 text-center">
                        &copy
                        <script>
                            document.write(new Date().getFullYear())
                        </script> Help Desk. <i class="ph-duotone ph-heart text-danger"></i>
                    </div>
                </div><!-- row -->
            </div><!-- auth-sidefooter -->
        </div><!-- auth-wrapper -->
    </div><!-- auth-main -->

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/fonts/custom-font.js') }}"></script>
    <script src="{{ asset('assets/js/pcoded.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>

    @stack('js')
</body>

</html>
