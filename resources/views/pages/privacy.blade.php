@extends('layouts.app')

@section('title', 'Privacy Policy | DigitalBhudev')
@section('meta_description', 'Learn how DigitalBhudev protects and respects personal data for devotees and verified Bhudev priests.')

@section('content')
<main>
    <section class="page-hero">
        <div class="container">
            <span class="eyebrow">DATA PROTECTION & TRUST</span>
            <h1>Privacy Policy</h1>
            <p>Your privacy and sacred trust are fundamental to DigitalBhudev. Learn how your data is handled and protected.</p>
        </div>
    </section>

    <section class="content-section">
        <div class="container" style="max-width:880px;margin:0 auto">
            <div style="background:var(--card-bg);border:1px solid var(--primary-border);border-radius:var(--radius-lg);padding:38px;box-shadow:var(--shadow);display:flex;flex-direction:column;gap:24px">
                <div>
                    <h2 style="font-family:'Playfair Display',serif;font-size:22px;color:var(--heading);margin:0 0 8px">1. Information We Collect</h2>
                    <p style="color:var(--ink);font-size:14px;line-height:1.7;margin:0">
                        We collect information you provide directly to us when registering a Bhudev profile, submitting an inquiry, or requesting a ceremony booking. This may include your name, mobile/WhatsApp number, email address, city, state, experience details, and puja preferences.
                    </p>
                </div>

                <div>
                    <h2 style="font-family:'Playfair Display',serif;font-size:22px;color:var(--heading);margin:0 0 8px">2. Bhudev Privacy Controls</h2>
                    <p style="color:var(--ink);font-size:14px;line-height:1.7;margin:0">
                        Bhudev priests have complete control over their personal contact information. Through the Bhudev Portal, priests can choose whether to display their mobile phone number, email address, or street address on their public profile. If toggled off, devotees can only connect through our secure platform inquiry form.
                    </p>
                </div>

                <div>
                    <h2 style="font-family:'Playfair Display',serif;font-size:22px;color:var(--heading);margin:0 0 8px">3. How Information is Used</h2>
                    <p style="color:var(--ink);font-size:14px;line-height:1.7;margin:0">
                        We use the information collected solely to facilitate communication between devotees and verified priests, manage ceremony bookings, dispatch administrative alerts, and improve our platform features. We do not sell or rent your personal data to third-party advertisers.
                    </p>
                </div>

                <div>
                    <h2 style="font-family:'Playfair Display',serif;font-size:22px;color:var(--heading);margin:0 0 8px">4. Data Security</h2>
                    <p style="color:var(--ink);font-size:14px;line-height:1.7;margin:0">
                        We employ standard security protocols, including password hashing (Bcrypt), encrypted sessions, CSRF protection, and role-based access control, to safeguard your information against unauthorized access.
                    </p>
                </div>

                <div>
                    <h2 style="font-family:'Playfair Display',serif;font-size:22px;color:var(--heading);margin:0 0 8px">5. Contact Us Regarding Privacy</h2>
                    <p style="color:var(--ink);font-size:14px;line-height:1.7;margin:0">
                        If you have questions about this policy or wish to update or delete your profile data, please reach out to us via our <a href="{{ route('contact') }}" style="color:var(--primary);font-weight:600">Contact Us</a> page.
                    </p>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
