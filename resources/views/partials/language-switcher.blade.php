@php
    $currentLocale = app()->getLocale();
    $locales = [
        'en' => ['label' => 'English', 'short' => 'EN', 'flag' => 'EN'],
        'gu' => ['label' => 'ગુજરાતી', 'short' => 'ગુજ', 'flag' => 'ગુજ'],
        'hi' => ['label' => 'हिन्दी', 'short' => 'हिं', 'flag' => 'हिं'],
    ];
@endphp

<div class="lang-switcher-wrap" style="position:relative;display:inline-flex;align-items:center;">
    <div class="lang-pill-group" style="display:inline-flex;align-items:center;background:rgba(251, 207, 232, 0.18);border:1px solid rgba(244, 114, 182, 0.35);border-radius:24px;padding:2px 4px;gap:2px;">
        <span style="font-size:12px;padding:0 4px;opacity:0.85" title="{{ __('messages.languages.select') }}">🌐</span>
        @foreach($locales as $code => $data)
            <a href="{{ route('locale.switch', $code) }}"
               class="lang-pill {{ $currentLocale === $code ? 'active' : '' }}"
               style="font-size:11.5px;font-weight:{{ $currentLocale === $code ? '700' : '500' }};padding:4px 9px;min-height:28px;display:inline-flex;align-items:center;justify-content:center;border-radius:18px;text-decoration:none;transition:all 0.2s ease;
                      background:{{ $currentLocale === $code ? 'var(--primary, #be185d)' : 'transparent' }};
                      color:{{ $currentLocale === $code ? '#ffffff' : 'inherit' }};
                      box-shadow:{{ $currentLocale === $code ? '0 2px 8px rgba(190, 24, 93, 0.35)' : 'none' }};"
               title="{{ $data['label'] }}">
                {{ $data['label'] }}
            </a>
        @endforeach
    </div>
</div>
