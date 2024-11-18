@php use Illuminate\Support\Facades\Auth; @endphp
    <!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">


    <!-- Fonts and icons -->
    <script src="{{ asset('js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
        WebFont.load({
            google: {families: ["Public Sans:300,400,500,600,700"]},
            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons",
                ],
                urls: ["{{ asset('css/fonts.min.css') }}"],
            },
            active: function () {
                sessionStorage.fonts = true;
            },
        });
    </script>
    <link href="{{ asset('fontawesome/css/fontawesome.css') }}" rel="stylesheet"/>
    <link href="{{ asset('fontawesome/css/brands.css') }}" rel="stylesheet"/>
    <link href="{{ asset('fontawesome/css/solid.css') }}" rel="stylesheet"/>
    <link href="{{ asset('fontawesome/css/solid.css') }}" rel="stylesheet"/>

    <!-- CDN Libraries -->
    <link href="//cdn.datatables.net/2.1.6/css/dataTables.dataTables.min.css" rel="stylesheet"/>
    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet"/>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/plugins.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/kaiadmin.min.css') }}"/>
    <script src="{{ asset('js/core/jquery-3.7.1.min.js') }}"></script>
    <script type="text/javascript" src="//cdn.datatables.net/2.1.6/js/dataTables.min.js"></script>

    <!-- Scripts -->
    {{--    @vite(['resources/sass/app.scss', 'resources/js/app.js'])--}}
</head>
<body>
<div id="app">
    <div class="main-panel">
        <div class="container">
            @yield('content')
        </div>
    </div>
    <!-- End Custom template -->
</div>

@yield('scripts')
@include('layouts.includes.scripts')
</body>
</html>
