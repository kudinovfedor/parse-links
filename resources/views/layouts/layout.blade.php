<!doctype html>
<html lang="{{ app()->getLocale() }}" class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name="description" content="Laravel project">
    <meta name="theme-color" content="#146994">
    <link href="{{ asset('manifest.webmanifest') }}" rel="manifest">
    @stack('css')
    <link rel="stylesheet" href="{{ asset('css/font-awesome.all.css') }}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.12/css/all.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script>
        (function (html) {
            html.className = html.className.replace(/\bno-js\b/, 'js');
        })(document.documentElement);

        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js').then(function (registration) {
                console.log(`[ServiceWorker] Registered. Scope is %o`, registration);
            }).catch(function (error) {
                console.log(`[ServiceWorker] Failed to Register with ${error}`);
            });
        }
    </script>
</head>
<body>

<?php //var_dump(apache_get_modules()); ?>

<div class="wrapper h-100">
    <nav class="navbar navbar-dark bg-dark navbar-expand-md">
        <div class="container">
            <a class="navbar-brand mb-0 h1" href="{{ url('/') }}">{{ config('app.name', 'Laravel') }}</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup"
                    aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('sites') }}">Sites</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="list-unstyled">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @yield('content')

</div>

<script src="{{ asset('js/app.js') }}"></script>
@stack('js')
</body>
</html>
