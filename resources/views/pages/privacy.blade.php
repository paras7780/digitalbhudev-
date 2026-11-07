@extends('layouts.app')

@section('title', 'Privacy Policy | DigitalBhudev')
@section('meta_description', 'Learn how DigitalBhudev protects and respects personal data for devotees and verified Bhudev priests in accordance with Indian IT laws.')
@section('canonical', route('privacy'))

@push('schema')
@php
    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Privacy Policy', 'item' => route('privacy')],
        ],
    ];
    $pageSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'WebPage',
        'name' => 'Privacy Policy | DigitalBhudev',
        'description' => 'Comprehensive data protection, priest privacy controls, and security policies of DigitalBhudev.',
        'url' => route('privacy'),
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
            <span class="eyebrow">DATA PROTECTION, PRIVACY &amp; SACRED TRUST</span>
            <h1>Privacy Policy</h1>
            <p>Your privacy and sacred trust are fundamental to DigitalBhudev. Learn how your data is handled, stored, and protected in full accordance with the Information Technology Act, 2000.</p>
        </div>
    </section>

    <section class="content-section">
        <div class="container" style="max-width:920px;margin:0 auto">
            <div style="background:var(--card-bg);border:1px solid var(--primary-border);border-radius:var(--radius-lg);padding:38px;box-shadow:var(--shadow);display:flex;flex-direction:column;gap:28px">
                
                {{-- Effective Date & Scope Badge --}}
                <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;padding-bottom:18px;border-bottom:1px solid var(--primary-border)">
                    <span style="font-size:13px;color:var(--muted)">Last Updated &amp; Effective: <strong>{{ date('F Y') }}</strong></span>
                    <span style="font-size:12px;background:#fce7f3;color:#9d174d;border:1px solid #fbcfe8;padding:3px 10px;border-radius:999px;font-weight:700">🔒 IT Act 2000 &amp; SPDI Compliant</span>
                </div>

                <div>
                    <h2 style="font-family:'Playfair Display',serif;font-size:22px;color:var(--heading);margin:0 0 10px">1. Information We Collect</h2>
                    <p style="color:var(--ink);font-size:14px;line-height:1.75;margin:0 0 10px">
                        DigitalBhudev collects only the essential information needed to facilitate authentic spiritual connections between devotees and verified Vedic priests:
                    </p>
                    <ul style="color:var(--ink);font-size:14px;line-height:1.75;padding-left:22px;margin:0">
                        <li><strong>For Devotees:</strong> Full name, mobile/WhatsApp number, email address, city/location, ceremony requirements (e.g. Satyanarayan Katha, Griha Pravesh, Havan), and preferred Muhurat date.</li>
                        <li><strong>For Bhudev Priests:</strong> Legal name, profile display name, phone/WhatsApp contact, email address, city, state, Veda branch (e.g. Shukla Yajurveda, Rigveda), educational credentials (Shastri, Acharya degrees), ritual specializations, experience years, and optional profile photos/ceremony gallery.</li>
                    </ul>
                </div>

                <div>
                    <h2 style="font-family:'Playfair Display',serif;font-size:22px;color:var(--heading);margin:0 0 10px">2. Bhudev Priest Privacy &amp; Visibility Controls</h2>
                    <p style="color:var(--ink);font-size:14px;line-height:1.75;margin:0">
                        Bhudev priests retain 100% control over their personal contact information on DigitalBhudev. Through the Bhudev Portal, priests can independently toggle:
                    </p>
                    <ul style="color:var(--ink);font-size:14px;line-height:1.75;padding-left:22px;margin:8px 0 0">
                        <li>Whether their mobile number and direct WhatsApp call button are displayed publicly or kept hidden.</li>
                        <li>Whether their street address is shown or restricted to city-level only.</li>
                        <li>When contact details are hidden, devotees can only connect via our secure encrypted platform inquiry form, ensuring total spam protection.</li>
                    </ul>
                </div>

                <div>
                    <h2 style="font-family:'Playfair Display',serif;font-size:22px;color:var(--heading);margin:0 0 10px">3. How Your Information is Used</h2>
                    <p style="color:var(--ink);font-size:14px;line-height:1.75;margin:0">
                        Information collected is strictly utilized to:
                    </p>
                    <ul style="color:var(--ink);font-size:14px;line-height:1.75;padding-left:22px;margin:8px 0 0">
                        <li>Connect devotees directly with verified local priests for ceremony scheduling and Muhurat consultation.</li>
                        <li>Deliver requested daily Panchang calculations, customized Samagri checklists, and WhatsApp notifications.</li>
                        <li>Authenticate priest credentials and protect devotees from fraudulent claims.</li>
                        <li><strong>Strict No-Spam &amp; No-Sale Policy:</strong> We do not sell, rent, monetize, or trade your personal data to any third-party advertisers or telemarketers.</li>
                    </ul>
                </div>

                <div>
                    <h2 style="font-family:'Playfair Display',serif;font-size:22px;color:var(--heading);margin:0 0 10px">4. WhatsApp Communications &amp; Direct Messaging</h2>
                    <p style="color:var(--ink);font-size:14px;line-height:1.75;margin:0">
                        When you click "Connect on WhatsApp" or "Book via WhatsApp", you are redirected to WhatsApp's secure platform. Direct communications between devotees and priests on WhatsApp are subject to WhatsApp's end-to-end encryption and terms. DigitalBhudev does not intercept, read, or store your private WhatsApp chat messages.
                    </p>
                </div>

                <div>
                    <h2 style="font-family:'Playfair Display',serif;font-size:22px;color:var(--heading);margin:0 0 10px">5. Data Security &amp; Storage Protocols</h2>
                    <p style="color:var(--ink);font-size:14px;line-height:1.75;margin:0">
                        We employ enterprise-grade security protocols to protect all stored information:
                    </p>
                    <ul style="color:var(--ink);font-size:14px;line-height:1.75;padding-left:22px;margin:8px 0 0">
                        <li><strong>Password Encryption:</strong> All account credentials are encrypted using industry-standard Bcrypt hashing algorithms.</li>
                        <li><strong>Session &amp; CSRF Security:</strong> Every form and authenticated request is protected with cryptographically secure Cross-Site Request Forgery (CSRF) tokens.</li>
                        <li><strong>Transport Security:</strong> All data transmissions are encrypted using SSL/TLS HTTPS protocols.</li>
                    </ul>
                </div>

                <div>
                    <h2 style="font-family:'Playfair Display',serif;font-size:22px;color:var(--heading);margin:0 0 10px">6. Cookies &amp; Browser Preferences</h2>
                    <p style="color:var(--ink);font-size:14px;line-height:1.75;margin:0">
                        DigitalBhudev uses essential session cookies solely to preserve your preferred language (English, Gujarati, or Hindi) and maintain secure user logins. We do not deploy intrusive third-party cross-site tracking cookies.
                    </p>
                </div>

                <div>
                    <h2 style="font-family:'Playfair Display',serif;font-size:22px;color:var(--heading);margin:0 0 10px">7. User Rights &amp; Data Deletion</h2>
                    <p style="color:var(--ink);font-size:14px;line-height:1.75;margin:0">
                        You have the right to inspect, update, or permanently delete your personal information and profile listing. Registered Bhudevs can modify their profile details or request account deletion directly from the portal or by contacting our administration team.
                    </p>
                </div>

                <div>
                    <h2 style="font-family:'Playfair Display',serif;font-size:22px;color:var(--heading);margin:0 0 10px">8. Grievance Redressal Officer</h2>
                    <p style="color:var(--ink);font-size:14px;line-height:1.75;margin:0 0 12px">
                        In accordance with the Information Technology Act, 2000 and rules made thereunder, the Grievance Officer for the purposes of data protection and privacy concerns is:
                    </p>
                    <div style="background:var(--bg);border:1px solid var(--card-border);border-radius:8px;padding:16px 20px;font-size:13.5px;color:var(--ink);line-height:1.6">
                        <strong>Grievance Officer:</strong> Paras Rajyaguru<br>
                        <strong>Platform:</strong> DigitalBhudev (Vishwas No Setu)<br>
                        <strong>Email:</strong> privacy@digitalbhudev.com<br>
                        <strong>Location:</strong> Surat, Gujarat, India<br>
                        <strong>Response Window:</strong> Within 48 business hours
                    </div>
                </div>

            </div>
        </div>
    </section>
</main>
@endsection
