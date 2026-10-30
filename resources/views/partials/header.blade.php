<div class="topline">
    <div class="container top-line-inner" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px">
        <span>🙏 {{ __('messages.connecting_tradition') }}</span>
        <div style="display:inline-flex;align-items:center;gap:14px">
            @include('partials.language-switcher')
            <a href="{{ route('contact') }}">{{ __('messages.need_help') }}</a>
        </div>
    </div>
</div>
<header class="site-header"><div class="container nav-wrap">
<a aria-label="DigitalBhudev home" class="brand" href="{{ route('home') }}"><span class="brand-mark"><img src="{{ asset('assets/img/logo-mark.svg') }}" alt="DigitalBhudev"></span><span><strong>{{ __('messages.app_name') }}</strong><small>{{ __('messages.app_tagline') }}</small></span></a>
<button aria-expanded="false" aria-label="Open navigation" class="menu-toggle"><span></span><span></span><span></span></button>
<nav class="main-nav">
    <a class="{{ request()->routeIs('home')?'active':'' }}" href="{{ route('home') }}">{{ __('messages.nav.home') }}</a>
    <a class="{{ request()->routeIs('bhudevs.*')?'active':'' }}" href="{{ route('bhudevs.index') }}">{{ __('messages.nav.find_bhudev') }}</a>
    <a class="{{ request()->routeIs('services.*')?'active':'' }}" href="{{ route('services.index') }}">{{ __('messages.nav.services') }}</a>
    <a class="{{ request()->routeIs('samagri.*')?'active':'' }}" href="{{ route('samagri.index') }}">{{ __('messages.nav.samagri') }}</a>
    <a class="{{ request()->routeIs('muhurat.*')?'active':'' }}" href="{{ route('muhurat.index') }}">{{ __('messages.nav.muhurat') }}</a>
    <a class="{{ request()->routeIs('panchang.*')?'active':'' }}" href="{{ route('panchang.index') }}">{{ __('messages.nav.panchang') }}</a>
    <a class="{{ request()->routeIs('rashifal.*')?'active':'' }}" href="{{ route('rashifal.index') }}">{{ __('messages.nav.rashifal') }}</a>
    <a class="{{ request()->routeIs('about')?'active':'' }}" href="{{ route('about') }}">{{ __('messages.nav.about') }}</a>

    {{-- Mobile Drawer Action Footer --}}
    <div class="mobile-nav-footer">
        <div class="mobile-lang-row">
            <span class="mobile-lang-label">{{ __('messages.languages.select') }}:</span>
            @include('partials.language-switcher')
        </div>
        <div class="mobile-auth-buttons">
            @auth
                @if(auth()->user()->isAdmin())
                    <a class="btn btn-primary btn-sm" href="{{ route('admin.dashboard') }}">{{ __('messages.nav.admin') }}</a>
                @elseif(auth()->user()->isBhudev())
                    <a class="btn btn-primary btn-sm" href="{{ route('bhudev.dashboard') }}">{{ __('messages.nav.my_dashboard') }}</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" style="display:block;margin-top:6px">
                    @csrf
                    <button class="btn btn-light btn-sm" style="width:100%">{{ __('messages.nav.logout') }}</button>
                </form>
            @else
                <a class="btn btn-light btn-sm" href="{{ route('login') }}" style="flex:1;text-align:center">{{ __('messages.nav.login') }}</a>
                <a class="btn btn-primary btn-sm" href="{{ route('register') }}" style="flex:1.2;text-align:center">{{ __('messages.nav.join_digitalbhudev') !== 'messages.nav.join_digitalbhudev' ? __('messages.nav.join_digitalbhudev') : 'Join DigitalBhudev' }} ↗</a>
            @endauth
        </div>
    </div>
</nav>
<div class="nav-backdrop"></div>
<div class="nav-actions">
@auth 
    @if(auth()->user()->isAdmin())
        <a class="login-link" href="{{ route('admin.dashboard') }}">{{ __('messages.nav.admin') }}</a>
    @elseif(auth()->user()->isBhudev())
        <a class="login-link" href="{{ route('bhudev.dashboard') }}">{{ __('messages.nav.my_dashboard') }}</a>
    @endif
    <form method="POST" action="{{ route('logout') }}" style="display:inline">
        @csrf
        <button class="login-link" style="background:none;border:0;cursor:pointer">{{ __('messages.nav.logout') }}</button>
    </form>
@else
    <a class="login-link" href="{{ route('login') }}">{{ __('messages.nav.login') }}</a>
    <a class="btn btn-primary btn-sm" href="{{ route('register') }}">{{ __('messages.nav.join_digitalbhudev') !== 'messages.nav.join_digitalbhudev' ? __('messages.nav.join_digitalbhudev') : 'Join DigitalBhudev' }} <span>↗</span></a>
@endauth
</div>
</div></header>
