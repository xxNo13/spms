<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Vendors -->
        <link rel="stylesheet" href="{{ asset('vendors/perfect-scrollbar/perfect-scrollbar.css') }}">
        <link rel="stylesheet" href="{{ asset('vendors/bootstrap-icons/bootstrap-icons.css') }}">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/bootstrap.css') }}">
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <link rel="stylesheet" href="{{ mix('css/pages/auth.css') }}">
        <link rel="stylesheet" href="{{ asset('css/custom.css?v=').time() }}">
        <link rel="icon" href="{{ asset('images/logo/icon.png') }}">


        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>
    <body>
        <div id="auth">
            <div class="row h-100">
                <div class="center-form col-12 position-absolute top-50 start-50 translate-middle">
                    <div class="position-relative">
                        {{ $slot }}
                    </div>
                </div>
                <div class="col-lg-12 d-none d-lg-block">
                    <div id="auth-right">

                    </div>
                </div>
            </div>
        </div>
        <script src="{{ asset('/js/jquery.js') }}"></script>
        <script>
                $('#toggleshow').click(function () {
                    if ($(this).hasClass('bi-eye-slash')) {
                        $(this).removeClass('bi-eye-slash');
                        $(this).addClass('bi-eye');
                        $('.password-padding').attr('type', 'text');
                    } else {
                        $(this).removeClass('bi-eye');
                        $(this).addClass('bi-eye-slash');
                        $('.password-padding').attr('type', 'password');
                    }
                });
        </script>
    </body>
</html>
