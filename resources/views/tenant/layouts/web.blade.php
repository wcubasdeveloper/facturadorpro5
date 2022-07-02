<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="sidebar-light sidebar-left-big-icons">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

{{--    <title>{{ config('app.name', 'Facturación Electrónica') }}</title>--}}
    <title>Facturación Electrónica</title>
    <!-- Scripts -->

    <!-- Fonts -->
    {{--<link rel="dns-prefetch" href="https://fonts.gstatic.com">--}}
    {{--<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">--}}

    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

    <link rel="stylesheet" href="{{ asset('porto-light/vendor/bootstrap/css/bootstrap.css') }}" />
    <link rel="stylesheet" href="{{ asset('porto-light/vendor/animate/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('porto-light/vendor/font-awesome/css/fontawesome-all.min.css') }}" />
    {{--<link rel="stylesheet" href="{{ asset('porto-light/vendor/select2/css/select2.css') }}" />--}}
{{--    <link rel="stylesheet" href="{{ asset('porto-light/vendor/select2-bootstrap-theme/select2-bootstrap.min.css') }}" />--}}
{{--    <link rel="stylesheet" href="{{ asset('porto-light/vendor/datatables/media/css/dataTables.bootstrap4.css') }}" />--}}
    <link rel="stylesheet" href="{{ asset('porto-light/css/theme.css') }}" />
    <link rel="stylesheet" href="{{ asset('porto-light/css/custom.css') }}" />
    @if (file_exists(public_path('theme/theme.css')))
        <link rel="stylesheet" href="{{ asset('theme/theme.css') }}" />
    @endif
    {{--<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" />--}}
    {{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.26.29/sweetalert2.min.css" />--}}
{{--    <link rel="stylesheet" href="{{asset('porto-light/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css')}}" />--}}

    <!-- Specific Page Vendor CSS -->
    {{--<link rel="stylesheet" href="{{asset('porto-light/vendor/jquery-ui/jquery-ui.css')}}" />--}}
    {{--<link rel="stylesheet" href="{{asset('porto-light/vendor/jquery-ui/jquery-ui.theme.css')}}" />--}}
    {{--<link rel="stylesheet" href="{{asset('porto-light/vendor/select2/css/select2.css')}}" />--}}
{{--    <link rel="stylesheet" href="{{asset('porto-light/vendor/select2-bootstrap-theme/select2-bootstrap.min.css')}}" />--}}

    <!-- Daterange picker plugins css -->
    {{--<link href="{{ asset('porto-light/vendor/bootstrap-timepicker/css/bootstrap-timepicker.css') }}" rel="stylesheet">--}}
    {{--<link href="{{ asset('porto-light/vendor/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">--}}

{{--    <link rel="stylesheet" href="{{asset('porto-light/vendor/bootstrap-timepicker/css/bootstrap-timepicker.css')}}" />--}}

    <link rel="stylesheet" href="{{asset('porto-light/vendor/jquery-loading/dist/jquery.loading.css')}}" />

    @if (file_exists(public_path('theme/custom_styles.css')))
        <link rel="stylesheet" href="{{ asset('theme/custom_styles.css') }}" />
    @endif
    {{--@stack('styles')--}}


    {{--<script src="{{ asset('porto-light/vendor/modernizr/modernizr.js') }}"></script>--}}

    <style>
        .body-web {
            height: 100vh;
        }
        #main-wrapper {
            height: 100%;
        }
        .row {
            height: 100%;
            justify-content: center;
            align-items: center;
        }
        .card {
            margin: 0;
        }
    </style>
</head>
<body class="body-web">
<div id="main-wrapper">
    <div class="row">
        <div class="col-md-6">
            @yield('content')
        </div>
    </div>
</div>

    {{--@stack('scripts')--}}
    
    @yield('content-mercadopago')
    
    <script src="{{ asset('js/manifest.js') }}"></script>
    <script src="{{ asset('js/vendor.js') }}"></script>
    <script src="{{ mix('js/app.js') }}"></script>
    <!-- Theme Base, Components and Settings -->
    {{--<script src="{{asset('porto-light/js/theme.js')}}"></script>--}}
</body>
</html>
