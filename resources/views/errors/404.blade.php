@extends('layouts.app')

@section('title', '404 — Page Not Found | DigitalBhudev')

@section('content')
<main>
    <section class="content-section" style="padding:80px 0;text-align:center">
        <div class="container" style="max-width:580px;margin:0 auto">
            <div style="width:72px;height:72px;border-radius:50%;background:var(--primary-soft);color:var(--primary);border:1.5px solid var(--primary-border);display:inline-flex;align-items:center;justify-content:center;font-size:32px;margin-bottom:16px;box-shadow:0 4px 12px rgba(219,39,119,0.12)">
                ॐ
            </div>
            <h1 style="font-family:'Playfair Display',serif;font-size:32px;color:var(--heading);margin:0 0 10px">
                Page Not Found
            </h1>
            <p style="color:var(--muted);font-size:15px;line-height:1.6;margin:0 0 28px">
                The page you are looking for may have been moved, renamed, or is unavailable.
            </p>

            <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
                <a href="{{ route('home') }}" class="btn btn-primary">
                    Return to Homepage &rarr;
                </a>
                <a href="{{ route('bhudevs.index') }}" class="btn btn-light" style="border-radius:var(--radius-full)">
                    Find a Bhudev
                </a>
            </div>
        </div>
    </section>
</main>
@endsection
