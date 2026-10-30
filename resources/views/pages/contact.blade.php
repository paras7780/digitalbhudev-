@extends('layouts.app')

@section('title', 'Contact Us & Devotee Support | DigitalBhudev')
@section('meta_description', 'Connect with the DigitalBhudev team for help finding a Bhudev, puja inquiries, or technical support.')

@section('content')
<main>
    <section class="page-hero">
        <div class="container">
            <span class="eyebrow">DEVOTEE SUPPORT & INQUIRIES</span>
            <h1>We Are Here to Help</h1>
            <p>Connect with our team for assistance finding a Bhudev, organizing ceremonies, or joining as a priest.</p>
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

            @if($errors->any())
                <div style="background:#fdeeee;border:1px solid #f8c9c9;border-radius:8px;padding:14px 20px;margin-bottom:28px;color:#c02b2b;font-size:14px">
                    <strong style="display:block;margin-bottom:6px">Please fix the following:</strong>
                    <ul style="margin:0;padding-left:18px">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="contact-grid" style="display:grid;grid-template-columns:1.5fr 1fr;gap:36px;align-items:start">
                {{-- Left: Contact Form --}}
                <div style="background:var(--card-bg);border:1px solid var(--primary-border);border-radius:var(--radius-lg);padding:36px;box-shadow:var(--shadow)">
                    <h2 style="font-family:'Playfair Display',serif;font-size:24px;color:var(--heading);margin:0 0 8px">
                        Send an Inquiry
                    </h2>
                    <p style="color:var(--muted);font-size:14px;margin:0 0 24px">
                        Fill in your details below and our team will get in touch via WhatsApp or phone.
                    </p>

                    <form method="POST" action="{{ route('contact.store') }}">
                        @csrf

                        <div style="margin-bottom:18px">
                            <label for="name" style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:var(--heading)">
                                Your Full Name <span style="color:var(--primary)">*</span>
                            </label>
                            <input id="name" name="name" value="{{ old('name') }}" required maxlength="120" placeholder="e.g. Anand Sharma" style="width:100%;padding:10px 14px;border:1px solid var(--line);border-radius:var(--radius-sm);font-size:14px">
                        </div>

                        <div class="form-grid-2" style="display:grid;grid-template-columns:1fr 1fr;gap:18px;margin-bottom:18px">
                            <div>
                                <label for="phone" style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:var(--heading)">
                                    WhatsApp / Mobile Number
                                </label>
                                <input id="phone" name="phone" value="{{ old('phone') }}" maxlength="25" placeholder="e.g. 98250 12345" style="width:100%;padding:10px 14px;border:1px solid var(--line);border-radius:var(--radius-sm);font-size:14px">
                            </div>

                            <div>
                                <label for="email" style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:var(--heading)">
                                    Email Address <span style="color:var(--primary)">*</span>
                                </label>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="you@example.com" style="width:100%;padding:10px 14px;border:1px solid var(--line);border-radius:var(--radius-sm);font-size:14px">
                            </div>
                        </div>

                        <div style="margin-bottom:18px">
                            <label for="subject" style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:var(--heading)">
                                Subject / Ceremony Needed
                            </label>
                            <input id="subject" name="subject" value="{{ old('subject') }}" maxlength="180" placeholder="e.g. Need Bhudev for Griha Pravesh in Ahmedabad" style="width:100%;padding:10px 14px;border:1px solid var(--line);border-radius:var(--radius-sm);font-size:14px">
                        </div>

                        <div style="margin-bottom:24px">
                            <label for="message" style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:var(--heading)">
                                Your Message / Questions <span style="color:var(--primary)">*</span>
                            </label>
                            <textarea id="message" name="message" rows="5" required maxlength="5000" placeholder="Please describe how we can assist you..." style="width:100%;padding:10px 14px;border:1px solid var(--line);border-radius:var(--radius-sm);font-size:14px">{{ old('message') }}</textarea>
                        </div>

                        {{-- Honeypot spam defense --}}
                        <input type="text" name="website" style="display:none" tabindex="-1" autocomplete="off">

                        <button class="btn btn-primary" type="submit" style="padding:12px 28px;font-size:14px;font-weight:600">
                            Send Inquiry &rarr;
                        </button>
                    </form>
                </div>

                {{-- Right: Direct Reach & FAQ --}}
                <div style="display:flex;flex-direction:column;gap:20px">
                    {{-- WhatsApp Support Card --}}
                    <div style="background:var(--card-bg);border:1px solid var(--primary-border);border-radius:var(--radius-lg);padding:26px;box-shadow:var(--shadow)">
                        <div style="width:48px;height:48px;border-radius:50%;background:#eaf7ee;color:#25d366;display:flex;align-items:center;justify-content:center;font-size:24px;margin-bottom:14px">
                            💬
                        </div>
                        <h3 style="font-family:'Playfair Display',serif;font-size:20px;color:var(--heading);margin:0 0 6px">
                            Instant WhatsApp Support
                        </h3>
                        <p style="color:var(--muted);font-size:13px;line-height:1.6;margin:0 0 16px">
                            Need fast assistance finding a priest or choosing a Muhurat? Chat directly with our coordinator on WhatsApp.
                        </p>
                        <a href="https://api.whatsapp.com/send?phone=919876543210&text={{ urlencode('Namaste DigitalBhudev, I need assistance with a Vedic ceremony.') }}" target="_blank" class="btn" style="background:#25d366;color:#ffffff;border:none;width:100%;padding:11px;font-size:14px;font-weight:700;display:flex;align-items:center;justify-content:center;gap:8px;border-radius:var(--radius-full)">
                            Chat on WhatsApp &rarr;
                        </a>
                    </div>

                    {{-- Response Guarantee --}}
                    <div style="background:var(--blue-soft);border:1px solid var(--blue-border);border-radius:var(--radius-md);padding:22px">
                        <h4 style="font-family:'Playfair Display',serif;font-size:16px;color:#1e40af;margin:0 0 8px">
                            ⏱️ Fast Response Guarantee
                        </h4>
                        <p style="color:#1e3a8a;font-size:13px;line-height:1.6;margin:0">
                            We respond to all devotee and Bhudev inquiries within <strong>2 hours</strong> during standard operating hours (8:00 AM – 9:00 PM IST).
                        </p>
                    </div>

                    {{-- Quick FAQ --}}
                    <div style="background:var(--card-bg);border:1px solid var(--card-border);border-radius:var(--radius-lg);padding:24px;box-shadow:var(--shadow)">
                        <h4 style="font-family:'Playfair Display',serif;font-size:16px;color:var(--heading);margin:0 0 12px">
                            Frequently Asked Questions
                        </h4>
                        <div style="display:flex;flex-direction:column;gap:12px;font-size:13px">
                            <div>
                                <strong style="color:var(--primary);display:block;margin-bottom:2px">How do I book a Bhudev?</strong>
                                <span style="color:var(--muted)">Browse verified profiles on our <a href="{{ route('bhudevs.index') }}" style="color:var(--primary);font-weight:600">Find a Bhudev</a> page and click "Book on WhatsApp" or send an inquiry.</span>
                            </div>
                            <div>
                                <strong style="color:var(--primary);display:block;margin-bottom:2px">Are Bhudevs verified?</strong>
                                <span style="color:var(--muted)">Yes. Every Bhudev profile is individually reviewed by our administration before approval.</span>
                            </div>
                            <div>
                                <strong style="color:var(--primary);display:block;margin-bottom:2px">Are you a Brahmin priest?</strong>
                                <span style="color:var(--muted)">You can join our network by registering through our <a href="{{ route('register') }}" style="color:var(--primary);font-weight:600">Bhudev Registration</a> portal.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
