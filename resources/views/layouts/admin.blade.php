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

    <style>
        @media (max-width: 900px) {
            .admin-topbar {
                padding: 0 10px !important;
                height: 58px !important;
                display: flex !important;
                align-items: center !important;
                justify-content: space-between !important;
                gap: 8px !important;
                width: 100% !important;
                box-sizing: border-box !important;
            }
            .topbar-left {
                display: flex !important;
                align-items: center !important;
                gap: 8px !important;
                flex-shrink: 0 !important;
                min-width: 44px !important;
            }
            .sidebar-toggle-btn {
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                width: 42px !important;
                min-width: 42px !important;
                height: 42px !important;
                padding: 0 !important;
                font-size: 24px !important;
                font-weight: 700 !important;
                line-height: 1 !important;
                background: #ffffff !important;
                border: 1.5px solid var(--admin-border, #fbcfe8) !important;
                border-radius: 8px !important;
                color: var(--admin-primary, #be185d) !important;
                cursor: pointer !important;
                box-shadow: 0 2px 8px rgba(74, 22, 51, 0.1) !important;
                flex-shrink: 0 !important;
                z-index: 10 !important;
            }
            .topbar-mobile-brand {
                display: flex !important;
                align-items: center !important;
                gap: 6px !important;
                text-decoration: none !important;
                min-width: 0 !important;
            }
            .topbar-mobile-title {
                display: block !important;
                font-size: 14px !important;
                font-weight: 800 !important;
                color: var(--admin-heading, #4a1633) !important;
                white-space: nowrap !important;
                overflow: hidden !important;
                text-overflow: ellipsis !important;
                max-width: 110px !important;
            }
            .admin-breadcrumbs {
                display: none !important;
            }
            .topbar-right {
                display: flex !important;
                align-items: center !important;
                gap: 6px !important;
                flex-shrink: 0 !important;
                justify-content: flex-end !important;
            }
            .admin-user-pill {
                padding: 4px 8px !important;
                font-size: 11px !important;
                gap: 6px !important;
            }
            .admin-user-pill span:not(.admin-avatar):not(.badge):not([style*="background"]) {
                display: none !important;
            }
            .logout-btn-top {
                padding: 6px 10px !important;
                font-size: 11.5px !important;
            }
        }
    </style>
    
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
            function setupAdminSidebar() {
                var toggle = document.querySelector('.sidebar-toggle-btn');
                var close = document.querySelector('.sidebar-close-btn');
                var overlay = document.querySelector('.sidebar-overlay');
                var sidebar = document.querySelector('.admin-sidebar');

                function toggleSidebar(open) {
                    if (!sidebar) return;
                    if (open) {
                        sidebar.classList.add('open');
                        if (overlay) overlay.classList.add('open');
                        document.body.classList.add('sidebar-open');
                    } else {
                        sidebar.classList.remove('open');
                        if (overlay) overlay.classList.remove('open');
                        document.body.classList.remove('sidebar-open');
                    }
                }

                if (toggle) {
                    toggle.onclick = function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        var shouldOpen = sidebar && !sidebar.classList.contains('open');
                        toggleSidebar(shouldOpen);
                    };
                }

                if (close) {
                    close.onclick = function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        toggleSidebar(false);
                    };
                }

                if (overlay) {
                    overlay.onclick = function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        toggleSidebar(false);
                    };
                }

                if (sidebar) {
                    sidebar.querySelectorAll('a').forEach(function(a) {
                        a.addEventListener('click', function() {
                            if (window.innerWidth <= 900) {
                                toggleSidebar(false);
                            }
                        });
                    });
                }
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', setupAdminSidebar);
            } else {
                setupAdminSidebar();
            }
        })();
    </script>
    @stack('scripts')
</body>
</html>
