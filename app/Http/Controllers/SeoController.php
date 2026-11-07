<?php

namespace App\Http\Controllers;

use App\Models\BhudevDomain;
use App\Models\BhudevProfile;
use App\Services\PoojaSamagriService;
use App\Support\Seo;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * robots.txt and sitemap.xml, generated per host.
 *
 * On the main DigitalBhudev host the sitemap lists every public page and every visible Bhudev
 * profile. On a Pandit's custom domain it lists only that website's landing page, so search
 * engines never discover the directory through a Pandit's own domain.
 */
class SeoController extends Controller
{
    public function robots(Request $request): Response
    {
        $tenant = $this->tenant();

        $lines = ['User-agent: *'];

        if ($tenant) {
            $lines[] = 'Allow: /';
        } else {
            $lines = array_merge($lines, [
                'Allow: /',
                'Disallow: /admin',
                'Disallow: /bhudev',
                'Disallow: /login',
                'Disallow: /register',
                'Disallow: /password',
                'Disallow: /email/',
                'Disallow: /language/',
                'Disallow: /lang/',
                'Disallow: /*?preview_domain=',
            ]);
        }

        $lines[] = '';
        $lines[] = 'Sitemap: ' . route('seo.sitemap');

        return response(implode("\n", $lines) . "\n", 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    public function sitemap(Request $request): Response
    {
        $tenant = $this->tenant();

        if ($tenant) {
            $entries = [[
                'loc'        => Seo::siteUrl(),
                'lastmod'    => optional($tenant->updated_at)->toDateString(),
                'changefreq' => 'weekly',
                'priority'   => '1.0',
            ]];
        } else {
            $entries = [
                ['loc' => route('home'),           'changefreq' => 'daily',   'priority' => '1.0'],
                ['loc' => route('bhudevs.index'),  'changefreq' => 'daily',   'priority' => '0.9'],
                ['loc' => route('services.index'), 'changefreq' => 'monthly', 'priority' => '0.8'],
                ['loc' => route('samagri.index'),  'changefreq' => 'monthly', 'priority' => '0.8'],
                ['loc' => route('muhurat.index'),  'changefreq' => 'monthly', 'priority' => '0.7'],
                ['loc' => route('panchang.index'), 'changefreq' => 'daily',   'priority' => '0.7'],
                ['loc' => route('rashifal.index'), 'changefreq' => 'daily',   'priority' => '0.7'],
                ['loc' => route('about'),          'changefreq' => 'yearly',  'priority' => '0.4'],
                ['loc' => route('contact'),        'changefreq' => 'yearly',  'priority' => '0.4'],
                ['loc' => route('privacy'),        'changefreq' => 'monthly', 'priority' => '0.5'],
                ['loc' => route('terms'),          'changefreq' => 'monthly', 'priority' => '0.5'],
                ['loc' => route('refund.policy'),  'changefreq' => 'monthly', 'priority' => '0.5'],
                ['loc' => route('disclaimer'),     'changefreq' => 'monthly', 'priority' => '0.5'],
            ];

            foreach (array_keys(PoojaSamagriService::all()) as $slug) {
                $entries[] = ['loc' => route('samagri.show', $slug), 'changefreq' => 'monthly', 'priority' => '0.7'];
            }

            BhudevProfile::publiclyVisible()
                ->orderBy('id')
                ->select(['slug', 'updated_at'])
                ->each(function (BhudevProfile $profile) use (&$entries) {
                    $entries[] = [
                        'loc'        => route('bhudevs.show', $profile->slug),
                        'lastmod'    => optional($profile->updated_at)->toDateString(),
                        'changefreq' => 'weekly',
                        'priority'   => '0.8',
                    ];
                });
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
             . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($entries as $entry) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>' . htmlspecialchars($entry['loc'], ENT_XML1) . "</loc>\n";
            if (! empty($entry['lastmod'])) {
                $xml .= '    <lastmod>' . $entry['lastmod'] . "</lastmod>\n";
            }
            $xml .= '    <changefreq>' . $entry['changefreq'] . "</changefreq>\n";
            $xml .= '    <priority>' . $entry['priority'] . "</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>' . "\n";

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    private function tenant(): ?BhudevDomain
    {
        $domain = app()->bound('current_tenant_domain') ? app('current_tenant_domain') : null;

        return $domain instanceof BhudevDomain && $domain->isActive() ? $domain : null;
    }
}
