<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel | DigitalBhudev')</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    
    <!-- Existing Site Styles & Fonts -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}?v={{ @filemtime(public_path('assets/css/style.css')) ?: 1 }}">
    <!-- Dedicated Admin Panel Styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}?v={{ @filemtime(public_path('assets/css/admin.css')) ?: 1 }}">
    
    @stack('head')
</head>
<body class="admin-body">
    <div class="admin-app">
        <!-- Sidebar Navigation -->
        @include('partials.admin-sidebar')

        <!-- Main Wrapper -->
        <div class="admin-main">
            <!-- Header / Topbar -->
            @include('partials.admin-header')

            <!-- Main Content Area -->
            <main class="admin-content">
                <!-- Alerts / Flash messages -->
                @include('partials.admin-alerts')

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Reusable Confirmation Modals -->
    @include('partials.admin-modal')

    <!-- jQuery & Admin JS -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/admin.js') }}"></script>
    <script>
        (function() {
            var toggleBtn = document.querySelector('.sidebar-toggle-btn');
            var closeBtn = document.querySelector('.sidebar-close-btn');
            var sidebar = document.querySelector('.admin-sidebar');
            var overlay = document.querySelector('.sidebar-overlay');
            function toggle(open) {
                if (sidebar) sidebar.classList.toggle('open', open);
                if (overlay) overlay.classList.toggle('open', open);
                document.body.classList.toggle('sidebar-open', open);
            }
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    var shouldOpen = sidebar && !sidebar.classList.contains('open');
                    toggle(shouldOpen);
                });
            }
            if (closeBtn) {
                closeBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    toggle(false);
                });
            }
            if (overlay) {
                overlay.addEventListener('click', function(e) {
                    e.preventDefault();
                    toggle(false);
                });
            }
        })();
    </script>
    @stack('scripts')
</body>
</html>
