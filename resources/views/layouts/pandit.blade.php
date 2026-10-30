<!doctype html>
<html lang="gu-IN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', ($domain->hero_title ?? $pandit->display_name) . ' | Vedic Priest & Astrologer')</title>
    <meta name="description" content="@yield('meta_description', ($domain->hero_tagline ?? 'Authentic Vedic Ceremonies, Pujas & Muhurat consultations by ' . $pandit->display_name))">

    <!-- Fonts & Base CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400..700;1,9..40,400..700&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <style>
        :root {
            --pandit-primary: {{ $domain->theme_color ?: '#be185d' }};
            --pandit-gold: #f472b6;
            --pandit-dark: #4a1633;
            --pandit-bg: #fdfafc;
            --pandit-card-border: #f3e5ee;
        }

        body {
            background-color: var(--pandit-bg);
            color: #3b322a;
            font-family: 'DM Sans', sans-serif;
            margin: 0;
            padding: 0;
        }

        /* White-labeled Pandit Navbar - NO LOGIN / NO REGISTER */
        .pandit-header {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(8px);
            border-bottom: 2px solid var(--pandit-gold);
            box-shadow: 0 4px 20px rgba(41, 36, 31, 0.06);
            padding: 14px 0;
        }

        .pandit-nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .pandit-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: var(--pandit-dark);
        }

        .pandit-brand-om {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, var(--pandit-primary), #9d174d);
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            font-weight: bold;
            box-shadow: 0 4px 12px rgba(190, 24, 93, 0.25);
        }

        .pandit-brand-title {
            font-family: 'Playfair Display', serif;
            font-size: 20px;
            font-weight: 700;
            line-height: 1.2;
            color: var(--pandit-dark);
        }

        .pandit-brand-subtitle {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--pandit-primary);
            font-weight: 600;
        }

        .pandit-menu {
            display: flex;
            align-items: center;
            gap: 28px;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .pandit-menu a {
            text-decoration: none;
            color: #554a40;
            font-size: 15px;
            font-weight: 500;
            transition: color 0.2s;
        }

        .pandit-menu a:hover {
            color: var(--pandit-primary);
        }

        .btn-whatsapp-header {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #25d366;
            color: #fff !important;
            padding: 10px 20px;
            border-radius: 999px;
            font-weight: 600;
            font-size: 14px;
            box-shadow: 0 4px 14px rgba(37, 211, 102, 0.3);
            text-decoration: none;
            transition: transform 0.2s, background 0.2s;
        }

        .btn-whatsapp-header:hover {
            background: #1ebd5a;
            transform: translateY(-1px);
        }

        /* Mobile Hamburger & Responsive Navigation */
        .mobile-toggle {
            display: none;
            background: #fdfaf6;
            border: 1px solid var(--pandit-gold);
            border-radius: 6px;
            width: 44px;
            height: 44px;
            font-size: 24px;
            color: var(--pandit-dark);
            cursor: pointer;
            align-items: center;
            justify-content: center;
        }

        .pandit-menu-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(30, 24, 18, 0.5);
            backdrop-filter: blur(4px);
            z-index: 998;
        }

        @media (max-width: 860px) {
            .mobile-toggle {
                display: inline-flex;
            }

            .pandit-menu {
                display: none;
                position: fixed;
                top: 74px;
                left: 0;
                right: 0;
                bottom: 0;
                background: #ffffff;
                border-top: 1px solid var(--pandit-gold);
                padding: 20px 24px 40px;
                flex-direction: column;
                align-items: stretch;
                gap: 0;
                overflow-y: auto;
                -webkit-overflow-scrolling: touch;
                z-index: 999;
                box-shadow: 0 16px 36px rgba(41, 36, 31, 0.15);
            }

            .pandit-menu.open {
                display: flex;
            }

            .pandit-menu-backdrop.open {
                display: block;
            }

            .pandit-menu li {
                border-bottom: 1px solid #f2e8dc;
                padding: 12px 0;
            }

            .pandit-menu a {
                font-size: 16px;
                font-weight: 600;
                display: block;
                padding: 4px 0;
            }

            .pandit-menu .btn-whatsapp-header {
                justify-content: center;
                margin-top: 12px;
                width: 100%;
                box-sizing: border-box;
            }

            .pandit-hero-grid {
                grid-template-columns: 1fr !important;
                gap: 32px !important;
                text-align: center;
            }

            .pandit-hero-grid .btn-whatsapp-header {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 600px) {
            .pandit-form-grid {
                grid-template-columns: 1fr !important;
                gap: 12px !important;
            }
        }

        /* Footer */
        .pandit-footer {
            background: #240f21;
            color: #fdf2f8;
            padding: 50px 0 30px;
            margin-top: 60px;
            border-top: 3px solid var(--pandit-primary);
        }

        .pandit-footer-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 40px;
        }

        @media (max-width: 768px) {
            .pandit-footer-container {
                grid-template-columns: 1fr;
                gap: 28px;
            }
        }
    </style>
    @stack('head')
</head>
<body>
    <!-- White-labeled Pandit Navigation -->
    <header class="pandit-header">
        <div class="pandit-nav-container">
            <a href="#hero" class="pandit-brand">
                <div class="pandit-brand-om">ॐ</div>
                <div>
                    <div class="pandit-brand-title">{{ $domain->hero_title ?? $pandit->display_name }}</div>
                    <div class="pandit-brand-subtitle">Vedic Pandit & Astrologer</div>
                </div>
            </a>

            <button type="button" class="mobile-toggle" id="panditMobileToggle" aria-label="Toggle navigation">☰</button>

            <ul class="pandit-menu" id="panditMenu">
                <li><a href="#about">{{ __('messages.pandit.about') }}</a></li>
                <li><a href="#ceremonies">{{ __('messages.pandit.ceremonies') }}</a></li>
                @if($pandit->publicImages->isNotEmpty())
                    <li><a href="#gallery">{{ __('messages.pandit.gallery') }}</a></li>
                @endif
                @if(!empty($instagramEligible) && !empty($instagramPosts) && $instagramPosts->isNotEmpty())
                    <li><a href="#instagram">📹 Reels &amp; Videos</a></li>
                @endif
                @if($domain->dakshina_qr_path || $domain->upi_id)
                    <li><a href="#dakshina">{{ __('messages.pandit.dakshina') }}</a></li>
                @endif
                <li><a href="#contact">{{ __('messages.pandit.contact') }}</a></li>
                <li>@include('partials.language-switcher')</li>
                <li>
                    <a href="{{ $domain->generateWhatsAppUrl('Namaste Shastriji, I would like to inquire about Vedic ceremonies.') }}" target="_blank" class="btn-whatsapp-header">
                        💬 Connect on WhatsApp
                    </a>
                </li>
            </ul>
        </div>
    </header>
    <div class="pandit-menu-backdrop" id="panditBackdrop"></div>

    <main>
        @yield('content')
    </main>

    <!-- White-labeled Pandit Footer -->
    <footer class="pandit-footer">
        <div class="pandit-footer-container">
            <div>
                <h3 style="font-family:'Playfair Display',serif;color:#fff;font-size:22px;margin:0 0 12px">
                    {{ $domain->hero_title ?? $pandit->display_name }}
                </h3>
                <p style="color:#baaea2;font-size:14px;line-height:1.7;margin:0 0 16px;max-width:440px">
                    Dedicated to conducting authentic Vedic rituals with pure mantra-uccharan, complete puja vidhi, and auspicious Muhurat guidance for families across {{ $pandit->city ?? 'Gujarat' }} and global devotees.
                </p>
                <div style="font-size:13px;color:#d5a45c">
                    🕉️ Veda Branch: {{ $pandit->qualification ?? 'Shukla Yajurveda' }} • Experience: {{ $pandit->experience_years ? $pandit->experience_years . '+ Years' : 'Seasoned' }}
                </div>
            </div>

            <div>
                <h4 style="color:#fff;font-size:16px;margin:0 0 14px;text-transform:uppercase;letter-spacing:1px;font-family:'Playfair Display',serif">Quick Links</h4>
                <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:10px;font-size:14px">
                    <li><a href="#about" style="color:#baaea2;text-decoration:none">About Shastri Ji</a></li>
                    <li><a href="#ceremonies" style="color:#baaea2;text-decoration:none">Pujas & Ceremonies</a></li>
                    @if(!empty($instagramEligible) && !empty($instagramPosts) && $instagramPosts->isNotEmpty())
                        <li><a href="#instagram" style="color:#baaea2;text-decoration:none">Vedic Reels & Posts</a></li>
                    @endif
                    <li><a href="#contact" style="color:#baaea2;text-decoration:none">Book a Ceremony</a></li>
                </ul>
            </div>

            <div>
                <h4 style="color:#fff;font-size:16px;margin:0 0 14px;text-transform:uppercase;letter-spacing:1px;font-family:'Playfair Display',serif">Direct Booking</h4>
                <p style="color:#baaea2;font-size:13px;line-height:1.6;margin:0 0 14px">
                    Location: {{ $pandit->city ? $pandit->city . ', ' . ($pandit->state ?? 'Gujarat') : 'Gujarat, India' }}
                </p>
                <a href="{{ $domain->generateWhatsAppUrl('Namaste Shastriji, I would like to check your availability for an upcoming ceremony.') }}" target="_blank" class="btn-whatsapp-header" style="padding:8px 16px;font-size:13px">
                    💬 WhatsApp Consultation
                </a>
            </div>
        </div>

        <div style="max-width:1200px;margin:30px auto 0;padding:20px 24px 0;border-top:1px solid #362c23;text-align:center;font-size:12px;color:#8f7d6e">
            © {{ date('Y') }} {{ $domain->hero_title ?? $pandit->display_name }}. All Vedic ceremonies conducted with traditional Maryada & Shastra Vidhi.
        </div>
    </footer>

    <script>
        (function() {
            var toggle = document.getElementById('panditMobileToggle');
            var menu = document.getElementById('panditMenu');
            var backdrop = document.getElementById('panditBackdrop');
            if (toggle && menu) {
                toggle.addEventListener('click', function() {
                    var open = !menu.classList.contains('open');
                    menu.classList.toggle('open', open);
                    if (backdrop) backdrop.classList.toggle('open', open);
                    toggle.textContent = open ? '✕' : '☰';
                });
            }
            if (backdrop) {
                backdrop.addEventListener('click', function() {
                    if (menu) menu.classList.remove('open');
                    backdrop.classList.remove('open');
                    if (toggle) toggle.textContent = '☰';
                });
            }
            if (menu) {
                menu.querySelectorAll('a').forEach(function(a) {
                    a.addEventListener('click', function() {
                        menu.classList.remove('open');
                        if (backdrop) backdrop.classList.remove('open');
                        if (toggle) toggle.textContent = '☰';
                    });
                });
            }
        })();
    </script>
    @stack('scripts')
</body>
</html>
