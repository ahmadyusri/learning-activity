<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('titlePage') {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('assets/assets/css/webpacks/app.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/assets/css/webpacks/user.css') }}" rel="stylesheet">

    <!-- Alertify -->
    <link rel="stylesheet" href="{{ asset('assets/frameworks/alertify/alertify.min.css') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/fontawesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/solid.min.css">

    <!-- Loading Style -->
    <link rel="stylesheet" href="{{ asset('assets/assets/css/loading-style.css') }}">

    @yield('styles')
</head>

<body>
    <div id="app">
        <main class="d-flex flex-row">
            @include('layouts.user.components.sidebar')

            <!-- Page Content -->
            <div class="content">
                @include('layouts.user.components.navigation')

                <div class="my-3">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    <!-- Loading -->
    <div class="bgLoading">
        <div id="loader">
            <div id="shadow"></div>
            <div id="box"></div>
        </div>
    </div>
    <!-- Loading -->


    <!-- Scripts -->
    <script src="{{ asset('assets/assets/js/webpacks/app.js') }}"></script>
    <script src="{{ asset('assets/assets/js/webpacks/user.js') }}"></script>

    <!-- Alertify -->
    <script defer src="{{ asset('assets/frameworks/alertify/alertify.min.js') }}"></script>
    <script defer src="{{ asset('assets/frameworks/alertify/option.js') }}"></script>

    <script>
        const rootUrl = "{{ url('/') }}";
        const csrfToken = "{{ csrf_token() }}";
    </script>

    @yield('scripts')
</body>

</html>
