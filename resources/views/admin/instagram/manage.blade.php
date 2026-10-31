@extends('layouts.admin')

@section('title', 'Manage Instagram: ' . $profile->display_name . ' | DigitalBhudev Admin')

@section('breadcrumbs')
    <span class="sep">/</span>
    <a href="{{ route('admin.instagram.index') }}">Instagram Integration</a>
    <span class="sep">/</span>
    <strong>{{ $profile->display_name }}</strong>
@endsection

@section('content')
{{-- Profile Banner Header --}}
<div style="background:var(--admin-card-bg);border:1px solid var(--admin-card-border);border-radius:var(--admin-radius-lg);padding:22px;margin-bottom:24px;box-shadow:var(--admin-shadow)">
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px">
        <div style="display:flex;align-items:center;gap:16px">
            @if($profile->photo_path)
                <img src="{{ asset('storage/' . $profile->photo_path) }}" alt="{{ $profile->display_name }}" style="width:54px;height:54px;border-radius:50%;object-fit:cover;border:2px solid var(--admin-card-border)">
            @else
                <div style="width:54px;height:54px;border-radius:50%;background:var(--admin-sidebar-active);color:var(--color-primary);display:flex;align-items:center;justify-content:center;font-size:24px;font-weight:700">ॐ</div>
            @endif
            <div>
                <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
                    <h1 style="font-size:22px;color:var(--admin-heading);margin:0;font-family:'Playfair Display',serif">
                        {{ $profile->display_name }}
                    </h1>
                    @if($eligibility['has_active_website'])
                        <span style="background:var(--accent-green-soft);color:var(--accent-green);font-size:11px;font-weight:700;padding:3px 10px;border-radius:999px;border:1px solid var(--border-color)">
                            ✓ Active Website (Eligible)
                        </span>
                    @else
                        <span style="background:var(--accent-amber-soft);color:var(--accent-amber);font-size:11px;font-weight:700;padding:3px 10px;border-radius:999px;border:1px solid var(--border-color)">
                            ⏳ No Active Website (Ineligible for Display)
                        </span>
                    @endif
                </div>
                <div style="font-size:13px;color:var(--admin-muted);margin-top:4px">
                    {{ $profile->city ? $profile->city . ', ' . ($profile->state ?? 'Gujarat') : 'Location unset' }}
                    @if($eligibility['domain_name'])
                        • Personal Domain: <strong style="font-family:monospace">{{ $eligibility['domain_name'] }}</strong>
                        @if($eligibility['has_active_website'])
                            (<a href="http://{{ $eligibility['domain_name'] }}" target="_blank" style="color:var(--accent-green);text-decoration:none">View Live ↗</a>)
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <div style="display:flex;gap:10px;align-items:center">
            <a href="{{ route('admin.bhudevs.show', $profile) }}" class="btn-action" style="padding:8px 14px;background:#fff;border:1px solid var(--admin-card-border);color:var(--admin-heading);text-decoration:none">
                ← View Profile
            </a>
            @if($eligibility['has_active_website'])
                <a href="http://{{ $eligibility['domain_name'] }}" target="_blank" class="btn-action btn-action-primary" style="padding:8px 16px;text-decoration:none">
                    🌐 Open Pandit Website
                </a>
            @endif
        </div>
    </div>
</div>

{{-- Main Grid --}}
<div style="display:grid;grid-template-columns:1.5fr 1fr;gap:24px;align-items:start">
    {{-- Left Column: Settings, Post URLs, Embeds --}}
    <div style="display:flex;flex-direction:column;gap:24px">

        {{-- Integration Status Card & Toggle --}}
        <div style="background:#fff;border:1px solid #ebd9c4;border-radius:14px;padding:22px;box-shadow:0 2px 8px rgba(0,0,0,0.02)">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;flex-wrap:wrap;gap:12px">
                <div>
                    <h2 style="font-size:17px;color:var(--admin-dark);margin:0 0 4px">
                        📸 Instagram Publication Controls
                    </h2>
                    <div style="font-size:13px;color:var(--admin-muted)">
                        Control whether validated Instagram content is rendered on this Pandit's website.
                    </div>
                </div>

                <div>
                    @if($settings->is_enabled)
                        <span style="background:#e8f7ee;color:#1e7e34;font-size:12px;font-weight:700;padding:4px 12px;border-radius:999px;border:1px solid #c3e6cb">
                            ● Display Active
                        </span>
                    @else
                        <span style="background:#f3f4f6;color:#6b7280;font-size:12px;font-weight:600;padding:4px 12px;border-radius:999px">
                            ○ Display Disabled
                        </span>
                    @endif
                </div>
            </div>

            {{-- Exact subtle notice requirement --}}
            <div style="background:#faf7f2;border:1px solid #ebd9c4;border-left:3px solid #b94e20;border-radius:8px;padding:11px 14px;margin-bottom:18px">
                <p style="font-size:12.5px;color:#6e5f52;line-height:1.5;margin:0">
                    {{ $eligibility['notice_text'] }}
                </p>

                {{-- If no active website, provide the 2 clear options --}}
                @if(!$eligibility['has_active_website'])
                    <div style="margin-top:10px;padding-top:10px;border-top:1px dashed #e2d2c1;display:flex;flex-wrap:wrap;gap:10px;align-items:center">
                        <span style="font-size:12px;font-weight:600;color:#875200">Choose an option:</span>

                        {{-- Option A: WhatsApp contact --}}
                        <a href="{{ $eligibility['whatsapp_url'] }}" target="_blank" class="btn-whatsapp-header" style="font-size:12px;padding:6px 14px;text-decoration:none">
                            💬 Option A: Contact Us on WhatsApp
                        </a>

                        {{-- Option B: Inline trigger --}}
                        <a href="#websiteRequestBox" style="font-size:12px;color:#b94e20;font-weight:600;text-decoration:underline">
                            📝 Option B: Submit Website Request Form ↓
                        </a>
                    </div>
                @endif
            </div>

            {{-- Toggle Button Form --}}
            <form method="POST" action="{{ route('admin.instagram.toggle', $profile) }}" style="display:flex;align-items:center;justify-content:space-between;padding-top:6px;border-top:1px solid #f2e9df">
                @csrf
                <input type="hidden" name="is_enabled" value="{{ $settings->is_enabled ? 0 : 1 }}">

                <div style="font-size:13px;color:#453a30">
                    Current setting: <strong>{{ $settings->is_enabled ? 'Enabled' : 'Disabled' }}</strong>
                </div>

                @if($settings->is_enabled)
                    <button type="submit" class="btn-action" style="background:#fee2e2;border-color:#fca5a5;color:#991b1b;padding:8px 18px;font-weight:600">
                        Disable Public Display
                    </button>
                @else
                    <button type="submit" class="btn-action btn-action-primary" style="padding:8px 18px;font-weight:600" {{ !$eligibility['has_active_website'] ? 'disabled title="Active website required"' : '' }}>
                        Enable Public Display
                    </button>
                @endif
            </form>
            @error('toggle')
                <div style="font-size:12px;color:#dc2626;margin-top:8px">{{ $message }}</div>
            @enderror
        </div>

        {{-- Site-wide Auto-Play Control Card --}}
        <div style="background:#fff;border:1px solid #ebd9c4;border-left:4px solid #8b5cf6;border-radius:14px;padding:22px;box-shadow:0 2px 8px rgba(0,0,0,0.02)">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;flex-wrap:wrap;gap:12px">
                <div>
                    <h2 style="font-size:17px;color:#5b21b6;margin:0 0 4px">
                        ⚡ Auto-Play Reels Control
                    </h2>
                    <div style="font-size:13px;color:var(--admin-muted)">
                        Control whether ceremony videos and Reels autoplay automatically as visitors scroll this Pandit's website.
                    </div>
                </div>
                <div>
                    @if($settings->auto_play ?? true)
                        <span style="background:#ede9fe;color:#7c3aed;font-size:12px;font-weight:700;padding:4px 12px;border-radius:999px;border:1px solid #ddd6fe">
                            ● Auto-Play Enabled
                        </span>
                    @else
                        <span style="background:#f3f4f6;color:#6b7280;font-size:12px;font-weight:600;padding:4px 12px;border-radius:999px">
                            ○ Auto-Play Disabled
                        </span>
                    @endif
                </div>
            </div>

            <form method="POST" action="{{ route('admin.instagram.autoplay', $profile) }}" style="display:flex;align-items:center;justify-content:space-between;padding-top:10px;border-top:1px solid #f2e9df">
                @csrf
                <input type="hidden" name="auto_play" value="{{ ($settings->auto_play ?? true) ? 0 : 1 }}">
                <div style="font-size:13px;color:#453a30">
                    Auto-play status: <strong>{{ ($settings->auto_play ?? true) ? 'Active (Muted auto-play with 1-tap unmute)' : 'Disabled (Manual play required)' }}</strong>
                </div>
                <button type="submit" class="btn-action" style="background:{{ ($settings->auto_play ?? true) ? '#fee2e2;border-color:#fca5a5;color:#991b1b' : '#7c3aed;border-color:#6d28d9;color:#fff' }};padding:8px 18px;font-weight:600">
                    {{ ($settings->auto_play ?? true) ? 'Disable Auto-Play' : 'Enable Auto-Play' }}
                </button>
            </form>
        </div>

        {{-- Add Content Card with Tabs (Video Upload vs Instagram URL) --}}
        <div style="background:#fff;border:1px solid #ebd9c4;border-radius:14px;padding:22px;box-shadow:0 2px 8px rgba(0,0,0,0.02)">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;flex-wrap:wrap;gap:12px">
                <h2 style="font-size:17px;color:var(--admin-dark);margin:0">
                    ➕ Add Ceremony Content
                </h2>
                <div style="display:inline-flex;background:#f3f4f6;padding:3px;border-radius:8px;gap:4px">
                    <button type="button" id="adminTabUpload" onclick="switchAdminTab('upload')" style="background:#fff;color:#1f2937;border:1px solid #e5e7eb;border-radius:6px;padding:5px 12px;font-size:12.5px;font-weight:700;cursor:pointer">
                        📤 Upload Video File (Auto-Play)
                    </button>
                    <button type="button" id="adminTabUrl" onclick="switchAdminTab('url')" style="background:transparent;color:#6b7280;border:none;border-radius:6px;padding:5px 12px;font-size:12.5px;font-weight:600;cursor:pointer">
                        🔗 Instagram URL Link
                    </button>
                </div>
            </div>

            {{-- Tab 1: Video Upload --}}
            <div id="adminPaneUpload">
                <div style="background:#fdf2f8;border:1px solid #fbcfe8;border-radius:8px;padding:12px 14px;margin-bottom:16px">
                    <strong style="color:#9d174d;font-size:13px;display:flex;align-items:center;gap:6px">
                        <span>⚡ Direct Video Upload with Auto-Play</span>
                    </strong>
                    <p style="margin:4px 0 0;font-size:12px;color:#831843;line-height:1.4">
                        Uploads MP4/MOV/WebM video file directly to the server. Natively auto-plays on the Pandit's personal domain.
                    </p>
                </div>

                <form method="POST" action="{{ route('admin.instagram.upload-video', $profile) }}" enctype="multipart/form-data">
                    @csrf
                    <div style="margin-bottom:14px">
                        <label style="display:block;font-size:13px;font-weight:600;color:#453a30;margin-bottom:6px">
                            Video File (MP4, MOV, WebM, max 100MB) *
                        </label>
                        <input type="file" name="video" required accept="video/mp4,video/quicktime,video/webm,video/m4v" style="width:100%;padding:10px 12px;border:1px dashed #d8c8b8;border-radius:8px;font-size:13px;box-sizing:border-box">
                        @error('video')
                            <div style="font-size:12px;color:#dc2626;margin-top:4px">{{ $message }}</div>
                        @enderror
                    </div>

                    <div style="margin-bottom:14px">
                        <label style="display:block;font-size:13px;font-weight:600;color:#453a30;margin-bottom:6px">
                            Ceremony / Vidhi Caption (Optional)
                        </label>
                        <input type="text" name="caption" value="{{ old('caption') }}" placeholder="e.g. Satyanarayan Katha Katha Live at Devotee Residence" style="width:100%;padding:10px 14px;border:1px solid #d8c8b8;border-radius:8px;font-size:14px;box-sizing:border-box">
                    </div>

                    <div style="margin-bottom:18px;display:flex;align-items:center;gap:8px">
                        <input type="checkbox" id="admin_is_autoplay" name="is_autoplay" value="1" checked style="width:18px;height:18px;accent-color:#be185d">
                        <label for="admin_is_autoplay" style="font-size:13px;font-weight:600;color:#453a30;cursor:pointer">
                            Enable Auto-Play on website for this reel
                        </label>
                    </div>

                    <button type="submit" class="btn-action btn-action-primary" style="padding:10px 22px;font-weight:600">
                        🚀 Upload Video &amp; Enable Auto-Play
                    </button>
                </form>
            </div>

            {{-- Tab 2: Instagram URL --}}
            <div id="adminPaneUrl" style="display:none">
                <form method="POST" action="{{ route('admin.instagram.urls.store', $profile) }}">
                    @csrf
                    <div style="margin-bottom:14px">
                        <label style="display:block;font-size:13px;font-weight:600;color:#453a30;margin-bottom:6px">
                            Instagram Post or Reel URL *
                        </label>
                        <input type="url" name="url" value="{{ old('url') }}" placeholder="https://www.instagram.com/reel/Cxxxxxx/ or https://www.instagram.com/p/Cxxxxxx/" style="width:100%;padding:10px 14px;border:1px solid #d8c8b8;border-radius:8px;font-size:14px;box-sizing:border-box">
                        @error('url')
                            <div style="font-size:12px;color:#dc2626;margin-top:4px">{{ $message }}</div>
                        @enderror
                    </div>

                    <div style="margin-bottom:18px">
                        <label style="display:block;font-size:13px;font-weight:600;color:#453a30;margin-bottom:6px">
                            Ceremony / Vidhi Caption (Optional)
                        </label>
                        <input type="text" name="caption" value="{{ old('caption') }}" placeholder="e.g. Rudrabhishek Mahavidhi performed by Shastri Ji in Ahmedabad" style="width:100%;padding:10px 14px;border:1px solid #d8c8b8;border-radius:8px;font-size:14px;box-sizing:border-box">
                    </div>

                    <div style="display:flex;align-items:center;justify-content:space-between">
                        <div style="font-size:12px;color:#786b60">
                            ✓ Duplicate prevention active • HTTPS only
                        </div>
                        <button type="submit" class="btn-action btn-action-primary" style="padding:9px 20px;font-weight:600">
                            Validate &amp; Add URL
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Active Instagram Posts List --}}
        <div style="background:#fff;border:1px solid #ebd9c4;border-radius:14px;padding:22px;box-shadow:0 2px 8px rgba(0,0,0,0.02)">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
                <h2 style="font-size:17px;color:var(--admin-dark);margin:0">
                    🎬 Saved Posts &amp; Reels ({{ $posts->count() }})
                </h2>
                <span style="font-size:12px;color:var(--admin-muted)">
                    Sorted by publication sequence
                </span>
            </div>

            @if($posts->isEmpty())
                <div style="text-align:center;padding:36px 20px;background:#faf7f2;border-radius:10px;border:1px dashed #ebd9c4;color:#786b60">
                    <div style="font-size:32px;margin-bottom:8px">📹</div>
                    <div style="font-weight:600;font-size:14px;color:#453a30">No Instagram URLs added yet</div>
                    <div style="font-size:13px;margin-top:4px">Paste a Reel or post URL using the form above to display it on Shastri Ji's website.</div>
                </div>
            @else
                <div style="display:flex;flex-direction:column;gap:14px">
                    @foreach($posts as $post)
                        <div style="border:1px solid #ebd9c4;border-radius:10px;padding:14px 18px;display:flex;align-items:flex-start;justify-content:space-between;gap:16px;background:#fff;flex-wrap:wrap">
                            <div style="display:flex;align-items:flex-start;gap:14px;flex:1;min-width:0">
                                @if($post->isVideoUpload())
                                    <div style="width:72px;height:72px;border-radius:8px;overflow:hidden;background:#000;flex-shrink:0;position:relative">
                                        <video src="{{ $post->getVideoUrl() }}" style="width:100%;height:100%;object-fit:cover" muted playsinline></video>
                                        <span style="position:absolute;bottom:2px;right:2px;background:rgba(0,0,0,0.7);color:#fff;font-size:9px;padding:1px 3px;border-radius:2px">▶ MP4</span>
                                    </div>
                                @else
                                    <div style="width:40px;height:40px;border-radius:8px;background:{{ $post->media_type === 'reel' ? '#fce8f3' : '#e0f2fe' }};color:{{ $post->media_type === 'reel' ? '#be185d' : '#0369a1' }};display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0">
                                        {{ $post->media_type === 'reel' ? '▶' : '📷' }}
                                    </div>
                                @endif

                                <div style="min-width:0;flex:1">
                                    <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">
                                        @if($post->isVideoUpload())
                                            <span style="background:#ecfdf5;color:#059669;font-size:10.5px;font-weight:700;padding:2px 8px;border-radius:4px;border:1px solid #a7f3d0">
                                                🎬 DIRECT VIDEO
                                            </span>
                                        @else
                                            <span style="background:{{ $post->media_type === 'reel' ? '#fce8f3' : '#e0f2fe' }};color:{{ $post->media_type === 'reel' ? '#be185d' : '#0369a1' }};font-size:11px;font-weight:700;padding:2px 8px;border-radius:4px;text-transform:uppercase">
                                                {{ $post->media_type }}
                                            </span>
                                        @endif

                                        @if($post->is_autoplay)
                                            <span style="background:#ede9fe;color:#7c3aed;font-size:10.5px;font-weight:600;padding:2px 8px;border-radius:4px;border:1px solid #ddd6fe">
                                                ⚡ Auto-Play ON
                                            </span>
                                        @endif

                                        @if($post->isVideoUpload())
                                            <a href="{{ $post->getVideoUrl() }}" target="_blank" style="font-family:monospace;font-size:13px;color:var(--admin-dark);text-decoration:none;font-weight:600;word-break:break-all">
                                                {{ basename($post->video_path) }} ↗
                                            </a>
                                        @else
                                            <a href="{{ $post->url }}" target="_blank" style="font-family:monospace;font-size:13px;color:var(--admin-dark);text-decoration:none;font-weight:600;word-break:break-all">
                                                {{ $post->url }} ↗
                                            </a>
                                        @endif
                                    </div>

                                    @if($post->caption)
                                        <div style="font-size:13px;color:#6b5a4d;margin-top:4px">
                                            {{ $post->caption }}
                                        </div>
                                    @endif

                                    @if(!$post->isVideoUpload())
                                        <div style="margin-top:8px;padding:6px 10px;background:#fefce8;border:1px dashed #fde047;border-radius:6px;font-size:11.5px">
                                            <span style="font-weight:600;color:#854d0e">Attach raw video file for zero-redirect Auto-Play:</span>
                                            <form method="POST" action="{{ route('admin.instagram.upload-video', $profile) }}" enctype="multipart/form-data" style="margin-top:4px;display:flex;gap:6px;align-items:center;flex-wrap:wrap">
                                                @csrf
                                                <input type="hidden" name="post_id" value="{{ $post->id }}">
                                                <input type="file" name="video" required accept="video/mp4,video/quicktime,video/webm" style="font-size:11px">
                                                <button type="submit" class="btn-action" style="font-size:11px;padding:3px 8px">
                                                    Attach MP4
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div style="display:flex;align-items:center;gap:10px">
                                <form method="POST" action="{{ route('admin.instagram.urls.destroy', [$profile, $post]) }}" onsubmit="return confirm('Remove this Instagram post from this Bhudev?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action" style="background:#fff;border-color:#fca5a5;color:#dc2626;padding:6px 12px;font-size:12px">
                                        🗑 Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Option B: Website Request Form (Only if no active website) --}}
        @if(!$eligibility['has_active_website'])
            <div id="websiteRequestBox" style="background:#fffcf5;border:1px solid #ebd9c4;border-top:3px solid #b94e20;border-radius:14px;padding:22px;box-shadow:0 2px 8px rgba(0,0,0,0.02)">
                <h2 style="font-size:17px;color:#b94e20;margin:0 0 6px">
                    📝 Option B: Request Website &amp; Personal Domain for {{ $profile->display_name }}
                </h2>
                <p style="font-size:13px;color:#6e5f52;line-height:1.5;margin:0 0 16px">
                    Submit a domain onboarding inquiry on behalf of this Bhudev so their personal website can be configured and Instagram display unlocked.
                </p>

                <form method="POST" action="{{ route('admin.instagram.request-website', $profile) }}">
                    @csrf
                    <div style="margin-bottom:12px">
                        <label style="display:block;font-size:13px;font-weight:600;color:#453a30;margin-bottom:4px">
                            Requested Domain / Subdomain
                        </label>
                        <input type="text" name="requested_domain" value="{{ old('requested_domain', \Illuminate\Support\Str::slug($profile->display_name) . '.digitalbhudev.in') }}" placeholder="shastriparas.in or shastri-paras.digitalbhudev.in" style="width:100%;padding:9px 12px;border:1px solid #d8c8b8;border-radius:6px;font-size:13px;box-sizing:border-box">
                    </div>

                    <div style="margin-bottom:12px">
                        <label style="display:block;font-size:13px;font-weight:600;color:#453a30;margin-bottom:4px">
                            Contact WhatsApp Number
                        </label>
                        <input type="tel" name="whatsapp_number" value="{{ old('whatsapp_number', $profile->user?->phone ?? '919876543210') }}" style="width:100%;padding:9px 12px;border:1px solid #d8c8b8;border-radius:6px;font-size:13px;box-sizing:border-box">
                    </div>

                    <div style="margin-bottom:16px">
                        <label style="display:block;font-size:13px;font-weight:600;color:#453a30;margin-bottom:4px">
                            Notes / Specific Requirements
                        </label>
                        <textarea name="notes" rows="2" placeholder="Custom branding, specific domain registrar, or muhurat preferences..." style="width:100%;padding:9px 12px;border:1px solid #d8c8b8;border-radius:6px;font-size:13px;box-sizing:border-box">{{ old('notes') }}</textarea>
                    </div>

                    <button type="submit" class="btn-action btn-action-primary" style="padding:9px 20px;font-weight:600">
                        Submit Website Request
                    </button>
                </form>
            </div>
        @endif
    </div>

    {{-- Right Column: Copy Instructions & Meta API Setup Wizard --}}
    <div style="display:flex;flex-direction:column;gap:24px">

        {{-- How to Copy Post/Reel URL Instructions --}}
        <div style="background:#fff;border:1px solid #ebd9c4;border-radius:14px;padding:22px;box-shadow:0 2px 8px rgba(0,0,0,0.02)">
            <h3 style="font-size:16px;color:var(--admin-dark);margin:0 0 8px;display:flex;align-items:center;gap:8px">
                <span>📋</span> How to Copy Instagram Post or Reel URL
            </h3>
            <p style="font-size:13px;color:var(--admin-muted);margin:0 0 14px">
                Follow these 10 simple steps in the Instagram app or web browser:
            </p>

            <ol style="margin:0;padding-left:20px;font-size:12.5px;color:#554a40;line-height:1.7">
                <li>Open Instagram in the mobile app or browser.</li>
                <li>Open the desired ceremony post or Reel.</li>
                <li>Select the <strong>Share</strong> option or <strong>••• (three-dot)</strong> menu.</li>
                <li>Select <strong>Copy link</strong>.</li>
                <li>Return to the Bhudev admin panel.</li>
                <li>Verify you have selected <strong>{{ $profile->display_name }}</strong>.</li>
                <li>Paste the URL into the input field on the left.</li>
                <li>Click <strong>Validate &amp; Add URL</strong>.</li>
                <li>Review the result and verify the media type badge (Reel/Post).</li>
                <li>Ensure the Pandit's personal domain is active for public display.</li>
            </ol>

            <div style="background:#fdf9f4;border:1px dashed #d5a45c;border-radius:8px;padding:10px 12px;margin-top:16px">
                <div style="font-size:11px;font-weight:700;color:#875200;text-transform:uppercase">Sample URL Formats</div>
                <div style="font-family:monospace;font-size:12px;color:#221a14;margin-top:4px">https://www.instagram.com/reel/C3zY4X1v8Wk/</div>
                <div style="font-family:monospace;font-size:12px;color:#221a14;margin-top:2px">https://www.instagram.com/p/CzX41v8Wk01/</div>
            </div>

            <div style="margin-top:14px">
                <details style="font-size:12.5px;color:#6b5a4d">
                    <summary style="font-weight:600;cursor:pointer;color:var(--admin-dark)">Frequently Asked Questions (FAQs)</summary>
                    <div style="margin-top:8px;line-height:1.6">
                        <p><strong>Q: Can private account posts be embedded?</strong><br>A: No, Instagram only allows public posts to be embedded on websites.</p>
                        <p><strong>Q: Will duplicate links be saved?</strong><br>A: No, the system checks for existing URLs and shortcodes to prevent duplicates.</p>
                        <p><strong>Q: What happens if a post is deleted on Instagram?</strong><br>A: The embed gracefully links to the Instagram post or you can delete it using the button above.</p>
                    </div>
                </details>
            </div>
        </div>

        {{-- Meta Instagram API Connection Wizard --}}
        <div style="background:#fff;border:1px solid #ebd9c4;border-radius:14px;padding:22px;box-shadow:0 2px 8px rgba(0,0,0,0.02)">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
                <h3 style="font-size:16px;color:var(--admin-dark);margin:0;display:flex;align-items:center;gap:8px">
                    <span>⚡</span> Official Meta API Connection
                </h3>
                <div>
                    @if($settings->connection_status === 'connected')
                        <span style="background:#e8f7ee;color:#1e7e34;font-size:11px;font-weight:700;padding:3px 8px;border-radius:999px">
                            ✓ Connected
                        </span>
                    @elseif($settings->connection_status === 'error')
                        <span style="background:#fee2e2;color:#991b1b;font-size:11px;font-weight:700;padding:3px 8px;border-radius:999px">
                            ✕ Connection Error
                        </span>
                    @else
                        <span style="background:#f3f4f6;color:#6b7280;font-size:11px;font-weight:600;padding:3px 8px;border-radius:999px">
                            Disconnected
                        </span>
                    @endif
                </div>
            </div>

            <p style="font-size:12.5px;color:var(--admin-muted);margin:0 0 14px;line-height:1.5">
                Connecting via official Meta Instagram Graph API allows server-side verification of Pandit account ownership. Status becomes <strong>Connected</strong> only after real API validation.
            </p>

            @if($settings->connection_status === 'connected')
                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:12px 14px;margin-bottom:16px">
                    <div style="font-size:13px;font-weight:700;color:#15803d">
                        Connected as @<span>{{ $settings->account_username }}</span>
                    </div>
                    <div style="font-size:11px;color:#166534;margin-top:3px">
                        Account ID: {{ $settings->account_id }} • Last verified: {{ $settings->last_sync_at?->diffForHumans() ?? 'Recently' }}
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.instagram.disconnect', $profile) }}">
                    @csrf
                    <button type="submit" class="btn-action" style="background:#fff;border-color:#fca5a5;color:#dc2626;padding:8px 16px;font-size:12px;font-weight:600">
                        Disconnect Instagram Account
                    </button>
                </form>
            @else
                @if($settings->last_error)
                    <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:10px 12px;margin-bottom:14px;font-size:12px;color:#991b1b">
                        <strong>Last Error:</strong> {{ $settings->last_error }}
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.instagram.connect-api', $profile) }}">
                    @csrf
                    <div style="margin-bottom:12px">
                        <label style="display:block;font-size:12.5px;font-weight:600;color:#453a30;margin-bottom:4px">
                            Meta User Access Token *
                        </label>
                        <input type="password" name="access_token" required placeholder="EAA..." style="width:100%;padding:9px 12px;border:1px solid #d8c8b8;border-radius:6px;font-size:13px;box-sizing:border-box">
                        @error('access_token')
                            <div style="font-size:12px;color:#dc2626;margin-top:4px">{{ $message }}</div>
                        @enderror
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:14px">
                        <div>
                            <label style="display:block;font-size:12px;font-weight:600;color:#453a30;margin-bottom:4px">
                                Meta App ID (Optional)
                            </label>
                            <input type="text" name="app_id" value="{{ old('app_id', $settings->app_id) }}" placeholder="123456789..." style="width:100%;padding:8px 10px;border:1px solid #d8c8b8;border-radius:6px;font-size:12px;box-sizing:border-box">
                        </div>
                        <div>
                            <label style="display:block;font-size:12px;font-weight:600;color:#453a30;margin-bottom:4px">
                                Meta App Secret (Optional)
                            </label>
                            <input type="password" name="app_secret" placeholder="App Secret..." style="width:100%;padding:8px 10px;border:1px solid #d8c8b8;border-radius:6px;font-size:12px;box-sizing:border-box">
                        </div>
                    </div>

                    <button type="submit" class="btn-action btn-action-primary" style="padding:9px 18px;font-weight:600;font-size:13px">
                        ⚡ Verify &amp; Connect API
                    </button>
                </form>
            @endif

            {{-- Meta Developer Setup Guidance --}}
            <div style="margin-top:18px;padding-top:14px;border-top:1px solid #f2e9df;font-size:12px;color:#6b5a4d;line-height:1.6">
                <div style="font-weight:700;color:var(--admin-dark);margin-bottom:6px">Meta Developer Setup Requirements:</div>
                <ul style="margin:0;padding-left:18px">
                    <li>Create an app in the <a href="https://developers.facebook.com/apps/" target="_blank" style="color:#b94e20">Meta Developer Portal ↗</a>.</li>
                    <li>Add the <strong>Instagram Graph API</strong> or <strong>Instagram Basic Display</strong> product.</li>
                    <li>Required scope: <code>instagram_basic</code>.</li>
                    <li>Supports Creator and Business Instagram accounts. Personal accounts require Instagram Basic Display product.</li>
                    <li>Tokens are encrypted securely at rest using AES-256 in DigitalBhudev.</li>
                </ul>
            </div>
        </div>

    </div>
</div>

<script>
    function switchAdminTab(tab) {
        var paneUpload = document.getElementById('adminPaneUpload');
        var paneUrl = document.getElementById('adminPaneUrl');
        var btnUpload = document.getElementById('adminTabUpload');
        var btnUrl = document.getElementById('adminTabUrl');

        if (tab === 'upload') {
            paneUpload.style.display = 'block';
            paneUrl.style.display = 'none';

            btnUpload.style.background = '#fff';
            btnUpload.style.color = '#1f2937';
            btnUpload.style.fontWeight = '700';
            btnUpload.style.border = '1px solid #e5e7eb';

            btnUrl.style.background = 'transparent';
            btnUrl.style.color = '#6b7280';
            btnUrl.style.fontWeight = '600';
            btnUrl.style.border = 'none';
        } else {
            paneUpload.style.display = 'none';
            paneUrl.style.display = 'block';

            btnUrl.style.background = '#fff';
            btnUrl.style.color = '#1f2937';
            btnUrl.style.fontWeight = '700';
            btnUrl.style.border = '1px solid #e5e7eb';

            btnUpload.style.background = 'transparent';
            btnUpload.style.color = '#6b7280';
            btnUpload.style.fontWeight = '600';
            btnUpload.style.border = 'none';
        }
    }
</script>
@endsection
