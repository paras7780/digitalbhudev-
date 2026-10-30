@extends('layouts.admin')

@section('title', 'Instagram Integration | DigitalBhudev Admin')

@section('breadcrumbs')
    <span class="sep">/</span>
    <strong>Instagram Integration</strong>
@endsection

@section('content')
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:24px;flex-wrap:wrap;gap:16px">
    <div>
        <h1 style="font-size:24px;color:var(--admin-dark);margin:0 0 6px;font-family:'Playfair Display',serif">
            📸 Instagram Integration Directory
        </h1>
        <p style="font-size:14px;color:var(--admin-muted);margin:0;max-width:700px">
            Manage Instagram post and Reel URLs, official Meta Graph API connections, and individual Pandit website publication settings. Content is displayed strictly on each Bhudev's own active website.
        </p>
    </div>
</div>

{{-- Top Metrics --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:16px;margin-bottom:24px">
    <div style="background:var(--admin-card-bg);border:1px solid var(--admin-card-border);border-radius:var(--admin-radius-lg);padding:18px;display:flex;align-items:center;gap:16px;box-shadow:var(--admin-shadow)">
        <div style="width:44px;height:44px;border-radius:10px;background:var(--accent-blue-soft);color:var(--accent-blue);display:flex;align-items:center;justify-content:center;font-size:20px">👥</div>
        <div>
            <div style="font-size:12px;color:var(--admin-muted);text-transform:uppercase;font-weight:600">Total Bhudevs</div>
            <div style="font-size:22px;font-weight:700;color:var(--admin-heading)">{{ $totalCount }}</div>
        </div>
    </div>

    <div style="background:var(--admin-card-bg);border:1px solid var(--admin-card-border);border-radius:var(--admin-radius-lg);padding:18px;display:flex;align-items:center;gap:16px;box-shadow:var(--admin-shadow)">
        <div style="width:44px;height:44px;border-radius:10px;background:var(--accent-green-soft);color:var(--accent-green);display:flex;align-items:center;justify-content:center;font-size:20px">🌐</div>
        <div>
            <div style="font-size:12px;color:var(--admin-muted);text-transform:uppercase;font-weight:600">Active Pandit Websites</div>
            <div style="font-size:22px;font-weight:700;color:var(--accent-green)">{{ $activeSites }}</div>
        </div>
    </div>

    <div style="background:var(--admin-card-bg);border:1px solid var(--admin-card-border);border-radius:var(--admin-radius-lg);padding:18px;display:flex;align-items:center;gap:16px;box-shadow:var(--admin-shadow)">
        <div style="width:44px;height:44px;border-radius:10px;background:var(--admin-sidebar-active);color:var(--color-primary);display:flex;align-items:center;justify-content:center;font-size:20px">📸</div>
        <div>
            <div style="font-size:12px;color:var(--admin-muted);text-transform:uppercase;font-weight:600">Instagram Enabled</div>
            <div style="font-size:22px;font-weight:700;color:var(--color-primary)">{{ $enabledCount }}</div>
        </div>
    </div>
</div>

{{-- Search & Filters --}}
<div style="background:var(--admin-card-bg);border:1px solid var(--admin-card-border);border-radius:var(--admin-radius-lg);padding:16px;margin-bottom:20px;box-shadow:var(--admin-shadow)">
    <form method="GET" action="{{ route('admin.instagram.index') }}" style="display:flex;flex-wrap:wrap;gap:12px;align-items:center;justify-content:space-between">
        <div style="display:flex;flex-wrap:wrap;gap:12px;align-items:center;flex:1">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by name, city, phone, domain..." style="padding:10px 14px;border:1px solid var(--admin-input-border);border-radius:var(--admin-radius-sm);font-size:14px;min-width:260px;flex:1">

            <select name="filter" style="padding:10px 14px;border:1px solid var(--admin-input-border);border-radius:var(--admin-radius-sm);font-size:14px;background:#fff;color:#333">
                <option value="">All Bhudevs</option>
                <option value="with_active_website" {{ request('filter') === 'with_active_website' ? 'selected' : '' }}>✓ Has Active Website (Eligible)</option>
                <option value="no_website" {{ request('filter') === 'no_website' ? 'selected' : '' }}>⏳ No Active Website (Ineligible)</option>
                <option value="enabled" {{ request('filter') === 'enabled' ? 'selected' : '' }}>📸 Instagram Enabled</option>
            </select>

            <button type="submit" class="btn-action btn-action-primary" style="padding:10px 18px">Filter</button>
            @if(request()->hasAny(['q', 'filter']))
                <a href="{{ route('admin.instagram.index') }}" class="btn-action" style="padding:10px 14px;background:#f5f0eb;color:#554a40;text-decoration:none">Reset</a>
            @endif
        </div>
    </form>
</div>

{{-- Profiles Table --}}
<div style="background:#fff;border:1px solid #ebd9c4;border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.02)">
    <table class="admin-table" style="width:100%;border-collapse:collapse">
        <thead>
            <tr style="background:#fcf9f5;border-bottom:1px solid #eedecf;text-align:left;font-size:12px;text-transform:uppercase;color:#786b60">
                <th style="padding:14px 18px">Bhudev Profile</th>
                <th style="padding:14px 18px">Personal Website / Domain</th>
                <th style="padding:14px 18px">Instagram Status</th>
                <th style="padding:14px 18px">Reels & Posts</th>
                <th style="padding:14px 18px;text-align:right">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($profiles as $p)
                @php
                    $hasActiveDomain = $p->activeDomain && $p->activeDomain->isActive();
                    $domainName = $p->activeDomain?->domain_name ?? $p->domains->first()?->domain_name;
                    $domainStatus = $p->activeDomain ? 'active' : ($p->domains->first()?->status ?? 'none');
                    $igSetting = $p->instagramSetting;
                    $isEnabled = $igSetting && $igSetting->is_enabled;
                    $postCount = $p->instagramPosts->count();
                @endphp
                <tr style="border-bottom:1px solid #f2e9df">
                    <td style="padding:14px 18px">
                        <div style="display:flex;align-items:center;gap:12px">
                            @if($p->photo_path)
                                <img src="{{ asset('storage/' . $p->photo_path) }}" alt="{{ $p->display_name }}" style="width:40px;height:40px;border-radius:50%;object-fit:cover;border:1px solid #eedecf">
                            @else
                                <div style="width:40px;height:40px;border-radius:50%;background:#fdeee5;color:#b94e20;display:flex;align-items:center;justify-content:center;font-weight:700">ॐ</div>
                            @endif
                            <div>
                                <a href="{{ route('admin.bhudevs.show', $p) }}" style="font-weight:600;color:var(--admin-dark);text-decoration:none">
                                    {{ $p->display_name }}
                                </a>
                                <div style="font-size:12px;color:var(--admin-muted);margin-top:2px">
                                    {{ $p->city ? $p->city . ', ' . ($p->state ?? 'Gujarat') : 'Location unset' }}
                                    @if($p->user?->phone)
                                        • 📞 {{ $p->user->phone }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </td>

                    <td style="padding:14px 18px">
                        @if($hasActiveDomain)
                            <div style="display:flex;align-items:center;gap:6px">
                                <span style="background:#e8f7ee;color:#1e7e34;font-size:11px;font-weight:700;padding:3px 8px;border-radius:4px">ACTIVE</span>
                                <a href="http://{{ $domainName }}" target="_blank" style="font-family:monospace;font-size:13px;color:#1e7e34;text-decoration:none">
                                    {{ $domainName }} ↗
                                </a>
                            </div>
                        @elseif($domainName)
                            <div style="display:flex;align-items:center;gap:6px">
                                <span style="background:#fef3c7;color:#92400e;font-size:11px;font-weight:700;padding:3px 8px;border-radius:4px">
                                    {{ strtoupper(str_replace('_', ' ', $domainStatus)) }}
                                </span>
                                <span style="font-family:monospace;font-size:13px;color:#92400e">{{ $domainName }}</span>
                            </div>
                        @else
                            <span style="background:#f3f4f6;color:#6b7280;font-size:11px;font-weight:600;padding:3px 8px;border-radius:4px">
                                No Website
                            </span>
                        @endif
                    </td>

                    <td style="padding:14px 18px">
                        <div style="display:flex;flex-direction:column;gap:4px">
                            <div>
                                @if($isEnabled)
                                    <span style="background:#e8f7ee;color:#1e7e34;font-size:11px;font-weight:700;padding:2px 8px;border-radius:999px">
                                        ✓ Display Enabled
                                    </span>
                                @else
                                    <span style="background:#f3f4f6;color:#6b7280;font-size:11px;font-weight:600;padding:2px 8px;border-radius:999px">
                                        Disabled
                                    </span>
                                @endif
                            </div>

                            @if($igSetting && $igSetting->connection_status === 'connected')
                                <div style="font-size:11px;color:#e1306c;font-weight:600">
                                    🔗 @<span>{{ $igSetting->account_username }}</span>
                                </div>
                            @endif
                        </div>
                    </td>

                    <td style="padding:14px 18px">
                        @if($postCount > 0)
                            <span style="font-weight:600;color:var(--admin-dark)">{{ $postCount }}</span>
                            <span style="font-size:12px;color:var(--admin-muted)">URLs</span>
                        @else
                            <span style="font-size:13px;color:#9ca3af">—</span>
                        @endif
                    </td>

                    <td style="padding:14px 18px;text-align:right">
                        <a href="{{ route('admin.instagram.show', $p) }}" class="btn-action" style="background:#fce8f3;border-color:#f9a8d4;color:#be185d;padding:8px 14px;font-weight:600;text-decoration:none">
                            📸 Manage Instagram
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align:center;padding:40px 20px;color:var(--admin-muted)">
                        No Bhudev profiles found matching your search.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($profiles->hasPages())
        <div style="padding:16px 20px;border-top:1px solid #eedecf">
            {{ $profiles->links() }}
        </div>
    @endif
</div>
@endsection
