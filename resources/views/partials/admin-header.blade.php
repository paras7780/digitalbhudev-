<header class="admin-topbar">
    <div class="topbar-left">
        <button type="button" class="sidebar-toggle-btn" aria-label="Toggle Navigation">
            ☰
        </button>
        <a href="{{ route('admin.dashboard') }}" class="topbar-mobile-brand">
            <span class="brand-mark"><img src="{{ asset('assets/img/logo-mark.svg') }}" alt="DigitalBhudev"></span>
            <span class="topbar-mobile-title">DigitalBhudev</span>
        </a>
        <nav class="admin-breadcrumbs" aria-label="Breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            @yield('breadcrumbs')
        </nav>
    </div>

    <div class="topbar-right">
        <a href="{{ route('home') }}" target="_blank" class="topbar-link" title="Open DigitalBhudev homepage in new tab">
            <span>View Website</span>
            <span style="font-size:10px">↗</span>
        </a>

        <div class="admin-user-pill">
            <span class="admin-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</span>
            <span style="font-weight:600;color:var(--admin-heading)">{{ auth()->user()->name }}</span>
            <span style="font-size:10.5px;background:var(--admin-pink-soft);color:var(--admin-primary);border:1px solid var(--admin-pink-border);padding:2px 8px;border-radius:9999px;font-weight:700">Admin</span>
        </div>

        <form method="POST" action="{{ route('logout') }}" style="margin:0">
            @csrf
            <button type="submit" class="logout-btn-top" title="Sign out of Admin Panel">
                Log out
            </button>
        </form>
    </div>
</header>
