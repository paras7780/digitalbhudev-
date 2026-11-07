@extends('layouts.app')

@section('title', 'Terms of Use & Ceremony Guidelines | DigitalBhudev')
@section('meta_description', 'Terms and conditions governing the use of DigitalBhudev platform for devotees and verified Brahmin priests across Gujarat and India.')
@section('canonical', route('terms'))

@push('schema')
@php
    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Terms of Use', 'item' => route('terms')],
        ],
    ];
    $pageSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'WebPage',
        'name' => 'Terms of Use & Ceremony Guidelines | DigitalBhudev',
        'description' => 'Legal guidelines, priest verification standards, and devotee conduct terms for DigitalBhudev.',
        'url' => route('terms'),
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
            <span class="eyebrow">LEGAL AGREEMENT &amp; SACRED MARYADA</span>
            <h1>Terms of Use</h1>
            <p>Welcome to DigitalBhudev (Vishwas No Setu). By accessing or using our platform, you agree to comply with and be bound by the following sacred terms and operational guidelines.</p>
        </div>
    </section>

    <section class="content-section">
        <div class="container" style="max-width:920px;margin:0 auto">
            <div style="background:var(--card-bg);border:1px solid var(--primary-border);border-radius:var(--radius-lg);padding:38px;box-shadow:var(--shadow);display:flex;flex-direction:column;gap:28px">
                
                {{-- Platform Mission Banner --}}
                <div style="background:linear-gradient(135deg, #fff5f9, #fdf2f8);border:1.5px solid #fbcfe8;border-radius:12px;padding:20px 24px;display:flex;align-items:flex-start;gap:14px">
                    <span style="font-size:24px;line-height:1">🏛️</span>
                    <div>
                        <strong style="font-size:15px;color:var(--heading);display:block;margin-bottom:4px">Connecting Tradition with Technology</strong>
                        <p style="font-size:13.5px;color:#831843;line-height:1.6;margin:0">
                            DigitalBhudev operates as a transparent digital bridge connecting devout families with authentic, verified Vedic Brahmin priests (Bhudevs) for traditional home pujas, sacred ceremonies, and Shubh Muhurat guidance.
                        </p>
                    </div>
                </div>

                <div>
                    <h2 style="font-family:'Playfair Display',serif;font-size:22px;color:var(--heading);margin:0 0 10px">1. Acceptance of Terms</h2>
                    <p style="color:var(--ink);font-size:14px;line-height:1.75;margin:0">
                        By visiting the website, searching our Bhudev directory, submitting booking requests, using our Panchang calculators, or creating a Bhudev profile, you acknowledge that you have read, understood, and agreed to these Terms of Use and our associated Privacy Policy.
                    </p>
                </div>

                <div>
                    <h2 style="font-family:'Playfair Display',serif;font-size:22px;color:var(--heading);margin:0 0 10px">2. Bhudev Priest Registration &amp; Verification Standards</h2>
                    <p style="color:var(--ink);font-size:14px;line-height:1.75;margin:0 0 10px">
                        Priests applying for listing on DigitalBhudev agree to provide truthful and authentic details regarding:
                    </p>
                    <ul style="color:var(--ink);font-size:14px;line-height:1.75;padding-left:22px;margin:0 0 10px">
                        <li><strong>Vedic Credentials:</strong> Specific Veda branch (e.g. Shukla Yajurveda, Rigveda, Samaveda), Gurukul education, and traditional Shastri/Acharya credentials.</li>
                        <li><strong>Profile Maryada:</strong> Maintaining sacred decorum in all devotee communications, adhering to pure mantra-uccharan, and conducting ceremonies with authentic vidhi.</li>
                        <li><strong>Administrative Vetting:</strong> DigitalBhudev administrators review and verify submitted documents before activating any public profile. We reserve the absolute right to reject, suspend, or permanently remove any profile containing inaccurate, fraudulent, or disrespectful conduct.</li>
                    </ul>
                </div>

                <div>
                    <h2 style="font-family:'Playfair Display',serif;font-size:22px;color:var(--heading);margin:0 0 10px">3. Devotee Booking Guidelines &amp; Ritual Conduct</h2>
                    <p style="color:var(--ink);font-size:14px;line-height:1.75;margin:0 0 10px">
                        Devotees connecting with verified priests agree to:
                    </p>
                    <ul style="color:var(--ink);font-size:14px;line-height:1.75;padding-left:22px;margin:0">
                        <li>Provide accurate ceremony location, family Gotra details, and preferred Muhurat timings.</li>
                        <li>Accord sacred respect (Maryada) and hospitality to the visiting priest conducting ceremonies in their home or venue.</li>
                        <li>Coordinate the arrangement of essential ritual Samagri (fruits, flowers, panchamrit, havan samagri) in advance according to the priest’s guidance or our free Samagri checklists.</li>
                    </ul>
                </div>

                <div>
                    <h2 style="font-family:'Playfair Display',serif;font-size:22px;color:var(--heading);margin:0 0 10px">4. Zero-Commission &amp; Voluntary Dakshina Principle</h2>
                    <p style="color:var(--ink);font-size:14px;line-height:1.75;margin:0">
                        In adherence to Sanatana Dharma, the sacred honorarium (Dakshina) offered to a Brahmin priest is voluntary and personal. DigitalBhudev does not charge or deduct any middleman commission from ritual Dakshina. Devotees and priests coordinate Dakshina directly and transparently with mutual goodwill.
                    </p>
                </div>

                <div>
                    <h2 style="font-family:'Playfair Display',serif;font-size:22px;color:var(--heading);margin:0 0 10px">5. Pandit Personal Domain &amp; SaaS Websites</h2>
                    <p style="color:var(--ink);font-size:14px;line-height:1.75;margin:0">
                        Verified Bhudevs utilizing our multi-domain SaaS service (e.g. custom domain websites like <em>parasrajyaguru.in</em>) agree to utilize the platform strictly for authentic religious, cultural, and astrological services. Impersonation of other priests, unauthorized copyright infringement, or hosting unauthorized content is strictly prohibited and results in immediate domain termination.
                    </p>
                </div>

                <div>
                    <h2 style="font-family:'Playfair Display',serif;font-size:22px;color:var(--heading);margin:0 0 10px">6. Intellectual Property &amp; Content Rights</h2>
                    <p style="color:var(--ink);font-size:14px;line-height:1.75;margin:0">
                        All platform branding, visual marks ("DigitalBhudev", "Vishwas No Setu"), Panchang algorithms, customized Samagri card designs, and software code are the intellectual property of DigitalBhudev. Priests retain ownership of their individual uploaded ceremony photos, videos, and biographies.
                    </p>
                </div>

                <div>
                    <h2 style="font-family:'Playfair Display',serif;font-size:22px;color:var(--heading);margin:0 0 10px">7. Limitation of Platform Liability</h2>
                    <p style="color:var(--ink);font-size:14px;line-height:1.75;margin:0">
                        DigitalBhudev serves as a verified directory and discovery facilitator. While we take utmost care to verify background information, priests operate as independent spiritual practitioners. Devotees are encouraged to communicate ceremony requirements clearly. DigitalBhudev shall not be liable for indirect or consequential damages arising from private arrangements made outside platform protocols.
                    </p>
                </div>

                <div>
                    <h2 style="font-family:'Playfair Display',serif;font-size:22px;color:var(--heading);margin:0 0 10px">8. Governing Law &amp; Jurisdiction</h2>
                    <p style="color:var(--ink);font-size:14px;line-height:1.75;margin:0">
                        These Terms shall be governed by and construed in accordance with the laws of India. Any disputes arising out of or in connection with the platform shall be subject to the exclusive jurisdiction of the competent courts in <strong>Surat, Gujarat, India</strong>.
                    </p>
                </div>

                <div>
                    <h2 style="font-family:'Playfair Display',serif;font-size:22px;color:var(--heading);margin:0 0 10px">9. Contact &amp; Clarifications</h2>
                    <p style="color:var(--ink);font-size:14px;line-height:1.75;margin:0 0 12px">
                        If you have questions regarding these terms, ceremony guidelines, or priest code of conduct, please reach out to our administrative team:
                    </p>
                    <div style="display:flex;flex-wrap:wrap;gap:12px;align-items:center">
                        <a href="{{ route('contact') }}" class="btn btn-primary btn-sm">
                            <span>💬 Contact Administrative Team</span>
                        </a>
                        <span style="font-size:13px;color:var(--muted)">or email: <strong style="color:var(--heading)">legal@digitalbhudev.com</strong></span>
                    </div>
                </div>

            </div>
        </div>
    </section>
</main>
@endsection
