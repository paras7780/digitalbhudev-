@extends('layouts.app')

@section('title', 'Forgot Password | DigitalBhudev')

@section('content')
<main>
    <section class="page-hero">
        <div class="container">
            <span class="eyebrow">ACCOUNT RECOVERY</span>
            <h1>Reset Your Password</h1>
            <p>Enter your registered email address and we will send you a password reset link.</p>
        </div>
    </section>

    <section class="content-section">
        <div class="container" style="max-width:540px;margin:0 auto">
            <div class="form-shell" style="background:var(--card-bg);border:1px solid var(--primary-border);border-radius:var(--radius-lg);padding:38px;box-shadow:var(--shadow)">
                <div style="text-align:center;margin-bottom:24px">
                    <div style="width:64px;height:64px;border-radius:50%;background:var(--primary-soft);color:var(--primary);border:1.5px solid var(--primary-border);display:inline-flex;align-items:center;justify-content:center;font-size:28px;margin-bottom:12px;box-shadow:0 4px 12px rgba(219,39,119,0.12)">
                        🔑
                    </div>
                    <h2 style="font-family:'Playfair Display',serif;font-size:24px;margin:0 0 6px;color:var(--heading)">
                        Password Assistance
                    </h2>
                    <p style="color:var(--muted);font-size:13px;margin:0">
                        Enter the email associated with your Bhudev or Admin account.
                    </p>
                </div>

                @if(session('status'))
                    <div style="background:var(--green-soft);border:1px solid var(--green-border);border-radius:var(--radius-sm);padding:12px 16px;margin-bottom:20px;color:var(--green);font-size:13px;text-align:center">
                        ✓ {{ session('status') }}
                    </div>
                @endif

                @if($errors->any())
                    <div style="background:var(--rose-soft);border:1px solid var(--rose-border);border-radius:var(--radius-sm);padding:12px 16px;margin-bottom:20px;color:var(--rose);font-size:13px">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="form-row" style="margin-bottom:20px">
                        <label for="email" style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:var(--heading)">
                            Registered Email Address <span style="color:var(--primary)">*</span>
                        </label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="you@example.com" style="width:100%;padding:10px 14px;border:1px solid var(--line);border-radius:var(--radius-sm);font-size:14px">
                    </div>

                    <button class="btn btn-primary" type="submit" style="width:100%;padding:12px;font-size:14px;font-weight:600">
                        Send Password Reset Link &rarr;
                    </button>
                </form>

                <div style="margin-top:24px;padding-top:18px;border-top:1px solid var(--line);text-align:center;font-size:13px">
                    <span style="color:var(--muted)">Remembered your password?</span>
                    <a href="{{ route('login') }}" style="color:var(--primary);font-weight:600;text-decoration:none;margin-left:4px">
                        Log In &rarr;
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
