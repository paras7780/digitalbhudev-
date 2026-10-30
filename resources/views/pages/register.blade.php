@extends('layouts.app')

@section('title', 'Join DigitalBhudev | Bhudev Registration')
@section('meta_description', 'Register as a Vedic Brahmin priest on DigitalBhudev. Showcase your services, receive direct bookings, and connect with devotees.')

@section('content')
<main>
    <section class="page-hero">
        <div class="container">
            <span class="eyebrow">VEDIC COMMUNITY</span>
            <h1>Register as a Bhudev</h1>
            <p>Create your priest account, showcase your ceremonies, and receive direct puja bookings from devotees across your region.</p>
        </div>
    </section>

    <section class="content-section">
        <div class="container" style="max-width:580px;margin:0 auto">
            <div class="form-shell" style="background:var(--card-bg);border:1px solid var(--primary-border);border-radius:var(--radius-lg);padding:38px;box-shadow:var(--shadow)">
                <div style="text-align:center;margin-bottom:26px">
                    <div style="width:64px;height:64px;border-radius:50%;background:var(--primary-soft);color:var(--primary);border:1.5px solid var(--primary-border);display:inline-flex;align-items:center;justify-content:center;font-size:28px;margin-bottom:12px;box-shadow:0 4px 12px rgba(219,39,119,0.12)">
                        🕉️
                    </div>
                    <h2 style="font-family:'Playfair Display',serif;font-size:24px;margin:0 0 6px;color:var(--heading)">
                        Bhudev Registration
                    </h2>
                    <p style="color:var(--muted);font-size:13px;margin:0">
                        Join Gujarat's trusted network of verified Vedic priests.
                    </p>
                </div>

                @if($errors->any())
                    <div style="background:var(--rose-soft);border:1px solid var(--rose-border);border-radius:var(--radius-sm);padding:12px 16px;margin-bottom:20px;color:var(--rose);font-size:13px">
                        <strong style="display:block;margin-bottom:4px">Please correct the following:</strong>
                        <ul style="margin:0;padding-left:18px">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register.store') }}">
                    @csrf

                    <div class="form-row" style="margin-bottom:18px">
                        <label for="name" style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:var(--heading)">
                            Full Name <span style="color:var(--primary)">*</span>
                        </label>
                        <input id="name" name="name" value="{{ old('name') }}" autocomplete="name" required maxlength="120" placeholder="e.g. Pandit Rameshwar Joshi" style="width:100%;padding:10px 14px;border:1px solid var(--line);border-radius:var(--radius-sm);font-size:14px">
                    </div>

                    <div class="form-row" style="margin-bottom:18px">
                        <label for="phone" style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:var(--heading)">
                            Mobile / WhatsApp Number <span style="color:var(--primary)">*</span>
                        </label>
                        <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" autocomplete="tel" required maxlength="25" placeholder="e.g. 98250 12345" style="width:100%;padding:10px 14px;border:1px solid var(--line);border-radius:var(--radius-sm);font-size:14px">
                        <small style="display:block;margin-top:4px;color:var(--muted);font-size:11px">Devotees will use this number to contact you once approved.</small>
                    </div>

                    <div class="form-row" style="margin-bottom:18px">
                        <label for="email" style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:var(--heading)">
                            Email Address <span style="color:var(--primary)">*</span>
                        </label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required placeholder="you@example.com" style="width:100%;padding:10px 14px;border:1px solid var(--line);border-radius:var(--radius-sm);font-size:14px">
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:20px">
                        <div class="form-row" style="margin:0">
                            <label for="password" style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:var(--heading)">
                                Password <span style="color:var(--primary)">*</span>
                            </label>
                            <input id="password" name="password" type="password" autocomplete="new-password" required minlength="12" placeholder="Min. 12 chars" style="width:100%;padding:10px 14px;border:1px solid var(--line);border-radius:var(--radius-sm);font-size:14px">
                        </div>

                        <div class="form-row" style="margin:0">
                            <label for="password_confirmation" style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:var(--heading)">
                                Confirm Password <span style="color:var(--primary)">*</span>
                            </label>
                            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required minlength="12" placeholder="Re-enter password" style="width:100%;padding:10px 14px;border:1px solid var(--line);border-radius:var(--radius-sm);font-size:14px">
                        </div>
                    </div>

                    <div style="background:var(--blue-soft);border:1px solid var(--blue-border);border-radius:var(--radius-sm);padding:12px 14px;margin-bottom:22px;font-size:12px;color:#1e40af;line-height:1.5">
                        🛡️ <strong>Verification Policy:</strong> After registration, verify your email and complete your profile. Your profile will be reviewed by our administrator before appearing in public searches.
                    </div>

                    <button class="btn btn-primary" type="submit" style="width:100%;padding:12px;font-size:14px;font-weight:600">
                        Create Bhudev Account &rarr;
                    </button>
                </form>

                <div style="margin-top:24px;padding-top:18px;border-top:1px solid var(--line);text-align:center;font-size:13px">
                    <span style="color:var(--muted)">Already have an account?</span>
                    <a href="{{ route('login') }}" style="color:var(--primary);font-weight:600;text-decoration:none;margin-left:4px">
                        Log In &rarr;
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
