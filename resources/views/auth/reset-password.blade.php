@extends('layouts.app')

@section('title', 'Set New Password | DigitalBhudev')

@section('content')
<main>
    <section class="page-hero">
        <div class="container">
            <span class="eyebrow">SECURITY RECOVERY</span>
            <h1>Choose a New Password</h1>
            <p>Enter your email and create a new secure password for your account.</p>
        </div>
    </section>

    <section class="content-section">
        <div class="container" style="max-width:540px;margin:0 auto">
            <div class="form-shell" style="background:var(--card-bg);border:1px solid var(--primary-border);border-radius:var(--radius-lg);padding:38px;box-shadow:var(--shadow)">
                <div style="text-align:center;margin-bottom:24px">
                    <div style="width:64px;height:64px;border-radius:50%;background:var(--primary-soft);color:var(--primary);border:1.5px solid var(--primary-border);display:inline-flex;align-items:center;justify-content:center;font-size:28px;margin-bottom:12px;box-shadow:0 4px 12px rgba(219,39,119,0.12)">
                        🔒
                    </div>
                    <h2 style="font-family:'Playfair Display',serif;font-size:24px;margin:0 0 6px;color:var(--heading)">
                        Set New Password
                    </h2>
                    <p style="color:var(--muted);font-size:13px;margin:0">
                        Password must be at least 12 characters with mixed case and numbers.
                    </p>
                </div>

                @if($errors->any())
                    <div style="background:var(--rose-soft);border:1px solid var(--rose-border);border-radius:var(--radius-sm);padding:12px 16px;margin-bottom:20px;color:var(--rose);font-size:13px">
                        <ul style="margin:0;padding-left:18px">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="form-row" style="margin-bottom:18px">
                        <label for="email" style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:var(--heading)">
                            Email Address <span style="color:var(--primary)">*</span>
                        </label>
                        <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autocomplete="email" style="width:100%;padding:10px 14px;border:1px solid var(--line);border-radius:var(--radius-sm);font-size:14px">
                    </div>

                    <div class="form-row" style="margin-bottom:18px">
                        <label for="password" style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:var(--heading)">
                            New Password <span style="color:var(--primary)">*</span>
                        </label>
                        <input id="password" type="password" name="password" minlength="12" required autocomplete="new-password" style="width:100%;padding:10px 14px;border:1px solid var(--line);border-radius:var(--radius-sm);font-size:14px">
                    </div>

                    <div class="form-row" style="margin-bottom:24px">
                        <label for="password_confirmation" style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:var(--heading)">
                            Confirm New Password <span style="color:var(--primary)">*</span>
                        </label>
                        <input id="password_confirmation" type="password" name="password_confirmation" minlength="12" required autocomplete="new-password" style="width:100%;padding:10px 14px;border:1px solid var(--line);border-radius:var(--radius-sm);font-size:14px">
                    </div>

                    <button class="btn btn-primary" type="submit" style="width:100%;padding:12px;font-size:14px;font-weight:600">
                        Update Password & Log In &rarr;
                    </button>
                </form>
            </div>
        </div>
    </section>
</main>
@endsection
