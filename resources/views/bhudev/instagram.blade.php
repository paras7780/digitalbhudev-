@extends('layouts.bhudev')

@section('title', 'Instagram & Reels Integration | Bhudev Portal')

@section('breadcrumbs')
    <span class="sep">/</span>
    <strong>Instagram & Reels</strong>
@endsection

@section('content')
<div class="admin-page-header">
    <div class="admin-page-title">
        <h1>📸 Instagram & Reels Integration</h1>
        <p>Connect your Instagram posts and Reels to showcase your ceremonies and pooja recordings directly on your personal website.</p>
    </div>
    <div class="admin-page-actions">
        @if($eligibility['has_active_website'])
            <a href="http://{{ $eligibility['domain_name'] }}" target="_blank" class="btn btn-outline">
                🌐 View My Live Website ↗
            </a>
        @endif
        <a href="{{ route('bhudev.website') }}" class="btn btn-light">
            ⚙ Website Settings
        </a>
    </div>
</div>

{{-- Strict Specification Notice Banner --}}
<div style="background:#fff9eb;border:1px solid #fcd34d;border-left:5px solid #d97706;border-radius:10px;padding:16px 20px;margin-bottom:24px;box-shadow:0 2px 8px rgba(0,0,0,0.02)">
    <div style="display:flex;align-items:flex-start;gap:14px">
        <span style="font-size:24px;line-height:1">📢</span>
        <div>
            <strong style="color:#92400e;font-size:14.5px;display:block;margin-bottom:4px">
                Instagram Integration Policy
            </strong>
            <p style="margin:0;color:#78350f;font-size:13.5px;line-height:1.5">
                {{ $eligibility['notice_text'] }}
            </p>
        </div>
    </div>
</div>

{{-- Domain Eligibility Status Card --}}
@if($eligibility['has_active_website'])
    <div class="admin-card" style="margin-bottom:24px;border-left:4px solid #10b981">
        <div class="admin-card-header" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px">
            <div>
                <h3 style="margin:0;display:flex;align-items:center;gap:8px;font-size:16px;color:var(--admin-heading)">
                    <span>✓ Personal Website Active & Eligible</span>
                    <span class="badge" style="background:#ecfdf5;color:#059669;border:1px solid #a7f3d0">LIVE</span>
                </h3>
                <p style="margin:4px 0 0;font-size:13px;color:var(--admin-muted)">
                    Your personal domain is <strong style="font-family:monospace;color:var(--admin-heading)">{{ $eligibility['domain_name'] }}</strong>. Added Instagram posts and Reels are displayed here.
                </p>
            </div>
            <div>
                <form method="POST" action="{{ route('bhudev.instagram.toggle') }}" style="display:flex;align-items:center;gap:10px;margin:0">
                    @csrf
                    <input type="hidden" name="is_enabled" value="{{ $settings->is_enabled ? 0 : 1 }}">
                    <span style="font-size:13px;font-weight:600;color:{{ $settings->is_enabled ? '#059669' : '#64748b' }}">
                        {{ $settings->is_enabled ? 'Reels Display is ON' : 'Reels Display is OFF' }}
                    </span>
                    <button type="submit" class="btn {{ $settings->is_enabled ? 'btn-light' : 'btn-primary' }} btn-sm">
                        {{ $settings->is_enabled ? 'Pause Display' : 'Enable Display' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
@else
    <div class="admin-card" style="margin-bottom:24px;border-left:4px solid #f59e0b;background:#fffdfa">
        <div class="admin-card-header">
            <h3 style="margin:0;display:flex;align-items:center;gap:8px;font-size:16px;color:#92400e">
                <span>⏳ Active Website Required to Display Instagram Content</span>
            </h3>
            <p style="margin:6px 0 0;font-size:13.5px;color:#78350f;line-height:1.5">
                You currently do not have an active personal website on DigitalBhudev. You can still paste and organize your Instagram Reel and Post links below. Once your personal domain is activated, your reels will automatically show up beautifully.
            </p>
        </div>
        <div class="admin-card-body" style="background:#fff;border-radius:8px;padding:16px;margin-top:12px;border:1px solid #fed7aa">
            <h4 style="margin:0 0 10px;font-size:14px;color:var(--admin-heading)">Ready to get your own branded Pandit website?</h4>
            <div style="display:flex;gap:12px;flex-wrap:wrap;align-items:center">
                @php
                    $waText = rawurlencode(\App\Services\InstagramIntegrationService::WHATSAPP_INQUIRY_TEXT);
                    $waPhone = \App\Services\InstagramIntegrationService::DEFAULT_SUPPORT_PHONE;
                @endphp
                <a href="https://wa.me/{{ $waPhone }}?text={{ $waText }}" target="_blank" class="btn btn-sm" style="background:#25D366;color:#fff;display:inline-flex;align-items:center;gap:6px">
                    <span>💬 Contact via WhatsApp</span>
                </a>
                <span style="font-size:13px;color:var(--admin-muted)">or request your domain directly:</span>
            </div>
            <form method="POST" action="{{ route('bhudev.instagram.request-website') }}" style="margin-top:14px;display:flex;gap:10px;flex-wrap:wrap">
                @csrf
                <input type="text" name="requested_domain" placeholder="e.g. {{ \Illuminate\Support\Str::slug($profile->display_name) }}.digitalbhudev.in" style="flex:1;min-width:240px;padding:8px 12px;border:1px solid var(--admin-border);border-radius:6px;font-size:13px">
                <input type="text" name="whatsapp_number" value="{{ auth()->user()->phone }}" placeholder="WhatsApp Number" style="width:160px;padding:8px 12px;border:1px solid var(--admin-border);border-radius:6px;font-size:13px">
                <button type="submit" class="btn btn-primary btn-sm">Request Website</button>
            </form>
        </div>
    </div>
@endif

<div class="bhudev-two-col" style="display:grid;grid-template-columns:1.2fr 1fr;gap:24px;align-items:start">
    {{-- Left Column: Add Reels & Connected Gallery --}}
    <div style="display:flex;flex-direction:column;gap:24px">
        {{-- Add Reel / Post Card --}}
        <div class="admin-card">
            <div class="admin-card-header">
                <h3 style="margin:0;font-size:16px;color:var(--admin-heading)">➕ Add Instagram Post or Reel</h3>
                <p style="margin:4px 0 0;font-size:12.5px;color:var(--admin-muted)">
                    Copy the URL of any public post or Reel from Instagram and paste it below.
                </p>
            </div>
            <div class="admin-card-body" style="padding:20px">
                <form method="POST" action="{{ route('bhudev.instagram.urls.store') }}">
                    @csrf
                    <div style="margin-bottom:14px">
                        <label style="display:block;font-size:12.5px;font-weight:600;margin-bottom:6px;color:var(--admin-heading)">
                            Instagram Post or Reel URL *
                        </label>
                        <input type="url" name="url" required value="{{ old('url') }}" placeholder="https://www.instagram.com/reel/Cxxxxxx/ or https://www.instagram.com/p/Cxxxxxx/" style="width:100%;padding:10px 14px;border:1.5px solid var(--admin-border);border-radius:8px;font-size:13.5px;font-family:monospace">
                        <div style="font-size:11.5px;color:var(--admin-muted);margin-top:4px">
                            Supported: standard posts (<code>/p/ID</code>) and reels (<code>/reel/ID</code>). Ensure the post is public on Instagram.
                        </div>
                    </div>

                    <div style="margin-bottom:16px">
                        <label style="display:block;font-size:12.5px;font-weight:600;margin-bottom:6px;color:var(--admin-heading)">
                            Caption / Ceremony Title (Optional)
                        </label>
                        <input type="text" name="caption" value="{{ old('caption') }}" placeholder="e.g. Satyanarayan Katha Live, Navchandi Yagya Highlights" style="width:100%;padding:9px 13px;border:1.5px solid var(--admin-border);border-radius:8px;font-size:13px">
                    </div>

                    <button type="submit" class="btn btn-primary" style="width:100%">
                        📸 Add to My Digital Presence
                    </button>
                </form>
            </div>
        </div>

        {{-- Connected Reels / Posts List --}}
        <div class="admin-card">
            <div class="admin-card-header" style="display:flex;justify-content:space-between;align-items:center">
                <h3 style="margin:0;font-size:16px;color:var(--admin-heading)">
                    Connected Content ({{ $posts->count() }})
                </h3>
            </div>
            <div class="admin-card-body" style="padding:0">
                @if($posts->isEmpty())
                    <div style="padding:32px 20px;text-align:center;color:var(--admin-muted)">
                        <div style="font-size:36px;margin-bottom:8px">📸</div>
                        <strong style="color:var(--admin-heading);display:block;margin-bottom:4px">No Instagram content connected yet</strong>
                        <p style="font-size:13px;margin:0">Add your first ceremony post or Reel URL above to feature it on your personal website.</p>
                    </div>
                @else
                    <div style="display:flex;flex-direction:column;divide-y:1px solid var(--admin-border)">
                        @foreach($posts as $post)
                            <div style="padding:16px 20px;display:flex;justify-content:space-between;align-items:center;gap:12px;border-bottom:1px solid var(--admin-border)">
                                <div style="display:flex;align-items:center;gap:14px;min-width:0">
                                    <div style="width:42px;height:42px;border-radius:8px;background:linear-gradient(135deg, #fce7f3, #fbcfe8);color:var(--primary);display:grid;place-items:center;font-size:20px;flex-shrink:0">
                                        {{ $post->media_type === 'reel' ? '🎬' : '📷' }}
                                    </div>
                                    <div style="min-width:0">
                                        <div style="display:flex;align-items:center;gap:8px">
                                            <span class="badge" style="background:#fdf2f8;color:#be185d;border:1px solid #fbcfe8;font-size:10.5px;text-transform:uppercase">
                                                {{ $post->media_type }}
                                            </span>
                                            <strong style="font-size:13px;color:var(--admin-heading);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                                                {{ $post->caption ?: 'Shortcode: ' . $post->shortcode }}
                                            </strong>
                                        </div>
                                        <div style="margin-top:4px;font-size:12px">
                                            <a href="{{ $post->url }}" target="_blank" style="color:var(--primary);text-decoration:none;font-family:monospace">
                                                {{ Str::limit($post->url, 45) }} ↗
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <form method="POST" action="{{ route('bhudev.instagram.urls.destroy', $post) }}" onsubmit="return confirm('Are you sure you want to remove this Instagram item?');" style="margin:0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-light btn-sm" style="color:#dc2626;padding:6px 10px" title="Delete">
                                            ✕ Remove
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Right Column: Guidance, Preview & Official Meta API --}}
    <div style="display:flex;flex-direction:column;gap:24px">
        {{-- How it works guidance card --}}
        <div class="admin-card" style="background:#faf6f9">
            <div class="admin-card-header">
                <h3 style="margin:0;font-size:15px;color:var(--admin-heading)">💡 How Instagram Display Works</h3>
            </div>
            <div class="admin-card-body" style="padding:16px 20px;font-size:13px;line-height:1.6;color:#4b3d48">
                <ol style="margin:0;padding-left:18px;display:flex;flex-direction:column;gap:8px">
                    <li><strong>Paste URL:</strong> Copy the link of your ceremony video or post from Instagram app or web.</li>
                    <li><strong>Automatic Embedding:</strong> Our engine automatically extracts the safe shortcode and generates a responsive Instagram viewer.</li>
                    <li><strong>Domain Showcase:</strong> Devotees visiting your personal website (e.g. <code>{{ $profile->slug }}.digitalbhudev.in</code>) see your active Reels, building strong faith and trust.</li>
                </ol>
            </div>
        </div>

        {{-- Meta Graph API Connection (Optional) --}}
        <div class="admin-card">
            <div class="admin-card-header">
                <h3 style="margin:0;font-size:15px;color:var(--admin-heading)">🔗 Meta Instagram API (Optional)</h3>
                <p style="margin:4px 0 0;font-size:12px;color:var(--admin-muted)">
                    If you have a Meta Developer access token, you can connect your account directly.
                </p>
            </div>
            <div class="admin-card-body" style="padding:18px 20px">
                @if($settings->connection_status === 'connected')
                    <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:12px 16px;margin-bottom:14px">
                        <div style="display:flex;align-items:center;justify-content:space-between">
                            <div>
                                <span style="font-size:11px;font-weight:700;color:#16a34a;text-transform:uppercase">Connected Account</span>
                                <strong style="display:block;font-size:14px;color:#166534">&#64;{{ $settings->account_username ?: 'instagram_user' }}</strong>
                            </div>
                            <form method="POST" action="{{ route('bhudev.instagram.disconnect') }}" style="margin:0">
                                @csrf
                                <button type="submit" class="btn btn-light btn-sm" style="color:#dc2626">Disconnect</button>
                            </form>
                        </div>
                    </div>
                @else
                    <form method="POST" action="{{ route('bhudev.instagram.connect-api') }}">
                        @csrf
                        <div style="margin-bottom:12px">
                            <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;color:var(--admin-heading)">Instagram User Access Token</label>
                            <input type="text" name="access_token" required placeholder="IGQV..." style="width:100%;padding:8px 12px;border:1.5px solid var(--admin-border);border-radius:6px;font-size:12.5px;font-family:monospace">
                        </div>
                        <div style="margin-bottom:14px;display:grid;grid-template-columns:1fr 1fr;gap:10px">
                            <div>
                                <label style="display:block;font-size:11.5px;font-weight:600;margin-bottom:4px;color:var(--admin-heading)">App ID (optional)</label>
                                <input type="text" name="app_id" placeholder="12345..." style="width:100%;padding:8px 10px;border:1.5px solid var(--admin-border);border-radius:6px;font-size:12px">
                            </div>
                            <div>
                                <label style="display:block;font-size:11.5px;font-weight:600;margin-bottom:4px;color:var(--admin-heading)">App Secret (optional)</label>
                                <input type="password" name="app_secret" placeholder="••••••••" style="width:100%;padding:8px 10px;border:1.5px solid var(--admin-border);border-radius:6px;font-size:12px">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm" style="width:100%">Connect API</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
