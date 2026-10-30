@extends('layouts.admin')

@section('title', $profile->display_name . ' | Bhudev Profile | DigitalBhudev Admin')

@section('breadcrumbs')
    <span class="sep">/</span>
    <a href="{{ route('admin.bhudevs') }}">Bhudev Directory</a>
    <span class="sep">/</span>
    <strong>{{ $profile->display_name }}</strong>
@endsection

@section('content')
<!-- Header Banner with Avatar, Badges & Action Toolbar -->
<div class="profile-detail-header">
    <div class="profile-detail-main">
        @if($profile->photo_path)
            <img src="{{ asset('storage/' . $profile->photo_path) }}" alt="{{ $profile->display_name }}" class="profile-big-avatar">
        @else
            <div class="profile-big-avatar">ॐ</div>
        @endif
        <div class="profile-title-block">
            <h1>{{ $profile->display_name }}</h1>
            <div style="font-size:13px;color:var(--admin-muted)">
                <span>{{ collect([$profile->city, $profile->state])->filter()->join(', ') ?: 'Location not set' }}</span>
                <span style="margin:0 6px">·</span>
                <span>Member since {{ $profile->created_at->format('d M Y') }}</span>
                <span style="margin:0 6px">·</span>
                <code>/bhudevs/{{ $profile->slug }}</code>
            </div>
            <div class="profile-meta-pills">
                @if($profile->status === 'approved')
                    <span class="badge badge-approved">✓ Approved</span>
                @elseif($profile->status === 'pending')
                    <span class="badge badge-pending">⏳ Pending Review</span>
                @elseif($profile->status === 'rejected')
                    <span class="badge badge-rejected">✕ Rejected</span>
                @elseif($profile->status === 'suspended')
                    <span class="badge badge-suspended">⏸ Suspended</span>
                @endif

                @if($profile->status === 'approved')
                    @if($profile->is_visible)
                        <span class="badge badge-visible">🌐 Publicly Visible</span>
                    @else
                        <span class="badge badge-hidden">🔒 Hidden from Public</span>
                    @endif
                @else
                    <span class="badge badge-hidden">🔒 Direct URL Inaccessible</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Action Toolbar -->
    <div class="action-group">
        <a href="{{ route('admin.bhudevs.edit', $profile) }}" class="btn-action btn-action-primary" style="padding:9px 14px">
            ✎ Edit Profile
        </a>
        <a href="{{ route('admin.instagram.show', $profile) }}" class="btn-action" style="background:#fce8f3;border-color:#f9a8d4;color:#be185d;padding:9px 14px;text-decoration:none;font-weight:600">
            📸 Instagram Settings
        </a>

        @if($profile->status !== 'approved')
            <form method="POST" action="{{ route('admin.bhudevs.approve', $profile) }}" style="display:inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn-action btn-action-success" style="padding:9px 14px">
                    ✓ Approve Profile
                </button>
            </form>
        @endif

        @if($profile->status === 'pending' || $profile->status === 'approved')
            <button type="button" class="btn-action btn-action-danger" data-action="reject" data-url="{{ route('admin.bhudevs.reject', $profile) }}" data-name="{{ $profile->display_name }}" style="padding:9px 14px">
                Reject Submission
            </button>
        @endif

        @if($profile->status === 'approved')
            <form method="POST" action="{{ route('admin.bhudevs.toggle-visibility', $profile) }}" style="display:inline">
                @csrf
                @method('PATCH')
                @if($profile->is_visible)
                    <button type="submit" class="btn-action btn-action-warning" style="padding:9px 14px">
                        Hide Profile
                    </button>
                @else
                    <button type="submit" class="btn-action btn-action-success" style="padding:9px 14px">
                        Show Profile
                    </button>
                @endif
            </form>

            <button type="button" class="btn-action" data-action="suspend" data-url="{{ route('admin.bhudevs.suspend', $profile) }}" data-name="{{ $profile->display_name }}" style="padding:9px 14px">
                Suspend Account
            </button>

            @if($profile->is_visible)
                <a href="{{ route('bhudevs.show', $profile->slug) }}" target="_blank" class="btn-action" style="padding:9px 14px">
                    Public View ↗
                </a>
            @endif
        @elseif($profile->status === 'suspended')
            <form method="POST" action="{{ route('admin.bhudevs.reactivate', $profile) }}" style="display:inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn-action btn-action-success" style="padding:9px 14px">
                    Reactivate Account
                </button>
            </form>
        @endif

        <button type="button" class="btn-action btn-action-danger" data-action="delete" data-url="{{ route('admin.bhudevs.delete', $profile) }}" data-name="{{ $profile->display_name }}" style="padding:9px 14px" title="Archive account">
            🗑 Delete
        </button>
    </div>
</div>

@if($profile->status === 'rejected' && $profile->rejection_reason)
    <div style="background:#fdeeee;border:1px solid #f8c9c9;border-radius:var(--admin-radius);padding:16px 20px;margin-bottom:24px;color:#c02b2b">
        <strong style="display:block;margin-bottom:4px">Rejection Notice / Feedback:</strong>
        <p style="margin:0;font-size:13px">{{ $profile->rejection_reason }}</p>
    </div>
@endif

<div class="detail-grid">
    <!-- Left Column: Profile Attributes & Details -->
    <div>
        <!-- Profile Attributes Card -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h3>Profile Overview</h3>
                <a href="{{ route('admin.bhudevs.edit', $profile) }}" class="btn-action">Edit Details</a>
            </div>
            <div class="admin-card-body">
                <dl class="detail-data-list">
                    <dt>Full Display Name</dt>
                    <dd><strong>{{ $profile->display_name }}</strong></dd>

                    <dt>Account Email</dt>
                    <dd>
                        <a href="mailto:{{ $profile->user->email }}">{{ $profile->user->email }}</a>
                        @if($profile->show_email)
                            <span class="badge badge-visible" style="margin-left:6px;font-size:10px">Public</span>
                        @else
                            <span class="badge badge-hidden" style="margin-left:6px;font-size:10px">Private</span>
                        @endif
                    </dd>

                    <dt>Mobile Number</dt>
                    <dd>
                        <span>{{ $profile->user->phone ?: 'Not provided' }}</span>
                        @if($profile->user->phone)
                            @if($profile->show_mobile)
                                <span class="badge badge-visible" style="margin-left:6px;font-size:10px">Public</span>
                            @else
                                <span class="badge badge-hidden" style="margin-left:6px;font-size:10px">Private</span>
                            @endif
                        @endif
                    </dd>

                    <dt>City & State</dt>
                    <dd>{{ collect([$profile->city, $profile->state])->filter()->join(', ') ?: '—' }}</dd>

                    <dt>Full Address</dt>
                    <dd>
                        <span>{{ $profile->address ?: 'Not provided' }}</span>
                        @if($profile->address)
                            @if($profile->show_address)
                                <span class="badge badge-visible" style="margin-left:6px;font-size:10px">Public</span>
                            @else
                                <span class="badge badge-hidden" style="margin-left:6px;font-size:10px">Private</span>
                            @endif
                        @endif
                    </dd>

                    <dt>Service Area</dt>
                    <dd>{{ $profile->service_area ?: 'Not specified (Local/Regional)' }}</dd>

                    <dt>Availability</dt>
                    <dd>
                        @if($profile->availability)
                            <span style="background:#fcf2e3;color:#854d19;padding:4px 8px;border-radius:4px;font-weight:600">{{ $profile->availability }}</span>
                        @else
                            <span style="color:var(--admin-muted)">Not specified</span>
                        @endif
                    </dd>

                    <dt>Experience</dt>
                    <dd>{{ $profile->experience_years !== null ? $profile->experience_years . ' years' : 'Not specified' }}</dd>

                    <dt>Languages</dt>
                    <dd>
                        @if(!empty($profile->languages))
                            {{ implode(', ', $profile->languages) }}
                        @else
                            <span style="color:var(--admin-muted)">Not specified</span>
                        @endif
                    </dd>

                    <dt>Expertise</dt>
                    <dd>{{ $profile->expertise ?: '—' }}</dd>

                    <dt>Qualifications</dt>
                    <dd>{{ $profile->qualification ?: '—' }}</dd>

                    <dt>Biography</dt>
                    <dd style="line-height:1.7;white-space:pre-wrap">{{ $profile->bio ?: 'No biography written yet.' }}</dd>
                </dl>
            </div>
        </div>

        <!-- Pooja Services Card -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h3>Assigned Pooja & Vedic Services</h3>
                <span style="font-size:12px;color:var(--admin-muted)">{{ $profile->services->count() }} services active</span>
            </div>
            <div class="admin-card-body">
                @if($profile->services->isNotEmpty())
                    <div style="display:flex;gap:8px;flex-wrap:wrap">
                        @foreach($profile->services as $service)
                            <span class="tag" style="padding:6px 12px;font-size:12px;font-weight:600;background:#fbf3e6;border:1px solid #ebd9c2">
                                🕉 {{ $service->name }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <p style="margin:0;color:var(--admin-muted)">No specific services tagged yet.</p>
                @endif
            </div>
        </div>

        <!-- Image Gallery Card -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h3>Photo Gallery</h3>
                <span style="font-size:12px;color:var(--admin-muted)">{{ $profile->images->count() }} uploaded</span>
            </div>
            <div class="admin-card-body">
                @if($profile->images->isNotEmpty())
                    <div class="gallery-thumbs-grid">
                        @foreach($profile->images as $img)
                            <div class="gallery-thumb-item">
                                <img src="{{ asset('storage/'.$img->path) }}" alt="{{ $img->alt_text }}" loading="lazy">
                                @if(!$img->is_approved)
                                    <div style="position:absolute;bottom:0;left:0;right:0;background:rgba(178,106,0,0.85);color:#fff;font-size:10px;text-align:center;padding:2px">Pending Approval</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p style="margin:0;color:var(--admin-muted)">No gallery images uploaded.</p>
                @endif
            </div>
        </div>

        <!-- SEO Metadata Card -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h3>SEO & Search Optimization</h3>
            </div>
            <div class="admin-card-body">
                <dl class="detail-data-list">
                    <dt>SEO Title</dt>
                    <dd>{{ $profile->seo_title ?: $profile->display_name . ' | DigitalBhudev' }}</dd>

                    <dt>SEO Description</dt>
                    <dd>{{ $profile->seo_description ?: Str::limit(strip_tags($profile->bio ?: 'View profile on DigitalBhudev.'), 150) }}</dd>

                    <dt>Profile Slug</dt>
                    <dd><code>{{ $profile->slug }}</code></dd>
                </dl>
            </div>
        </div>
    </div>

    <!-- Right Column: User Account Info, Review Timeline, Audit Log -->
    <div>
        <!-- Account Info Card -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h3>Account Credentials</h3>
            </div>
            <div class="admin-card-body">
                <div style="margin-bottom:12px">
                    <span style="font-size:11px;font-weight:700;color:var(--admin-muted);text-transform:uppercase">User Account ID</span>
                    <div style="font-weight:600">#{{ $profile->user_id }}</div>
                </div>
                <div style="margin-bottom:12px">
                    <span style="font-size:11px;font-weight:700;color:var(--admin-muted);text-transform:uppercase">Email Status</span>
                    <div>
                        @if($profile->user->email_verified_at)
                            <span class="badge badge-approved" style="font-size:10px">✓ Verified ({{ $profile->user->email_verified_at->format('d M Y') }})</span>
                        @else
                            <span class="badge badge-pending" style="font-size:10px">⏳ Unverified</span>
                        @endif
                    </div>
                </div>
                <div style="margin-bottom:12px">
                    <span style="font-size:11px;font-weight:700;color:var(--admin-muted);text-transform:uppercase">Account State</span>
                    <div>
                        @if($profile->user->is_active)
                            <span style="color:var(--admin-green);font-weight:600">● Active</span>
                        @else
                            <span style="color:var(--admin-red);font-weight:600">● Deactivated</span>
                        @endif
                    </div>
                </div>
                <div>
                    <span style="font-size:11px;font-weight:700;color:var(--admin-muted);text-transform:uppercase">Registered At</span>
                    <div style="font-size:12px;color:var(--admin-muted)">{{ $profile->user->created_at->format('d M Y, H:i') }}</div>
                </div>
            </div>
        </div>

        <!-- Status & Review History Timeline -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h3>Status History</h3>
            </div>
            <div class="admin-card-body">
                <div class="timeline">
                    @forelse($profile->statusHistory as $hist)
                        <div class="timeline-item">
                            <div class="timeline-dot" style="@if($hist->to_status==='approved') background:var(--admin-green); @elseif($hist->to_status==='rejected') background:var(--admin-red); @endif"></div>
                            <div class="timeline-content">
                                <div class="timeline-time">{{ $hist->created_at->format('d M Y, H:i') }}</div>
                                <strong>
                                    {{ ucfirst($hist->from_status ?: 'Creation') }} → {{ ucfirst($hist->to_status) }}
                                </strong>
                                @if($hist->reason)
                                    <div style="color:var(--admin-muted);margin-top:2px">
                                        "{{ $hist->reason }}"
                                    </div>
                                @endif
                                <div style="font-size:10px;color:#8f7d6e;margin-top:2px">
                                    by {{ $hist->changedBy?->name ?: 'System' }} · {{ $hist->is_visible ? 'Public' : 'Hidden' }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <p style="margin:0;color:var(--admin-muted);font-size:12px">No status events recorded yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Audit Trail Card -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h3>Admin Actions Trail</h3>
            </div>
            <div class="admin-card-body">
                <div class="timeline">
                    @forelse($auditLogs as $log)
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <div class="timeline-time">{{ $log->created_at->diffForHumans() }}</div>
                                <strong>{{ str_replace(['admin.', '.'], [' ', ' '], ucwords($log->action, '.')) }}</strong>
                                <div style="font-size:11px;color:var(--admin-muted)">
                                    by {{ $log->user?->name ?: 'System' }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <p style="margin:0;color:var(--admin-muted);font-size:12px">No audit events recorded.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
