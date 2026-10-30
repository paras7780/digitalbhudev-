<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('meta_description', 'Discover traditional services, connect with Bhudevs, and explore pooja and Panchang information with DigitalBhudev.')">
    <title>@yield('title', 'DigitalBhudev — Vishwas No Setu')</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}?v={{ @filemtime(public_path('assets/css/style.css')) ?: 1 }}">
    <script defer src="{{ asset('assets/js/script.js') }}?v={{ @filemtime(public_path('assets/js/script.js')) ?: 1 }}"></script>
    @stack('head')
</head>
<body>
    @include('partials.header')
    @yield('content')
    @include('partials.footer')
    @stack('scripts')
</body>
</html>
