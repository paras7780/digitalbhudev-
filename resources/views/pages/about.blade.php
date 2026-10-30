@extends('layouts.app')

@section('title', 'About Us — Vishwas No Setu | DigitalBhudev')
@section('meta_description', 'DigitalBhudev is a dedicated digital platform bridging devotees with verified Brahmin Bhudevs for traditional Vedic ceremonies across Gujarat.')

@section('content')
<main>
    <section class="page-hero">
        <div class="container">
            <span class="eyebrow">OUR SACRED PURPOSE</span>
            <h1>A Bridge Between Tradition &amp; Modernity</h1>
            <p>Preserving timeless Vedic heritage and connecting families with verified Brahmin priests across Gujarat.</p>
        </div>
    </section>

    <section class="content-section">
        <div class="container" style="max-width:920px;margin:0 auto">
            {{-- Mission Overview --}}
            <div style="background:var(--card-bg);border:1px solid var(--primary-border);border-radius:var(--radius-lg);padding:36px;box-shadow:var(--shadow);margin-bottom:32px">
                <div style="display:flex;align-items:center;gap:14px;margin-bottom:18px">
                    <span style="width:48px;height:48px;border-radius:50%;background:var(--primary-soft);color:var(--primary);border:1px solid var(--primary-border);display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0">
                        ॐ
                    </span>
                    <div>
                        <h2 style="font-family:'Playfair Display',serif;font-size:24px;color:var(--heading);margin:0">
                            Vishwas No Setu — The Bridge of Faith
                        </h2>
                        <small style="color:var(--primary);font-weight:600;font-size:13px">Connecting Generations with Sanatana Dharma</small>
                    </div>
                </div>

                <p style="color:var(--ink);font-size:15px;line-height:1.8;margin:0 0 16px">
                    Finding an authentic, experienced Brahmin priest (Bhudev) for ceremonies like Griha Pravesh, Vivah, Satyanarayan Katha, or Rudrabhishek has traditionally depended on word of mouth. In fast-paced modern life, families moving to new cities often struggle to find trusted local priests.
                </p>
                <p style="color:var(--ink);font-size:15px;line-height:1.8;margin:0">
                    <strong>DigitalBhudev</strong> was created to solve this challenge. We provide a transparent, dedicated platform where devotees can discover verified priests, check their qualifications, download free samagri checklists, and connect directly via phone and WhatsApp with zero middleman commissions.
                </p>
            </div>

            {{-- 4 Core Values Grid --}}
            <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(260px, 1fr));gap:20px;margin-bottom:36px">
                <div style="background:var(--card-bg);border:1px solid var(--card-border);border-radius:var(--radius-lg);padding:24px;box-shadow:var(--shadow)">
                    <span style="font-size:28px;display:block;margin-bottom:10px">🛡️</span>
                    <h3 style="font-family:'Playfair Display',serif;font-size:18px;margin:0 0 8px;color:var(--heading)">Verified Lineage</h3>
                    <p style="color:var(--muted);font-size:13px;line-height:1.6;margin:0">
                        Every Bhudev account is individually reviewed by our administration before appearing in public listings.
                    </p>
                </div>

                <div style="background:var(--card-bg);border:1px solid var(--card-border);border-radius:var(--radius-lg);padding:24px;box-shadow:var(--shadow)">
                    <span style="font-size:28px;display:block;margin-bottom:10px">🤝</span>
                    <h3 style="font-family:'Playfair Display',serif;font-size:18px;margin:0 0 8px;color:var(--heading)">Zero Middleman Fees</h3>
                    <p style="color:var(--muted);font-size:13px;line-height:1.6;margin:0">
                        Devotees connect directly with priests. We take no commission or booking fees on religious ceremonies.
                    </p>
                </div>

                <div style="background:var(--card-bg);border:1px solid var(--card-border);border-radius:var(--radius-lg);padding:24px;box-shadow:var(--shadow)">
                    <span style="font-size:28px;display:block;margin-bottom:10px">📖</span>
                    <h3 style="font-family:'Playfair Display',serif;font-size:18px;margin:0 0 8px;color:var(--heading)">Authentic Vidhi</h3>
                    <p style="color:var(--muted);font-size:13px;line-height:1.6;margin:0">
                        Our priests conduct ceremonies according to Shastras with clear mantra pronunciation and sacred dedication.
                    </p>
                </div>

                <div style="background:var(--card-bg);border:1px solid var(--card-border);border-radius:var(--radius-lg);padding:24px;box-shadow:var(--shadow)">
                    <span style="font-size:28px;display:block;margin-bottom:10px">🗓️</span>
                    <h3 style="font-family:'Playfair Display',serif;font-size:18px;margin:0 0 8px;color:var(--heading)">Daily Wisdom</h3>
                    <p style="color:var(--muted);font-size:13px;line-height:1.6;margin:0">
                        Access verified daily Nitya Panchang, Daily Rashifal predictions, and Shubh Muhurat dates every morning.
                    </p>
                </div>
            </div>

            {{-- Dual CTA Section --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px">
                <div style="background:var(--card-bg);border:1px solid var(--primary-border);border-radius:var(--radius-lg);padding:28px;text-align:center;box-shadow:var(--shadow)">
                    <span style="font-size:32px;display:block;margin-bottom:8px">🙏</span>
                    <h3 style="font-family:'Playfair Display',serif;font-size:20px;color:var(--heading);margin:0 0 8px">For Devotees</h3>
                    <p style="color:var(--muted);font-size:13px;line-height:1.6;margin:0 0 18px">
                        Find an experienced Bhudev in Ahmedabad, Surat, Vadodara, Rajkot, or your local town.
                    </p>
                    <a href="{{ route('bhudevs.index') }}" class="btn btn-primary" style="font-size:13px">
                        Find a Bhudev &rarr;
                    </a>
                </div>

                <div style="background:var(--card-bg);border:1px solid var(--primary-border);border-radius:var(--radius-lg);padding:28px;text-align:center;box-shadow:var(--shadow)">
                    <span style="font-size:32px;display:block;margin-bottom:8px">🕉️</span>
                    <h3 style="font-family:'Playfair Display',serif;font-size:20px;color:var(--heading);margin:0 0 8px">For Brahmin Priests</h3>
                    <p style="color:var(--muted);font-size:13px;line-height:1.6;margin:0 0 18px">
                        Join Gujarat's fastest growing community of verified Bhudevs and receive direct devotee inquiries.
                    </p>
                    <a href="{{ route('register') }}" class="btn btn-light" style="font-size:13px;border-color:var(--primary-border);color:var(--primary);font-weight:600;border-radius:var(--radius-full)">
                        Register as a Bhudev &rarr;
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
