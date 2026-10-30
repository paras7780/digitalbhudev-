@extends('layouts.app')

@section('title', "Today's Nitya Panchang — " . \Carbon\Carbon::parse($date)->format('d F Y') . ' | DigitalBhudev')
@section('meta_description', "Today's traditional Hindu Panchang with Tithi, Vaar, Nakshatra, Yoga, Karana, Sunrise, Sunset and Auspicious Muhurats.")

@section('content')
@php
    $carbon = \Carbon\Carbon::parse($date);
@endphp

<main>
    <section class="page-hero">
        <div class="container">
            <span class="eyebrow">TODAY'S CALENDAR</span>
            <h1>Nitya Panchang</h1>
            <p>Traditional daily Hindu Panchang for today, <strong>{{ $carbon->format('l, d F Y') }}</strong> ({{ $location ?: 'Ahmedabad, Gujarat' }}).</p>
        </div>
    </section>

    <section class="content-section">
        <div class="container">
            <!-- Location Switcher Form & Today Badge -->
            <div style="background:var(--card-bg);border:1px solid var(--primary-border);border-radius:var(--radius-lg);padding:18px 24px;margin-bottom:28px;box-shadow:var(--shadow);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px">
                <form method="GET" action="{{ route('panchang.index') }}" style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
                    <label for="location" style="font-weight:700;font-size:13px;color:var(--heading)">📍 Select City:</label>
                    <input type="text" id="location" name="location" value="{{ $location }}" placeholder="e.g. Ahmedabad, Surat, Rajkot" style="padding:9px 12px;border:1px solid var(--line);border-radius:var(--radius-sm);font-size:13px;background:#fff;min-width:200px">
                    <button class="btn btn-primary btn-sm" type="submit">Update City</button>
                    @if($location)
                        <a href="{{ route('panchang.index') }}" class="btn btn-light btn-sm" title="Reset to default">Default (Ahmedabad)</a>
                    @endif
                </form>

                <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
                    @if($entry)
                        @php
                            $panchangWa = "🪔 *DigitalBhudev Aajnu Panchang — {$carbon->format('d M Y')}*\n"
                                . "📍 Location: " . ($entry->location ?: 'Ahmedabad, Gujarat') . "\n"
                                . "🗓️ Tithi: {$entry->tithi}\n"
                                . "⚡ Vaar: {$entry->vaar} | ⭐ Nakshatra: {$entry->nakshatra}\n"
                                . "☀️ Sunrise: {$entry->sunrise} | 🌇 Sunset: {$entry->sunset}\n"
                                . "👉 View full Panchang: " . route('panchang.index');
                        @endphp
                        <a href="https://api.whatsapp.com/send?text={{ urlencode($panchangWa) }}" target="_blank" class="btn btn-light btn-sm" style="background:#eaf7ee;color:#1e7e34;border-color:#bce5c7;font-weight:700;display:inline-flex;align-items:center;gap:6px;border-radius:var(--radius-full)">
                            <span>📲</span> Share on WhatsApp
                        </a>
                    @endif
                    <span class="tag" style="background:var(--primary-soft);color:var(--primary);border:1px solid var(--primary-border);font-weight:700;font-size:11px;padding:6px 14px;border-radius:var(--radius-full)">
                        ● Current Day: {{ $carbon->format('d M Y') }}
                    </span>
                </div>
            </div>

            @if($entry)
                <!-- Main Panchang Highlights Banner -->
                <div style="background:linear-gradient(135deg, #fdf2f8 0%, #ffffff 50%, #fce7f3 100%);border:1px solid var(--primary-border);border-radius:var(--radius-lg);padding:30px;margin-bottom:28px;box-shadow:var(--shadow)">
                    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:20px">
                        <div>
                            <span class="eyebrow">DAINIK PANCHANG</span>
                            <h2 style="font-family:'Playfair Display',Georgia,serif;font-size:28px;margin:6px 0;color:var(--heading)">
                                {{ $entry->tithi }}
                            </h2>
                            <div style="font-size:13px;color:var(--muted)">
                                <strong>{{ $entry->vaar }}</strong> · Location: {{ $entry->location ?: 'Ahmedabad, Gujarat' }} · Timezone: {{ $entry->timezone }}
                            </div>
                        </div>
                        <div style="background:var(--card-bg);padding:14px 22px;border-radius:var(--radius-md);border:1px solid var(--primary-border);text-align:center;box-shadow:var(--shadow-sm)">
                            <span style="display:block;font-size:10px;font-weight:700;color:var(--primary);letter-spacing:.1em">CURRENT DATE</span>
                            <strong style="font-size:18px;color:var(--heading)">{{ $carbon->format('d M Y') }}</strong>
                        </div>
                    </div>

                    <!-- 5 Vedic Limbs -->
                    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(180px, 1fr));gap:14px">
                        <div style="background:var(--card-bg);padding:16px;border-radius:var(--radius-md);border:1px solid var(--card-border);box-shadow:var(--shadow-sm)">
                            <small style="display:block;font-size:10px;font-weight:700;color:var(--muted);text-transform:uppercase">Tithi (तिथि)</small>
                            <strong style="font-size:15px;color:var(--heading);margin-top:4px;display:block">{{ $entry->tithi }}</strong>
                        </div>
                        <div style="background:var(--card-bg);padding:16px;border-radius:var(--radius-md);border:1px solid var(--card-border);box-shadow:var(--shadow-sm)">
                            <small style="display:block;font-size:10px;font-weight:700;color:var(--muted);text-transform:uppercase">Vaar (वार)</small>
                            <strong style="font-size:15px;color:var(--heading);margin-top:4px;display:block">{{ $entry->vaar }}</strong>
                        </div>
                        <div style="background:var(--card-bg);padding:16px;border-radius:var(--radius-md);border:1px solid var(--card-border);box-shadow:var(--shadow-sm)">
                            <small style="display:block;font-size:10px;font-weight:700;color:var(--muted);text-transform:uppercase">Nakshatra (नक्षत्र)</small>
                            <strong style="font-size:15px;color:var(--heading);margin-top:4px;display:block">{{ $entry->nakshatra }}</strong>
                        </div>
                        <div style="background:var(--card-bg);padding:16px;border-radius:var(--radius-md);border:1px solid var(--card-border);box-shadow:var(--shadow-sm)">
                            <small style="display:block;font-size:10px;font-weight:700;color:var(--muted);text-transform:uppercase">Yoga (योग)</small>
                            <strong style="font-size:15px;color:var(--heading);margin-top:4px;display:block">{{ $entry->yoga }}</strong>
                        </div>
                        <div style="background:var(--card-bg);padding:16px;border-radius:var(--radius-md);border:1px solid var(--card-border);box-shadow:var(--shadow-sm)">
                            <small style="display:block;font-size:10px;font-weight:700;color:var(--muted);text-transform:uppercase">Karana (करण)</small>
                            <strong style="font-size:15px;color:var(--heading);margin-top:4px;display:block">{{ $entry->karana }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Sun & Moon Astronomical Timings -->
                <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(240px, 1fr));gap:20px;margin-bottom:28px">
                    <div style="background:var(--card-bg);border:1px solid var(--card-border);border-radius:var(--radius-lg);padding:24px;box-shadow:var(--shadow)">
                        <h3 style="font-family:'Playfair Display',Georgia,serif;font-size:18px;margin:0 0 16px;display:flex;align-items:center;gap:8px;color:var(--heading)">
                            <span>☀️ Surya (Sun) Timings</span>
                        </h3>
                        <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--line)">
                            <span style="color:var(--muted)">Sunrise (सूर्योदय)</span>
                            <strong style="color:var(--primary)">{{ $entry->sunrise ?: '—' }}</strong>
                        </div>
                        <div style="display:flex;justify-content:space-between;padding:10px 0">
                            <span style="color:var(--muted)">Sunset (सूर्यास्त)</span>
                            <strong style="color:var(--primary)">{{ $entry->sunset ?: '—' }}</strong>
                        </div>
                    </div>

                    <div style="background:var(--card-bg);border:1px solid var(--card-border);border-radius:var(--radius-lg);padding:24px;box-shadow:var(--shadow)">
                        <h3 style="font-family:'Playfair Display',Georgia,serif;font-size:18px;margin:0 0 16px;display:flex;align-items:center;gap:8px;color:var(--heading)">
                            <span>🌙 Chandra (Moon) Timings</span>
                        </h3>
                        <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--line)">
                            <span style="color:var(--muted)">Moonrise (चन्द्रोदय)</span>
                            <strong style="color:var(--heading)">{{ $entry->moonrise ?: '—' }}</strong>
                        </div>
                        <div style="display:flex;justify-content:space-between;padding:10px 0">
                            <span style="color:var(--muted)">Moonset (चन्द्रास्त)</span>
                            <strong style="color:var(--heading)">{{ $entry->moonset ?: '—' }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Muhurats & Notes -->
                @if($entry->notes)
                    <div style="background:var(--card-bg);border:1px solid var(--primary-border);border-radius:var(--radius-lg);padding:24px 28px;box-shadow:var(--shadow)">
                        <h3 style="font-family:'Playfair Display',Georgia,serif;font-size:18px;margin:0 0 10px;color:var(--primary)">
                            🕉 Auspicious Muhurats & Kalam Periods
                        </h3>
                        <p style="margin:0;font-size:13px;line-height:1.8;color:var(--ink)">{{ $entry->notes }}</p>
                    </div>
                @endif
            @else
                <div class="form-shell" style="text-align:center;padding:48px 20px;background:var(--card-bg);border:1px solid var(--primary-border);border-radius:var(--radius-lg)">
                    <h2 style="color:var(--heading)">Calculating Today's Panchang...</h2>
                    <p style="color:var(--muted)">Generating verified astronomical calculations for today.</p>
                </div>
            @endif
        </div>
    </section>
</main>
@endsection
