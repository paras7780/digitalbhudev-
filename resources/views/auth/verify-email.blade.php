@extends('layouts.app')

@section('title', 'Verify Your Email | DigitalBhudev')

@section('content')
<main>
    <section class="page-hero">
        <div class="container">
            <span class="eyebrow">ACCOUNT VERIFICATION</span>
            <h1>Verify Your Email Address</h1>
            <p>Please confirm your email address to access your Bhudev portal.</p>
        </div>
    </section>

    <section class="content-section">
        <div class="container" style="max-width:620px;margin:0 auto">
            <div class="form-shell" style="background:var(--card-bg);border:1px solid var(--primary-border);border-radius:var(--radius-lg);padding:38px;box-shadow:var(--shadow)">
                <div style="text-align:center;margin-bottom:24px">
                    <div style="width:64px;height:64px;border-radius:50%;background:var(--primary-soft);color:var(--primary);border:1.5px solid var(--primary-border);display:inline-flex;align-items:center;justify-content:center;font-size:28px;margin-bottom:12px;box-shadow:0 4px 12px rgba(219,39,119,0.12)">
                        ✉️
                    </div>
                    <h2 style="font-family:'Playfair Display',serif;font-size:24px;margin:0 0 8px;color:var(--heading)">
                        Check Your Inbox
                    </h2>
                    <p style="color:var(--muted);font-size:14px;margin:0;line-height:1.6">
                        We sent a secure verification link to:<br>
                        <strong style="color:var(--heading);font-size:15px">{{ auth()->user()->email }}</strong>
                    </p>
                </div>

                @if(session('status'))
                    <div style="background:var(--green-soft);border:1px solid var(--green-border);border-radius:var(--radius-sm);padding:12px 16px;margin-bottom:24px;color:var(--green);font-size:13px;text-align:center">
                        ✓ {{ session('status') }}
                    </div>
                @endif

                <p style="font-size:13px;color:#1e40af;line-height:1.6;margin-bottom:24px;background:var(--blue-soft);border-radius:var(--radius-sm);padding:14px 16px;border:1px solid var(--blue-border)">
                    Click the link in the email to activate your account and access your Bhudev dashboard. If you did not receive the email within a couple of minutes, check your spam folder or click below to request a new link.
                </p>

                <div style="display:flex;flex-direction:column;gap:12px;align-items:center">
                    <form method="POST" action="{{ route('verification.send') }}" style="width:100%">
                        @csrf
                        <button type="submit" class="btn btn-primary" style="width:100%;padding:12px;font-size:14px;font-weight:600">
                            Resend Verification Email
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}" style="width:100%">
                        @csrf
                        <button type="submit" class="btn btn-light" style="width:100%;padding:10px;font-size:13px">
                            Log out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
