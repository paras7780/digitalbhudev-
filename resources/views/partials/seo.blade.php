{{--
    Shared SEO head tags. Pages set what they need through sections:
        @section('title')            page title (already used by the layouts)
        @section('meta_description') plain-text description
        @section('og_type')          website | profile | article      (default: website)
        @section('og_image')         absolute image URL               (default: branded logo)
        @section('meta_robots')      e.g. "noindex, nofollow"          (default: index, follow)
        @section('canonical')        absolute URL                      (default: current URL without query)
    and add structured data with @push('schema') <script type="application/ld+json">...</script> @endpush
--}}
@php
    $seoSite        = $seoSite ?? 'app';
    $seoSiteName    = $seoSiteName ?? config('app.name', 'DigitalBhudev');
    $seoTitle       = \App\Support\Seo::text($__env->yieldContent('title', $seoSiteName));
    $seoDescription = \App\Support\Seo::text($__env->yieldContent('meta_description', 'Verified Brahmin Bhudevs for Vedic pujas, daily Panchang, Rashifal and free Pooja Samagri checklists.'));
    $seoType        = \App\Support\Seo::text($__env->yieldContent('og_type', 'website'));
    $seoImage       = \App\Support\Seo::absolute(\App\Support\Seo::text($__env->yieldContent('og_image', asset('assets/img/og-default.jpg'))));
    $seoRobots      = \App\Support\Seo::text($__env->yieldContent('meta_robots', 'index, follow'));
    $seoCanonical   = \App\Support\Seo::absolute(\App\Support\Seo::text($__env->yieldContent('canonical', url()->current())));
    $seoLocale      = ['gu' => 'gu_IN', 'hi' => 'hi_IN'][app()->getLocale()] ?? 'en_IN';
    $seoSiteUrl     = \App\Support\Seo::siteUrl();
    $jsonFlags      = \App\Support\Seo::JSON_FLAGS;
@endphp
<link rel="canonical" href="{{ $seoCanonical }}">
<meta name="robots" content="{{ $seoRobots }}">
<meta name="theme-color" content="{{ $seoThemeColor ?? '#be185d' }}">
<meta property="og:type" content="{{ $seoType }}">
<meta property="og:site_name" content="{{ $seoSiteName }}">
<meta property="og:locale" content="{{ $seoLocale }}">
<meta property="og:title" content="{{ $seoTitle }}">
<meta property="og:description" content="{{ $seoDescription }}">
<meta property="og:url" content="{{ $seoCanonical }}">
<meta property="og:image" content="{{ $seoImage }}">
<meta property="og:image:alt" content="{{ $seoTitle }}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $seoTitle }}">
<meta name="twitter:description" content="{{ $seoDescription }}">
<meta name="twitter:image" content="{{ $seoImage }}">
@if($seoSite === 'app')
    @php
        $siteSchema = [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'Organization',
                    '@id'   => $seoSiteUrl . '#organization',
                    'name'  => $seoSiteName,
                    'alternateName' => 'Vishwas No Setu',
                    'url'   => $seoSiteUrl,
                    'logo'  => \App\Support\Seo::absolute(asset('assets/img/og-default.jpg')),
                    'areaServed' => 'Gujarat, India',
                ],
                [
                    '@type' => 'WebSite',
                    '@id'   => $seoSiteUrl . '#website',
                    'name'  => $seoSiteName,
                    'url'   => $seoSiteUrl,
                    'inLanguage' => ['en', 'gu', 'hi'],
                    'publisher' => ['@id' => $seoSiteUrl . '#organization'],
                    'potentialAction' => [
                        '@type'  => 'SearchAction',
                        'target' => ['@type' => 'EntryPoint', 'urlTemplate' => route('bhudevs.index') . '?q={search_term_string}'],
                        'query-input' => 'required name=search_term_string',
                    ],
                ],
            ],
        ];
    @endphp
    <script type="application/ld+json">{!! json_encode($siteSchema, $jsonFlags) !!}</script>
@endif
@stack('schema')
