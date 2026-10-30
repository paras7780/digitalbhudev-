@extends('layouts.app')

@section('title', 'Log in to DigitalBhudev | Portal Access')

@section('content')
<main>
    <section class="page-hero">
        <div class="container">
            <span class="eyebrow">PORTAL ACCESS</span>
            <h1>Log in to DigitalBhudev</h1>
            <p>Access your Bhudev profile dashboard or site administrator control center.</p>
        </div>
    </section>

    <section class="content-section">
        <div class="container" style="max-width:520px;margin:0 auto">
            <div class="form-shell" style="background:#fff;border:1.5px solid var(--card-border);border-radius:var(--radius-lg);padding:40px;box-shadow:var(--shadow)">
                <div style="text-align:center;margin-bottom:26px">
                    <div style="width:72px;height:72px;display:inline-flex;align-items:center;justify-content:center;margin-bottom:14px">
                        <img src="{{ asset('assets/img/logo-mark.svg') }}" alt="DigitalBhudev" style="width:100%;height:100%;object-fit:contain;filter:drop-shadow(0 4px 12px rgba(219,39,119,0.22))">
                    </div>
                    <h2 style="font-family:'Playfair Display',serif;font-size:26px;margin:0 0 6px;color:var(--heading)">
                        Welcome Back
                    </h2>
                    <p style="color:var(--muted);font-size:13.5px;margin:0">
                        Sign in to manage your profile, pujas, or portal settings.
                    </p>
                </div>

                @if(session('status'))
                    <div style="background:#ecfdf5;border:1px solid #a7f3d0;border-radius:10px;padding:12px 16px;margin-bottom:20px;color:#047857;font-size:13px;text-align:center">
                        ✓ {{ session('status') }}
                    </div>
                @endif

                @if($errors->any())
                    <div style="background:#fff1f2;border:1px solid #fecdd3;border-radius:10px;padding:12px 16px;margin-bottom:20px;color:#be123c;font-size:13px">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.store') }}">
                    @csrf

                    <div class="form-row" style="margin-bottom:18px">
                        <label for="email" style="display:block;font-size:12.5px;font-weight:700;margin-bottom:6px;color:var(--heading)">
                            Email Address <span style="color:#e11d48">*</span>
                        </label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="username" required autofocus placeholder="you@example.com" style="width:100%;padding:11px 14px;border:1.5px solid #ebdce6;border-radius:10px;font-size:13.5px;box-sizing:border-box">
                    </div>

                    <div class="form-row" style="margin-bottom:16px">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px">
                            <label for="password" style="font-size:12.5px;font-weight:700;color:var(--heading);margin:0">
                                Password <span style="color:#e11d48">*</span>
                            </label>
                            <a href="{{ route('password.request') }}" style="font-size:12px;color:var(--primary);text-decoration:none;font-weight:600">
                                Forgot password?
                            </a>
                        </div>
                        <input id="password" type="password" name="password" autocomplete="current-password" required placeholder="••••••••••••" style="width:100%;padding:11px 14px;border:1.5px solid #ebdce6;border-radius:10px;font-size:13.5px;box-sizing:border-box">
                    </div>

                    <div style="display:flex;align-items:center;justify-content:flex-start;gap:10px;margin:12px 0 24px;text-align:left">
                        <input id="remember" type="checkbox" name="remember" value="1" style="width:18px !important;height:18px !important;min-width:18px !important;min-height:18px !important;max-width:18px !important;max-height:18px !important;margin:0 !important;padding:0 !important;cursor:pointer;accent-color:var(--primary);flex:0 0 18px !important;border-radius:4px;box-shadow:none !important">
                        <label for="remember" style="font-size:13.5px;color:var(--heading);cursor:pointer;font-weight:500;margin:0 !important;padding:0 !important;display:inline-block;line-height:1;text-align:left;user-select:none">
                            Keep me logged in
                        </label>
                    </div>

                    <button class="btn btn-primary" type="submit" style="width:100%;padding:13px;font-size:14px;font-weight:700">
                        Log in to Dashboard &rarr;
                    </button>
                </form>

                <div style="margin-top:26px;padding-top:20px;border-top:1px solid #f3e5ee;text-align:center;font-size:13px">
                    <span style="color:var(--muted)">Are you a Vedic Brahmin priest?</span><br>
                    <a href="{{ route('register') }}" style="color:var(--primary);font-weight:700;text-decoration:none;display:inline-block;margin-top:6px">
                        Create a Bhudev Profile &rarr;
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
