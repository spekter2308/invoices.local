<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    {{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.4/css/bulma.min.css">--}}

    <style>
        .level { display: flex; align-items: center; }
        .flex { flex: 1; }
        .m-r { margin-right: 1em; }
        .pd-1 { padding-right: 1em; }
        .pdl-1 { padding-left: 1em }
    </style>
</head>
<body>
<div id="app">

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="top-menu">
                        @include('layouts.nav')
                    </div>
                </div>
            </div>
        </div>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
