<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('meta_description', 'Discover traditional services, connect with Bhudevs, and explore pooja and Panchang information with DigitalBhudev.')">
    <title>@yield('title', 'DigitalBhudev — Vishwas No Setu')</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}?v={{ @filemtime(public_path('assets/css/style.css')) ?: 1 }}">
    <style>
        /* Mobile Header Critical Rules */
        @media (max-width: 1100px) {
            .nav-actions { display: none !important; }
            .menu-toggle {
                display: flex !important;
                flex-direction: column !important;
                justify-content: center !important;
                align-items: center !important;
                gap: 5px !important;
                width: 42px !important;
                height: 42px !important;
                padding: 8px !important;
                border-radius: 8px !important;
                border: 1.5px solid var(--card-border, #fbcfe8) !important;
                background: #ffffff !important;
                cursor: pointer !important;
                flex-shrink: 0 !important;
                z-index: 52 !important;
                box-shadow: 0 2px 6px rgba(74, 22, 51, 0.08) !important;
            }
            .menu-toggle span {
                display: block !important;
                width: 22px !important;
                height: 2.5px !important;
                background: var(--heading, #4a1633) !important;
                border-radius: 2px !important;
                margin: 0 !important;
                transition: transform 0.2s, opacity 0.2s !important;
            }
            .menu-toggle.active span:nth-child(1) {
                transform: translateY(7.5px) rotate(45deg);
            }
            .menu-toggle.active span:nth-child(2) {
                opacity: 0;
            }
            .menu-toggle.active span:nth-child(3) {
                transform: translateY(-7.5px) rotate(-45deg);
            }
            .main-nav {
                display: flex !important;
                position: fixed !important;
                top: 0 !important;
                right: 0 !important;
                bottom: 0 !important;
                width: min(88vw, 320px) !important;
                height: 100vh !important;
                height: 100dvh !important;
                background: #ffffff !important;
                flex-direction: column !important;
                justify-content: flex-start !important;
                align-items: stretch !important;
                padding: 20px 20px 28px !important;
                gap: 6px !important;
                box-shadow: -8px 0 32px rgba(0, 0, 0, 0.28) !important;
                transform: translateX(105%) !important;
                visibility: hidden !important;
                pointer-events: none !important;
                transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.28s !important;
                z-index: 9999 !important;
                overflow-y: auto !important;
                -webkit-overflow-scrolling: touch !important;
                margin: 0 !important;
            }
            .main-nav.open {
                transform: translateX(0) !important;
                visibility: visible !important;
                pointer-events: auto !important;
            }
            .nav-backdrop {
                display: none;
                position: fixed !important;
                inset: 0 !important;
                background: rgba(36, 15, 33, 0.55) !important;
                backdrop-filter: blur(4px) !important;
                -webkit-backdrop-filter: blur(4px) !important;
                z-index: 9998 !important;
                opacity: 0;
                transition: opacity 0.25s ease;
            }
            .nav-backdrop.open {
                display: block !important;
                opacity: 1 !important;
            }
            body.nav-open {
                overflow: hidden !important;
            }
        }
    </style>
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
