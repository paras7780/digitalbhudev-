@extends('layouts.admin')

@section('title', 'Pandit Websites & SaaS Domains | DigitalBhudev Admin')

@section('breadcrumbs')
    <span class="sep">/</span>
    <strong>Pandit Websites &amp; Domains</strong>
@endsection

@section('content')
<div class="admin-page-header">
    <div class="admin-page-title">
        <h1>Pandit Websites &amp; Custom Domains (SaaS)</h1>
        <p>Manage individual white-labeled domains, track website requests, set quotations, and activate live Pandit websites.</p>
    </div>
    <div class="admin-page-actions">
        <button type="button" class="btn btn-primary" onclick="openCreateDomainModal()">
            ➕ Assign New Domain
        </button>
    </div>
</div>

{{-- METRIC CARDS --}}
<div class="metrics-grid" style="grid-template-columns:repeat(auto-fit, minmax(180px, 1fr));margin-bottom:24px">
    <a href="{{ route('admin.domains.index') }}" class="metric-card" style="text-decoration:none;border-radius:var(--admin-radius-lg);border:1px solid var(--admin-card-border);box-shadow:var(--admin-shadow)">
        <div class="metric-icon" style="background:var(--accent-blue-soft);color:var(--accent-blue)">🌐</div>
        <div class="metric-body">
            <span class="metric-value">{{ $counts['total'] }}</span>
            <span class="metric-label">Total Domains</span>
        </div>
    </a>
    <a href="{{ route('admin.domains.index', ['status' => 'inquiry_submitted']) }}" class="metric-card" style="text-decoration:none;border-radius:var(--admin-radius-lg);border:1px solid var(--admin-card-border);box-shadow:var(--admin-shadow)">
        <div class="metric-icon" style="background:var(--accent-amber-soft);color:var(--accent-amber)">⏳</div>
        <div class="metric-body">
            <span class="metric-value">{{ $counts['inquiry_submitted'] }}</span>
            <span class="metric-label">Inquiries Received</span>
        </div>
    </a>
    <a href="{{ route('admin.domains.index', ['status' => 'quoted']) }}" class="metric-card" style="text-decoration:none;border-radius:var(--admin-radius-lg);border:1px solid var(--admin-card-border);box-shadow:var(--admin-shadow)">
        <div class="metric-icon" style="background:var(--admin-sidebar-active);color:var(--color-primary)">🏷️</div>
        <div class="metric-body">
            <span class="metric-value">{{ $counts['quoted'] }}</span>
            <span class="metric-label">Quoted / Pending Pay</span>
        </div>
    </a>
    <a href="{{ route('admin.domains.index', ['status' => 'active']) }}" class="metric-card" style="text-decoration:none;border-radius:var(--admin-radius-lg);border:1px solid var(--admin-card-border);box-shadow:var(--admin-shadow)">
        <div class="metric-icon" style="background:var(--accent-green-soft);color:var(--accent-green)">✓</div>
        <div class="metric-body">
            <span class="metric-value" style="color:var(--accent-green)">{{ $counts['active'] }}</span>
            <span class="metric-label">Active &amp; Live</span>
        </div>
    </a>
    <a href="{{ route('admin.domains.index', ['status' => 'suspended']) }}" class="metric-card" style="text-decoration:none;border-radius:var(--admin-radius-lg);border:1px solid var(--admin-card-border);box-shadow:var(--admin-shadow)">
        <div class="metric-icon" style="background:#fff1f2;color:#e11d48">⏸️</div>
        <div class="metric-body">
            <span class="metric-value">{{ $counts['suspended'] }}</span>
            <span class="metric-label">Suspended</span>
        </div>
    </a>
</div>

{{-- FILTER BAR --}}
<div class="admin-card" style="margin-bottom:24px">
    <form method="GET" action="{{ route('admin.domains.index') }}" style="display:flex;flex-wrap:wrap;gap:12px;align-items:center;padding:6px 0">
        <div style="flex:1;min-width:240px">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by domain, Pandit name, or headline..." style="width:100%;padding:9px 14px;border:1px solid var(--admin-input-border);border-radius:var(--admin-radius-sm);font-size:14px;box-sizing:border-box">
        </div>

        <select name="status" style="padding:9px 14px;border:1px solid var(--admin-input-border);border-radius:var(--admin-radius-sm);font-size:14px;background:#fff">
            <option value="">All Statuses</option>
            <option value="inquiry_submitted" {{ request('status') === 'inquiry_submitted' ? 'selected' : '' }}>Inquiry Submitted</option>
            <option value="quoted" {{ request('status') === 'quoted' ? 'selected' : '' }}>Quoted</option>
            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
            <option value="dns_pending" {{ request('status') === 'dns_pending' ? 'selected' : '' }}>DNS Pending</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active (Live)</option>
            <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
        </select>

        <select name="tier" style="padding:9px 14px;border:1px solid var(--admin-input-border);border-radius:var(--admin-radius-sm);font-size:14px;background:#fff">
            <option value="">All Tiers</option>
            <option value="standard_saas" {{ request('tier') === 'standard_saas' ? 'selected' : '' }}>Standard SaaS</option>
            <option value="dedicated_custom" {{ request('tier') === 'dedicated_custom' ? 'selected' : '' }}>Dedicated Custom DB</option>
        </select>

        <button type="submit" class="btn btn-primary" style="padding:9px 20px">Filter</button>
        @if(request()->hasAny(['search', 'status', 'tier']))
            <a href="{{ route('admin.domains.index') }}" class="btn btn-outline" style="padding:9px 16px">Clear</a>
        @endif
    </form>
</div>

{{-- DOMAINS TABLE --}}
<div class="admin-card">
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:180px">Domain &amp; Tier</th>
                    <th>Bhudev (Pandit Ji)</th>
                    <th>Hero Title &amp; WhatsApp</th>
                    <th style="width:130px">Status</th>
                    <th style="width:140px">Pricing Quote</th>
                    <th style="width:340px;text-align:right">Management &amp; Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($domains as $item)
                    <tr>
                        <td>
                            <strong style="font-size:15px;color:var(--admin-ink);display:block">
                                {{ $item->domain_name }}
                            </strong>
                            <div style="margin-top:4px;display:flex;gap:6px;align-items:center">
                                @if($item->tier === 'dedicated_custom')
                                    <span class="badge" style="background:#5a3f8a;color:#fff;font-size:10px">Dedicated DB</span>
                                @else
                                    <span class="badge" style="background:#eaf5ea;color:#216a30;font-size:10px">Standard SaaS</span>
                                @endif
                                <a href="{{ route('home') }}?preview_domain={{ $item->domain_name }}" target="_blank" style="font-size:11px;color:#b94e20;text-decoration:none" title="Preview Pandit Landing Page">
                                    👁️ Preview ↗
                                </a>
                            </div>
                        </td>

                        <td>
                            @if($item->profile)
                                <a href="{{ route('admin.bhudevs.show', $item->profile->id) }}" style="font-weight:700;color:var(--admin-ink);text-decoration:none">
                                    {{ $item->profile->display_name }}
                                </a>
                                <small style="display:block;color:var(--admin-muted)">
                                    📍 {{ $item->profile->city ?: 'City unassigned' }} • {{ $item->profile->user?->email }}
                                </small>
                            @else
                                <span style="color:#999">Unassigned Profile</span>
                            @endif
                        </td>

                        <td>
                            <div style="font-size:13px;font-weight:600;color:#29241f">
                                {{ $item->hero_title ?: ($item->profile?->display_name ?? 'Default Title') }}
                            </div>
                            <div style="font-size:12px;margin-top:2px">
                                💬 WhatsApp: <strong>{{ $item->getEffectiveWhatsAppNumber() ?: 'Not set' }}</strong>
                            </div>
                            @if($item->bhudev_notes)
                                <div style="font-size:11px;color:#7a6d5f;margin-top:4px;max-width:240px;line-height:1.4">
                                    <em>Note: "{{ Str::limit($item->bhudev_notes, 60) }}"</em>
                                </div>
                            @endif
                        </td>

                        <td>
                            @php
                                $badges = [
                                    'inquiry_submitted' => ['bg' => '#fff9eb', 'fg' => '#875200', 'border' => '#fedb9b', 'icon' => '⏳ Inquiry'],
                                    'quoted'            => ['bg' => '#fef3e9', 'fg' => '#b94e20', 'border' => '#fedbce', 'icon' => '🏷️ Quoted'],
                                    'paid'              => ['bg' => '#eaf2fd', 'fg' => '#1d54b2', 'border' => '#bcd2f5', 'icon' => '💳 Paid'],
                                    'dns_pending'       => ['bg' => '#f5f0fb', 'fg' => '#5e2ba6', 'border' => '#ded0f6', 'icon' => '🌐 DNS Setup'],
                                    'active'            => ['bg' => '#f0faf2', 'fg' => '#1e5e29', 'border' => '#bce1c5', 'icon' => '✓ Live'],
                                    'suspended'         => ['bg' => '#fdf2f2', 'fg' => '#a82020', 'border' => '#f8c9c9', 'icon' => '⏸️ Suspended'],
                                ];
                                $b = $badges[$item->status] ?? ['bg' => '#eee', 'fg' => '#444', 'border' => '#ccc', 'icon' => $item->status];
                            @endphp
                            <span class="badge" style="background:{{ $b['bg'] }};color:{{ $b['fg'] }};border:1px solid {{ $b['border'] }};font-size:11px">
                                {{ $b['icon'] }}
                            </span>
                            @if($item->status === 'suspended')
                                <div style="font-size:11px;color:#a82020;margin-top:3px;max-width:180px;line-height:1.3">
                                    <em>Reason: {{ Str::limit($item->suspension_reason ?: 'Renewal pending', 40) }}</em>
                                </div>
                                <div style="margin-top:4px">
                                    <a href="{{ $item->generateSuspensionWhatsAppUrl() }}" target="_blank" style="color:#25d366;font-size:11px;font-weight:700;text-decoration:none" title="Send WhatsApp Notice">
                                        💬 WhatsApp Notice
                                    </a>
                                </div>
                            @elseif($item->activated_at && $item->status === 'active')
                                <small style="display:block;color:var(--admin-muted);font-size:10px;margin-top:2px">
                                    Since {{ $item->activated_at->format('d M Y') }}
                                </small>
                            @endif
                        </td>

                        <td>
                            @if($item->quoted_price)
                                <strong style="font-size:16px;color:#b94e20">
                                    ₹{{ number_format($item->quoted_price, 2) }}
                                </strong>
                                <small style="display:block;color:var(--admin-muted);font-size:11px">Per Year</small>
                            @else
                                <span style="color:#999;font-size:12px;font-style:italic">Not Quoted Yet</span>
                            @endif
                        </td>

                        <td style="text-align:right">
                            <div style="display:flex;gap:6px;justify-content:flex-end;align-items:center;flex-wrap:wrap">
                                {{-- Quick Status Update Form --}}
                                @if($item->status !== 'active')
                                    <form action="{{ route('admin.domains.update-status', $item->id) }}" method="POST" style="display:inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="active">
                                        <button type="submit" class="btn btn-sm btn-primary" style="background:#2e6930;border-color:#2e6930" title="Activate Domain">
                                            ✓ Activate
                                        </button>
                                    </form>
                                @else
                                    <button type="button" class="btn btn-sm btn-outline" style="color:#a82020;border-color:#f8c9c9" title="Suspend Domain with Reason"
                                            data-action="{{ route('admin.domains.update-status', $item->id) }}"
                                            data-item="{{ base64_encode(json_encode($item)) }}"
                                            onclick="openSuspendModalFromBtn(this)">
                                        ⏸️ Suspend
                                    </button>
                                    <a href="{{ $item->generateRenewalReminderWhatsAppUrl() }}" target="_blank" class="btn btn-sm btn-light" style="background:#fef5ea;color:#b94e20;border:1px solid #fedb9b" title="Send WhatsApp Renewal Reminder">
                                        💬 Reminder
                                    </a>
                                @endif

                                {{-- Details & Edit Modal Trigger --}}
                                <button type="button" class="btn btn-sm btn-outline"
                                        data-action="{{ route('admin.domains.update', $item->id) }}"
                                        data-item="{{ base64_encode(json_encode($item)) }}"
                                        onclick="openDomainModalFromBtn(this)">
                                    ⚙️ Edit &amp; Quote
                                </button>

                                <form action="{{ route('admin.domains.delete', $item->id) }}" method="POST" onsubmit="return confirm('Delete this domain mapping?');" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline" style="color:#a82020;border-color:#f8c9c9" title="Delete">
                                        ✕
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center;padding:48px 24px;color:var(--admin-muted)">
                            <div style="font-size:36px;margin-bottom:8px">🌐</div>
                            <strong>No domain inquiries or active websites found.</strong>
                            <p style="margin:4px 0 0;font-size:13px">When Bhudevs request a custom personal domain from their portal, it will appear here.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($domains->hasPages())
        <div style="padding:16px 20px;border-top:1px solid #ede4d5">
            {{ $domains->links() }}
        </div>
    @endif
</div>

{{-- EDIT & QUOTE MODAL --}}
<div id="domainEditModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.6);z-index:9999;align-items:center;justify-content:center;padding:20px">
    <div style="background:#fff;border-radius:12px;max-width:600px;width:100%;box-shadow:0 12px 36px rgba(0,0,0,0.3);overflow:hidden">
        <div style="padding:18px 24px;border-bottom:1px solid #ede4d5;display:flex;align-items:center;justify-content:space-between">
            <h3 style="font-family:'Playfair Display',serif;margin:0;font-size:20px;color:#29241f" id="modalTitle">
                Configure Pandit Domain
            </h3>
            <button type="button" onclick="closeDomainModal()" style="background:none;border:none;font-size:20px;cursor:pointer;color:#888">&times;</button>
        </div>

        <form id="domainEditForm" method="POST" style="padding:24px">
            @csrf
            @method('PUT')

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:14px">
                <div>
                    <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px">Domain Name *</label>
                    <input type="text" name="domain_name" id="modal_domain_name" required style="width:100%;padding:8px 10px;border:1px solid #ccc;border-radius:6px;font-size:13px;box-sizing:border-box">
                </div>
                <div>
                    <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px">Quoted Price (₹) *</label>
                    <input type="number" step="0.01" name="quoted_price" id="modal_quoted_price" placeholder="4999.00" style="width:100%;padding:8px 10px;border:1px solid #ccc;border-radius:6px;font-size:13px;box-sizing:border-box">
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:14px">
                <div>
                    <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px">Status *</label>
                    <select name="status" id="modal_status" style="width:100%;padding:8px 10px;border:1px solid #ccc;border-radius:6px;font-size:13px;box-sizing:border-box">
                        <option value="inquiry_submitted">Inquiry Submitted</option>
                        <option value="quoted">Quoted</option>
                        <option value="paid">Paid</option>
                        <option value="dns_pending">DNS Pending</option>
                        <option value="active">Active (Live)</option>
                        <option value="suspended">Suspended</option>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px">Architecture Tier *</label>
                    <select name="tier" id="modal_tier" style="width:100%;padding:8px 10px;border:1px solid #ccc;border-radius:6px;font-size:13px;box-sizing:border-box">
                        <option value="standard_saas">Standard SaaS (Shared DB)</option>
                        <option value="dedicated_custom">Dedicated Custom DB</option>
                    </select>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:14px">
                <div>
                    <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px">Hero Title / Name</label>
                    <input type="text" name="hero_title" id="modal_hero_title" placeholder="Shastri Paras Rajyaguru" style="width:100%;padding:8px 10px;border:1px solid #ccc;border-radius:6px;font-size:13px;box-sizing:border-box">
                </div>
                <div>
                    <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px">WhatsApp Inquiry Number</label>
                    <input type="text" name="whatsapp_number" id="modal_whatsapp_number" placeholder="9876543210" style="width:100%;padding:8px 10px;border:1px solid #ccc;border-radius:6px;font-size:13px;box-sizing:border-box">
                </div>
            </div>

            <div style="margin-bottom:14px">
                <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px">Hero Tagline</label>
                <input type="text" name="hero_tagline" id="modal_hero_tagline" placeholder="20+ Years Vedic Karamkand & Jyotish in Ahmedabad" style="width:100%;padding:8px 10px;border:1px solid #ccc;border-radius:6px;font-size:13px;box-sizing:border-box">
            </div>

            <div style="margin-bottom:14px">
                <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px">About Pandit Ji / Personal Introduction (Shown on Homepage)</label>
                <textarea name="about_intro" id="modal_about_intro" rows="4" placeholder="Personal introduction shown on Pandit's personal website homepage..." style="width:100%;padding:8px 10px;border:1px solid #ccc;border-radius:6px;font-size:13px;box-sizing:border-box"></textarea>
            </div>

            <div style="margin-bottom:18px">
                <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px">Admin Notes / Agreement Details</label>
                <textarea name="admin_notes" id="modal_admin_notes" rows="3" placeholder="Enter payment reference, custom requirements, or renewal details..." style="width:100%;padding:8px 10px;border:1px solid #ccc;border-radius:6px;font-size:13px;box-sizing:border-box"></textarea>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:10px">
                <button type="button" class="btn btn-outline" onclick="closeDomainModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

{{-- CREATE / ASSIGN DOMAIN MODAL --}}
<div id="createDomainModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.6);z-index:9999;align-items:center;justify-content:center;padding:20px">
    <div style="background:#fff;border-radius:12px;max-width:600px;width:100%;box-shadow:0 12px 36px rgba(0,0,0,0.3);overflow:hidden">
        <div style="padding:18px 24px;border-bottom:1px solid #ede4d5;display:flex;align-items:center;justify-content:space-between">
            <h3 style="font-family:'Playfair Display',serif;margin:0;font-size:20px;color:#29241f">
                ➕ Assign / Create Domain for Bhudev
            </h3>
            <button type="button" onclick="closeCreateDomainModal()" style="background:none;border:none;font-size:20px;cursor:pointer;color:#888">&times;</button>
        </div>

        <form action="{{ route('admin.domains.store') }}" method="POST" style="padding:24px">
            @csrf

            <div style="margin-bottom:14px">
                <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px">Select Bhudev (Pandit Ji) *</label>
                <select name="bhudev_profile_id" required style="width:100%;padding:9px 10px;border:1px solid #ccc;border-radius:6px;font-size:13px;box-sizing:border-box">
                    <option value="">-- Choose Bhudev Profile --</option>
                    @foreach($allBhudevs as $b)
                        <option value="{{ $b->id }}">{{ $b->display_name }} ({{ $b->city ?: 'City unassigned' }} • {{ $b->user?->email }})</option>
                    @endforeach
                </select>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:14px">
                <div>
                    <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px">Domain Name *</label>
                    <input type="text" name="domain_name" required placeholder="e.g. shastriparas.in" style="width:100%;padding:8px 10px;border:1px solid #ccc;border-radius:6px;font-size:13px;box-sizing:border-box">
                </div>
                <div>
                    <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px">Quoted Price (₹)</label>
                    <input type="number" step="0.01" name="quoted_price" placeholder="4999.00" style="width:100%;padding:8px 10px;border:1px solid #ccc;border-radius:6px;font-size:13px;box-sizing:border-box">
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:14px">
                <div>
                    <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px">Initial Status *</label>
                    <select name="status" style="width:100%;padding:8px 10px;border:1px solid #ccc;border-radius:6px;font-size:13px;box-sizing:border-box">
                        <option value="active" selected>Active (Live immediately)</option>
                        <option value="quoted">Quoted</option>
                        <option value="paid">Paid</option>
                        <option value="dns_pending">DNS Pending</option>
                        <option value="inquiry_submitted">Inquiry Submitted</option>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px">Architecture Tier *</label>
                    <select name="tier" style="width:100%;padding:8px 10px;border:1px solid #ccc;border-radius:6px;font-size:13px;box-sizing:border-box">
                        <option value="standard_saas" selected>Standard SaaS (Shared DB)</option>
                        <option value="dedicated_custom">Dedicated Custom DB</option>
                    </select>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:14px">
                <div>
                    <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px">Hero Title / Name</label>
                    <input type="text" name="hero_title" placeholder="Shastri Paras Rajyaguru" style="width:100%;padding:8px 10px;border:1px solid #ccc;border-radius:6px;font-size:13px;box-sizing:border-box">
                </div>
                <div>
                    <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px">WhatsApp Inquiry Number</label>
                    <input type="text" name="whatsapp_number" placeholder="9876543210" style="width:100%;padding:8px 10px;border:1px solid #ccc;border-radius:6px;font-size:13px;box-sizing:border-box">
                </div>
            </div>

            <div style="margin-bottom:14px">
                <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px">Hero Tagline</label>
                <input type="text" name="hero_tagline" placeholder="20+ Years Vedic Karamkand & Jyotish in Ahmedabad" style="width:100%;padding:8px 10px;border:1px solid #ccc;border-radius:6px;font-size:13px;box-sizing:border-box">
            </div>

            <div style="margin-bottom:18px">
                <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px">Admin Notes / Agreement Details</label>
                <textarea name="admin_notes" rows="2" placeholder="Internal notes, payment reference..." style="width:100%;padding:8px 10px;border:1px solid #ccc;border-radius:6px;font-size:13px;box-sizing:border-box"></textarea>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:10px">
                <button type="button" class="btn btn-outline" onclick="closeCreateDomainModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">➕ Assign &amp; Create Domain</button>
            </div>
        </form>
    </div>
</div>

{{-- SUSPEND DOMAIN MODAL WITH REASON & WHATSAPP ACTION --}}
<div id="suspendDomainModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.6);z-index:9999;align-items:center;justify-content:center;padding:20px">
    <div style="background:#fff;border-radius:12px;max-width:540px;width:100%;box-shadow:0 12px 36px rgba(0,0,0,0.3);overflow:hidden">
        <div style="padding:18px 24px;border-bottom:1px solid #ede4d5;display:flex;align-items:center;justify-content:space-between">
            <h3 style="font-family:'Playfair Display',serif;margin:0;font-size:20px;color:#a82020">
                ⏸️ Suspend Domain Website
            </h3>
            <button type="button" onclick="closeSuspendModal()" style="background:none;border:none;font-size:20px;cursor:pointer;color:#888">&times;</button>
        </div>

        <form id="suspendDomainForm" method="POST" style="padding:24px">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="suspended">

            <div style="background:#fdf2f2;border:1px solid #f8c9c9;border-radius:8px;padding:12px 16px;margin-bottom:16px;color:#7a1b1b;font-size:13px">
                Suspending will immediately disable the live website on <strong id="suspendDomainName"></strong> and redirect inquiries.
            </div>

            <div style="margin-bottom:14px">
                <label style="display:block;font-size:12px;font-weight:600;margin-bottom:6px">Select Reason for Suspension *</label>
                <select id="presetSuspensionReason" onchange="onPresetReasonChange(this)" style="width:100%;padding:9px 10px;border:1px solid #ccc;border-radius:6px;font-size:13px;box-sizing:border-box">
                    <option value="Annual domain &amp; hosting renewal fee pending">Annual domain &amp; hosting renewal fee pending</option>
                    <option value="Subscription payment defaulted / unconfirmed">Subscription payment defaulted / unconfirmed</option>
                    <option value="Client requested temporary holiday / pause">Client requested temporary holiday / pause</option>
                    <option value="Violation of platform terms or Maryada">Violation of platform terms or Maryada</option>
                    <option value="custom">Other / Custom reason (type below)</option>
                </select>
            </div>

            <div style="margin-bottom:18px">
                <label style="display:block;font-size:12px;font-weight:600;margin-bottom:6px">Detailed Suspension Note (For Follow-up &amp; Bhudev notice)</label>
                <textarea name="suspension_reason" id="suspend_reason_text" rows="3" required oninput="updateSuspendWhatsAppLink()" style="width:100%;padding:9px 10px;border:1px solid #ccc;border-radius:6px;font-size:13px;box-sizing:border-box">Annual domain &amp; hosting renewal fee pending</textarea>
                <small style="color:#7a6d5f;font-size:11px;display:block;margin-top:4px">This reason will be logged so you can follow up with Pandit Ji anytime.</small>
            </div>

            <div style="background:#fffcf7;border:1px solid #ebd9c4;border-radius:8px;padding:12px 14px;margin-bottom:18px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px">
                <div style="font-size:12px;color:#6d5f53">
                    Want to alert Pandit Ji first?
                </div>
                <a id="suspendWhatsAppLink" href="#" target="_blank" class="btn btn-sm btn-light" style="background:#25d366;color:#fff;border-color:#25d366;font-size:12px;text-decoration:none">
                    💬 Send WhatsApp Notice
                </a>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:10px">
                <button type="button" class="btn btn-outline" onclick="closeSuspendModal()">Cancel</button>
                <button type="submit" class="btn btn-primary" style="background:#a82020;border-color:#a82020">
                    Confirm Suspension
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let currentSuspendItem = null;
    const statusUpdateUrlTemplate = "{{ route('admin.domains.update-status', ':id') }}";
    const domainUpdateUrlTemplate = "{{ route('admin.domains.update', ':id') }}";

    function openSuspendModalFromBtn(btn) {
        const actionUrl = btn.getAttribute('data-action');
        const item = JSON.parse(atob(btn.getAttribute('data-item')));
        currentSuspendItem = item;
        document.getElementById('suspendDomainName').textContent = item.domain_name;
        document.getElementById('suspendDomainForm').action = actionUrl;
        document.getElementById('suspend_reason_text').value = item.suspension_reason || 'Annual domain & hosting renewal fee pending';
        updateSuspendWhatsAppLink();
        document.getElementById('suspendDomainModal').style.display = 'flex';
    }

    function openSuspendModal(item) {
        currentSuspendItem = item;
        document.getElementById('suspendDomainName').textContent = item.domain_name;
        document.getElementById('suspendDomainForm').action = statusUpdateUrlTemplate.replace(':id', item.id);
        document.getElementById('suspend_reason_text').value = item.suspension_reason || 'Annual domain & hosting renewal fee pending';
        updateSuspendWhatsAppLink();
        document.getElementById('suspendDomainModal').style.display = 'flex';
    }

    function closeSuspendModal() {
        document.getElementById('suspendDomainModal').style.display = 'none';
    }

    function onPresetReasonChange(select) {
        if (select.value !== 'custom') {
            document.getElementById('suspend_reason_text').value = select.value;
        } else {
            document.getElementById('suspend_reason_text').value = '';
            document.getElementById('suspend_reason_text').focus();
        }
        updateSuspendWhatsAppLink();
    }

    function updateSuspendWhatsAppLink() {
        if (!currentSuspendItem) return;
        const reason = encodeURIComponent(document.getElementById('suspend_reason_text').value || 'Annual renewal pending');
        const rawPhone = currentSuspendItem.whatsapp_number || (currentSuspendItem.profile && currentSuspendItem.profile.user ? currentSuspendItem.profile.user.phone : '919876543210');
        const phone = rawPhone ? rawPhone.replace(/\D/g, '') : '919876543210';
        const name = currentSuspendItem.hero_title || (currentSuspendItem.profile ? currentSuspendItem.profile.display_name : 'Shastri Ji');
        const msg = encodeURIComponent("Namaste " + name + ", this is an important notice regarding your personal website (" + currentSuspendItem.domain_name + ").\n\n⚠️ Status: Temporarily Suspended\n📌 Reason: " + decodeURIComponent(reason) + "\n\nPlease contact us to complete your renewal and restore your live website.\n\nDigitalBhudev Support Team");
        document.getElementById('suspendWhatsAppLink').href = 'https://wa.me/' + (phone.length === 10 ? '91' + phone : phone) + '?text=' + msg;
    }

    function openCreateDomainModal() {
        document.getElementById('createDomainModal').style.display = 'flex';
    }

    function closeCreateDomainModal() {
        document.getElementById('createDomainModal').style.display = 'none';
    }

    function openDomainModalFromBtn(btn) {
        const actionUrl = btn.getAttribute('data-action');
        const item = JSON.parse(atob(btn.getAttribute('data-item')));
        document.getElementById('modalTitle').textContent = 'Configure Domain: ' + item.domain_name;
        document.getElementById('domainEditForm').action = actionUrl;
        document.getElementById('modal_domain_name').value = item.domain_name || '';
        document.getElementById('modal_quoted_price').value = item.quoted_price || '';
        document.getElementById('modal_status').value = item.status || 'inquiry_submitted';
        document.getElementById('modal_tier').value = item.tier || 'standard_saas';
        document.getElementById('modal_hero_title').value = item.hero_title || '';
        document.getElementById('modal_whatsapp_number').value = item.whatsapp_number || '';
        document.getElementById('modal_hero_tagline').value = item.hero_tagline || '';
        document.getElementById('modal_about_intro').value = item.about_intro || '';
        document.getElementById('modal_admin_notes').value = item.admin_notes || '';
        document.getElementById('domainEditModal').style.display = 'flex';
    }

    function openDomainModal(item) {
        document.getElementById('modalTitle').textContent = 'Configure Domain: ' + item.domain_name;
        document.getElementById('domainEditForm').action = domainUpdateUrlTemplate.replace(':id', item.id);
        document.getElementById('modal_domain_name').value = item.domain_name || '';
        document.getElementById('modal_quoted_price').value = item.quoted_price || '';
        document.getElementById('modal_status').value = item.status || 'inquiry_submitted';
        document.getElementById('modal_tier').value = item.tier || 'standard_saas';
        document.getElementById('modal_hero_title').value = item.hero_title || '';
        document.getElementById('modal_whatsapp_number').value = item.whatsapp_number || '';
        document.getElementById('modal_hero_tagline').value = item.hero_tagline || '';
        document.getElementById('modal_about_intro').value = item.about_intro || '';
        document.getElementById('modal_admin_notes').value = item.admin_notes || '';
        document.getElementById('domainEditModal').style.display = 'flex';
    }

    function closeDomainModal() {
        document.getElementById('domainEditModal').style.display = 'none';
    }
</script>
@endpush
@endsection
