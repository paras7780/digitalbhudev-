@extends('layouts.app')

@section('title', 'Terms of Use | DigitalBhudev')
@section('meta_description', 'Terms and conditions governing the use of DigitalBhudev platform for devotees and verified Brahmin priests.')

@section('content')
<main>
    <section class="page-hero">
        <div class="container">
            <span class="eyebrow">LEGAL AGREEMENT & GUIDELINES</span>
            <h1>Terms of Use</h1>
            <p>Welcome to DigitalBhudev. By accessing or using our platform, you agree to comply with and be bound by the following terms.</p>
        </div>
    </section>

    <section class="content-section">
        <div class="container" style="max-width:880px;margin:0 auto">
            <div style="background:var(--card-bg);border:1px solid var(--primary-border);border-radius:var(--radius-lg);padding:38px;box-shadow:var(--shadow);display:flex;flex-direction:column;gap:24px">
                <div>
                    <h2 style="font-family:'Playfair Display',serif;font-size:22px;color:var(--heading);margin:0 0 8px">1. Acceptance of Terms</h2>
                    <p style="color:var(--ink);font-size:14px;line-height:1.7;margin:0">
                        DigitalBhudev provides an online bridge connecting devotees with verified Vedic Brahmin priests (Bhudevs) across Gujarat and beyond. By registering, browsing, or booking services, you acknowledge that you have read, understood, and agreed to these Terms of Use.
                    </p>
                </div>

                <div>
                    <h2 style="font-family:'Playfair Display',serif;font-size:22px;color:var(--heading);margin:0 0 8px">2. Bhudev Registration & Verification</h2>
                    <p style="color:var(--ink);font-size:14px;line-height:1.7;margin:0">
                        Priests applying for registration agree to provide accurate personal, educational, and spiritual background information. DigitalBhudev administrators verify qualifications, Veda branch, and experience before activating public profiles. We reserve the right to suspend or remove any profile containing misleading or fraudulent information.
                    </p>
                </div>

                <div>
                    <h2 style="font-family:'Playfair Display',serif;font-size:22px;color:var(--heading);margin:0 0 8px">3. Devotee Bookings & Ceremony Conduct</h2>
                    <p style="color:var(--ink);font-size:14px;line-height:1.7;margin:0">
                        Devotees submitting ceremony inquiries or booking requests agree to treat priests with sacred respect (Maryada) and provide truthful ceremony requirements and location details. Direct honorarium (Dakshina) and Samagri arrangements are coordinated between devotees and the assigned priest respectfully.
                    </p>
                </div>

                <div>
                    <h2 style="font-family:'Playfair Display',serif;font-size:22px;color:var(--heading);margin:0 0 8px">4. Platform Role & Limitations</h2>
                    <p style="color:var(--ink);font-size:14px;line-height:1.7;margin:0">
                        While DigitalBhudev verifies credentials to ensure authenticity and quality, we act as a facilitation bridge. We encourage open communication prior to ceremonies to confirm exact Muhurat times, Samagri lists, and ceremony logistics.
                    </p>
                </div>

                <div>
                    <h2 style="font-family:'Playfair Display',serif;font-size:22px;color:var(--heading);margin:0 0 8px">5. Intellectual Property & Updates</h2>
                    <p style="color:var(--ink);font-size:14px;line-height:1.7;margin:0">
                        All platform content, graphics, Panchang calculations, and branding remain the property of DigitalBhudev. These terms may be updated periodically to reflect new features and community guidelines. Continued use after changes indicates acceptance.
                    </p>
                </div>

                <div>
                    <h2 style="font-family:'Playfair Display',serif;font-size:22px;color:var(--heading);margin:0 0 8px">6. Questions or Support</h2>
                    <p style="color:var(--ink);font-size:14px;line-height:1.7;margin:0">
                        If you have questions regarding our terms or require assistance with a booking or priest verification, please contact our administrative team via our <a href="{{ route('contact') }}" style="color:var(--primary);font-weight:600">Contact Us</a> page.
                    </p>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
