@extends('layouts.app')

@section('title', "Today's Daily Rashifal — " . \Carbon\Carbon::parse($date)->format('d F Y') . ' | DigitalBhudev')
@section('meta_description', "Today's Vedic Rashifal predictions for all twelve zodiac signs for " . \Carbon\Carbon::parse($date)->format('l, d F Y'))

@section('content')
@php
    $zodiacMeta = [
        'Aries' => ['symbol' => '♈', 'hindi' => 'Mesh (मेष)', 'color' => '#b94e20'],
        'Taurus' => ['symbol' => '♉', 'hindi' => 'Vrishabh (वृषभ)', 'color' => '#405b46'],
        'Gemini' => ['symbol' => '♊', 'hindi' => 'Mithun (मिथुन)', 'color' => '#3a6073'],
        'Cancer' => ['symbol' => '♋', 'hindi' => 'Kark (कर्क)', 'color' => '#83580b'],
        'Leo' => ['symbol' => '♌', 'hindi' => 'Simha (सिंह)', 'color' => '#9c411a'],
        'Virgo' => ['symbol' => '♍', 'hindi' => 'Kanya (कन्या)', 'color' => '#3b6e51'],
        'Libra' => ['symbol' => '♎', 'hindi' => 'Tula (तुला)', 'color' => '#7b4397'],
        'Scorpio' => ['symbol' => '♏', 'hindi' => 'Vrishchik (वृश्चिक)', 'color' => '#8e1b1b'],
        'Sagittarius' => ['symbol' => '♐', 'hindi' => 'Dhanu (धनु)', 'color' => '#a06000'],
        'Capricorn' => ['symbol' => '♑', 'hindi' => 'Makar (मकर)', 'color' => '#4a5568'],
        'Aquarius' => ['symbol' => '♒', 'hindi' => 'Kumbh (कुंभ)', 'color' => '#2b6cb0'],
        'Pisces' => ['symbol' => '♓', 'hindi' => 'Meen (मीन)', 'color' => '#2c7a7b'],
    ];
    $carbon = \Carbon\Carbon::parse($date);
@endphp

<main>
    <section class="page-hero">
        <div class="container">
            <span class="eyebrow">TODAY'S HOROSCOPE</span>
            <h1>Daily Rashifal</h1>
            <p>Planetary insights and Vedic guidance for all 12 Rashi signs for today, <strong>{{ $carbon->format('l, d F Y') }}</strong>.</p>
        </div>
    </section>

    <section class="content-section">
        <div class="container">
            <!-- Current Date Badge -->
            <div style="background:var(--card-bg);border:1px solid var(--primary-border);border-radius:var(--radius-lg);padding:18px 24px;margin-bottom:28px;box-shadow:var(--shadow);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
                <div style="display:flex;align-items:center;gap:10px">
                    <span style="font-size:20px;color:var(--primary)">📅</span>
                    <div>
                        <strong style="font-size:15px;color:var(--heading)">Today: {{ $carbon->format('l, d F Y') }}</strong>
                        <small style="display:block;color:var(--muted)">Synchronized daily according to Indian Standard Time (IST)</small>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
                    @php
                        $rashifalOverallWa = "♈ *DigitalBhudev Dainik Rashifal — {$carbon->format('d M Y')}*\n"
                            . "Check today's horoscope & planetary predictions for all 12 Rashi signs:\n"
                            . route('rashifal.index');
                    @endphp
                    <a href="https://api.whatsapp.com/send?text={{ urlencode($rashifalOverallWa) }}" target="_blank" class="btn btn-light btn-sm" style="background:#eaf7ee;color:#1e7e34;border-color:#bce5c7;font-weight:700;display:inline-flex;align-items:center;gap:6px;border-radius:var(--radius-full)">
                        <span>📲</span> Share on WhatsApp
                    </a>
                    <span class="tag" style="background:var(--primary-soft);color:var(--primary);border:1px solid var(--primary-border);font-weight:700;font-size:11px;padding:6px 14px;border-radius:var(--radius-full)">
                        ● Current Day Predictions
                    </span>
                </div>
            </div>

            <!-- 12 Signs Grid -->
            <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(320px, 1fr));gap:20px">
                @foreach(['Aries','Taurus','Gemini','Cancer','Leo','Virgo','Libra','Scorpio','Sagittarius','Capricorn','Aquarius','Pisces'] as $sign)
                    @php
                        $meta = $zodiacMeta[$sign];
                        $item = $items->get($sign);
                    @endphp
                    <article class="service-card" style="display:flex;flex-direction:column;min-height:auto;padding:24px;border-radius:var(--radius-lg);border:1px solid var(--card-border);box-shadow:var(--shadow)">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px">
                            <div style="display:flex;align-items:center;gap:12px">
                                <div style="width:44px;height:44px;border-radius:50%;background:var(--primary-soft);border:1px solid var(--primary-border);display:grid;place-items:center;font-size:22px;color:{{ $meta['color'] }}">
                                    {{ $meta['symbol'] }}
                                </div>
                                <div>
                                    <h3 style="margin:0;font-size:17px;color:var(--heading)">{{ $sign }}</h3>
                                    <small style="color:var(--muted);font-weight:600">{{ $meta['hindi'] }}</small>
                                </div>
                            </div>
                            <span class="tag" style="background:var(--primary-soft);color:var(--primary);border:1px solid var(--primary-border);font-weight:700;border-radius:var(--radius-full)">Today</span>
                        </div>

                        @if($item)
                            <h4 style="font-size:14px;color:var(--primary);margin:0 0 10px;line-height:1.4">{{ $item->title }}</h4>
                            <div style="font-size:12px;color:var(--ink);line-height:1.8;white-space:pre-line;flex:1">
                                {{ $item->content }}
                            </div>
                            @php
                                $signWa = "✨ *{$sign} ({$meta['hindi']}) Rashifal — {$carbon->format('d M Y')}*\n"
                                    . "{$item->title}\n"
                                    . \Illuminate\Support\Str::limit(strip_tags($item->content), 180) . "\n\n"
                                    . "👉 Read full horoscope on DigitalBhudev: " . route('rashifal.index');
                            @endphp
                            <div style="margin-top:14px;padding-top:10px;border-top:1px solid var(--line)">
                                <a href="https://api.whatsapp.com/send?text={{ urlencode($signWa) }}" target="_blank" style="font-size:11px;color:#1e7e34;text-decoration:none;font-weight:600;display:inline-flex;align-items:center;gap:4px">
                                    <span>📲</span> Share {{ $sign }} on WhatsApp
                                </a>
                            </div>
                        @else
                            <p style="color:var(--muted);font-size:12px">Updating predictions for today...</p>
                        @endif
                    </article>
                @endforeach
            </div>
        </div>
    </section>
</main>
@endsection
