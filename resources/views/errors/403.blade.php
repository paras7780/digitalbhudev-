@extends('layouts.app')

@section('title', '403 — Unauthorized Access | DigitalBhudev')

@section('content')
<main>
    <section class="content-section" style="padding:80px 0;text-align:center">
        <div class="container" style="max-width:580px;margin:0 auto">
            <div style="width:72px;height:72px;border-radius:50%;background:var(--primary-soft);color:var(--primary);border:1.5px solid var(--primary-border);display:inline-flex;align-items:center;justify-content:center;font-size:32px;margin-bottom:16px;box-shadow:0 4px 12px rgba(219,39,119,0.12)">
                🔒
            </div>
            <h1 style="font-family:'Playfair Display',serif;font-size:32px;color:var(--heading);margin:0 0 10px">
                Access Restricted
            </h1>
            <p style="color:var(--muted);font-size:15px;line-height:1.6;margin:0 0 28px">
                You do not have administrative privileges to access this area. If you are a Bhudev, please access your dedicated portal.
            </p>

            <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
                @auth
                    @if(auth()->user()->isBhudev())
                        <a href="{{ route('bhudev.dashboard') }}" class="btn btn-primary">
                            Go to My Bhudev Portal &rarr;
                        </a>
                    @elseif(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                            Go to Admin Dashboard &rarr;
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary">
                        Log In &rarr;
                    </a>
                @endauth
                <a href="{{ route('home') }}" class="btn btn-light" style="border-radius:var(--radius-full)">
                    Return to Homepage
                </a>
            </div>
        </div>
    </section>
</main>
@endsection
