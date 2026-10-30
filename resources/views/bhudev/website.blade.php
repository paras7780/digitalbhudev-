@extends('layouts.bhudev')

@section('title', 'My Personal Website & Domain | Bhudev Portal')

@section('breadcrumbs')
    <span class="sep">/</span>
    <strong>My Personal Website</strong>
@endsection

@section('content')
<div class="admin-page-header">
    <div class="admin-page-title">
        <h1>My Personal Website & Domain</h1>
        <p>Get your own independent, white-labeled website (e.g., <code>shastriparas.in</code>). Receive devotee bookings directly on your WhatsApp with zero commission.</p>
    </div>
    @if($activeDomain)
        <div class="admin-page-actions">
            <a href="http://{{ $activeDomain->domain_name }}" target="_blank" class="btn btn-primary" style="background:#25d366;border-color:#25d366">
                🌐 Visit My Live Website ↗
            </a>
        </div>
    @endif
</div>

{{-- SUSPENDED DOMAIN NOTICE --}}
@if($latestDomainRequest && $latestDomainRequest->status === 'suspended')
    <div style="background:#fdf2f2;border:1px solid #f8c9c9;border-left:5px solid #a82020;border-radius:10px;padding:24px;margin-bottom:28px">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:16px">
            <div>
                <span style="background:#a82020;color:#fff;padding:3px 10px;border-radius:999px;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px">
                    ⏸️ WEBSITE TEMPORARILY SUSPENDED
                </span>
                <h2 style="font-family:'Playfair Display',serif;font-size:24px;color:#a82020;margin:8px 0 6px">
                    {{ $latestDomainRequest->domain_name }}
                </h2>
                <div style="background:#fff;border:1px solid #f2b8b8;border-radius:6px;padding:12px 16px;margin:12px 0;color:#901d1d;font-size:14px">
                    <strong>Suspension Reason:</strong> {{ $latestDomainRequest->suspension_reason ?: 'Annual hosting & domain renewal pending' }}
                </div>
                <p style="margin:0;color:#7a1b1b;font-size:13px;line-height:1.5">
                    Your personal website is currently paused, and devotees cannot submit ceremony bookings. Please contact the administration on WhatsApp to clear pending dues and reactivate immediately.
                </p>
            </div>
            <div>
                <a href="https://api.whatsapp.com/send?phone=919876543210&text={{ urlencode('Namaste DigitalBhudev Admin, my website ' . $latestDomainRequest->domain_name . ' is suspended. Reason: ' . ($latestDomainRequest->suspension_reason ?: 'Renewal') . '. Please assist me to reactivate.') }}" target="_blank" class="btn btn-primary" style="background:#25d366;border-color:#25d366">
                    💬 WhatsApp Admin to Reactivate
                </a>
            </div>
        </div>
    </div>
@endif

{{-- ACTIVE DOMAIN BANNER --}}
@if($activeDomain)
    <div style="background:#f0faf2;border:1px solid #bce1c5;border-left:5px solid #2e6930;border-radius:10px;padding:24px;margin-bottom:28px">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:16px">
            <div>
                <span style="background:#2e6930;color:#fff;padding:3px 10px;border-radius:999px;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px">
                    ● WEBSITE LIVE
                </span>
                <h2 style="font-family:'Playfair Display',serif;font-size:24px;color:#1e5e29;margin:8px 0 6px">
                    {{ $activeDomain->domain_name }}
                </h2>
                <p style="margin:0;color:#285e33;font-size:14px;line-height:1.6">
                    Devotee bookings on your domain are routed directly to WhatsApp: <strong>+{{ $activeDomain->getEffectiveWhatsAppNumber() }}</strong>.
                </p>
            </div>
            <div>
                <a href="{{ route('home') }}?preview_domain={{ $activeDomain->domain_name }}" target="_blank" class="btn btn-outline" style="background:#fff">
                    👁️ Preview In App
                </a>
            </div>
        </div>

        <div style="margin-top:20px;padding-top:16px;border-top:1px solid #c8e6ce;display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:16px;font-size:13px;color:#285e33">
            <div>
                <span style="color:#5e7e64;display:block">Hero Headline:</span>
                <strong>{{ $activeDomain->hero_title ?: $profile->display_name }}</strong>
            </div>
            <div>
                <span style="color:#5e7e64;display:block">Service Tier:</span>
                <strong>{{ strtoupper(str_replace('_', ' ', $activeDomain->tier)) }}</strong>
            </div>
            <div>
                <span style="color:#5e7e64;display:block">Commission Rate:</span>
                <strong style="color:#1e5e29">0% (You keep 100% of Dakshina)</strong>
            </div>
        </div>
    </div>

    {{-- LIVE WEBSITE CONTENT & INTRODUCTION EDITOR --}}
    <div style="background:var(--card-bg);border:1px solid var(--card-border);border-radius:var(--radius-lg);padding:28px;margin-bottom:28px;box-shadow:var(--shadow)">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:10px">
            <div>
                <h3 style="font-family:'Playfair Display',serif;font-size:20px;color:var(--heading);margin:0 0 4px">
                    📝 Personal Introduction &amp; Live Website Content
                </h3>
                <p style="margin:0;color:var(--muted);font-size:13px">
                    The details below are currently displayed on your personal website (<code>{{ $activeDomain->domain_name }}</code>). You can update your introduction and details anytime.
                </p>
            </div>
            <a href="{{ route('home') }}?preview_domain={{ $activeDomain->domain_name }}" target="_blank" class="btn btn-sm btn-outline">
                👁️ View Live Changes ↗
            </a>
        </div>

        <form action="{{ route('bhudev.website.content') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:16px">
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:var(--heading);margin-bottom:6px">
                        Hero Title (Your Name / Title on Homepage) *
                    </label>
                    <input type="text" name="hero_title" value="{{ old('hero_title', $activeDomain->hero_title ?: $profile->display_name) }}" required style="width:100%;padding:10px 12px;border:1px solid var(--line);border-radius:var(--radius-sm);font-size:14px;color:var(--ink);box-sizing:border-box">
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:var(--heading);margin-bottom:6px">
                        Hero Tagline *
                    </label>
                    <input type="text" name="hero_tagline" value="{{ old('hero_tagline', $activeDomain->hero_tagline ?: ($profile->experience_years ? $profile->experience_years . '+ Years of Sacred Vedic Tradition in ' . ($profile->city ?? 'Gujarat') : '')) }}" style="width:100%;padding:10px 12px;border:1px solid var(--line);border-radius:var(--radius-sm);font-size:14px;color:var(--ink);box-sizing:border-box">
                </div>
            </div>

            <div style="margin-bottom:16px">
                <label style="display:block;font-size:13px;font-weight:600;color:var(--heading);margin-bottom:6px">
                    About Shastri Ji / Personal Introduction (આપનો વિશેષ પરિચય) *
                </label>
                <textarea name="about_intro" rows="6" required placeholder="Type your personal introduction here..." style="width:100%;padding:12px;border:1px solid var(--line);border-radius:var(--radius-sm);font-size:14px;color:var(--ink);box-sizing:border-box;line-height:1.6">{{ old('about_intro', $activeDomain->about_intro ?: $profile->bio) }}</textarea>
                <small style="color:var(--muted);font-size:12px;display:block;margin-top:4px">
                    This introduction appears under the <strong>"About Shastri Ji"</strong> section on your personal website homepage.
                </small>
            </div>

            {{-- Dakshina / UPI QR Code Upload --}}
            <div style="background:var(--primary-soft);border:1px solid var(--primary-border);border-radius:var(--radius-md);padding:20px;margin-bottom:20px">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:14px">
                    <div>
                        <strong style="color:var(--primary);font-size:15px;display:flex;align-items:center;gap:6px">
                            <span>🪙</span> Dakshina UPI QR Code (Direct Devotee Offering)
                        </strong>
                        <p style="margin:4px 0 0;color:var(--muted);font-size:12px;line-height:1.5;max-width:560px">
                            Upload your GPay, PhonePe, Paytm, or BHIM QR code. Devotees on your website can scan and pay Dakshina directly to your account.
                            <strong style="color:var(--green);display:block;margin-top:2px">🔒 Note: This QR Code is ONLY shown on your personal website ({{ $activeDomain->domain_name }}) and never on public platform directories.</strong>
                        </p>
                    </div>

                    @if($activeDomain->dakshina_qr_path)
                        <div style="text-align:center">
                            <img src="{{ asset('storage/' . $activeDomain->dakshina_qr_path) }}" alt="Dakshina QR" style="width:90px;height:90px;object-fit:contain;border:2px solid var(--primary-border);border-radius:var(--radius-sm);background:#fff;padding:4px">
                            <small style="display:block;color:var(--green);font-weight:700;font-size:11px;margin-top:2px">✓ Active QR</small>
                        </div>
                    @endif
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                    <div>
                        <label style="display:block;font-size:12px;font-weight:600;color:var(--heading);margin-bottom:4px">
                            {{ $activeDomain->dakshina_qr_path ? 'Replace QR Code Image' : 'Upload QR Code Image' }} (JPG/PNG)
                        </label>
                        <input type="file" name="dakshina_qr" accept="image/*" style="width:100%;font-size:12px">
                    </div>
                    <div>
                        <label style="display:block;font-size:12px;font-weight:600;color:var(--heading);margin-bottom:4px">
                            Your UPI ID (Optional)
                        </label>
                        <input type="text" name="upi_id" value="{{ old('upi_id', $activeDomain->upi_id) }}" placeholder="e.g. shastriparas@okhdfcbank" style="width:100%;padding:8px 10px;border:1px solid var(--line);border-radius:var(--radius-sm);font-size:13px;color:var(--ink);box-sizing:border-box">
                    </div>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;align-items:flex-end">
                <div>
                    <label style="display:block;font-size:13px;font-weight:600;color:var(--heading);margin-bottom:6px">
                        WhatsApp Number for Leads *
                    </label>
                    <input type="tel" name="whatsapp_number" value="{{ old('whatsapp_number', $activeDomain->whatsapp_number ?: ($profile->user?->phone ?? '9876543210')) }}" required style="width:100%;padding:10px 12px;border:1px solid var(--line);border-radius:var(--radius-sm);font-size:14px;color:var(--ink);box-sizing:border-box">
                </div>
                <div style="text-align:right">
                    <button type="submit" class="btn btn-primary" style="padding:12px 24px;font-size:14px">
                        💾 Save &amp; Update Live Website
                    </button>
                </div>
            </div>
        </form>
    </div>
@elseif($latestDomainRequest)
    {{-- INQUIRY PROGRESS TRACKER --}}
    <div style="background:#fff;border:1px solid #ede4d5;border-radius:12px;padding:26px;margin-bottom:28px;box-shadow:0 4px 14px rgba(41,36,31,0.04)">
        <h3 style="font-family:'Playfair Display',serif;font-size:18px;color:#29241f;margin:0 0 16px">
            Website Request Status: <code>{{ $latestDomainRequest->domain_name }}</code>
        </h3>

        {{-- Visual Steps --}}
        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(140px, 1fr));gap:12px;margin-bottom:20px">
            @php
                $steps = [
                    'inquiry_submitted' => '1. Inquiry Received',
                    'quoted'            => '2. Price Quoted',
                    'paid'              => '3. Payment Done',
                    'dns_pending'       => '4. DNS Setup',
                    'active'            => '5. Live!',
                ];
                $statusOrder = ['inquiry_submitted', 'quoted', 'paid', 'dns_pending', 'active'];
                $currentIndex = array_search($latestDomainRequest->status, $statusOrder);
                if ($currentIndex === false) $currentIndex = 0;
            @endphp

            @foreach($steps as $key => $label)
                @php $idx = array_search($key, $statusOrder); @endphp
                <div style="text-align:center;padding:12px 8px;border-radius:8px;border:1px solid {{ $idx <= $currentIndex ? '#bce1c5' : '#eee' }};background:{{ $idx <= $currentIndex ? '#f0faf2' : '#fafafa' }};color:{{ $idx <= $currentIndex ? '#1e5e29' : '#888' }}">
                    <div style="font-weight:700;font-size:13px">{{ $label }}</div>
                    <div style="font-size:11px;margin-top:2px">
                        {{ $idx < $currentIndex ? '✓ Completed' : ($idx === $currentIndex ? '⏳ In Progress' : 'Pending') }}
                    </div>
                </div>
            @endforeach
        </div>

        @if($latestDomainRequest->status === 'quoted')
            <div style="background:#fff9eb;border:1px solid #fedb9b;border-radius:8px;padding:16px 20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
                <div>
                    <strong style="color:#875200;font-size:15px">Administration Quotation Ready:</strong>
                    <div style="color:#6d4810;font-size:14px;margin-top:4px">
                        Quoted Price: <strong style="font-size:18px;color:#b94e20">₹{{ number_format($latestDomainRequest->quoted_price, 2) }}</strong> (Setup & Annual Hosting)
                    </div>
                    @if($latestDomainRequest->admin_notes)
                        <div style="color:#6d4810;font-size:13px;margin-top:4px;font-style:italic">
                            Note from Admin: "{{ $latestDomainRequest->admin_notes }}"
                        </div>
                    @endif
                </div>
                <div>
                    <span style="font-size:13px;color:#875200">Our team will contact you on WhatsApp to confirm payment & activation.</span>
                </div>
            </div>
        @else
            <p style="margin:0;color:#5a5146;font-size:14px">
                Your request has been received by our technical team. We will review domain availability and contact you on WhatsApp: <strong>{{ $latestDomainRequest->whatsapp_number }}</strong>.
            </p>
        @endif
    </div>
@endif

{{-- VALUE PROP CARD & REQUEST FORM --}}
<div class="bhudev-two-col" style="display:grid;grid-template-columns:1.1fr 0.9fr;gap:28px">
    {{-- Form Column --}}
    <div style="background:#fff;border:1px solid #ede4d5;border-radius:12px;padding:30px;box-shadow:0 4px 18px rgba(41,36,31,0.04)">
        <h2 style="font-family:'Playfair Display',serif;font-size:20px;color:#29241f;margin:0 0 8px">
            {{ $latestDomainRequest ? 'Update or Request Another Domain' : 'Request Your Branded Website' }}
        </h2>
        <p style="color:#6b5a4d;font-size:13px;line-height:1.6;margin:0 0 22px">
            Tell us your desired domain name. Our administration will check availability, prepare your quotation, and connect it to your portal.
        </p>

        <form action="{{ route('bhudev.website.request') }}" method="POST">
            @csrf

            <div style="margin-bottom:16px">
                <label style="display:block;font-size:13px;font-weight:600;color:#29241f;margin-bottom:6px">
                    Desired Domain Name *
                </label>
                <div style="display:flex;align-items:center;background:#faf6f0;border:1px solid #dcd2c4;border-radius:6px;overflow:hidden">
                    <span style="padding:10px 12px;color:#7a6d5f;font-size:13px;background:#f2ebd9;border-right:1px solid #dcd2c4">www.</span>
                    <input type="text" name="requested_domain" value="{{ old('requested_domain', $latestDomainRequest?->domain_name ?? (strtolower(str_replace(' ', '', $profile->display_name)) . '.in')) }}" required placeholder="e.g. shastriparas.in" style="flex:1;border:none;background:transparent;padding:10px 12px;font-size:14px;color:#29241f;outline:none">
                </div>
                <small style="color:#7a6d5f;font-size:11px;display:block;margin-top:4px">We support .in, .com, .co.in or custom domains.</small>
            </div>

            <div style="margin-bottom:16px">
                <label style="display:block;font-size:13px;font-weight:600;color:#29241f;margin-bottom:6px">
                    Hero Title on Your Website
                </label>
                <input type="text" name="hero_title" value="{{ old('hero_title', $latestDomainRequest?->hero_title ?? $profile->display_name) }}" placeholder="e.g. Shastri Paras Rajyaguru" style="width:100%;padding:10px 12px;border:1px solid #dcd2c4;border-radius:6px;font-size:14px;color:#29241f;box-sizing:border-box">
                <small style="color:#7a6d5f;font-size:11px;display:block;margin-top:4px">This will appear in big sacred bold text at the top of your homepage.</small>
            </div>

            <div style="margin-bottom:16px">
                <label style="display:block;font-size:13px;font-weight:600;color:#29241f;margin-bottom:6px">
                    Hero Tagline
                </label>
                <input type="text" name="hero_tagline" value="{{ old('hero_tagline', $latestDomainRequest?->hero_tagline ?? ($profile->experience_years ? $profile->experience_years . '+ Years of Sacred Vedic Tradition in ' . ($profile->city ?? 'Gujarat') : '')) }}" placeholder="e.g. 20+ Years of Sacred Vedic Experience in Ahmedabad" style="width:100%;padding:10px 12px;border:1px solid #dcd2c4;border-radius:6px;font-size:14px;color:#29241f;box-sizing:border-box">
            </div>

            <div style="margin-bottom:16px">
                <label style="display:block;font-size:13px;font-weight:600;color:#29241f;margin-bottom:6px">
                    About Shastri Ji / Introduction for Your Website (આપનો વિશેષ પરિચય)
                </label>
                <textarea name="about_intro" rows="5" placeholder="Write an introduction about yourself, your Vedic lineage/Gotra, spiritual education, and the ceremonies you conduct. Devotees will read this on your personal website homepage..." style="width:100%;padding:10px 12px;border:1px solid #dcd2c4;border-radius:6px;font-size:13px;color:#29241f;box-sizing:border-box;line-height:1.6">{{ old('about_intro', $latestDomainRequest?->about_intro ?? $profile->bio) }}</textarea>
                <small style="color:#7a6d5f;font-size:11px;display:block;margin-top:4px">This introduction will be shown prominently to your devotees on your personal website.</small>
            </div>

            <div style="margin-bottom:16px">
                <label style="display:block;font-size:13px;font-weight:600;color:#29241f;margin-bottom:6px">
                    WhatsApp Number for Direct Inquiries *
                </label>
                <input type="tel" name="whatsapp_number" value="{{ old('whatsapp_number', $latestDomainRequest?->whatsapp_number ?? (auth()->user()->phone ?? auth()->user()->mobile)) }}" required placeholder="e.g. 9876543210" style="width:100%;padding:10px 12px;border:1px solid #dcd2c4;border-radius:6px;font-size:14px;color:#29241f;box-sizing:border-box">
                <small style="color:#7a6d5f;font-size:11px;display:block;margin-top:4px">Devotee inquiries submitted on your domain will open this WhatsApp number directly.</small>
            </div>

            <div style="margin-bottom:20px">
                <label style="display:block;font-size:13px;font-weight:600;color:#29241f;margin-bottom:6px">
                    Special Notes or Preferred Ceremonies
                </label>
                <textarea name="notes" rows="3" placeholder="Tell us if you want any specific pujas or images featured prominently..." style="width:100%;padding:10px 12px;border:1px solid #dcd2c4;border-radius:6px;font-size:13px;color:#29241f;box-sizing:border-box">{{ old('notes', $latestDomainRequest?->bhudev_notes) }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;padding:12px;font-size:15px;justify-content:center">
                🚀 Submit Website Request
            </button>
        </form>
    </div>

    {{-- Value Proposition Column --}}
    <div style="display:flex;flex-direction:column;gap:20px">
        <div style="background:#fff;border:1px solid #ede4d5;border-radius:12px;padding:26px;box-shadow:0 4px 18px rgba(41,36,31,0.04)">
            <h3 style="font-family:'Playfair Display',serif;font-size:18px;color:#29241f;margin:0 0 16px">
                Why Have Your Own Website?
            </h3>

            <div style="display:flex;flex-direction:column;gap:14px;font-size:14px">
                <div style="display:flex;align-items:flex-start;gap:12px">
                    <span style="font-size:18px">💬</span>
                    <div>
                        <strong style="color:#29241f">100% Direct WhatsApp Bookings</strong>
                        <p style="margin:2px 0 0;color:#6b5a4d;font-size:12px;line-height:1.5">Devotees contact you directly. No middleman, no commission deducted from your Dakshina.</p>
                    </div>
                </div>

                <div style="display:flex;align-items:flex-start;gap:12px">
                    <span style="font-size:18px">👑</span>
                    <div>
                        <strong style="color:#29241f">Pure White-Label (Only You)</strong>
                        <p style="margin:2px 0 0;color:#6b5a4d;font-size:12px;line-height:1.5">No other pandits appear on your domain. Visitors only see your profile, your photos, and your pujas.</p>
                    </div>
                </div>

                <div style="display:flex;align-items:flex-start;gap:12px">
                    <span style="font-size:18px">🌍</span>
                    <div>
                        <strong style="color:#29241f">High Prestige with NRIs & Metro Clients</strong>
                        <p style="margin:2px 0 0;color:#6b5a4d;font-size:12px;line-height:1.5">Having your own .in or .com domain commands 2x–4x higher Dakshina for weddings, Vastu, and Havans.</p>
                    </div>
                </div>

                <div style="display:flex;align-items:flex-start;gap:12px">
                    <span style="font-size:18px">⚡</span>
                    <div>
                        <strong style="color:#29241f">Zero Tech Headaches</strong>
                        <p style="margin:2px 0 0;color:#6b5a4d;font-size:12px;line-height:1.5">We manage the hosting, SSL security, and speed. You just focus on performing sacred rituals.</p>
                    </div>
                </div>
            </div>
        </div>

        <div style="background:#fcf8f2;border:1px dashed #d5a45c;border-radius:12px;padding:20px;text-align:center">
            <span style="font-size:24px">🕉️</span>
            <strong style="display:block;color:#875200;font-size:14px;margin-top:6px">Questions about Custom Domains?</strong>
            <p style="margin:4px 0 12px;color:#6d4810;font-size:12px;line-height:1.5">
                Our support team is available on WhatsApp to guide you through domain selection and setup.
            </p>
            <a href="https://wa.me/919876543210?text=Namaste%2C+I+have+a+question+about+getting+my+own+Pandit+website+domain." target="_blank" style="color:#b94e20;font-size:13px;font-weight:700;text-decoration:none">
                💬 Chat with Support on WhatsApp →
            </a>
        </div>
    </div>
</div>
@endsection
