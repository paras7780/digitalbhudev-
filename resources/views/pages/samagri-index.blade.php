@extends('layouts.app')

@section('title', 'Pooja Samagri Checklists & Guides | DigitalBhudev')
@section('meta_description', 'Free authentic Vedic pooja samagri checklists for Satyanarayan Katha, Griha Pravesh, Rudrabhishek, Vivah, Navchandi, and Ganesh Sthapana.')

@section('content')
<main>
    <section class="page-hero">
        <div class="container">
            <span class="eyebrow">VEDIC POOJA SAMAGRI CHECKLISTS</span>
            <h1>Authentic Pooja Samagri Lists</h1>
            <p>Everything you need to arrange before your sacred ceremonies. Free, printable, and shareable on WhatsApp.</p>
        </div>
    </section>

    <section class="content-section">
        <div class="container">
            <div style="text-align:center;max-width:760px;margin:0 auto 36px">
                <span style="font-size:32px;display:block;margin-bottom:8px">🪔</span>
                <h2 style="font-family:'Playfair Display',serif;font-size:26px;color:var(--heading);margin:0 0 10px">
                    Prepare for Your Sacred Ceremonies with Ease
                </h2>
                <p style="color:var(--muted);font-size:15px;line-height:1.6">
                    Never worry about missing essential puja articles. Select a ceremony below to access our detailed checklist, print a copy for your shopping, or share it directly with family on WhatsApp.
                </p>
            </div>

            <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(320px, 1fr));gap:24px;margin-bottom:48px">
                @foreach($all as $key => $item)
                    <div style="background:var(--card-bg);border:1px solid var(--card-border);border-radius:var(--radius-lg);padding:26px;box-shadow:var(--shadow);display:flex;flex-direction:column;justify-content:space-between;transition:transform 0.2s,box-shadow 0.2s">
                        <div>
                            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:12px">
                                <div>
                                    <span style="font-size:12px;font-weight:700;color:var(--primary);letter-spacing:0.06em;text-transform:uppercase;display:block">
                                        {{ $item['deity'] }}
                                    </span>
                                    <h3 style="font-family:'Playfair Display',serif;font-size:20px;color:var(--heading);margin:4px 0 2px">
                                        {{ $item['name'] }}
                                    </h3>
                                    <span style="font-size:13px;color:var(--primary);font-weight:600">
                                        {{ $item['name_gu'] }}
                                    </span>
                                </div>
                                <span style="background:var(--primary-soft);color:var(--primary);border:1px solid var(--primary-border);width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0">
                                    🕉️
                                </span>
                            </div>

                            <p style="color:var(--muted);font-size:13px;line-height:1.6;margin-bottom:18px">
                                {{ $item['description'] }}
                            </p>

                            <div style="background:var(--primary-soft);border:1px solid var(--primary-border);border-radius:var(--radius-sm);padding:10px 14px;margin-bottom:20px;display:flex;justify-content:space-between;font-size:12px;color:var(--heading)">
                                <span>⏱️ Approx. <strong>{{ $item['duration'] }}</strong></span>
                                <span>📋 <strong>{{ count($item['sections']) }} checklist sections</strong></span>
                            </div>
                        </div>

                        <div style="display:flex;gap:10px;align-items:center">
                            <a href="{{ route('samagri.show', $key) }}" class="btn btn-primary" style="flex:1;text-align:center;padding:10px 16px;font-size:13px">
                                View Full Checklist &rarr;
                            </a>
                            <a href="https://api.whatsapp.com/send?text={{ urlencode('Check out this complete pooja samagri list for ' . $item['name'] . ': ' . route('samagri.show', $key)) }}" target="_blank" class="btn btn-light" style="padding:10px 14px;font-size:13px;background:#e9f7ef;color:#1e7e34;border-color:#c3e6cb;border-radius:var(--radius-full)" title="Share on WhatsApp">
                                📲
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Bhudev Patrika Studio Callout --}}
            <div style="background:var(--card-bg);border:1px solid var(--primary-border);border-left:5px solid var(--primary);border-radius:var(--radius-lg);padding:24px 28px;margin-bottom:36px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;box-shadow:var(--shadow)">
                <div style="display:flex;align-items:center;gap:16px">
                    <span style="font-size:36px">📜</span>
                    <div>
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px">
                            <strong style="font-size:16px;color:var(--heading)">Pooja Samagri Patrika Studio</strong>
                            <span style="background:var(--primary);color:#fff;font-size:10px;font-weight:700;padding:2px 8px;border-radius:var(--radius-full)">FOR BHUDEVS</span>
                        </div>
                        <p style="margin:0;color:var(--muted);font-size:13.5px;line-height:1.5">
                            Are you a Shastri Ji or Bhudev? Customize authentic Gujarati Pooja Patrikas with your personal name, astrology firm, and phone number. Instant 1-click HD download or WhatsApp sharing.
                        </p>
                    </div>
                </div>
                <div>
                    <a href="{{ route('bhudev.samagri-studio') }}" class="btn btn-primary" style="font-size:13px;padding:10px 20px">
                        Open Patrika Studio &rarr;
                    </a>
                </div>
            </div>

            {{-- CTA Box --}}
            <div style="background:linear-gradient(135deg, #321128 0%, #4a1633 50%, #220f1f 100%);border:1px solid rgba(251,207,232,0.2);border-radius:var(--radius-lg);padding:40px;color:#fff;text-align:center;box-shadow:var(--shadow)">
                <span style="font-size:36px;display:block;margin-bottom:10px">🙏</span>
                <h3 style="font-family:'Playfair Display',serif;font-size:24px;margin:0 0 10px;color:#fdf2f8">
                    Need an Authentic Vedic Bhudev for Your Ceremony?
                </h3>
                <p style="color:#fce7f3;font-size:14px;max-width:620px;margin:0 auto 22px;line-height:1.6">
                    Connect with experienced, verified Brahmin pandits in your city who conduct ceremonies with complete scriptural purity and dedication.
                </p>
                <div style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap">
                    <a href="{{ route('bhudevs.index') }}" class="btn btn-primary" style="padding:12px 28px">
                        Find a Verified Bhudev &rarr;
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-light" style="background:transparent;color:#fff;border-color:rgba(255,255,255,0.4);border-radius:var(--radius-full)">
                        Ask a Question
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
