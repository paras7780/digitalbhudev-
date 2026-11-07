@extends('layouts.app')

@section('title', 'Puja, Astrological & Spiritual Disclaimer | DigitalBhudev')
@section('meta_description', 'Spiritual, ritual, and astrological disclaimer regarding Vedic ceremonies, daily Panchang calculations, Rashifal horoscope, and Muhurat on DigitalBhudev.')
@section('canonical', route('disclaimer'))

@push('schema')
@php
    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Puja & Astrological Disclaimer', 'item' => route('disclaimer')],
        ],
    ];
    $pageSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'WebPage',
        'name' => 'Puja, Astrological & Spiritual Disclaimer | DigitalBhudev',
        'description' => 'Vedic scriptural context and spiritual guidance disclaimers for pujas, Panchang, Rashifal, and astrological calculations.',
        'url' => route('disclaimer'),
        'publisher' => [
            '@type' => 'Organization',
            'name' => 'DigitalBhudev',
            'url' => url('/'),
        ],
    ];
@endphp
<script type="application/ld+json">
{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES) !!}
</script>
<script type="application/ld+json">
{!! json_encode($pageSchema, JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')
<main>
    <section class="page-hero">
        <div class="container">
            <span class="eyebrow">FAITH, SHASTRA &amp; GUIDANCE</span>
            <h1>Puja &amp; Astrological Disclaimer</h1>
            <p>Important spiritual context, scriptural traditions, and advisory boundaries regarding Vedic rituals, Panchang calculations, and astrological insights.</p>
        </div>
    </section>

    <section class="content-section">
        <div class="container" style="max-width:920px;margin:0 auto">
            <div style="background:var(--card-bg);border:1px solid var(--primary-border);border-radius:var(--radius-lg);padding:38px;box-shadow:var(--shadow);display:flex;flex-direction:column;gap:28px">
                
                {{-- Vedic Tradition Callout --}}
                <div style="background:linear-gradient(135deg, #fffbeb, #fef3c7);border:1.5px solid #fde68a;border-radius:12px;padding:20px 24px;display:flex;align-items:flex-start;gap:14px">
                    <span style="font-size:24px;line-height:1">🪔</span>
                    <div>
                        <strong style="font-size:15px;color:#92400e;display:block;margin-bottom:4px">Traditional Sanatana Dharma Foundation</strong>
                        <p style="font-size:13.5px;color:#78350f;line-height:1.6;margin:0">
                            DigitalBhudev is dedicated to preserving authentic Vedic culture and connecting devotees with knowledgeable Brahmin priests. All ceremonies, mantras, and Muhurat consultations follow ancient Vedic Shastras, Puranas, and familial Maryada.
                        </p>
                    </div>
                </div>

                <div>
                    <h2 style="font-family:'Playfair Display',serif;font-size:22px;color:var(--heading);margin:0 0 10px">1. Spiritual Nature of Vedic Ceremonies &amp; Havans</h2>
                    <p style="color:var(--ink);font-size:14px;line-height:1.75;margin:0">
                        Vedic pujas, Havans, Jaap, and Kathas (such as Satyanarayan Katha, Rudrabhishek, Navchandi, and Griha Pravesh) are sacred spiritual observances. In accordance with Hindu Shastras, the spiritual fruits (Phala-Shruti) of any ritual depend on the devotee’s faith (Shraddha), pure intent (Sankalpa), karmic factors, and divine grace. DigitalBhudev and verified priests do not guarantee specific material outcomes, miraculous cures, or supernatural claims.
                    </p>
                </div>

                <div>
                    <h2 style="font-family:'Playfair Display',serif;font-size:22px;color:var(--heading);margin:0 0 10px">2. Daily Panchang &amp; Astrological Calculations</h2>
                    <p style="color:var(--ink);font-size:14px;line-height:1.75;margin:0 0 10px">
                        Our Nitya Panchang data (Tithi, Nakshatra, Yoga, Karana, Rahu Kaal, Abhijit Muhurat) and Rashifal predictions are derived from established Vedic astronomical principles and mathematical planetary ephemeris (Drik Ganita / Surya Siddhanta):
                    </p>
                    <ul style="color:var(--ink);font-size:14px;line-height:1.75;padding-left:22px;margin:0">
                        <li><strong>Spiritual &amp; Cultural Guidance:</strong> Panchang timings and daily horoscopes are provided purely for cultural enrichment, festival celebration, and devotional guidance.</li>
                        <li><strong>Not a Substitute for Professional Advice:</strong> Astrological consultations, gemstone recommendations, or planetary remedies (Graha Shanti) must never be construed as, or substituted for, professional medical, legal, financial, architectural, or psychological counseling. Always consult certified medical and legal professionals for health and legal decisions.</li>
                    </ul>
                </div>

                <div>
                    <h2 style="font-family:'Playfair Display',serif;font-size:22px;color:var(--heading);margin:0 0 10px">3. Shubh Muhurat Selection &amp; Kul-Parampara</h2>
                    <p style="color:var(--ink);font-size:14px;line-height:1.75;margin:0">
                        While our automated and curated Shubh Muhurat charts indicate traditionally auspicious planetary alignments across Gujarat and India, individual family customs (Kul-Parampara), Gotra rules, and regional almanacs (Panchangs) can vary. Devotees are encouraged to consult directly with their chosen Shastri Ji or Kul-Purohit to verify exact Janma-Kundali alignments.
                    </p>
                </div>

                <div>
                    <h2 style="font-family:'Playfair Display',serif;font-size:22px;color:var(--heading);margin:0 0 10px">4. Priest Verification &amp; Independent Relationship</h2>
                    <p style="color:var(--ink);font-size:14px;line-height:1.75;margin:0">
                        DigitalBhudev takes extensive care to verify the educational credentials, Shastra knowledge, Veda branch, and identity of all Brahmin priests listed on the platform. However, registered Bhudevs operate as independent spiritual practitioners. Ceremony timing, specific vidhi preferences, Samagri lists, and voluntary Dakshina are coordinated directly between the devotee family and the priest.
                    </p>
                </div>

                <div>
                    <h2 style="font-family:'Playfair Display',serif;font-size:22px;color:var(--heading);margin:0 0 10px">5. Devotee Faith, Discretion &amp; Inquiries</h2>
                    <p style="color:var(--ink);font-size:14px;line-height:1.75;margin:0">
                        Devotees are advised to exercise their own personal faith, wisdom, and discretion while scheduling rituals or requesting astrological consultations. If you have questions regarding any scriptural reference, Muhurat calculation, or ritual procedure, our team and Vedic scholars are happy to assist you:
                    </p>
                    <div style="margin-top:14px;display:flex;flex-wrap:wrap;gap:12px;align-items:center">
                        <a href="{{ route('contact') }}" class="btn btn-primary btn-sm">
                            <span>💬 Contact Vedic Support Team</span>
                        </a>
                        <span style="font-size:13px;color:var(--muted)">or connect via: <strong style="color:var(--heading)">info@digitalbhudev.com</strong></span>
                    </div>
                </div>

            </div>
        </div>
    </section>
</main>
@endsection
