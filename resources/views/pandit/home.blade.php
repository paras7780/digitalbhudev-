@extends('layouts.pandit')

@section('title', ($domain->hero_title ?? $pandit->display_name) . ' | Official Vedic Priest & Astrologer')
@section('meta_description', $domain->hero_tagline ?? ('Book authentic Vedic rituals, Griha Pravesh, Satyanarayan Puja, and Rudrabhishek with ' . $pandit->display_name))

@section('content')
{{-- HERO SECTION --}}
<section id="hero" style="background:linear-gradient(135deg, #fff5f9 0%, #ffffff 50%, #eff6ff 100%);padding:60px 0 80px;border-bottom:1px solid #f3e5ee">
    <div class="pandit-hero-grid" style="max-width:1200px;margin:0 auto;padding:0 24px;display:grid;grid-template-columns:1.2fr 0.8fr;gap:48px;align-items:center">
        <div>
            <div style="display:inline-flex;align-items:center;gap:8px;background:#fdf2f8;border:1px solid #fbcfe8;color:var(--pandit-primary);padding:6px 14px;border-radius:999px;font-size:13px;font-weight:700;margin-bottom:18px;letter-spacing:0.5px">
                <span>🕉️</span>
                <span>AUTHENTIC VEDIC KARAMKAND</span>
            </div>

            <h1 style="font-family:'Playfair Display',serif;font-size:42px;line-height:1.2;color:var(--pandit-dark);margin:0 0 16px;font-weight:800">
                {{ $domain->hero_title ?? $pandit->display_name }}
            </h1>

            <p style="font-size:18px;color:#6b5a4d;line-height:1.6;margin:0 0 28px;max-width:580px">
                {{ $domain->hero_tagline ?? ($pandit->experience_years ? $pandit->experience_years . '+ Years of Sacred Shastra Vidhi & Vedic Pujas in ' . ($pandit->city ?? 'Gujarat') : 'Experienced Vedic Priest conducting authentic ceremonies with pure Sanskrit Mantra Uchcharan.') }}
            </p>

            {{-- Trust Badges --}}
            <div style="display:flex;flex-wrap:wrap;gap:12px;margin-bottom:32px">
                <span style="background:#fff;border:1px solid #e2d2c1;padding:6px 14px;border-radius:6px;font-size:13px;color:#453a30;font-weight:600">
                    ✓ Verified Brahmin Priest
                </span>
                @if($pandit->experience_years)
                    <span style="background:#fff;border:1px solid #e2d2c1;padding:6px 14px;border-radius:6px;font-size:13px;color:#453a30;font-weight:600">
                        ⏳ {{ $pandit->experience_years }}+ Years Experience
                    </span>
                @endif
                <span style="background:#fff;border:1px solid #e2d2c1;padding:6px 14px;border-radius:6px;font-size:13px;color:#453a30;font-weight:600">
                    📍 {{ $pandit->city ? $pandit->city . ', ' . ($pandit->state ?? 'Gujarat') : 'Gujarat, India' }}
                </span>
            </div>

            {{-- CTAs --}}
            <div style="display:flex;flex-wrap:wrap;gap:14px;align-items:center">
                <a href="{{ $domain->generateWhatsAppUrl('Namaste Shastriji, I visited your personal website and would like to check your availability for an upcoming puja.') }}" target="_blank" class="btn-whatsapp-header" style="font-size:16px;padding:14px 28px">
                    <span style="font-size:20px">💬</span>
                    <span>Book on WhatsApp (Instant Reply)</span>
                </a>

                <a href="#ceremonies" style="background:#fff;border:1px solid #d5a45c;color:#875200;padding:14px 24px;border-radius:999px;font-weight:600;font-size:15px;text-decoration:none">
                    📜 View Pujas & Samagri
                </a>
            </div>
        </div>

        {{-- Profile Portrait Card --}}
        <div style="text-align:center">
            <div style="position:relative;display:inline-block">
                <div style="position:absolute;inset:-10px;background:linear-gradient(135deg, #f472b6, var(--pandit-primary));border-radius:24px;filter:blur(12px);opacity:0.25"></div>
                <div style="position:relative;background:#fff;border:4px solid #fff;border-radius:20px;overflow:hidden;box-shadow:0 12px 36px rgba(74,22,51,0.12);max-width:360px">
                    @if($pandit->photo_path)
                        <img src="{{ asset('storage/' . $pandit->photo_path) }}" alt="{{ $pandit->display_name }}" style="width:100%;height:380px;object-fit:cover;display:block">
                    @else
                        <div style="width:340px;height:380px;background:linear-gradient(135deg, #f7ebe1, #edd1ba);display:flex;flex-direction:column;align-items:center;justify-content:center;color:#b94e20">
                            <span style="font-size:72px">ॐ</span>
                            <span style="font-family:'Playfair Display',serif;font-size:20px;font-weight:bold;margin-top:12px;color:#29241f">{{ $domain->hero_title ?? $pandit->display_name }}</span>
                        </div>
                    @endif

                    <div style="padding:16px 20px;background:#fff;border-top:1px solid #f0e6da;text-align:left">
                        <div style="font-family:'Playfair Display',serif;font-size:18px;font-weight:700;color:#221a14">
                            {{ $domain->hero_title ?? $pandit->display_name }}
                        </div>
                        <div style="font-size:13px;color:#875200;margin-top:2px">
                            {{ $pandit->qualification ?? 'Vedic Scholar & Priest' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- CREDENTIALS STRIP --}}
<section style="background:#fff;border-bottom:1px solid #ebd9c4;padding:28px 0">
    <div style="max-width:1200px;margin:0 auto;padding:0 24px;display:grid;grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));gap:24px;text-align:center">
        <div>
            <div style="font-size:24px;color:#b94e20;font-weight:800;font-family:'Playfair Display',serif">{{ $pandit->experience_years ? $pandit->experience_years . '+ Years' : '15+ Years' }}</div>
            <div style="font-size:13px;color:#6d5f53;margin-top:4px">Vedic Ritual Experience</div>
        </div>
        <div>
            <div style="font-size:24px;color:#b94e20;font-weight:800;font-family:'Playfair Display',serif">100% Shastra Vidhi</div>
            <div style="font-size:13px;color:#6d5f53;margin-top:4px">Pure Sanskrit Mantrocharan</div>
        </div>
        <div>
            <div style="font-size:24px;color:#b94e20;font-weight:800;font-family:'Playfair Display',serif">
                {{ !empty($pandit->languages) ? count((array)$pandit->languages) . ' Languages' : 'Gujarati & Hindi' }}
            </div>
            <div style="font-size:13px;color:#6d5f53;margin-top:4px">{{ !empty($pandit->languages) ? implode(', ', (array)$pandit->languages) : 'Fluent Mantra & Explanation' }}</div>
        </div>
        <div>
            <div style="font-size:24px;color:#25d366;font-weight:800;font-family:'Playfair Display',serif">Direct Booking</div>
            <div style="font-size:13px;color:#6d5f53;margin-top:4px">No Middleman / 0% Commission</div>
        </div>
    </div>
</section>

{{-- ABOUT SECTION --}}
<section id="about" style="padding:70px 0;background:#fdfaf6">
    <div style="max-width:1000px;margin:0 auto;padding:0 24px">
        <div style="text-align:center;margin-bottom:40px">
            <span style="color:#b94e20;font-weight:700;font-size:12px;letter-spacing:1px;text-transform:uppercase">ABOUT SHASTRI JI</span>
            <h2 style="font-family:'Playfair Display',serif;font-size:32px;color:#221a14;margin:8px 0 0">
                Spiritual Heritage & Vedic Lineage
            </h2>
        </div>

        <div style="background:#fff;border:1px solid #ede1d1;border-radius:16px;padding:36px;box-shadow:0 4px 18px rgba(41,36,31,0.04)">
            @php
                $introText = $domain->about_intro ?: ($pandit->bio ?: ($domain->hero_title ?? $pandit->display_name) . ' has been serving devotees across ' . ($pandit->city ?? 'Gujarat') . ' for over ' . ($pandit->experience_years ? $pandit->experience_years . ' years' : 'many years') . ', conducting traditional Vedic pujas, havans, and astrology consultations with unwavering devotion and adherence to Shastra traditions.');
            @endphp
            <p style="color:#4d4237;font-size:16px;line-height:1.8;margin:0 0 24px">
                {!! nl2br(e($introText)) !!}
            </p>

            @if($pandit->expertise)
                <div style="margin-top:20px;padding-top:20px;border-top:1px solid #f0e6da">
                    <strong style="color:#221a14;font-size:14px;display:block;margin-bottom:6px">Areas of Spiritual Expertise:</strong>
                    <div style="color:#6d5f53;font-size:14px;line-height:1.6">{{ $pandit->expertise }}</div>
                </div>
            @endif

            @if($pandit->service_area)
                <div style="margin-top:16px;padding-top:16px;border-top:1px solid #f0e6da">
                    <strong style="color:#221a14;font-size:14px;display:block;margin-bottom:6px">Service Coverage Area:</strong>
                    <div style="color:#6d5f53;font-size:14px;line-height:1.6">{{ $pandit->service_area }} (Domestic & Virtual Ceremonies for NRIs)</div>
                </div>
            @endif
        </div>
    </div>
</section>

{{-- CEREMONIES & PUJAS SECTION --}}
<section id="ceremonies" style="padding:70px 0;background:#fff;border-top:1px solid #ebd9c4">
    <div style="max-width:1200px;margin:0 auto;padding:0 24px">
        <div style="text-align:center;margin-bottom:48px">
            <span style="color:#b94e20;font-weight:700;font-size:12px;letter-spacing:1px;text-transform:uppercase">SACRED RITUALS</span>
            <h2 style="font-family:'Playfair Display',serif;font-size:32px;color:#221a14;margin:8px 0 10px">
                Ceremonies Conducted by Shastri Ji
            </h2>
            <p style="color:#6d5f53;font-size:15px;margin:0;max-width:600px;margin:0 auto">
                Every ritual includes complete Shastra Vidhi, Sankalpa, sacred Havan, and customized Samagri guidance.
            </p>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(320px, 1fr));gap:28px">
            @forelse($pandit->services as $service)
                <div style="background:#fffaf6;border:1px solid #eedecf;border-radius:14px;padding:28px;display:flex;flex-direction:column;justify-content:space-between;transition:transform 0.2s, box-shadow 0.2s;box-shadow:0 4px 12px rgba(41,36,31,0.03)">
                    <div>
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
                            <span style="font-size:26px">🪔</span>
                            <span style="background:#fff;border:1px solid #e5d4c2;padding:4px 10px;border-radius:999px;font-size:11px;font-weight:700;color:#875200">
                                VEDIC VIDHI
                            </span>
                        </div>
                        <h3 style="font-family:'Playfair Display',serif;font-size:20px;color:#221a14;margin:0 0 10px">
                            {{ $service->name }}
                        </h3>
                        <p style="color:#5e5044;font-size:14px;line-height:1.6;margin:0 0 16px">
                            {{ $service->description ?: 'Conducted with sacred Vedic Sankalpa, authentic mantra recitations, and complete aarti vidhi for the prosperity of your family.' }}
                        </p>
                    </div>

                    <div style="padding-top:16px;border-top:1px solid #f0e2d3">
                        <a href="{{ $domain->generateWhatsAppUrl('Namaste Shastriji, I would like to book ' . $service->name . '. Please let me know available Muhurats.') }}" target="_blank" class="btn-whatsapp-header" style="width:100%;justify-content:center;box-sizing:border-box;font-size:14px">
                            💬 Book {{ $service->name }}
                        </a>
                    </div>
                </div>
            @empty
                {{-- Fallback default ceremony cards if services not explicitly attached --}}
                @foreach(['Satyanarayan Katha & Puja' => 'Traditional Katha with banana leaves mandap, Panchamrit preparation, and sacred Shaligram abhishek.', 'Griha Pravesh & Vastu Shanti' => 'Navagraha Havan, Vastu Purush Sthapana, Toran Pooja, and milk boiling ritual for new homes.', 'Rudrabhishek & Shiva Pooja' => 'Sacred Abhishek of Lord Shiva with Gangajal, milk, panchamrit, and Laghurudra / Mahamrityunjaya chanting.'] as $pName => $pDesc)
                    <div style="background:#fffaf6;border:1px solid #eedecf;border-radius:14px;padding:28px;display:flex;flex-direction:column;justify-content:space-between;box-shadow:0 4px 12px rgba(41,36,31,0.03)">
                        <div>
                            <div style="font-size:26px;margin-bottom:12px">🪔</div>
                            <h3 style="font-family:'Playfair Display',serif;font-size:20px;color:#221a14;margin:0 0 10px">{{ $pName }}</h3>
                            <p style="color:#5e5044;font-size:14px;line-height:1.6;margin:0 0 16px">{{ $pDesc }}</p>
                        </div>
                        <div style="padding-top:16px;border-top:1px solid #f0e2d3">
                            <a href="{{ $domain->generateWhatsAppUrl('Namaste Shastriji, I would like to book ' . $pName . '.') }}" target="_blank" class="btn-whatsapp-header" style="width:100%;justify-content:center;box-sizing:border-box;font-size:14px">
                                💬 Book on WhatsApp
                            </a>
                        </div>
                    </div>
                @endforeach
            @endforelse
        </div>
    </div>
</section>

{{-- CEREMONY GALLERY SECTION --}}
@if($pandit->publicImages->isNotEmpty())
    <section id="gallery" style="padding:70px 0;background:#fdfaf6;border-top:1px solid #ebd9c4">
        <div style="max-width:1200px;margin:0 auto;padding:0 24px">
            <div style="text-align:center;margin-bottom:40px">
                <span style="color:#b94e20;font-weight:700;font-size:12px;letter-spacing:1px;text-transform:uppercase">CEREMONY GLIMPSES</span>
                <h2 style="font-family:'Playfair Display',serif;font-size:32px;color:#221a14;margin:8px 0 0">
                    Sacred Rituals & Yagyas Performed
                </h2>
            </div>

            <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(260px, 1fr));gap:20px">
                @foreach($pandit->publicImages as $img)
                    <div style="border-radius:12px;overflow:hidden;border:1px solid #eedecf;box-shadow:0 4px 14px rgba(41,36,31,0.06);background:#fff">
                        <img src="{{ asset('storage/' . $img->path) }}" alt="{{ $img->alt_text ?: 'Ceremony Photo' }}" style="width:100%;height:220px;object-fit:cover;display:block">
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

{{-- INSTAGRAM REELS & POSTS SECTION (DISPLAYED STRICTLY ON ACTIVE PERSONAL DOMAIN WHEN ELIGIBLE) --}}
@if(!empty($instagramEligible) && !empty($instagramPosts) && $instagramPosts->isNotEmpty())
    @php
        $siteAutoplayDefault = ($instagramSetting->auto_play ?? true);
    @endphp
    <section id="instagram" style="padding:60px 0 70px;background:#fdf9f4;border-top:1px solid #ebd9c4">
        <div style="max-width:1200px;margin:0 auto;padding:0 24px">
            <div style="text-align:center;margin-bottom:36px">
                <div style="display:inline-flex;align-items:center;gap:6px;background:#fce8f3;color:#be185d;border:1px solid #f9a8d4;padding:4px 12px;border-radius:999px;font-size:12px;font-weight:700;letter-spacing:0.5px;margin-bottom:10px">
                    <span>📸</span>
                    <span>VEDIC REELS &amp; DARSHAN</span>
                </div>
                <h2 style="font-family:'Playfair Display',serif;font-size:32px;color:#221a14;margin:0 0 10px">
                    Glimpses of Sacred Ceremonies &amp; Mantras
                </h2>
                <p style="color:#6d5f53;font-size:15px;margin:0 auto 16px;max-width:600px">
                    Watch official Reels and ceremony recordings conducted by {{ $domain->hero_title ?? $pandit->display_name }}.
                </p>

                {{-- Interactive Auto-Play Control Pill --}}
                <div style="display:inline-flex;align-items:center;gap:12px;background:#fff;border:1.5px solid #ebd9c4;padding:6px 18px;border-radius:999px;box-shadow:0 2px 10px rgba(41,36,31,0.05)">
                    <span style="font-size:13px;font-weight:600;color:#453a30;display:inline-flex;align-items:center;gap:6px">
                        <span id="autoplayStatusDot" style="display:inline-block;width:9px;height:9px;border-radius:50%;background:#10b981;box-shadow:0 0 8px #10b981;animation:pulseDot 2s infinite"></span>
                        Reels Auto-Play:
                    </span>
                    <button type="button" id="autoplayToggleBtn" onclick="toggleSiteAutoplay()" style="background:#fce8f3;color:#be185d;border:1px solid #fbcfe8;border-radius:999px;padding:4px 14px;font-size:12.5px;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:6px;transition:all 0.2s">
                        <span id="autoplayToggleIcon">⚡</span>
                        <span id="autoplayToggleLabel">Auto-Play ON</span>
                    </button>
                    <span style="font-size:11.5px;color:#786b60">Plays smoothly on scroll</span>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(320px, 1fr));gap:24px">
                @foreach($instagramPosts as $post)
                    <div class="bhudev-reel-card" style="background:#fff;border:1px solid #ebd9c4;border-radius:16px;overflow:hidden;box-shadow:0 4px 16px rgba(41,36,31,0.06);display:flex;flex-direction:column;transition:transform 0.2s">
                        {{-- Instagram / Video Header --}}
                        <div style="padding:14px 18px;border-bottom:1px solid #f2e9df;display:flex;align-items:center;justify-content:space-between;background:#fff">
                            <div style="display:flex;align-items:center;gap:10px">
                                <span style="background:linear-gradient(135deg, #f58529, #dd2a7b, #8134af);color:#fff;width:30px;height:30px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:bold;box-shadow:0 2px 6px rgba(221,42,123,0.3)">
                                    {{ $post->isVideoUpload() ? '🎬' : '📸' }}
                                </span>
                                <div>
                                    <div style="font-size:13px;font-weight:700;color:#221a14">{{ $domain->hero_title ?? $pandit->display_name }}</div>
                                    <div style="font-size:11px;color:#875200">{{ $post->isVideoUpload() ? 'Sacred Ceremony Recording' : 'Official Instagram' }}</div>
                                </div>
                            </div>
                            @if($post->isVideoUpload())
                                <span style="background:linear-gradient(135deg, #fce8f3, #fdf2f8);color:#be185d;border:1px solid #fbcfe8;font-size:11px;font-weight:700;padding:3px 9px;border-radius:6px;display:inline-flex;align-items:center;gap:4px">
                                    <span>🎬 Direct Video</span>
                                </span>
                            @else
                                <span style="background:{{ $post->media_type === 'reel' ? '#fce8f3' : '#e0f2fe' }};color:{{ $post->media_type === 'reel' ? '#be185d' : '#0369a1' }};font-size:11px;font-weight:700;padding:3px 8px;border-radius:4px;text-transform:uppercase">
                                    {{ $post->media_type === 'reel' ? '▶ Reel' : '📷 Post' }}
                                </span>
                            @endif
                        </div>

                        {{-- Embedded Player Area --}}
                        @if($post->isVideoUpload())
                            {{-- Native HTML5 Video Player with Autoplay & Sound Toggle --}}
                            <div class="bhudev-video-container" style="background:#0a0806;min-height:440px;max-height:560px;display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden">
                                <video 
                                    class="bhudev-reel-video" 
                                    src="{{ $post->getVideoUrl() }}" 
                                    {{ $siteAutoplayDefault && $post->is_autoplay ? 'autoplay' : '' }}
                                    muted 
                                    loop 
                                    playsinline 
                                    controls 
                                    controlsList="nodownload"
                                    preload="metadata"
                                    data-autoplay="{{ $siteAutoplayDefault && $post->is_autoplay ? '1' : '0' }}"
                                    style="width:100%;height:100%;max-height:560px;object-fit:contain;background:#000;display:block"
                                ></video>

                                {{-- Floating Unmute / Sound Toggle Overlay Button --}}
                                <button type="button" class="reel-sound-toggle-btn" onclick="toggleReelAudio(this)" style="position:absolute;bottom:14px;right:14px;background:rgba(20,16,14,0.78);backdrop-filter:blur(8px);-webkit-backdrop-filter:blur(8px);color:#fff;border:1px solid rgba(255,255,255,0.3);border-radius:999px;padding:6px 14px;font-size:12px;font-weight:600;display:flex;align-items:center;gap:6px;cursor:pointer;z-index:10;box-shadow:0 4px 12px rgba(0,0,0,0.35);transition:transform 0.15s, background 0.15s">
                                    <span class="sound-icon">🔇</span>
                                    <span class="sound-label">Tap for Sound</span>
                                </button>

                                {{-- Autoplay Indicator Badge --}}
                                <div class="reel-autoplay-badge" style="position:absolute;top:12px;left:12px;background:rgba(0,0,0,0.65);backdrop-filter:blur(6px);color:#34d399;border:1px solid rgba(52,211,153,0.35);border-radius:6px;padding:4px 9px;font-size:10.5px;font-weight:700;display:flex;align-items:center;gap:5px;z-index:10;letter-spacing:0.5px;text-transform:uppercase">
                                    <span style="display:inline-block;width:6px;height:6px;border-radius:50%;background:#34d399;box-shadow:0 0 6px #34d399"></span>
                                    <span>Auto-Playing</span>
                                </div>
                            </div>
                        @else
                            {{-- Embedded Instagram Card/Frame --}}
                            <div style="background:#faf7f2;min-height:380px;display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden">
                                <iframe 
                                    src="{{ $post->getEmbedUrl() }}" 
                                    style="width:100%;min-height:440px;border:none;overflow:hidden" 
                                    scrolling="no" 
                                    frameborder="0" 
                                    allowtransparency="true" 
                                    allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"
                                    title="Instagram {{ $post->media_type }}">
                                </iframe>
                            </div>
                        @endif

                        {{-- Caption & Action Footer --}}
                        <div style="padding:16px 18px;display:flex;flex-direction:column;gap:12px;flex:1;justify-content:space-between;background:#fff">
                            @if($post->caption)
                                <div style="font-size:13.5px;color:#453a30;line-height:1.55">
                                    {{ $post->caption }}
                                </div>
                            @endif

                            <div style="display:flex;align-items:center;justify-content:space-between;padding-top:10px;border-top:1px solid #f5ede4;flex-wrap:wrap;gap:10px">
                                @if(!$post->isVideoUpload())
                                    <a href="{{ $post->url }}" target="_blank" rel="noopener noreferrer" style="display:inline-flex;align-items:center;gap:6px;font-size:13px;color:#e1306c;font-weight:700;text-decoration:none">
                                        <span>Watch on Instagram</span>
                                        <span>↗</span>
                                    </a>
                                @else
                                    <span style="display:inline-flex;align-items:center;gap:6px;font-size:12.5px;color:#875200;font-weight:600">
                                        <span>⚡ Zero-Redirect Playback</span>
                                    </span>
                                @endif

                                <a href="{{ $domain->generateWhatsAppUrl('Namaste Shastriji, I watched your sacred ' . ($post->isVideoUpload() ? 'Ceremony Video' : 'Instagram ' . ucfirst($post->media_type)) . ' on your website and would like to inquire about this ceremony.') }}" target="_blank" style="display:inline-flex;align-items:center;gap:6px;font-size:12px;color:#1e7e34;font-weight:600;text-decoration:none">
                                    <span>💬 Book Similar Vidhi</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <style>
        @keyframes pulseDot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.15); }
        }
        .reel-sound-toggle-btn:hover {
            transform: scale(1.05);
            background: rgba(20,16,14,0.95) !important;
        }
        .bhudev-reel-card:hover {
            transform: translateY(-2px);
        }
    </style>

    <script>
        (function() {
            var siteAutoplayActive = {{ $siteAutoplayDefault ? 'true' : 'false' }};

            // Toggle Audio for a specific video card
            window.toggleReelAudio = function(btn) {
                var container = btn.closest('.bhudev-video-container');
                if (!container) return;
                var video = container.querySelector('video');
                if (!video) return;

                video.muted = !video.muted;
                var icon = btn.querySelector('.sound-icon');
                var label = btn.querySelector('.sound-label');

                if (video.muted) {
                    if (icon) icon.textContent = '🔇';
                    if (label) label.textContent = 'Tap for Sound';
                    btn.style.borderColor = 'rgba(255,255,255,0.3)';
                } else {
                    if (icon) icon.textContent = '🔊';
                    if (label) label.textContent = 'Sound ON';
                    btn.style.borderColor = '#10b981';
                    if (video.paused) {
                        video.play().catch(function() {});
                    }
                }
            };

            // Toggle Site Autoplay Mode
            window.toggleSiteAutoplay = function() {
                siteAutoplayActive = !siteAutoplayActive;
                var btn = document.getElementById('autoplayToggleBtn');
                var dot = document.getElementById('autoplayStatusDot');
                var label = document.getElementById('autoplayToggleLabel');
                var icon = document.getElementById('autoplayToggleIcon');
                var videos = document.querySelectorAll('.bhudev-reel-video');

                if (siteAutoplayActive) {
                    if (btn) {
                        btn.style.background = '#fce8f3';
                        btn.style.color = '#be185d';
                        btn.style.borderColor = '#fbcfe8';
                    }
                    if (dot) {
                        dot.style.background = '#10b981';
                        dot.style.boxShadow = '0 0 8px #10b981';
                    }
                    if (icon) icon.textContent = '⚡';
                    if (label) label.textContent = 'Auto-Play ON';

                    // Update badges
                    document.querySelectorAll('.reel-autoplay-badge').forEach(function(badge) {
                        badge.style.display = 'flex';
                    });

                    // Resume playing visible videos
                    checkVideosInView();
                } else {
                    if (btn) {
                        btn.style.background = '#f3f4f6';
                        btn.style.color = '#4b5563';
                        btn.style.borderColor = '#d1d5db';
                    }
                    if (dot) {
                        dot.style.background = '#9ca3af';
                        dot.style.boxShadow = 'none';
                    }
                    if (icon) icon.textContent = '⏸️';
                    if (label) label.textContent = 'Auto-Play PAUSED';

                    // Hide autoplay badges and pause all videos
                    document.querySelectorAll('.reel-autoplay-badge').forEach(function(badge) {
                        badge.style.display = 'none';
                    });
                    videos.forEach(function(v) {
                        v.pause();
                    });
                }
            };

            // IntersectionObserver for smart smooth Auto-Play on scroll
            function initVideoObserver() {
                var videos = document.querySelectorAll('.bhudev-reel-video');
                if (!videos.length) return;

                if ('IntersectionObserver' in window) {
                    var observer = new IntersectionObserver(function(entries) {
                        entries.forEach(function(entry) {
                            var video = entry.target;
                            if (entry.isIntersecting && siteAutoplayActive) {
                                var playPromise = video.play();
                                if (playPromise !== undefined) {
                                    playPromise.catch(function(err) {
                                        // Autoplay policy prevented playback, video muted handles this
                                        video.muted = true;
                                        video.play().catch(function() {});
                                    });
                                }
                            } else {
                                video.pause();
                            }
                        });
                    }, { threshold: 0.4 });

                    videos.forEach(function(video) {
                        observer.observe(video);
                    });
                }
            }

            function checkVideosInView() {
                var videos = document.querySelectorAll('.bhudev-reel-video');
                videos.forEach(function(video) {
                    var rect = video.getBoundingClientRect();
                    var inView = (rect.top >= 0 && rect.bottom <= (window.innerHeight || document.documentElement.clientHeight)) ||
                                 (rect.top < window.innerHeight && rect.bottom >= 0);
                    if (inView && siteAutoplayActive) {
                        video.play().catch(function() {});
                    }
                });
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initVideoObserver);
            } else {
                initVideoObserver();
            }
        })();
    </script>
@endif

{{-- DAKSHINA & UPI QR CODE SECTION (ONLY SHOWN ON PERSONAL DOMAIN IF SET) --}}
@if($domain->dakshina_qr_path || $domain->upi_id)
    <section id="dakshina" style="padding:60px 0;background:#fffcf7;border-top:1px solid #ebd9c4">
        <div style="max-width:800px;margin:0 auto;padding:0 24px;text-align:center">
            <span style="color:#b94e20;font-weight:700;font-size:12px;letter-spacing:1px;text-transform:uppercase">DIRECT DAKSHINA &amp; SEVA</span>
            <h2 style="font-family:'Playfair Display',serif;font-size:30px;color:#221a14;margin:8px 0 10px">
                Offer Dakshina Directly to Shastri Ji
            </h2>
            <p style="color:#6d5f53;font-size:15px;margin:0 0 32px">
                Scan via any UPI App (Google Pay, PhonePe, Paytm, BHIM) to offer sacred Dakshina directly to Shastri Ji's bank account.
            </p>

            <div style="background:#fff;border:2px solid #d5a45c;border-radius:18px;padding:32px;display:inline-block;box-shadow:0 8px 24px rgba(41,36,31,0.08);max-width:400px;width:100%;box-sizing:border-box">
                @if($domain->dakshina_qr_path)
                    <div style="background:#fff;border:1px solid #eedecf;border-radius:12px;padding:12px;display:inline-block;margin-bottom:18px;box-shadow:0 4px 12px rgba(0,0,0,0.04)">
                        <img src="{{ asset('storage/' . $domain->dakshina_qr_path) }}" alt="Dakshina QR Code" style="width:220px;height:220px;object-fit:contain;display:block">
                    </div>
                @endif

                @if($domain->upi_id)
                    <div style="background:#fdf5ec;border:1px dashed #d5a45c;border-radius:8px;padding:10px 14px;margin-bottom:14px;display:flex;align-items:center;justify-content:space-between;gap:8px">
                        <span style="font-size:13px;color:#875200;font-weight:600">UPI ID: <strong>{{ $domain->upi_id }}</strong></span>
                        <button type="button" onclick="navigator.clipboard.writeText('{{ $domain->upi_id }}'); alert('UPI ID copied: {{ $domain->upi_id }}');" style="background:#b94e20;color:#fff;border:none;padding:5px 10px;border-radius:4px;font-size:11px;cursor:pointer;font-weight:600">
                            📋 Copy
                        </button>
                    </div>
                @endif

                <div style="display:flex;align-items:center;justify-content:center;gap:6px;color:#1e5e29;font-size:12px;font-weight:600">
                    <span>✓</span> 100% Direct to Shastri Ji • 0% Platform Commission
                </div>
            </div>
        </div>
    </section>
@endif

{{-- DIRECT BOOKING & INQUIRY SECTION --}}
<section id="contact" style="padding:80px 0;background:linear-gradient(135deg, #241c16 0%, #17120e 100%);color:#fff">
    <div style="max-width:960px;margin:0 auto;padding:0 24px">
        <div style="text-align:center;margin-bottom:40px">
            <span style="color:#d5a45c;font-weight:700;font-size:12px;letter-spacing:1.5px;text-transform:uppercase">BOOK A CEREMONY</span>
            <h2 style="font-family:'Playfair Display',serif;font-size:34px;color:#fff;margin:8px 0 10px">
                Connect Directly with {{ $domain->hero_title ?? $pandit->display_name }}
            </h2>
            <p style="color:#baa99a;font-size:15px;margin:0 auto;max-width:540px">
                Fill the quick form below or click to chat directly on WhatsApp. No account or password required.
            </p>
        </div>

        <div style="background:#2e241c;border:1px solid #4a3c30;border-radius:18px;padding:36px;box-shadow:0 8px 32px rgba(0,0,0,0.3)">
            <form action="{{ route('pandit.inquiry.handle') }}" method="POST">
                @csrf
                <div class="pandit-form-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px">
                    <div>
                        <label style="display:block;color:#e8ded5;font-size:13px;font-weight:600;margin-bottom:6px">Your Full Name *</label>
                        <input type="text" name="name" required placeholder="e.g. Rajeshbhai Patel" style="width:100%;padding:12px 14px;border-radius:8px;border:1px solid #544537;background:#201812;color:#fff;font-size:14px;box-sizing:border-box">
                    </div>
                    <div>
                        <label style="display:block;color:#e8ded5;font-size:13px;font-weight:600;margin-bottom:6px">WhatsApp / Mobile Number *</label>
                        <input type="tel" name="phone" required placeholder="e.g. 9876543210" style="width:100%;padding:12px 14px;border-radius:8px;border:1px solid #544537;background:#201812;color:#fff;font-size:14px;box-sizing:border-box">
                    </div>
                </div>

                <div class="pandit-form-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px">
                    <div>
                        <label style="display:block;color:#e8ded5;font-size:13px;font-weight:600;margin-bottom:6px">Puja / Ceremony Desired</label>
                        <select name="pooja_service" style="width:100%;padding:12px 14px;border-radius:8px;border:1px solid #544537;background:#201812;color:#fff;font-size:14px;box-sizing:border-box">
                            <option value="Satyanarayan Katha">Satyanarayan Katha & Puja</option>
                            <option value="Griha Pravesh & Vastu">Griha Pravesh & Vastu Shanti</option>
                            <option value="Rudrabhishek">Rudrabhishek & Shiva Puja</option>
                            <option value="Vivah Sanskar">Vivah Sanskar (Wedding Ceremony)</option>
                            <option value="Navchandi Yagya">Navchandi Yagya / Chandi Path</option>
                            <option value="Kundali & Jyotish">Kundali & Jyotish Consultation</option>
                            <option value="Other Vedic Puja">Other Vedic Puja</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block;color:#e8ded5;font-size:13px;font-weight:600;margin-bottom:6px">Preferred Ceremony Date</label>
                        <input type="date" name="preferred_date" style="width:100%;padding:12px 14px;border-radius:8px;border:1px solid #544537;background:#201812;color:#fff;font-size:14px;box-sizing:border-box">
                    </div>
                </div>

                <div style="margin-bottom:24px">
                    <label style="display:block;color:#e8ded5;font-size:13px;font-weight:600;margin-bottom:6px">Your City & Specific Requirements</label>
                    <textarea name="message" rows="3" placeholder="Mention your city/area and any specific Muhurat or Samagri questions..." style="width:100%;padding:12px 14px;border-radius:8px;border:1px solid #544537;background:#201812;color:#fff;font-size:14px;box-sizing:border-box"></textarea>
                </div>

                <div style="display:flex;flex-wrap:wrap;gap:16px;align-items:center;justify-content:space-between">
                    <button type="submit" class="btn-whatsapp-header" style="font-size:16px;padding:14px 28px;border:none;cursor:pointer">
                        <span>💬 Confirm & Connect on WhatsApp</span>
                    </button>

                    <div style="font-size:12px;color:#baa99a">
                        🔒 Direct connection with Shastri Ji. Zero middleman fees.
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
