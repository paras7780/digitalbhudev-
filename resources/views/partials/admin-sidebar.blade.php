@php
    $pendingCount = \App\Models\BhudevProfile::where('status', 'pending')->count();
    $newInquiriesCount = \App\Models\Inquiry::where('status', 'new')->count();
    $pendingDomainsCount = \App\Models\BhudevDomain::where('status', 'inquiry_submitted')->count();
@endphp

<div class="sidebar-overlay"></div>

<aside class="admin-sidebar">
    <div class="sidebar-brand">
        <a href="{{ route('admin.dashboard') }}">
            <span class="brand-mark"><img src="{{ asset('assets/img/logo-mark.svg') }}" alt="DigitalBhudev"></span>
            <span>
                <strong>DigitalBhudev</strong>
                <small>Control Center</small>
            </span>
        </a>
        <button type="button" class="sidebar-close-btn" aria-label="Close navigation">✕</button>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-title">Overview</div>
        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="nav-icon">📊</span>
            <span>Dashboard</span>
        </a>

        <div class="nav-section-title">Bhudev Management</div>
        <a href="{{ route('admin.bhudevs') }}" class="sidebar-link {{ request()->routeIs('admin.bhudevs') && !request()->filled('status') ? 'active' : '' }}">
            <span class="nav-icon">👥</span>
            <span>All Bhudevs</span>
        </a>
        <a href="{{ route('admin.bhudevs', ['status' => 'pending']) }}" class="sidebar-link {{ request()->routeIs('admin.bhudevs') && request('status') === 'pending' ? 'active' : '' }}">
            <span class="nav-icon">⏳</span>
            <span>Pending Review</span>
            @if($pendingCount > 0)
                <span class="badge-counter">{{ $pendingCount }}</span>
            @endif
        </a>
        <a href="{{ route('admin.bhudevs.create') }}" class="sidebar-link {{ request()->routeIs('admin.bhudevs.create') ? 'active' : '' }}">
            <span class="nav-icon">➕</span>
            <span>Add New Bhudev</span>
        </a>

        <div class="nav-section-title">SaaS & Custom Domains</div>
        <a href="{{ route('admin.domains.index') }}" class="sidebar-link {{ request()->routeIs('admin.domains*') ? 'active' : '' }}">
            <span class="nav-icon">🌐</span>
            <span>Pandit Websites & Domains</span>
            @if($pendingDomainsCount > 0)
                <span class="badge-counter">{{ $pendingDomainsCount }}</span>
            @endif
        </a>

        <div class="nav-section-title">Content & Media</div>
        <a href="{{ route('admin.rashifals') }}" class="sidebar-link {{ request()->routeIs('admin.rashifals*') ? 'active' : '' }}">
            <span class="nav-icon">♈</span>
            <span>Daily Rashifal</span>
        </a>
        <a href="{{ route('admin.panchang') }}" class="sidebar-link {{ request()->routeIs('admin.panchang*') ? 'active' : '' }}">
            <span class="nav-icon">🗓️</span>
            <span>Nitya Panchang</span>
        </a>
        <a href="{{ route('admin.instagram.index') }}" class="sidebar-link {{ request()->routeIs('admin.instagram*') ? 'active' : '' }}">
            <span class="nav-icon">📸</span>
            <span>Instagram Integration</span>
        </a>

        <div class="nav-section-title">Communications</div>
        <a href="{{ route('admin.inquiries') }}" class="sidebar-link {{ request()->routeIs('admin.inquiries*') ? 'active' : '' }}">
            <span class="nav-icon">✉️</span>
            <span>Contact Inquiries</span>
            @if($newInquiriesCount > 0)
                <span class="badge-counter">{{ $newInquiriesCount }}</span>
            @endif
        </a>

        <div class="nav-section-title">System & Security</div>
        <a href="{{ route('admin.audit-logs') }}" class="sidebar-link {{ request()->routeIs('admin.audit-logs*') ? 'active' : '' }}">
            <span class="nav-icon">🛡️</span>
            <span>Audit Trail</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <a href="{{ route('home') }}" target="_blank">
            <span>🌐 DigitalBhudev Live</span>
        </a>
        <div style="display:flex;gap:8px;font-size:11px;margin-top:6px">
            <a href="{{ route('privacy') }}" target="_blank" style="color:#d8b4cb;text-decoration:none">Privacy</a>
            <span style="color:#632655">•</span>
            <a href="{{ route('terms') }}" target="_blank" style="color:#d8b4cb;text-decoration:none">Terms</a>
            <span style="color:#632655">•</span>
            <a href="{{ route('disclaimer') }}" target="_blank" style="color:#d8b4cb;text-decoration:none">Disclaimer</a>
        </div>
        <span style="font-size:10.5px;color:#8f7d6e;margin-top:4px">Admin Panel v1.0</span>
    </div>
</aside>
