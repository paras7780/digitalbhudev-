@extends('layouts.app')

@section('title', $profile->seo_title ?: $profile->display_name . ' | DigitalBhudev')
@section('meta_description', $profile->seo_description ?: \Illuminate\Support\Str::limit(strip_tags($profile->bio ?: 'Connect with ' . $profile->display_name . ' on DigitalBhudev for Vedic pujas, havans, and rituals.'), 155))

@section('content')
<main>
    <section class="page-hero">
        <div class="container">
            <span class="eyebrow">VERIFIED BHUDEV PROFILE</span>
            <h1>{{ $profile->display_name }}</h1>
            <p>{{ collect([$profile->city, $profile->state])->filter()->join(', ') }} &bull; Available for Vedic Ceremonies</p>
        </div>
    </section>

    <section class="content-section">
        <div class="container">
            @if(session('status'))
                <div style="background:#eaf5ea;border:1px solid #bce1c5;border-radius:8px;padding:14px 20px;margin-bottom:28px;color:#216a30;font-size:14px;display:flex;align-items:center;gap:10px">
                    <span style="font-size:18px">✓</span>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <div class="bhudev-profile-grid">
                {{-- Left Column: Comprehensive Profile Information --}}
                <div>
                    {{-- Profile Header Card --}}
                    <div style="background:var(--card-bg);border:1px solid var(--card-border);border-radius:var(--radius-lg);padding:28px;box-shadow:var(--shadow);margin-bottom:28px">
                        <div style="display:flex;gap:24px;align-items:flex-start;flex-wrap:wrap">
                            @if($profile->photo_path)
                                <img src="{{ asset('storage/' . $profile->photo_path) }}" alt="{{ $profile->display_name }}" style="width:120px;height:120px;border-radius:50%;object-fit:cover;border:3px solid var(--primary-border);flex-shrink:0;box-shadow:0 4px 14px rgba(219,39,119,0.12)">
                            @else
                                <div style="width:120px;height:120px;border-radius:50%;background:var(--primary-soft);display:flex;align-items:center;justify-content:center;font-size:42px;color:var(--primary);font-weight:700;border:3px solid var(--primary-border);flex-shrink:0">
                                    {{ strtoupper(substr($profile->display_name, 0, 1)) }}
                                </div>
                            @endif

                            <div style="flex:1;min-width:240px">
                                <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;flex-wrap:wrap">
                                    <h2 style="font-family:'Playfair Display',serif;font-size:26px;color:var(--heading);margin:0">
                                        {{ $profile->display_name }}
                                    </h2>
                                    <span style="background:var(--green-soft);color:var(--green);border:1px solid var(--green-border);border-radius:12px;padding:2px 10px;font-size:11px;font-weight:700;display:inline-flex;align-items:center;gap:4px">
                                        ✓ Verified Bhudev
                                    </span>
                                </div>

                                <p style="color:var(--muted);font-size:14px;margin:0 0 12px">
                                    📍 {{ collect([$profile->city, $profile->state])->filter()->join(', ') }}
                                    @if($profile->service_area)
                                        &bull; <span style="color:var(--primary);font-weight:600">Travel Area: {{ $profile->service_area }}</span>
                                    @endif
                                </p>

                                <div style="display:flex;gap:8px;flex-wrap:wrap">
                                    @if($profile->experience_years)
                                        <span style="background:var(--primary-soft);color:var(--primary);border:1px solid var(--primary-border);border-radius:var(--radius-full);padding:4px 12px;font-size:12px;font-weight:600">
                                            🕉️ {{ $profile->experience_years }}+ Years Experience
                                        </span>
                                    @endif
                                    @if($profile->availability)
                                        <span style="background:var(--green-soft);color:var(--green);border:1px solid var(--green-border);border-radius:var(--radius-full);padding:4px 12px;font-size:12px;font-weight:600">
                                            ⏱️ {{ $profile->availability }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if(!empty($profile->languages))
                            <div style="margin-top:20px;padding-top:16px;border-top:1px solid var(--line);font-size:13px;display:flex;align-items:center;gap:8px">
                                <strong style="color:var(--heading)">Languages Known:</strong>
                                <span style="color:var(--ink)">{{ implode(', ', $profile->languages) }}</span>
                            </div>
                        @endif
                    </div>

                    {{-- Biography / Lineage --}}
                    @if($profile->bio)
                        <div style="background:var(--card-bg);border:1px solid var(--card-border);border-radius:var(--radius-lg);padding:28px;box-shadow:var(--shadow);margin-bottom:28px">
                            <h3 style="font-family:'Playfair Display',serif;font-size:20px;color:var(--heading);margin:0 0 14px">
                                About & Lineage
                            </h3>
                            <div style="color:var(--ink);font-size:14px;line-height:1.8;white-space:pre-line">
                                {{ $profile->bio }}
                            </div>
                        </div>
                    @endif

                    {{-- Vedic Qualifications & Expertise --}}
                    @if($profile->qualification || $profile->expertise)
                        <div style="background:var(--card-bg);border:1px solid var(--card-border);border-radius:var(--radius-lg);padding:28px;box-shadow:var(--shadow);margin-bottom:28px">
                            <h3 style="font-family:'Playfair Display',serif;font-size:20px;color:var(--heading);margin:0 0 16px">
                                Qualifications & Expertise
                            </h3>

                            @if($profile->qualification)
                                <div style="margin-bottom:18px">
                                    <strong style="display:block;font-size:13px;color:var(--primary);text-transform:uppercase;letter-spacing:0.04em;margin-bottom:4px">
                                        🎓 Vedic Education & Lineage
                                    </strong>
                                    <p style="color:var(--ink);font-size:14px;line-height:1.6;margin:0">
                                        {{ $profile->qualification }}
                                    </p>
                                </div>
                            @endif

                            @if($profile->expertise)
                                <div>
                                    <strong style="display:block;font-size:13px;color:var(--primary);text-transform:uppercase;letter-spacing:0.04em;margin-bottom:4px">
                                        📜 Ritual Specializations
                                    </strong>
                                    <p style="color:var(--ink);font-size:14px;line-height:1.6;margin:0">
                                        {{ $profile->expertise }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Pooja Services Offered --}}
                    @if($profile->services->isNotEmpty())
                        <div style="background:var(--card-bg);border:1px solid var(--card-border);border-radius:var(--radius-lg);padding:28px;box-shadow:var(--shadow);margin-bottom:28px">
                            <h3 style="font-family:'Playfair Display',serif;font-size:20px;color:var(--heading);margin:0 0 16px">
                                Ceremonies & Pooja Services Conducted
                            </h3>
                            <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(220px, 1fr));gap:12px">
                                @foreach($profile->services as $service)
                                    <div style="background:var(--primary-soft);border:1px solid var(--primary-border);border-radius:var(--radius-sm);padding:12px 14px;display:flex;align-items:center;gap:10px">
                                        <span style="font-size:18px">🪔</span>
                                        <div>
                                            <strong style="font-size:13px;color:var(--heading);display:block">{{ $service->name }}</strong>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Ceremony Photos Gallery --}}
                    @if($profile->publicImages->isNotEmpty())
                        <div style="background:var(--card-bg);border:1px solid var(--card-border);border-radius:var(--radius-lg);padding:28px;box-shadow:var(--shadow);margin-bottom:28px">
                            <h3 style="font-family:'Playfair Display',serif;font-size:20px;color:var(--heading);margin:0 0 16px">
                                Ceremony Gallery ({{ $profile->publicImages->count() }} Photos)
                            </h3>
                            <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(180px, 1fr));gap:14px">
                                @foreach($profile->publicImages as $img)
                                    <div style="border-radius:var(--radius-sm);overflow:hidden;box-shadow:var(--shadow-sm);background:var(--primary-soft)">
                                        <img src="{{ asset('storage/' . $img->path) }}" alt="{{ $img->alt_text ?: $profile->display_name . ' ceremony photo' }}" loading="lazy" style="width:100%;height:160px;object-fit:cover;display:block">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Back link --}}
                    <div style="margin-top:20px">
                        <a class="btn btn-light" href="{{ route('bhudevs.index') }}">
                            &larr; Back to all Bhudevs
                        </a>
                    </div>
                </div>

                {{-- Right Column: Sticky Booking Card & Direct WhatsApp Connect --}}
                <div style="position:sticky;top:20px;display:flex;flex-direction:column;gap:20px">
                    {{-- Direct Action Card --}}
                    <div style="background:var(--card-bg);border:1.5px solid var(--primary-border);border-radius:var(--radius-lg);padding:26px;box-shadow:var(--shadow)">
                        <div style="text-align:center;margin-bottom:20px">
                            <span style="font-size:32px">🕉️</span>
                            <h3 style="font-family:'Playfair Display',serif;font-size:22px;margin:6px 0 4px;color:var(--heading)">
                                Book {{ $profile->display_name }}
                            </h3>
                            <p style="color:var(--muted);font-size:13px;margin:0">
                                Send a direct inquiry or chat on WhatsApp to check availability.
                            </p>
                        </div>

                        {{-- Direct WhatsApp Connect Button --}}
                        @php
                            $targetPhone = ($profile->show_mobile && $profile->user->phone)
                                ? preg_replace('/[^0-9]/', '', $profile->user->phone)
                                : '919876543210';
                            if (strlen($targetPhone) === 10) {
                                $targetPhone = '91' . $targetPhone;
                            }
                            $waMessage = "Namaste Panditji ({$profile->display_name}),\n"
                                . "I saw your profile on DigitalBhudev and would like to inquire about booking a Vedic ceremony in {$profile->city}.\n"
                                . "Profile reference: " . route('bhudevs.show', $profile->slug);
                        @endphp
                        <a href="https://api.whatsapp.com/send?phone={{ $targetPhone }}&text={{ urlencode($waMessage) }}" target="_blank" class="btn" style="background:#25d366;color:#ffffff;border:none;width:100%;padding:13px;font-size:15px;font-weight:700;display:flex;align-items:center;justify-content:center;gap:10px;border-radius:var(--radius-full);margin-bottom:12px;box-shadow:0 4px 14px rgba(37,211,102,0.3)">
                            <span style="font-size:20px">💬</span> Chat on WhatsApp
                        </a>

                        {{-- Direct Call Button if Phone Visible --}}
                        @if($profile->show_mobile && $profile->user->phone)
                            <a href="tel:{{ $profile->user->phone }}" class="btn btn-light" style="width:100%;padding:11px;font-size:14px;font-weight:600;display:flex;align-items:center;justify-content:center;gap:8px;margin-bottom:20px;border:1px solid var(--line);border-radius:var(--radius-full)">
                                <span>📞</span> Call: {{ $profile->user->phone }}
                            </a>
                        @endif

                        <div style="position:relative;text-align:center;margin:18px 0">
                            <span style="background:var(--card-bg);padding:0 12px;color:var(--muted);font-size:12px;font-weight:600;position:relative;z-index:2">OR SUBMIT INQUIRY</span>
                            <div style="position:absolute;top:50%;left:0;right:0;height:1px;background:var(--line);z-index:1"></div>
                        </div>

                        {{-- Direct Booking Form --}}
                        <form method="POST" action="{{ route('booking.store') }}">
                            @csrf
                            <input type="hidden" name="bhudev_profile_id" value="{{ $profile->id }}">

                            <div style="margin-bottom:12px">
                                <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;color:var(--heading)">Your Name <span style="color:var(--primary)">*</span></label>
                                <input type="text" name="name" required placeholder="e.g. Bhavesh Shah" style="width:100%;padding:9px 12px;border:1px solid var(--line);border-radius:var(--radius-sm);font-size:13px">
                            </div>

                            <div style="margin-bottom:12px">
                                <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;color:var(--heading)">WhatsApp / Phone Number <span style="color:var(--primary)">*</span></label>
                                <input type="text" name="phone" required placeholder="e.g. 98250 12345" style="width:100%;padding:9px 12px;border:1px solid var(--line);border-radius:var(--radius-sm);font-size:13px">
                            </div>

                            <div style="margin-bottom:12px">
                                <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;color:var(--heading)">Ceremony Needed <span style="color:var(--primary)">*</span></label>
                                <select name="pooja_service" required style="width:100%;padding:9px 12px;border:1px solid var(--line);border-radius:var(--radius-sm);font-size:13px;background:#fff">
                                    <option value="">Select ceremony / puja</option>
                                    @if($profile->services->isNotEmpty())
                                        @foreach($profile->services as $srv)
                                            <option value="{{ $srv->name }}">{{ $srv->name }}</option>
                                        @endforeach
                                    @endif
                                    <option value="Satyanarayan Katha">Satyanarayan Katha</option>
                                    <option value="Griha Pravesh / Vastu">Griha Pravesh / Vastu</option>
                                    <option value="Rudrabhishek">Rudrabhishek</option>
                                    <option value="Vivah Sanskar (Marriage)">Vivah Sanskar (Marriage)</option>
                                    <option value="Navchandi Yajna">Navchandi Yajna</option>
                                    <option value="Other Vedic Ritual">Other Vedic Ritual</option>
                                </select>
                            </div>

                            <div style="margin-bottom:12px">
                                <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;color:var(--heading)">Preferred Date</label>
                                <input type="date" name="preferred_date" style="width:100%;padding:9px 12px;border:1px solid var(--line);border-radius:var(--radius-sm);font-size:13px">
                            </div>

                            <div style="margin-bottom:16px">
                                <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;color:var(--heading)">Message / Venue Details</label>
                                <textarea name="message" rows="2" placeholder="e.g. Need ceremony at home in {{ $profile->city ?: 'our city' }}" style="width:100%;padding:9px 12px;border:1px solid var(--line);border-radius:var(--radius-sm);font-size:13px"></textarea>
                            </div>

                            {{-- Honeypot --}}
                            <input type="text" name="website" style="display:none" tabindex="-1" autocomplete="off">

                            <button type="submit" class="btn btn-primary" style="width:100%;padding:11px;font-size:14px;font-weight:600">
                                Send Booking Inquiry &rarr;
                            </button>
                        </form>
                    </div>

                    {{-- Trust Guarantees Card --}}
                    <div style="background:var(--blue-soft);border:1px solid var(--blue-border);border-radius:var(--radius-md);padding:20px;font-size:13px;color:#1e3a8a">
                        <strong style="display:block;color:#1e40af;font-size:14px;margin-bottom:8px">Why Book Through DigitalBhudev?</strong>
                        <ul style="margin:0;padding-left:18px;line-height:1.7">
                            <li>Verified Brahmin priests with traditional Vedic background.</li>
                            <li>No middleman or broker commissions.</li>
                            <li>Direct phone and WhatsApp connection.</li>
                            <li>Authentic rituals performed with proper Vidhi.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
