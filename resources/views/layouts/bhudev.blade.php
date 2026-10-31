<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Bhudev Portal | DigitalBhudev')</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <!-- Base Styles & Typography -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}?v={{ @filemtime(public_path('assets/css/style.css')) ?: 1 }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}?v={{ @filemtime(public_path('assets/css/admin.css')) ?: 1 }}">

    <style>
        /* Mobile Topbar & Sidebar Critical Rules (Guaranteed priority across all mobile devices) */
        @media (max-width: 900px) {
            .topbar-desktop-lang,
            .topbar-right .lang-switcher-wrap,
            .topbar-right .topbar-link {
                display: none !important;
            }
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
            .admin-user-pill span:not(.admin-avatar):not(.badge) {
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
        <!-- Sidebar Navigation (Bhudev Scoped) -->
        <div class="sidebar-overlay"></div>
        <aside class="admin-sidebar">
            <div class="sidebar-brand">
                <a href="{{ route('bhudev.dashboard') }}">
                    <span class="brand-mark"><img src="{{ asset('assets/img/logo-mark.svg') }}" alt="DigitalBhudev"></span>
                    <span>
                        <strong>DigitalBhudev</strong>
                        <small>Bhudev Portal</small>
                    </span>
                </a>
                <button type="button" class="sidebar-close-btn" aria-label="Close navigation">✕</button>
            </div>

            {{-- Mobile Drawer Language Selector --}}
            <div class="sidebar-lang-box">
                <span class="sidebar-lang-label">{{ __('messages.languages.select') }}:</span>
                @include('partials.language-switcher')
            </div>

            <nav class="sidebar-nav">
                <div class="nav-section-title">My Account</div>
                <a href="{{ route('bhudev.dashboard') }}" class="sidebar-link {{ request()->routeIs('bhudev.dashboard') ? 'active' : '' }}">
                    <span class="nav-icon">📊</span>
                    <span>{{ __('messages.dashboard.overview') }}</span>
                </a>

                <div class="nav-section-title">My Digital Presence</div>
                <a href="{{ route('bhudev.website') }}" class="sidebar-link {{ request()->routeIs('bhudev.website*') ? 'active' : '' }}">
                    <span class="nav-icon">🌐</span>
                    <span>{{ __('messages.dashboard.website') }}</span>
                    @if(auth()->user()->bhudevProfile && auth()->user()->bhudevProfile->activeDomain)
                        <span class="badge-counter" style="background:#2e6930;color:#fff">LIVE</span>
                    @endif
                </a>
                <a href="{{ route('bhudev.instagram') }}" class="sidebar-link {{ request()->routeIs('bhudev.instagram*') ? 'active' : '' }}">
                    <span class="nav-icon">📸</span>
                    <span>Instagram & Reels</span>
                    @if(auth()->user()->bhudevProfile && auth()->user()->bhudevProfile->instagramSetting && auth()->user()->bhudevProfile->instagramSetting->is_enabled)
                        <span class="badge-counter" style="background:#be185d;color:#fff">ON</span>
                    @endif
                </a>
                <a href="{{ route('bhudev.samagri-studio') }}" class="sidebar-link {{ request()->routeIs('bhudev.samagri-studio*') ? 'active' : '' }}">
                    <span class="nav-icon">📜</span>
                    <span>{{ __('messages.nav.samagri_studio') }}</span>
                    <span class="badge-counter" style="background:#b81414;color:#fff;font-weight:700">NEW</span>
                </a>

                <div class="nav-section-title">Profile & Ceremonies</div>
                <a href="{{ route('bhudev.profile.edit') }}" class="sidebar-link {{ request()->routeIs('bhudev.profile.edit') ? 'active' : '' }}">
                    <span class="nav-icon">✎</span>
                    <span>{{ __('messages.dashboard.profile') }}</span>
                </a>
                <a href="{{ route('bhudev.gallery') }}" class="sidebar-link {{ request()->routeIs('bhudev.gallery*') ? 'active' : '' }}">
                    <span class="nav-icon">🖼️</span>
                    <span>{{ __('messages.dashboard.gallery') }}</span>
                </a>

                <div class="nav-section-title">Security & Activity</div>
                <a href="{{ route('bhudev.password') }}" class="sidebar-link {{ request()->routeIs('bhudev.password*') ? 'active' : '' }}">
                    <span class="nav-icon">🔒</span>
                    <span>{{ __('messages.dashboard.change_password') }}</span>
                </a>
                <a href="{{ route('bhudev.history') }}" class="sidebar-link {{ request()->routeIs('bhudev.history*') ? 'active' : '' }}">
                    <span class="nav-icon">📜</span>
                    <span>Review & Status History</span>
                </a>

                @if(auth()->user()->bhudevProfile && auth()->user()->bhudevProfile->is_visible)
                    <div class="nav-section-title">Public Directory</div>
                    <a href="{{ route('bhudevs.show', auth()->user()->bhudevProfile->slug) }}" target="_blank" class="sidebar-link" style="color:#f472b6">
                        <span class="nav-icon">🌐</span>
                        <span>View My Public Profile ↗</span>
                    </a>
                @endif
            </nav>

            <div class="sidebar-footer">
                <a href="{{ route('home') }}" target="_blank">
                    <span>🌐 DigitalBhudev Home</span>
                </a>
                <span style="font-size:11px;color:#8f7d6e">Bhudev Portal</span>
            </div>
        </aside>

        <!-- Main Wrapper -->
        <div class="admin-main">
            <!-- Header / Topbar -->
            <header class="admin-topbar">
                <div class="topbar-left">
                    <button type="button" class="sidebar-toggle-btn" id="adminSidebarToggleBtn" aria-label="Toggle Navigation">
                        ☰
                    </button>
                    <a href="{{ route('bhudev.dashboard') }}" class="topbar-mobile-brand">
                        <span class="brand-mark"><img src="{{ asset('assets/img/logo-mark.svg') }}" alt="DigitalBhudev"></span>
                        <span class="topbar-mobile-title">DigitalBhudev</span>
                    </a>
                    <nav class="admin-breadcrumbs" aria-label="Breadcrumb">
                        <a href="{{ route('bhudev.dashboard') }}">Bhudev Portal</a>
                        @yield('breadcrumbs')
                    </nav>
                </div>

                <div class="topbar-right">
                    <div class="topbar-desktop-lang">
                        @include('partials.language-switcher')
                    </div>

                    @if(auth()->user()->bhudevProfile && auth()->user()->bhudevProfile->is_visible)
                        <a href="{{ route('bhudevs.show', auth()->user()->bhudevProfile->slug) }}" target="_blank" class="topbar-link">
                            <span>Live Profile ↗</span>
                        </a>
                    @endif

                    <div class="admin-user-pill">
                        <span class="admin-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'B', 0, 1)) }}</span>
                        <span style="font-weight:600;color:var(--admin-heading)">{{ auth()->user()->name }}</span>
                        @php $st = auth()->user()->bhudevProfile?->status ?? 'pending'; @endphp
                        @if($st === 'approved')
                            <span class="badge badge-approved" style="font-size:10px;padding:2px 7px">Approved</span>
                        @elseif($st === 'pending')
                            <span class="badge badge-pending" style="font-size:10px;padding:2px 7px">Pending</span>
                        @elseif($st === 'rejected')
                            <span class="badge badge-rejected" style="font-size:10px;padding:2px 7px">Rejected</span>
                        @elseif($st === 'suspended')
                            <span class="badge badge-suspended" style="font-size:10px;padding:2px 7px">Suspended</span>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('logout') }}" style="margin:0">
                        @csrf
                        <button type="submit" class="logout-btn-top">{{ __('messages.nav.logout') }}</button>
                    </form>
                </div>
            </header>

            <!-- Main Content -->
            <main class="admin-content">
                @include('partials.admin-alerts')
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/admin.js') }}"></script>
    <script>
        (function() {
            function setupAdminSidebar() {
                var toggle = document.getElementById('adminSidebarToggleBtn') || document.querySelector('.sidebar-toggle-btn');
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
