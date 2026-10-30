@extends('layouts.app')

@section('title', 'Vedic Shubh Muhurat 2026-2027 | DigitalBhudev')
@section('meta_description', 'Auspicious dates and timings for Vivah (Marriage), Griha Pravesh (House Warming), Vahan Kharidi, and Namkaran ceremonies.')

@section('content')
<main>
    <section class="page-hero">
        <div class="container">
            <span class="eyebrow">VEDIC ASTROLOGY GUIDE</span>
            <h1>Auspicious Shubh Muhurat Guide</h1>
            <p>Carefully calculated timings for weddings, house warming, vehicle purchases, and sacred ceremonies.</p>
        </div>
    </section>

    <section class="content-section">
        <div class="container">
            <div style="background:var(--blue-soft);border:1px solid var(--blue-border);border-radius:var(--radius-lg);padding:20px 26px;margin-bottom:36px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;box-shadow:var(--shadow-sm)">
                <div>
                    <strong style="color:#1e40af;font-size:15px;display:block">Need Personal Kundali Matching or Custom Muhurat?</strong>
                    <span style="color:#1e3a8a;font-size:13px">Our verified Vedic astrologers and Bhudevs provide personalized Muhurat consultation based on your Janma Kundali.</span>
                </div>
                <a href="{{ route('contact') }}" class="btn btn-primary" style="font-size:13px">
                    Consult an Astrologer &rarr;
                </a>
            </div>

            @foreach($muhurats as $key => $category)
                <div style="margin-bottom:48px">
                    <div style="display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:20px;flex-wrap:wrap;border-bottom:2px solid var(--line);padding-bottom:12px">
                        <div style="display:flex;align-items:center;gap:12px">
                            <span style="font-size:32px">{{ $category['icon'] }}</span>
                            <div>
                                <h2 style="font-family:'Playfair Display',serif;font-size:24px;color:var(--heading);margin:0">
                                    {{ $category['category'] }}
                                </h2>
                                <span style="font-size:14px;color:var(--primary);font-weight:600">{{ $category['category_gu'] }}</span>
                            </div>
                        </div>

                        @php
                            $waText = "🕉️ *{$category['category']} Auspicious Muhurats*\n"
                                . "Check upcoming Shubh Muhurat dates on DigitalBhudev:\n"
                                . route('muhurat.index');
                        @endphp
                        <a href="https://api.whatsapp.com/send?text={{ urlencode($waText) }}" target="_blank" class="btn btn-light" style="background:#eaf7ee;color:#1e7e34;border-color:#bce5c7;font-size:12px;border-radius:var(--radius-full)">
                            📲 Share on WhatsApp
                        </a>
                    </div>

                    <p style="color:var(--muted);font-size:14px;margin:0 0 20px;line-height:1.5">
                        {{ $category['description'] }}
                    </p>

                    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(280px, 1fr));gap:18px">
                        @foreach($category['dates'] as $mDate)
                            <div style="background:var(--card-bg);border:1px solid var(--card-border);border-radius:var(--radius-lg);padding:22px;box-shadow:var(--shadow);display:flex;flex-direction:column;justify-content:space-between">
                                <div>
                                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px">
                                        <span style="background:var(--primary-soft);color:var(--primary);border:1px solid var(--primary-border);padding:4px 12px;border-radius:var(--radius-full);font-size:12px;font-weight:700">
                                            {{ \Carbon\Carbon::parse($mDate['date'])->format('d M Y') }}
                                        </span>
                                        <span style="font-size:12px;color:var(--muted);font-weight:600">
                                            {{ $mDate['day'] }}
                                        </span>
                                    </div>

                                    <div style="font-size:13px;color:var(--ink);margin-bottom:8px">
                                        <strong style="color:var(--heading)">Tithi:</strong> {{ $mDate['tithi'] }}
                                    </div>
                                    <div style="font-size:13px;color:var(--ink);margin-bottom:12px">
                                        <strong style="color:var(--heading)">Nakshatra:</strong> {{ $mDate['nakshatra'] }}
                                    </div>

                                    <div style="background:var(--green-soft);border:1px solid var(--green-border);border-radius:var(--radius-sm);padding:10px 14px;font-size:12px;color:var(--green);margin-bottom:16px">
                                        ⏱️ <strong>Shubh Muhurat:</strong><br>{{ $mDate['time'] }}
                                    </div>
                                </div>

                                <a href="{{ route('bhudevs.index') }}?date={{ $mDate['date'] }}" class="btn btn-outline" style="text-align:center;font-size:12px;padding:8px">
                                    Book Bhudev for this Date &rarr;
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</main>
@endsection
