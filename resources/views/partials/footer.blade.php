<footer class="site-footer">
    <div class="container footer-main">
        <div class="footer-brand">
            <a class="brand brand-footer" href="{{ route('home') }}">
                <span class="brand-mark"><img src="{{ asset('assets/img/logo-mark.svg') }}" alt="DigitalBhudev"></span>
                <span>
                    <strong>{{ __('messages.app_name') }}</strong>
                    <small>{{ __('messages.app_tagline') }}</small>
                </span>
            </a>
            <p>{{ __('messages.footer.tagline') }}</p>
        </div>

        <div class="footer-col">
            <h4>{{ __('messages.footer.explore') }}</h4>
            <a href="{{ route('bhudevs.index') }}">{{ __('messages.footer.find_bhudev') }}</a>
            <a href="{{ route('services.index') }}">{{ __('messages.footer.services') }}</a>
            <a href="{{ route('samagri.index') }}">{{ __('messages.footer.samagri') }}</a>
            <a href="{{ route('muhurat.index') }}">{{ __('messages.footer.muhurat') }}</a>
            <a href="{{ route('panchang.index') }}">{{ __('messages.footer.panchang') }}</a>
            <a href="{{ route('rashifal.index') }}">{{ __('messages.footer.rashifal') }}</a>
        </div>

        <div class="footer-col">
            <h4>{{ __('messages.footer.company') }}</h4>
            <a href="{{ route('about') }}">{{ __('messages.footer.about_us') }}</a>
            <a href="{{ route('contact') }}">{{ __('messages.footer.contact') }}</a>
            <a href="{{ route('register') }}">{{ __('messages.footer.bhudev_registration') }}</a>
            <a href="{{ route('login') }}">{{ __('messages.footer.login') }}</a>
        </div>

        <div class="footer-col">
            <h4>{{ __('messages.footer.legal_policies') ?? 'Legal & Policies' }}</h4>
            <a href="{{ route('privacy') }}">{{ __('messages.footer.privacy_policy') }}</a>
            <a href="{{ route('terms') }}">{{ __('messages.footer.terms_of_use') }}</a>
            <a href="{{ route('refund.policy') }}">{{ __('messages.footer.cancellation_refund') }}</a>
            <a href="{{ route('disclaimer') }}">{{ __('messages.footer.disclaimer') }}</a>
        </div>
    </div>

    <div class="container footer-bottom" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:14px;border-top:1px solid rgba(255,255,255,0.1);padding-top:20px">
        <div style="font-size:12px;color:#d8c2d5">
            &copy; {{ date('Y') }} {{ __('messages.app_name') }}. {{ __('messages.footer.copyright') }}
            <span style="margin:0 8px;opacity:0.4">|</span>
            <a href="{{ route('privacy') }}" style="color:#e2cddf;text-decoration:none">{{ __('messages.footer.privacy_policy') }}</a>
            <span style="margin:0 6px;opacity:0.4">•</span>
            <a href="{{ route('terms') }}" style="color:#e2cddf;text-decoration:none">{{ __('messages.footer.terms_of_use') }}</a>
            <span style="margin:0 6px;opacity:0.4">•</span>
            <a href="{{ route('refund.policy') }}" style="color:#e2cddf;text-decoration:none">{{ __('messages.footer.cancellation_refund') }}</a>
            <span style="margin:0 6px;opacity:0.4">•</span>
            <a href="{{ route('disclaimer') }}" style="color:#e2cddf;text-decoration:none">{{ __('messages.footer.disclaimer') }}</a>
        </div>
        <div style="font-size:12px;color:#d8c2d5">
            {{ __('messages.footer.made_with_respect') }} <b style="color:var(--primary)">✦</b>
        </div>
    </div>
</footer>
