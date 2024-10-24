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

    {{-- [Template CSS Files] --}}
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
    <link rel="stylesheet" href="{{ asset('assets/css/style-preset.css') }}">
</head>

<body data-pc-preset="preset-1" data-pc-sidebar-theme="light" data-pc-sidebar-caption="true" data-pc-direction="ltr" data-pc-theme="light">
    <div class="auth-main v1">
        <div class="auth-wrapper">
            <div class="auth-form">
                <div class="error-card">
                    <div class="card-body">
                        <div class="error-image-block">
                            <img class="img-fluid" src="{{ asset('assets/images/pages/img-error-404.png') }}" alt="img">
                        </div>

                        <div class="text-center">
                            <h1 class="mt-2">Ops! Algo deu errado</h1>
                            <p class="mt-2 mb-4 text-muted f-20">Não foi possível encontrar a página que você procurava. Por que não tentar voltar à página inicial?</p>
                            <a class="btn btn-primary d-inline-flex align-items-center mb-3" href="{{ route('home') }}"><i class="ph-duotone ph-house me-2"></i> Ir para página inicial</a>
                        </div>
                    </div><!-- card-body -->
                </div><!-- error-card -->
            </div><!-- auth-form -->

            <div class="auth-sidefooter">
                <div class="row">
                    <div class="col my-1 text-center">
                        &copy
                        <script>
                            document.write(new Date().getFullYear())
                        </script> MedMais Consulta. <i class="ph-duotone ph-heart text-danger"></i>
                    </div>
                </div>
            </div><!-- auth-sidefooter -->
        </div><!-- auth-wrapper -->
    </div><!-- auth-main -->
</body>

</html>
