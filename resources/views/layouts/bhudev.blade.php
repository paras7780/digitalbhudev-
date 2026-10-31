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
                    <button type="button" class="sidebar-toggle-btn" aria-label="Toggle Navigation">
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
                    @include('partials.language-switcher')

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
