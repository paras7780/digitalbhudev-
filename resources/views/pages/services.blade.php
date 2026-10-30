@extends('layouts.app')

@section('title', 'Vedic Services & Sacred Ceremonies | DigitalBhudev')
@section('meta_description', 'Explore sacred Vedic ceremonies including Satyanarayan Katha, Griha Pravesh, Rudrabhishek, Vivah, Navchandi, and connect with verified Bhudevs across Gujarat.')

@section('content')
<main>
    <section class="page-hero">
        <div class="container">
            <span class="eyebrow">VEDIC CEREMONIES & RITUALS</span>
            <h1>Sacred Services for Important Moments</h1>
            <p>Connect with verified, experienced Brahmin Bhudevs to perform traditional Hindu rituals with complete devotion and scriptural accuracy.</p>
        </div>
    </section>

    <section class="content-section">
        <div class="container">
            <div style="text-align:center;max-width:760px;margin:0 auto 40px">
                <span style="font-size:32px;display:block;margin-bottom:8px">🪔</span>
                <h2 style="font-family:'Playfair Display',serif;font-size:28px;color:var(--heading);margin:0 0 10px">
                    Authentic Rites, Rooted in Tradition
                </h2>
                <p style="color:var(--muted);font-size:15px;line-height:1.6">
                    Whether you are moving into a new home, celebrating a sacred marriage, or seeking planetary peace, DigitalBhudev connects you directly with verified priests without middleman commissions.
                </p>
            </div>

            {{-- 6 Major Ceremonies Grid --}}
            <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(340px, 1fr));gap:24px;margin-bottom:48px">
                {{-- Satyanarayan --}}
                <div style="background:var(--card-bg);border:1px solid var(--card-border);border-radius:var(--radius-lg);padding:28px;box-shadow:var(--shadow);display:flex;flex-direction:column;justify-content:space-between">
                    <div>
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
                            <span style="background:var(--primary-soft);color:var(--primary);border:1px solid var(--primary-border);width:44px;height:44px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:22px">
                                🪔
                            </span>
                            <span style="background:var(--primary-soft);color:var(--primary);border:1px solid var(--primary-border);padding:3px 12px;border-radius:var(--radius-full);font-size:11px;font-weight:700">
                                2 - 2.5 Hours
                            </span>
                        </div>
                        <h3 style="font-family:'Playfair Display',serif;font-size:22px;color:var(--heading);margin:0 0 4px">
                            Shree Satyanarayan Katha
                        </h3>
                        <span style="font-size:13px;color:var(--primary);font-weight:600;display:block;margin-bottom:12px">
                            શ્રી સત્યનારાયણ ભગવાન કથા
                        </span>
                        <p style="color:var(--muted);font-size:13px;line-height:1.6;margin-bottom:16px">
                            Sacred worship of Lord Vishnu bringing family peace, prosperity, and spiritual fulfillment for housewarmings, birthdays, and auspicious milestones.
                        </p>
                    </div>
                    <div style="display:flex;gap:10px;padding-top:14px;border-top:1px solid var(--line)">
                        <a href="{{ route('samagri.show', 'satyanarayan-katha') }}" class="btn btn-light btn-sm" style="flex:1;text-align:center">
                            📋 Samagri List
                        </a>
                        <a href="{{ route('bhudevs.index') }}?q=Satyanarayan" class="btn btn-primary btn-sm" style="flex:1;text-align:center">
                            Find Panditji &rarr;
                        </a>
                    </div>
                </div>

                {{-- Griha Pravesh --}}
                <div style="background:var(--card-bg);border:1px solid var(--card-border);border-radius:var(--radius-lg);padding:28px;box-shadow:var(--shadow);display:flex;flex-direction:column;justify-content:space-between">
                    <div>
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
                            <span style="background:var(--green-soft);color:var(--green);border:1px solid var(--green-border);width:44px;height:44px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:22px">
                                🏡
                            </span>
                            <span style="background:var(--green-soft);color:var(--green);border:1px solid var(--green-border);padding:3px 12px;border-radius:var(--radius-full);font-size:11px;font-weight:700">
                                3 - 4 Hours
                            </span>
                        </div>
                        <h3 style="font-family:'Playfair Display',serif;font-size:22px;color:var(--heading);margin:0 0 4px">
                            Griha Pravesh &amp; Vastu Shanti
                        </h3>
                        <span style="font-size:13px;color:var(--primary);font-weight:600;display:block;margin-bottom:12px">
                            ગૃહ પ્રવેશ અને વાસ્તુ શાંતિ પૂજા
                        </span>
                        <p style="color:var(--muted);font-size:13px;line-height:1.6;margin-bottom:16px">
                            Purify and sanctify your new home with Vastu Purush worship, Navgraha Havan, and auspicious threshold rituals to invite lasting divine energy.
                        </p>
                    </div>
                    <div style="display:flex;gap:10px;padding-top:14px;border-top:1px solid var(--line)">
                        <a href="{{ route('samagri.show', 'griha-pravesh') }}" class="btn btn-light btn-sm" style="flex:1;text-align:center">
                            📋 Samagri List
                        </a>
                        <a href="{{ route('bhudevs.index') }}?q=Vastu" class="btn btn-primary btn-sm" style="flex:1;text-align:center">
                            Find Panditji &rarr;
                        </a>
                    </div>
                </div>

                {{-- Rudrabhishek --}}
                <div style="background:var(--card-bg);border:1px solid var(--card-border);border-radius:var(--radius-lg);padding:28px;box-shadow:var(--shadow);display:flex;flex-direction:column;justify-content:space-between">
                    <div>
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
                            <span style="background:var(--blue-soft);color:var(--blue);border:1px solid var(--blue-border);width:44px;height:44px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:22px">
                                🔱
                            </span>
                            <span style="background:var(--blue-soft);color:var(--blue);border:1px solid var(--blue-border);padding:3px 12px;border-radius:var(--radius-full);font-size:11px;font-weight:700">
                                1.5 - 2 Hours
                            </span>
                        </div>
                        <h3 style="font-family:'Playfair Display',serif;font-size:22px;color:var(--heading);margin:0 0 4px">
                            Mahadev Rudrabhishek
                        </h3>
                        <span style="font-size:13px;color:var(--primary);font-weight:600;display:block;margin-bottom:12px">
                            મહાદેવ રુદ્રાભિષેક પૂજા
                        </span>
                        <p style="color:var(--muted);font-size:13px;line-height:1.6;margin-bottom:16px">
                            Vedic Rudram recitation and sacred Abhishek with Panchamrit, Bilva leaves, and holy Gangajal for health, longevity, and liberation from karmic obstacles.
                        </p>
                    </div>
                    <div style="display:flex;gap:10px;padding-top:14px;border-top:1px solid var(--line)">
                        <a href="{{ route('samagri.show', 'rudrabhishek') }}" class="btn btn-light btn-sm" style="flex:1;text-align:center">
                            📋 Samagri List
                        </a>
                        <a href="{{ route('bhudevs.index') }}?q=Rudrabhishek" class="btn btn-primary btn-sm" style="flex:1;text-align:center">
                            Find Panditji &rarr;
                        </a>
                    </div>
                </div>

                {{-- Vivah Sanskar --}}
                <div style="background:var(--card-bg);border:1px solid var(--card-border);border-radius:var(--radius-lg);padding:28px;box-shadow:var(--shadow);display:flex;flex-direction:column;justify-content:space-between">
                    <div>
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
                            <span style="background:var(--rose-soft);color:var(--rose);border:1px solid var(--rose-border);width:44px;height:44px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:22px">
                                💍
                            </span>
                            <span style="background:var(--rose-soft);color:var(--rose);border:1px solid var(--rose-border);padding:3px 12px;border-radius:var(--radius-full);font-size:11px;font-weight:700">
                                Full Ceremony
                            </span>
                        </div>
                        <h3 style="font-family:'Playfair Display',serif;font-size:22px;color:var(--heading);margin:0 0 4px">
                            Vedic Vivah (Lagna) Sanskar
                        </h3>
                        <span style="font-size:13px;color:var(--primary);font-weight:600;display:block;margin-bottom:12px">
                            વૈદિક લગ્ન સંસ્કાર (વિવાહ વિધિ)
                        </span>
                        <p style="color:var(--muted);font-size:13px;line-height:1.6;margin-bottom:16px">
                            Traditional Gujarati and Vedic wedding rituals including Mandap Muhurat, Hastamelap, Mangal Fera, Saptapadi, and Kanyadaan conducted with scriptural purity.
                        </p>
                    </div>
                    <div style="display:flex;gap:10px;padding-top:14px;border-top:1px solid var(--line)">
                        <a href="{{ route('samagri.show', 'vivah-sanskar') }}" class="btn btn-light btn-sm" style="flex:1;text-align:center">
                            📋 Samagri List
                        </a>
                        <a href="{{ route('muhurat.index') }}" class="btn btn-primary btn-sm" style="flex:1;text-align:center">
                            Vivah Muhurats &rarr;
                        </a>
                    </div>
                </div>

                {{-- Navchandi Yajna --}}
                <div style="background:var(--card-bg);border:1px solid var(--card-border);border-radius:var(--radius-lg);padding:28px;box-shadow:var(--shadow);display:flex;flex-direction:column;justify-content:space-between">
                    <div>
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
                            <span style="background:var(--amber-soft);color:var(--amber);border:1px solid var(--amber-border);width:44px;height:44px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:22px">
                                🔥
                            </span>
                            <span style="background:var(--amber-soft);color:var(--amber);border:1px solid var(--amber-border);padding:3px 12px;border-radius:var(--radius-full);font-size:11px;font-weight:700">
                                4 - 6 Hours
                            </span>
                        </div>
                        <h3 style="font-family:'Playfair Display',serif;font-size:22px;color:var(--heading);margin:0 0 4px">
                            Shri Navchandi Yajna
                        </h3>
                        <span style="font-size:13px;color:var(--primary);font-weight:600;display:block;margin-bottom:12px">
                            શ્રી નવચંડી યજ્ઞ અને ચંડી પાઠ
                        </span>
                        <p style="color:var(--muted);font-size:13px;line-height:1.6;margin-bottom:16px">
                            Powerful Durga Saptashati recitation and grand Maha Yajna invoking Maa Chandi for victory, protection against negativity, and divine grace.
                        </p>
                    </div>
                    <div style="display:flex;gap:10px;padding-top:14px;border-top:1px solid var(--line)">
                        <a href="{{ route('samagri.show', 'navchandi-yajna') }}" class="btn btn-light btn-sm" style="flex:1;text-align:center">
                            📋 Samagri List
                        </a>
                        <a href="{{ route('bhudevs.index') }}?q=Navchandi" class="btn btn-primary btn-sm" style="flex:1;text-align:center">
                            Find Panditji &rarr;
                        </a>
                    </div>
                </div>

                {{-- Shubh Muhurat & Astrology --}}
                <div style="background:var(--card-bg);border:1px solid var(--card-border);border-radius:var(--radius-lg);padding:28px;box-shadow:var(--shadow);display:flex;flex-direction:column;justify-content:space-between">
                    <div>
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
                            <span style="background:var(--primary-soft);color:var(--primary);border:1px solid var(--primary-border);width:44px;height:44px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:22px">
                                ✦
                            </span>
                            <span style="background:var(--primary-soft);color:var(--primary);border:1px solid var(--primary-border);padding:3px 12px;border-radius:var(--radius-full);font-size:11px;font-weight:700">
                                Consultation
                            </span>
                        </div>
                        <h3 style="font-family:'Playfair Display',serif;font-size:22px;color:var(--heading);margin:0 0 4px">
                            Shubh Muhurat &amp; Jyotish
                        </h3>
                        <span style="font-size:13px;color:var(--primary);font-weight:600;display:block;margin-bottom:12px">
                            શુભ મુહૂર્ત અને જ્યોતિષ પરામર્શ
                        </span>
                        <p style="color:var(--muted);font-size:13px;line-height:1.6;margin-bottom:16px">
                            Personalized consultation for finding auspicious timings for vehicle purchase, business opening, engagement, and Janma Kundali guidance.
                        </p>
                    </div>
                    <div style="display:flex;gap:10px;padding-top:14px;border-top:1px solid var(--line)">
                        <a href="{{ route('muhurat.index') }}" class="btn btn-light btn-sm" style="flex:1;text-align:center">
                            View Muhurats
                        </a>
                        <a href="{{ route('contact') }}" class="btn btn-primary btn-sm" style="flex:1;text-align:center">
                            Consult Now &rarr;
                        </a>
                    </div>
                </div>
            </div>

            {{-- How Booking Works Section --}}
            <div style="background:var(--card-bg);border:1px solid var(--primary-border);border-radius:var(--radius-lg);padding:36px;box-shadow:var(--shadow);margin-bottom:40px">
                <div style="text-align:center;max-width:620px;margin:0 auto 28px">
                    <span style="font-size:12px;font-weight:700;letter-spacing:0.1em;color:var(--primary);text-transform:uppercase;display:block;margin-bottom:6px">SIMPLE 3-STEP JOURNEY</span>
                    <h3 style="font-family:'Playfair Display',serif;font-size:24px;color:var(--heading);margin:0">
                        How Booking a Bhudev Works
                    </h3>
                </div>

                <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(260px, 1fr));gap:24px">
                    <div style="background:var(--primary-soft);border:1px solid var(--primary-border);border-radius:var(--radius-md);padding:24px;text-align:center">
                        <div style="width:48px;height:48px;border-radius:50%;background:var(--primary-gradient);color:#fff;font-weight:700;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;font-size:18px;box-shadow:0 4px 12px rgba(219,39,119,0.25)">
                            1
                        </div>
                        <h4 style="font-family:'Playfair Display',serif;font-size:18px;margin:0 0 6px;color:var(--heading)">Explore &amp; Select</h4>
                        <p style="color:var(--muted);font-size:13px;line-height:1.6;margin:0">
                            Search Bhudev profiles by your city, ceremony needed, and languages. Review verified experience and photos.
                        </p>
                    </div>

                    <div style="background:var(--primary-soft);border:1px solid var(--primary-border);border-radius:var(--radius-md);padding:24px;text-align:center">
                        <div style="width:48px;height:48px;border-radius:50%;background:linear-gradient(135deg, #f59e0b, #d97706);color:#fff;font-weight:700;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;font-size:18px;box-shadow:0 4px 12px rgba(217,119,6,0.25)">
                            2
                        </div>
                        <h4 style="font-family:'Playfair Display',serif;font-size:18px;margin:0 0 6px;color:var(--heading)">Connect Directly</h4>
                        <p style="color:var(--muted);font-size:13px;line-height:1.6;margin:0">
                            Tap <strong>Chat on WhatsApp</strong> or call the Bhudev directly. Confirm date, muhurat, and dakshina with zero middleman fees.
                        </p>
                    </div>

                    <div style="background:var(--primary-soft);border:1px solid var(--primary-border);border-radius:var(--radius-md);padding:24px;text-align:center">
                        <div style="width:48px;height:48px;border-radius:50%;background:linear-gradient(135deg, #10b981, #059669);color:#fff;font-weight:700;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;font-size:18px;box-shadow:0 4px 12px rgba(5,150,105,0.25)">
                            3
                        </div>
                        <h4 style="font-family:'Playfair Display',serif;font-size:18px;margin:0 0 6px;color:var(--heading)">Conduct Sacred Vidhi</h4>
                        <p style="color:var(--muted);font-size:13px;line-height:1.6;margin:0">
                            Use our free printable Samagri checklists to prepare. The Bhudev conducts the ceremony with authentic Vedic mantras.
                        </p>
                    </div>
                </div>
            </div>

            {{-- CTA Box --}}
            <div style="background:linear-gradient(135deg, #321128 0%, #4a1633 50%, #220f1f 100%);border:1px solid rgba(251,207,232,0.2);border-radius:var(--radius-lg);padding:40px;color:#fff;text-align:center;box-shadow:var(--shadow)">
                <span style="font-size:36px;display:block;margin-bottom:10px">🕉️</span>
                <h3 style="font-family:'Playfair Display',serif;font-size:24px;margin:0 0 10px;color:#fdf2f8">
                    Ready to Book a Verified Bhudev?
                </h3>
                <p style="color:#fce7f3;font-size:14px;max-width:580px;margin:0 auto 22px;line-height:1.6">
                    Browse authentic profiles across Ahmedabad, Surat, Vadodara, Rajkot, and all regions of Gujarat.
                </p>
                <a href="{{ route('bhudevs.index') }}" class="btn btn-primary" style="padding:12px 28px">
                    Find a Bhudev Now &rarr;
                </a>
            </div>
        </div>
    </section>
</main>
@endsection
