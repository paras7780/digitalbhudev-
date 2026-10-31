<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', ($domain->hero_title ?? $pandit->display_name) . ' | Vedic Priest & Astrologer')</title>
    <meta name="description" content="@yield('meta_description', ($domain->hero_tagline ?? 'Authentic Vedic Ceremonies, Pujas & Muhurat consultations by ' . $pandit->display_name))">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <!-- Fonts & Base CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400..700;1,9..40,400..700&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}?v={{ @filemtime(public_path('assets/css/style.css')) ?: 1 }}">

    @php
        $rawColor = $domain->theme_color ?: '#be185d';
        $themeColorHex = match(strtolower(trim($rawColor))) {
            'saffron', 'kesari' => '#ea580c',
            'plum', 'purple'   => '#4a1633',
            'gold', 'yellow'   => '#d97706',
            'magenta', 'pink'  => '#be185d',
            'maroon', 'red'    => '#991b1b',
            'green'            => '#059669',
            default            => (str_starts_with($rawColor, '#') ? $rawColor : '#be185d'),
        };
    @endphp

    <style>
        :root {
            --pandit-primary: {{ $themeColorHex }};
            --pandit-gold: #f472b6;
            --pandit-dark: #4a1633;
            --pandit-bg: #fdfafc;
            --pandit-card-border: #f3e5ee;
        }

        * {
            box-sizing: border-box;
        }

        html, body {
            overflow-x: hidden;
            max-width: 100vw;
        }

        body {
            background-color: var(--pandit-bg);
            color: #3b322a;
            font-family: 'DM Sans', sans-serif;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }

        /* White-labeled Pandit Navbar - NO LOGIN / NO REGISTER */
        .pandit-header {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: #ffffff;
            border-bottom: 2px solid var(--pandit-gold);
            box-shadow: 0 4px 20px rgba(74, 22, 51, 0.06);
            padding: 10px 0;
        }

        .pandit-nav-container {
            width: min(1440px, calc(100% - 32px));
            max-width: 1440px;
            margin: 0 auto;
            padding: 0 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .pandit-brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: var(--pandit-dark);
            min-width: 0;
            max-width: calc(100% - 54px);
        }

        .pandit-brand-photo {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--pandit-primary);
            box-shadow: 0 4px 12px rgba(190, 24, 93, 0.22);
            display: block;
            flex-shrink: 0;
            background: #fdf2f8;
        }

        .pandit-brand-om {
            width: 46px;
            height: 46px;
            background: linear-gradient(135deg, var(--pandit-primary), #9d174d);
            color: #ffffff !important;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: bold;
            box-shadow: 0 4px 12px rgba(190, 24, 93, 0.25);
            flex-shrink: 0;
            line-height: 1;
        }

        .pandit-brand-text {
            display: flex;
            flex-direction: column;
            min-width: 0;
            overflow: hidden;
        }

        .pandit-brand-title {
            font-family: 'Playfair Display', serif;
            font-size: 19px;
            font-weight: 700;
            line-height: 1.2;
            color: var(--pandit-dark);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .pandit-brand-subtitle {
            font-size: 10.5px;
            text-transform: uppercase;
            letter-spacing: 0.9px;
            color: var(--pandit-primary);
            font-weight: 700;
            white-space: nowrap;
            margin-top: 2px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .pandit-menu {
            display: flex;
            align-items: center;
            gap: 18px;
            list-style: none;
            margin: 0;
            padding: 0;
            flex-wrap: nowrap;
        }

        .pandit-menu li {
            display: inline-flex;
            align-items: center;
            flex-shrink: 0;
        }

        .pandit-menu a {
            text-decoration: none;
            color: #4a3b32;
            font-size: 14px;
            font-weight: 600;
            white-space: nowrap;
            transition: color 0.18s ease;
            padding: 6px 2px;
            line-height: 1.2;
        }

        .pandit-menu a:hover {
            color: var(--pandit-primary);
        }

        .btn-whatsapp-header {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            background: #25d366;
            color: #ffffff !important;
            padding: 9px 18px;
            border-radius: 999px;
            font-weight: 700;
            font-size: 13.5px;
            white-space: nowrap;
            box-shadow: 0 4px 14px rgba(37, 211, 102, 0.3);
            text-decoration: none;
            flex-shrink: 0;
            line-height: 1;
            transition: transform 0.18s ease, background 0.18s ease;
        }

        .btn-whatsapp-header:hover {
            background: #1ebd5a;
            transform: translateY(-1px);
        }

        /* Intermediate widths: tighten menu spacing cleanly */
        @media (min-width: 1101px) and (max-width: 1300px) {
            .pandit-menu {
                gap: 12px;
            }
            .pandit-menu a {
                font-size: 13px;
            }
            .btn-whatsapp-header {
                padding: 8px 14px;
                font-size: 12.5px;
            }
        }

        /* Mobile Hamburger & Responsive Navigation Drawer */
        .mobile-toggle {
            display: none;
            background: #ffffff;
            border: 1px solid var(--pandit-card-border);
            border-radius: 8px;
            width: 42px;
            height: 42px;
            font-size: 22px;
            color: var(--pandit-dark);
            cursor: pointer;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            padding: 0;
        }

        .pandit-menu-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(30, 24, 18, 0.55);
            backdrop-filter: blur(4px);
            z-index: 9998;
        }

        @media (max-width: 1100px) {
            .mobile-toggle {
                display: inline-flex !important;
                background: #ffffff !important;
                border: 1.5px solid var(--pandit-card-border, #f3e5ee) !important;
                border-radius: 8px !important;
                width: 42px !important;
                height: 42px !important;
                font-size: 22px !important;
                color: var(--pandit-dark, #4a1633) !important;
                cursor: pointer !important;
                align-items: center !important;
                justify-content: center !important;
                flex-shrink: 0 !important;
                padding: 0 !important;
                line-height: 1 !important;
                z-index: 1001 !important;
            }

            .pandit-menu {
                display: none !important;
                position: fixed !important;
                top: 66px !important;
                left: 0 !important;
                right: 0 !important;
                bottom: 0 !important;
                background: #ffffff !important;
                border-top: 2px solid var(--pandit-gold, #f472b6) !important;
                padding: 16px 20px 40px !important;
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 0 !important;
                overflow-y: auto !important;
                -webkit-overflow-scrolling: touch !important;
                z-index: 9999 !important;
                box-shadow: 0 16px 36px rgba(41, 36, 31, 0.25) !important;
            }

            .pandit-menu.open {
                display: flex !important;
            }

            .pandit-menu-backdrop {
                display: none;
                position: fixed !important;
                inset: 0 !important;
                background: rgba(30, 24, 18, 0.55) !important;
                backdrop-filter: blur(4px) !important;
                -webkit-backdrop-filter: blur(4px) !important;
                z-index: 9998 !important;
            }

            .pandit-menu-backdrop.open {
                display: block !important;
            }

            .pandit-menu li {
                border-bottom: 1px solid #f2e8dc;
                padding: 12px 0;
                display: block;
            }

            .pandit-menu a {
                font-size: 15px;
                font-weight: 600;
                display: block;
                padding: 4px 0;
                white-space: normal;
            }

            .pandit-menu .btn-whatsapp-header {
                justify-content: center;
                margin-top: 14px;
                width: 100%;
                box-sizing: border-box;
                padding: 12px 18px;
                font-size: 14px;
            }

            .pandit-hero-grid {
                grid-template-columns: 1fr !important;
                gap: 28px !important;
                text-align: center;
            }

            .pandit-hero-grid .btn-whatsapp-header {
                width: 100%;
                justify-content: center;
            }

            div[style*="minmax(340px"],
            div[style*="minmax(320px"],
            div[style*="minmax(300px"],
            div[style*="minmax(280px"],
            div[style*="minmax(260px"],
            div[style*="minmax(220px"] {
                grid-template-columns: 1fr !important;
            }
        }

        @media (max-width: 768px) {
            .pandit-footer-container {
                grid-template-columns: 1fr !important;
                gap: 28px !important;
            }
            .pandit-hero-grid a[href="#ceremonies"] {
                width: 100% !important;
                box-sizing: border-box !important;
                text-align: center;
                display: block;
            }
        }

        @media (max-width: 600px) {
            .pandit-nav-container {
                width: calc(100% - 20px) !important;
            }
            .pandit-brand-title {
                font-size: 16px;
            }
            .pandit-brand-subtitle {
                font-size: 9.5px;
            }
            .pandit-brand-photo,
            .pandit-brand-om {
                width: 38px;
                height: 38px;
                font-size: 18px;
            }
            .pandit-form-grid {
                grid-template-columns: 1fr !important;
                gap: 12px !important;
            }
            input:not([type="checkbox"]):not([type="radio"]),
            select,
            textarea {
                font-size: 16px !important;
                min-height: 44px !important;
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
                @if($pandit->photo_path)
                    <img src="{{ asset('storage/' . $pandit->photo_path) }}" alt="{{ $domain->hero_title ?? $pandit->display_name }}" class="pandit-brand-photo">
                @else
                    <div class="pandit-brand-om">ॐ</div>
                @endif
                <div class="pandit-brand-text">
                    <div class="pandit-brand-title">{{ $domain->hero_title ?? $pandit->display_name }}</div>
                    <div class="pandit-brand-subtitle">Vedic Pandit &amp; Astrologer</div>
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

            function setMenu(open) {
                if (menu) menu.classList.toggle('open', open);
                if (backdrop) backdrop.classList.toggle('open', open);
                if (toggle) toggle.textContent = open ? '✕' : '☰';
                document.body.classList.toggle('pandit-nav-open', open);
            }

            if (toggle && menu) {
                toggle.onclick = function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    var open = !menu.classList.contains('open');
                    setMenu(open);
                };
            }
            if (backdrop) {
                backdrop.onclick = function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    setMenu(false);
                };
            }
            if (menu) {
                menu.querySelectorAll('a').forEach(function(a) {
                    a.addEventListener('click', function() {
                        setMenu(false);
                    });
                });
            }
        })();
    </script>
    @stack('scripts')
</body>
</html>
