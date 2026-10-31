@extends('layouts.app')

@section('title', 'DigitalBhudev — Vishwas No Setu | Book Verified Bhudevs & Vedic Ceremonies')
@section('meta_description', 'Connect with verified Brahmin Bhudevs for traditional Vedic pujas, access daily Panchang and Rashifal, and download free Pooja Samagri checklists.')

@section('content')
<main>
<section class="hero">
<div class="hero-pattern"></div>
<div class="container hero-grid">
<div class="hero-copy">
<div class="eyebrow"><span class="eyebrow-dot"></span> YOUR TRADITION, CONNECTED</div>
<h1>Bringing faith and tradition <em>closer to you.</em></h1>
<p class="hero-lead">Find trusted, verified Brahmin Bhudevs for home pujas, sacred ceremonies, and astrology guidance across Gujarat.</p>
<div class="hero-actions">
    <a class="btn btn-primary" href="{{ route('bhudevs.index') }}">Find a Bhudev <span>&rarr;</span></a>
    <a class="btn btn-light" href="https://api.whatsapp.com/send?phone=919876543210&text={{ urlencode('Namaste DigitalBhudev, I would like to book a Bhudev for a Vedic ceremony.') }}" target="_blank" style="background:#eaf7ee;color:#1e7e34;border-color:#bce5c7;display:inline-flex;align-items:center;gap:6px">
        <span>💬</span> Book via WhatsApp
    </a>
</div>
<div class="hero-trust"><div class="avatar-stack"><span>पं</span><span>ॐ</span><span>श्री</span></div><div><strong>Tradition meets convenience</strong><small>Direct connection &bull; No middleman fees</small></div></div>
</div>
<div aria-label="Decorative traditional Indian-inspired illustration" class="hero-art">
<div class="sun-disc"></div><div class="art-ring ring-one"></div><div class="art-ring ring-two"></div>
<div class="temple"><div class="temple-top">ॐ</div><div class="temple-roof"></div><div class="temple-body"><div class="temple-door"></div><i></i><i></i></div><div class="temple-steps"></div></div>
<div class="floating-note note-top"><span class="note-icon">✦</span><span><b>100% Verified Bhudevs</b><small>Rooted in trust</small></span></div>
<div class="floating-note note-bottom"><span class="note-icon">🪔</span><span><b>Pooja Samagri Guides</b><small>Free checklists</small></span></div>
<div class="petal petal-one"></div><div class="petal petal-two"></div><div class="petal petal-three"></div>
</div>
</div>
<div class="hero-bottom-wave"></div>
</section>

{{-- Search & Quick Match Bar --}}
<section class="quick-search">
<div class="container search-panel">
<div class="search-heading"><span class="search-symbol">⌕</span><div><strong>Find a Bhudev in your city</strong><small>Search by city, ceremony, or name</small></div></div>
<form action="{{ route('bhudevs.index') }}" class="search-form" method="get">
    <label class="sr-only" for="service-search">Search services or Bhudevs</label>
    <input id="service-search" name="q" placeholder="e.g. Ahmedabad, Satyanarayan, Vadodara…" type="search"/>
    <button class="btn btn-primary" type="submit">Search <span>&rarr;</span></button>
</form>
</div>
</section>

{{-- Instant Pooja Booking Widget (Customer Reach Driver) --}}
<section class="section" style="padding:40px 0;background:#ffffff">
<div class="container">
    @if(session('status'))
        <div style="background:#ecfdf5;border:1px solid #a7f3d0;border-radius:12px;padding:16px 20px;margin-bottom:24px;color:#047857;font-size:14px;text-align:center">
            ✓ {{ session('status') }}
        </div>
    @endif

    <div class="home-booking-card" style="background:#ffffff;border:1.5px solid var(--primary-border);border-radius:var(--radius-lg);padding:36px;box-shadow:var(--shadow);display:grid;grid-template-columns:1.2fr 1.8fr;gap:36px;align-items:center">
        <div>
            <span style="font-size:11.5px;font-weight:700;letter-spacing:0.12em;color:var(--primary);text-transform:uppercase;display:block;margin-bottom:6px">INSTANT DEVOTEE BOOKING</span>
            <h2 style="font-family:'Playfair Display',serif;font-size:28px;color:var(--heading);margin:0 0 10px;line-height:1.2">
                Need a Bhudev for an upcoming ceremony?
            </h2>
            <p style="color:var(--muted);font-size:14px;line-height:1.65;margin:0 0 20px">
                Tell us your ceremony and preferred date. Our team will instantly match you with verified local priests in your city.
            </p>

            <div style="display:flex;flex-direction:column;gap:10px;font-size:13px;color:#4b3d48">
                <div><strong style="color:var(--primary)">✓</strong> <strong>100% Verified Brahmin priests</strong></div>
                <div><strong style="color:var(--primary)">✓</strong> <strong>Direct contact &bull; Zero commission</strong></div>
                <div><strong style="color:var(--primary)">✓</strong> <strong>Free Pooja Samagri checklist provided</strong></div>
            </div>
        </div>

        <div>
            <form class="home-booking-form" method="POST" action="{{ route('booking.store') }}" style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
                @csrf
                <div>
                    <label style="display:block;font-size:12px;font-weight:700;margin-bottom:5px;color:var(--heading)">Your Name <span style="color:#e11d48">*</span></label>
                    <input type="text" name="name" required placeholder="e.g. Ramesh Patel" style="width:100%;padding:10px 14px;border:1.5px solid #ebdce6;border-radius:10px;font-size:13.5px;background:#ffffff;box-sizing:border-box">
                </div>

                <div>
                    <label style="display:block;font-size:12px;font-weight:700;margin-bottom:5px;color:var(--heading)">WhatsApp Number <span style="color:#e11d48">*</span></label>
                    <input type="text" name="phone" required placeholder="e.g. 98250 12345" style="width:100%;padding:10px 14px;border:1.5px solid #ebdce6;border-radius:10px;font-size:13.5px;background:#ffffff;box-sizing:border-box">
                </div>

                <div>
                    <label style="display:block;font-size:12px;font-weight:700;margin-bottom:5px;color:var(--heading)">Pooja Ceremony <span style="color:#e11d48">*</span></label>
                    <select name="pooja_service" required style="width:100%;padding:10px 14px;border:1.5px solid #ebdce6;border-radius:10px;font-size:13.5px;background:#fff;box-sizing:border-box">
                        <option value="">Select ceremony</option>
                        <option value="Satyanarayan Katha">Satyanarayan Katha</option>
                        <option value="Griha Pravesh / Vastu">Griha Pravesh / Vastu</option>
                        <option value="Rudrabhishek">Rudrabhishek</option>
                        <option value="Vivah Sanskar (Marriage)">Vivah Sanskar (Marriage)</option>
                        <option value="Navchandi Yajna">Navchandi Yajna</option>
                        <option value="Ganesh Sthapana & Pooja">Ganesh Sthapana & Pooja</option>
                        <option value="Other Sacred Ritual">Other Sacred Ritual</option>
                    </select>
                </div>

                <div>
                    <label style="display:block;font-size:12px;font-weight:700;margin-bottom:5px;color:var(--heading)">Your City <span style="color:#e11d48">*</span></label>
                    <input type="text" name="city" required placeholder="e.g. Ahmedabad, Surat" style="width:100%;padding:10px 14px;border:1.5px solid #ebdce6;border-radius:10px;font-size:13.5px;background:#ffffff;box-sizing:border-box">
                </div>

                <div style="grid-column:span 2">
                    <label style="display:block;font-size:12px;font-weight:700;margin-bottom:5px;color:var(--heading)">Preferred Date</label>
                    <input type="date" name="preferred_date" style="width:100%;padding:10px 14px;border:1.5px solid #ebdce6;border-radius:10px;font-size:13.5px;background:#ffffff;box-sizing:border-box">
                </div>

                {{-- Honeypot --}}
                <input type="text" name="website" style="display:none" tabindex="-1" autocomplete="off">

                <div style="grid-column:span 2;display:flex;gap:12px;align-items:center;margin-top:6px;flex-wrap:wrap">
                    <button type="submit" class="btn btn-primary" style="flex:1;padding:12px 20px;font-size:14px;font-weight:700">
                        Request Bhudev Match &rarr;
                    </button>
                    <a href="https://api.whatsapp.com/send?phone=919876543210&text={{ urlencode('Namaste DigitalBhudev, I would like to inquire about booking a Bhudev.') }}" target="_blank" class="btn btn-light" style="background:#eaf7ee;color:#1e7e34;border-color:#bce5c7;padding:12px 18px;font-size:13px" title="Chat on WhatsApp">
                        💬 WhatsApp
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
</section>

{{-- Services & Features Grid --}}
<section class="section section-services">
<div class="container">
<div class="section-heading"><div><span class="eyebrow">DISCOVER DIGITALBHUDEV</span><h2>Tradition for every <em>important moment.</em></h2></div><a class="text-link" href="{{ route('services.index') }}">Explore all services <span>&rarr;</span></a></div>
<div class="service-grid">
<a class="service-card" href="{{ route('services.index') }}#pooja"><div class="service-icon icon-saffron">🪔</div><span class="card-arrow">&nearr;</span><h3>Pooja &amp; Rituals</h3><p>Support for sacred ceremonies, havans, and Vedic rituals.</p><span class="card-link">Explore services &rarr;</span></a>
<a class="service-card" href="{{ route('bhudevs.index') }}"><div class="service-icon icon-green">ॐ</div><span class="card-arrow">&nearr;</span><h3>Find a Bhudev</h3><p>Discover verified Bhudev profiles across Gujarat and connect directly.</p><span class="card-link">Browse profiles &rarr;</span></a>
<a class="service-card" href="{{ route('samagri.index') }}"><div class="service-icon icon-rose">📋</div><span class="card-arrow">&nearr;</span><h3>Pooja Samagri Lists</h3><p>Free, printable Vedic checklists for Satyanarayan, Vastu, and Havans.</p><span class="card-link">View checklists &rarr;</span></a>
<a class="service-card" href="{{ route('muhurat.index') }}"><div class="service-icon icon-blue">✦</div><span class="card-arrow">&nearr;</span><h3>Shubh Muhurat Guide</h3><p>Auspicious dates for weddings, house warming, and vehicle purchases.</p><span class="card-link">Explore Muhurats &rarr;</span></a>
<a class="service-card" href="{{ route('panchang.index') }}"><div class="service-icon icon-rose">☼</div><span class="card-arrow">&nearr;</span><h3>Nitya Panchang</h3><p>Daily Tithi, Vaar, Nakshatra, and Choghadiya calculations.</p><span class="card-link">View Panchang &rarr;</span></a>
<a class="service-card" href="{{ route('rashifal.index') }}"><div class="service-icon icon-saffron">♈</div><span class="card-arrow">&nearr;</span><h3>Daily Rashifal</h3><p>Vedic horoscope predictions for all 12 Rashi signs, updated daily.</p><span class="card-link">Read Rashifal &rarr;</span></a>
</div>
</div>
</section>

<section class="section section-how">
<div class="container how-grid">
<div class="how-art"><div class="how-circle"><span class="om-large">ॐ</span><span class="circle-caption">A bridge between<br/>people &amp; tradition</span></div><span class="how-spark spark-a">✦</span><span class="how-spark spark-b">✧</span><div class="how-mini-card"><span>✓</span><div><b>Simple to get started</b><small>Find the right connection</small></div></div></div>
<div class="how-copy"><span class="eyebrow">A SIMPLE JOURNEY</span><h2>Your connection to tradition, <em>made simpler.</em></h2><p>DigitalBhudev brings discovery and connection into one place, helping people find traditional services and connect with the Bhudev community.</p>
<div class="steps"><div class="step"><span class="step-number">01</span><div><h3>Explore</h3><p>Browse Bhudev profiles, check qualifications, and explore ceremonies.</p></div></div><div class="step"><span class="step-number">02</span><div><h3>Connect</h3><p>Direct WhatsApp or phone inquiry with zero middleman commissions.</p></div></div><div class="step"><span class="step-number">03</span><div><h3>Conduct Vidhi</h3><p>Conduct your ceremony with authentic Vedic rites and complete peace of mind.</p></div></div></div>
<a class="btn btn-primary" href="{{ route('bhudevs.index') }}">Find a Bhudev <span>&rarr;</span></a>
</div>
</div>
</section>

<section class="section panchang-banner"><div class="container panchang-inner"><div><span class="eyebrow eyebrow-light">ROOTED IN TIMELESS WISDOM</span><h2>Stay connected to the <em>rhythm of tradition.</em></h2><p>Explore today's Panchang, auspicious muhurats, and daily planetary insights.</p><a class="btn btn-white" href="{{ route('panchang.index') }}">Explore Panchang <span>&rarr;</span></a></div><div class="calendar-card"><div class="calendar-top"><span>✦ TRADITIONAL CALENDAR</span><span>☼</span></div><div class="calendar-om">ॐ</div><div class="calendar-label">PANCHANG</div><div class="calendar-lines"><i></i><i></i><i></i></div><div class="calendar-footer">A guide to auspicious moments</div></div></div></section>

<section class="section section-values"><div class="container"><div class="section-heading centered"><div><span class="eyebrow">WHY DIGITALBHUDEV</span><h2>A trusted bridge to <em>your traditions.</em></h2><p class="section-intro">Technology can make traditional knowledge and services easier to discover while keeping the sacred human connection at the heart of every interaction.</p></div></div><div class="values-grid"><div class="value-item"><span>♧</span><h3>Community first</h3><p>Designed to bring devotees and the Bhudev community closer together.</p></div><div class="value-item"><span>✥</span><h3>Tradition &amp; technology</h3><p>Modern convenience with deep respect for established Vedic practices.</p></div><div class="value-item"><span>♡</span><h3>Meaningful connections</h3><p>Discover profiles and services that fit your family's customs.</p></div></div></div></section>

<section class="section cta-section" style="background:linear-gradient(135deg, #2c1226 0%, #1a0a16 100%);padding:70px 0;border-top:3px solid var(--primary)">
    <div class="container">
        <div class="cta-saas-card" style="background:#3b1935;border:1.5px solid #54274c;border-radius:var(--radius-lg);padding:48px 40px;display:grid;grid-template-columns:1.3fr 0.7fr;gap:40px;align-items:center;box-shadow:var(--shadow)">
            <div>
                <div style="display:inline-flex;align-items:center;gap:8px;background:#4d2146;border:1px solid #70335e;color:#f472b6;padding:5px 14px;border-radius:999px;font-size:12px;font-weight:700;letter-spacing:1px;margin-bottom:16px">
                    <span>🕉️</span>
                    <span>{{ __('messages.home.saas_banner_eyebrow') }}</span>
                </div>

                <h2 style="font-family:'Playfair Display',serif;font-size:36px;color:#fff;margin:0 0 14px;line-height:1.2;font-weight:700">
                    {{ __('messages.home.saas_banner_title') }}
                </h2>

                <p style="color:#fce7f3;font-size:16px;line-height:1.7;margin:0 0 24px;max-width:620px">
                    {{ __('messages.home.saas_banner_text') }}
                </p>

                <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));gap:12px;margin-bottom:28px">
                    <div style="color:#fdf2f8;font-size:13px;display:flex;align-items:center;gap:8px">
                        <span style="color:#f472b6">✓</span> Your Own Domain (e.g. yourname.in)
                    </div>
                    <div style="color:#fdf2f8;font-size:13px;display:flex;align-items:center;gap:8px">
                        <span style="color:#25d366">✓</span> Direct WhatsApp Inquiries (0% Commission)
                    </div>
                    <div style="color:#fdf2f8;font-size:13px;display:flex;align-items:center;gap:8px">
                        <span style="color:#f472b6">✓</span> Personal Dakshina UPI QR Code
                    </div>
                    <div style="color:#fdf2f8;font-size:13px;display:flex;align-items:center;gap:8px">
                        <span style="color:#f472b6">✓</span> Pure White-Label (Only Your Profile)
                    </div>
                </div>

                <div style="display:flex;flex-wrap:wrap;gap:14px;align-items:center">
                    <a class="btn btn-primary" href="{{ route('register') }}" style="padding:14px 28px;font-size:15px">
                        🚀 {{ __('messages.home.saas_banner_cta') }} &rarr;
                    </a>
                    <a href="https://api.whatsapp.com/send?phone=919876543210&text={{ urlencode('Namaste DigitalBhudev, I am a Brahmin priest and I would like to inquire about getting my personal website and domain.') }}" target="_blank" class="btn btn-light" style="background:#25d366;color:#fff;border-color:#25d366;padding:14px 22px;font-size:15px;display:inline-flex;align-items:center;gap:6px" title="{{ __('messages.actions.chat_whatsapp') }}">
                        <span>💬</span> {{ __('messages.actions.chat_whatsapp') }}
                    </a>
                </div>
            </div>

            <div style="text-align:center;background:#2a1125;border:1px solid #4a1f42;border-radius:18px;padding:32px 24px">
                <div style="width:72px;height:72px;margin:0 auto 16px;background:linear-gradient(135deg, #e11d48, #be185d);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:36px;color:#fff;box-shadow:0 8px 24px rgba(225,29,72,0.4)">
                    ॐ
                </div>
                <h3 style="font-family:'Playfair Display',serif;color:#fff;font-size:20px;margin:0 0 8px">
                    Bhudev Digital Setu
                </h3>
                <p style="color:#fce7f3;font-size:13px;line-height:1.6;margin:0 0 20px">
                    Preserving Vedic Parampara while empowering Shastri Jis with modern digital tools and direct devotee reach worldwide.
                </p>
                <div style="background:#3b1935;border-radius:999px;padding:10px 14px;font-size:12px;color:#f472b6;font-weight:600">
                    🔒 100% Privacy • Direct Devotee Connection • Verified Status
                </div>
            </div>
        </div>
    </div>
</section>
</main>
@endsection
