@extends('layouts.bhudev')

@section('title', 'My Dashboard | Bhudev Portal')

@section('breadcrumbs')
    <span class="sep">/</span>
    <strong>My Dashboard</strong>
@endsection

@section('content')
<div class="admin-page-header">
    <div class="admin-page-title">
        <h1>Namaste, {{ auth()->user()->name }}</h1>
        <p>Welcome to your personal DigitalBhudev portal. Manage your profile, ceremonies, and gallery.</p>
    </div>
    <div class="admin-page-actions">
        <a href="{{ route('bhudev.profile.edit') }}" class="btn btn-primary">
            ✎ Edit My Profile
        </a>
        @if($profile->is_visible)
            <a href="{{ route('bhudevs.show', $profile->slug) }}" target="_blank" class="btn btn-outline">
                🌐 View Public Profile ↗
            </a>
        @endif
    </div>
</div>

{{-- Status Alert Banners --}}
@if($profile->status === 'pending')
    <div style="background:#fff9eb;border:1px solid #fedb9b;border-left:5px solid #d5a45c;border-radius:8px;padding:18px 22px;margin-bottom:24px;display:flex;align-items:flex-start;gap:14px">
        <div style="font-size:24px;line-height:1">⏳</div>
        <div>
            <strong style="font-size:15px;color:#875200;display:block;margin-bottom:4px">Profile Pending Admin Verification</strong>
            <p style="margin:0;color:#6d4810;font-size:13px;line-height:1.5">
                Your profile submission is currently awaiting verification by the DigitalBhudev administration. Once verified and approved, your profile will be published to the public devotee directory for {{ $profile->city ?: 'your area' }}. You can continue refining your profile details and uploading ceremony photos in the meantime.
            </p>
        </div>
    </div>
@elseif($profile->status === 'approved')
    <div style="background:#f0faf2;border:1px solid #bce1c5;border-left:5px solid #2e6930;border-radius:8px;padding:18px 22px;margin-bottom:24px;display:flex;align-items:flex-start;gap:14px">
        <div style="font-size:24px;line-height:1">✓</div>
        <div>
            <strong style="font-size:15px;color:#1e5e29;display:block;margin-bottom:4px">Profile Verified & Approved</strong>
            <p style="margin:0;color:#285e33;font-size:13px;line-height:1.5">
                Your Bhudev credentials have been verified by the administrator.
                @if($profile->is_visible)
                    Your profile is currently <strong>Live</strong> on the public directory. Devotees can discover your pooja services and contact you.
                @else
                    Your profile is currently marked <strong>Hidden</strong> from public listings. You may contact admin if you need it republished.
                @endif
            </p>
        </div>
    </div>
@elseif($profile->status === 'rejected')
    <div style="background:#fdf2f2;border:1px solid #f8c9c9;border-left:5px solid #c02b2b;border-radius:8px;padding:18px 22px;margin-bottom:24px;display:flex;align-items:flex-start;gap:14px">
        <div style="font-size:24px;line-height:1">✕</div>
        <div>
            <strong style="font-size:15px;color:#a82020;display:block;margin-bottom:4px">Profile Submission Requires Attention</strong>
            <p style="margin:0 0 8px;color:#7a1b1b;font-size:13px;line-height:1.5">
                The administrator reviewed your profile and requested corrections.
            </p>
            @if($profile->rejection_reason)
                <div style="background:#fff;border:1px solid #f2b8b8;border-radius:6px;padding:10px 14px;color:#901d1d;font-size:13px;font-style:italic">
                    <strong>Admin Reason:</strong> "{{ $profile->rejection_reason }}"
                </div>
            @endif
            <p style="margin:8px 0 0;color:#7a1b1b;font-size:12px">
                Please update your profile information with the requested details and save to re-submit for review.
            </p>
        </div>
    </div>
@elseif($profile->status === 'suspended')
    <div style="background:#f1f5f9;border:1px solid #cbd5e1;border-left:5px solid #64748b;border-radius:8px;padding:18px 22px;margin-bottom:24px;display:flex;align-items:flex-start;gap:14px">
        <div style="font-size:24px;line-height:1">⏸</div>
        <div>
            <strong style="font-size:15px;color:#334155;display:block;margin-bottom:4px">Profile Suspended</strong>
            <p style="margin:0;color:#475569;font-size:13px;line-height:1.5">
                Your profile is currently suspended from appearing publicly. If you have questions regarding this status, please reach out to the site administration.
            </p>
        </div>
    </div>
@endif

@if(!empty($pendingRevision))
    <div style="background:#eff6ff;border:1px solid #bfdbfe;border-left:5px solid #3b82f6;border-radius:8px;padding:16px 20px;margin-bottom:24px;display:flex;align-items:center;gap:14px">
        <div style="font-size:20px;line-height:1">ℹ️</div>
        <div>
            <strong style="font-size:14px;color:#1e40af">Pending Profile Revisions</strong>
            <p style="margin:2px 0 0;color:#1e3a8a;font-size:13px">
                You recently submitted changes to your profile on {{ $pendingRevision->created_at->format('d M Y, h:i A') }}. While these are being reviewed, your currently approved profile remains active and visible to devotees.
            </p>
        </div>
    </div>
@endif

{{-- KPI Metric Cards --}}
<div class="admin-stat-grid">
    <div class="admin-stat-card {{ 'stat-' . $profile->status }}">
        <span class="stat-label">Profile Status</span>
        <span class="stat-value" style="font-size:22px;text-transform:capitalize">
            {{ ucfirst($profile->status) }}
        </span>
        <span class="stat-desc">
            @if($profile->status === 'approved') Verified Bhudev
            @elseif($profile->status === 'pending') Awaiting Review
            @elseif($profile->status === 'rejected') Needs Updates
            @else Suspended @endif
        </span>
    </div>

    <div class="admin-stat-card {{ $profile->is_visible ? 'stat-approved' : 'stat-hidden' }}">
        <span class="stat-label">Public Directory</span>
        <span class="stat-value" style="font-size:22px">
            {{ $profile->is_visible ? 'Live' : 'Hidden' }}
        </span>
        <span class="stat-desc">
            {{ $profile->is_visible ? 'Visible to all devotees' : 'Not searchable online' }}
        </span>
    </div>

    <div class="admin-stat-card stat-pending">
        <span class="stat-label">Pooja Services</span>
        <span class="stat-value">{{ $profile->services->count() }}</span>
        <span class="stat-desc">Rituals & ceremonies selected</span>
    </div>

    <div class="admin-stat-card stat-total">
        <span class="stat-label">Ceremony Photos</span>
        <span class="stat-value">{{ $profile->images->count() }}</span>
        <span class="stat-desc">Uploaded to your gallery</span>
    </div>
</div>

{{-- Two-Column Layout --}}
<div style="display:grid;grid-template-columns:1.2fr 0.8fr;gap:24px;margin-bottom:28px">
    {{-- Profile Snapshot --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <h3>Profile Summary</h3>
            <a href="{{ route('bhudev.profile.edit') }}" style="font-size:12px;color:var(--admin-saffron);text-decoration:none;font-weight:600">
                ✎ Edit Details &rarr;
            </a>
        </div>
        <div class="admin-card-body">
            <div style="display:flex;gap:20px;align-items:flex-start;margin-bottom:20px;padding-bottom:18px;border-bottom:1px solid var(--admin-border)">
                @if($profile->photo_path)
                    <img src="{{ asset('storage/' . $profile->photo_path) }}" alt="{{ $profile->display_name }}" style="width:72px;height:72px;border-radius:50%;object-fit:cover;border:2px solid var(--admin-gold)">
                @else
                    <div style="width:72px;height:72px;border-radius:50%;background:#f1e6d4;display:flex;align-items:center;justify-content:center;font-size:28px;color:#913815;font-weight:700">
                        {{ strtoupper(substr($profile->display_name, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <h2 style="font-family:'Playfair Display',serif;font-size:20px;margin:0 0 4px;color:var(--admin-ink)">
                        {{ $profile->display_name }}
                    </h2>
                    <p style="margin:0 0 6px;color:var(--admin-muted);font-size:13px">
                        📍 {{ $profile->city ?: 'City not set' }}{{ $profile->state ? ', ' . $profile->state : '' }}
                    </p>
                    <div style="display:flex;gap:8px;flex-wrap:wrap">
                        @if($profile->experience_years)
                            <span class="badge" style="background:#fbf3e5;color:#913815;border:1px solid #eed6af;font-size:11px">
                                {{ $profile->experience_years }} Years Experience
                            </span>
                        @endif
                        @if($profile->availability)
                            <span class="badge" style="background:#eaf5ea;color:#216a30;border:1px solid #bce1c5;font-size:11px">
                                {{ $profile->availability }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;font-size:13px">
                <div>
                    <span style="color:var(--admin-muted);display:block;font-size:11px;font-weight:700;text-transform:uppercase">Contact Mobile</span>
                    <strong style="color:var(--admin-ink)">{{ auth()->user()->phone ?? 'N/A' }}</strong>
                    <span style="display:block;font-size:11px;color:{{ $profile->show_mobile ? '#216a30' : '#888' }}">
                        {{ $profile->show_mobile ? '✓ Visible to devotees' : '🔒 Hidden from public' }}
                    </span>
                </div>
                <div>
                    <span style="color:var(--admin-muted);display:block;font-size:11px;font-weight:700;text-transform:uppercase">Contact Email</span>
                    <strong style="color:var(--admin-ink)">{{ auth()->user()->email }}</strong>
                    <span style="display:block;font-size:11px;color:{{ $profile->show_email ? '#216a30' : '#888' }}">
                        {{ $profile->show_email ? '✓ Visible to devotees' : '🔒 Hidden from public' }}
                    </span>
                </div>
                <div>
                    <span style="color:var(--admin-muted);display:block;font-size:11px;font-weight:700;text-transform:uppercase">Languages</span>
                    <span style="color:var(--admin-ink)">
                        {{ !empty($profile->languages) ? implode(', ', $profile->languages) : 'Not specified' }}
                    </span>
                </div>
                <div>
                    <span style="color:var(--admin-muted);display:block;font-size:11px;font-weight:700;text-transform:uppercase">Service Area</span>
                    <span style="color:var(--admin-ink)">{{ $profile->service_area ?: 'Local' }}</span>
                </div>
            </div>

            @if($profile->services->isNotEmpty())
                <div style="margin-top:18px;padding-top:16px;border-top:1px solid var(--admin-border)">
                    <span style="color:var(--admin-muted);display:block;font-size:11px;font-weight:700;text-transform:uppercase;margin-bottom:8px">Offered Pooja Services</span>
                    <div style="display:flex;gap:6px;flex-wrap:wrap">
                        @foreach($profile->services as $srv)
                            <span style="background:#f6f1e8;border:1px solid var(--admin-border);padding:4px 10px;border-radius:20px;font-size:12px;color:var(--admin-ink)">
                                🕉️ {{ $srv->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Quick Shortcuts & Privacy info --}}
    <div style="display:flex;flex-direction:column;gap:20px">
        <div class="admin-card" style="margin-bottom:0">
            <div class="admin-card-header">
                <h3>Quick Management</h3>
            </div>
            <div class="admin-card-body" style="display:flex;flex-direction:column;gap:10px">
                <a href="{{ route('bhudev.profile.edit') }}" class="sidebar-link" style="color:var(--admin-ink);background:#fbf7ef;border:1px solid var(--admin-border)">
                    <span class="nav-icon" style="color:var(--admin-saffron)">✎</span>
                    <div>
                        <strong style="display:block;font-size:13px">Edit Profile & Services</strong>
                        <small style="color:var(--admin-muted)">Update your bio, rituals, and address</small>
                    </div>
                </a>

                <a href="{{ route('bhudev.gallery') }}" class="sidebar-link" style="color:var(--admin-ink);background:#fbf7ef;border:1px solid var(--admin-border)">
                    <span class="nav-icon" style="color:var(--admin-gold)">🖼️</span>
                    <div>
                        <strong style="display:block;font-size:13px">Ceremony Photo Gallery</strong>
                        <small style="color:var(--admin-muted)">Upload images of rituals & pujas ({{ $profile->images->count() }} uploaded)</small>
                    </div>
                </a>

                <a href="{{ route('bhudev.password') }}" class="sidebar-link" style="color:var(--admin-ink);background:#fbf7ef;border:1px solid var(--admin-border)">
                    <span class="nav-icon" style="color:var(--admin-slate)">🔒</span>
                    <div>
                        <strong style="display:block;font-size:13px">Security & Password</strong>
                        <small style="color:var(--admin-muted)">Keep your login credentials secure</small>
                    </div>
                </a>

                <a href="{{ route('bhudev.history') }}" class="sidebar-link" style="color:var(--admin-ink);background:#fbf7ef;border:1px solid var(--admin-border)">
                    <span class="nav-icon" style="color:#64748b">📜</span>
                    <div>
                        <strong style="display:block;font-size:13px">Review & Status History</strong>
                        <small style="color:var(--admin-muted)">Track verification decisions and audit trail</small>
                    </div>
                </a>
            </div>
        </div>

        <div class="admin-card" style="background:#fffcf7;border-color:#eddac4;margin-bottom:0">
            <div class="admin-card-body" style="font-size:12px;color:#7d5c36;line-height:1.6">
                <strong style="display:block;font-size:13px;color:#913815;margin-bottom:6px">💡 Devotee Contact Tip</strong>
                Devotees can contact you directly when your profile is live. Make sure your mobile number and address privacy toggles reflect your personal preference in the Edit Profile section.
            </div>
        </div>
    </div>
</div>

{{-- Incoming Devotee Bookings & Inquiries --}}
<div class="admin-card">
    <div class="admin-card-header">
        <h3>Incoming Devotee Bookings &amp; Inquiries ({{ $incomingBookings->count() }})</h3>
        <span style="font-size:12px;color:var(--admin-muted)">Direct inquiries from devotees viewing your profile</span>
    </div>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:140px">Date</th>
                    <th>Devotee Name</th>
                    <th>Phone / WhatsApp</th>
                    <th>Requested Ceremony</th>
                    <th>Preferred Date</th>
                    <th>Message</th>
                    <th style="text-align:right">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($incomingBookings as $bk)
                    <tr>
                        <td style="white-space:nowrap;font-size:12px;color:var(--admin-muted)">
                            {{ $bk->created_at->format('d M Y, h:i A') }}
                        </td>
                        <td>
                            <strong>{{ $bk->name }}</strong>
                            @if($bk->city)
                                <small style="display:block;color:var(--admin-muted)">📍 {{ $bk->city }}</small>
                            @endif
                        </td>
                        <td>
                            @if($bk->phone)
                                <a href="tel:{{ $bk->phone }}" style="color:var(--admin-ink);font-weight:600;text-decoration:none">
                                    {{ $bk->phone }}
                                </a>
                            @else
                                <span style="color:var(--admin-muted)">—</span>
                            @endif
                        </td>
                        <td>
                            <strong style="color:#b94e20;font-size:13px">{{ $bk->pooja_service ?: 'Vedic Ritual' }}</strong>
                        </td>
                        <td>
                            @if($bk->preferred_date)
                                <span class="badge" style="background:#eaf5ea;color:#216a30;font-size:11px">
                                    {{ $bk->preferred_date->format('d M Y') }}
                                </span>
                            @else
                                <span style="color:var(--admin-muted)">Flexible</span>
                            @endif
                        </td>
                        <td style="font-size:12px;max-width:260px;color:var(--admin-muted)">
                            {{ $bk->message ?: '—' }}
                        </td>
                        <td style="text-align:right">
                            @if($bk->phone)
                                @php
                                    $clPhone = preg_replace('/[^0-9]/', '', $bk->phone);
                                    if (strlen($clPhone) === 10) $clPhone = '91' . $clPhone;
                                    $waReply = "Namaste {$bk->name}, this is Pandit {$profile->display_name}. I received your inquiry for {$bk->pooja_service} on DigitalBhudev.";
                                @endphp
                                <a href="https://api.whatsapp.com/send?phone={{ $clPhone }}&text={{ urlencode($waReply) }}" target="_blank" class="btn btn-sm" style="background:#25d366;color:#fff;border:none;font-size:11px;font-weight:700;display:inline-flex;align-items:center;gap:4px">
                                    <span>💬</span> Reply on WhatsApp
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center;padding:32px;color:var(--admin-muted)">
                            No devotee inquiries received yet. Once your profile is live, inquiries from devotees will appear here.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Recent Status History --}}
<div class="admin-card">
    <div class="admin-card-header">
        <h3>Recent Status & Review Activity</h3>
        <a href="{{ route('bhudev.history') }}" style="font-size:12px;color:var(--admin-saffron);text-decoration:none;font-weight:600">
            View Full History &rarr;
        </a>
    </div>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Date & Time</th>
                    <th>Transition</th>
                    <th>Reason / Note</th>
                    <th>Updated By</th>
                </tr>
            </thead>
            <tbody>
                @forelse($profile->statusHistory->take(5) as $hist)
                    <tr>
                        <td style="white-space:nowrap;font-size:12px;color:var(--admin-muted)">
                            {{ $hist->created_at->format('d M Y, h:i A') }}
                        </td>
                        <td>
                            <span class="badge badge-{{ $hist->to_status }}">
                                {{ ucfirst($hist->from_status ?: 'Initial') }} &rarr; {{ ucfirst($hist->to_status) }}
                            </span>
                        </td>
                        <td style="font-size:13px">
                            {{ $hist->reason ?: 'Status updated' }}
                        </td>
                        <td style="font-size:12px;color:var(--admin-muted)">
                            {{ $hist->changedBy?->name ?? 'System' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align:center;color:var(--admin-muted);padding:24px">
                            No status updates recorded yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
