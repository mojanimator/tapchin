<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="manifest" href="/build/manifest.json"/>
    <link rel="manifest" href="/build/manifest.webmanifest" crossorigin="use-credentials"/>
    <meta name="theme-color" content="#10b981">
    <link rel="icon" href="/assets/images/icon/favicon.ico">
    <link rel="apple-touch-icon" href="/assets/images/icon/apple-touch-icon.png" sizes="180x180">
    {{--    <link rel="mask-icon" href="/mask-icon.svg" color="#FFFFFF">--}}
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>

    <style>
        @font-face {
            font-family: FontAwesome;
            src: url({{asset('fonts/fontawesome/fontawesome-webfont.eot')}});

            src: url({{asset('fonts/fontawesome/fontawesome-webfont.ttf')}}) format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: Tanha;
            src: url({{asset('fonts/Tanha/Tanha-FD.eot')}});
            src: url('{{asset('fonts/Tanha/Tanha-FD.eot')}}?#iefix') format('embedded-opentype'),
            url({{asset('fonts/Tanha/Tanha-FD.woff2')}}) format('woff2'),
            url({{asset('fonts/Tanha/Tanha-FD.woff')}}) format('woff'),
            url({{asset('fonts/Tanha/Tanha-FD.ttf')}}) format('truetype'),
            url('{{asset('fonts/Tanha/Tanha-FD.svg')}}#Tanha-FD') format('svg');

            font-weight: normal;
            font-style: normal;

            font-display: swap;
        }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
    {{--    @vite('resources/css/app.css')--}}
    @yield('styles')
    <!-- Scripts -->
</head>
<body class="font-sans antialiased" id="app">

<main class="py-0  " style="min-height: 100vh !important ">

    @yield('content')
</main>
@yield('scripts')

</body>
</html>