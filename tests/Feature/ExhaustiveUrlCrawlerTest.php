<?php

namespace Tests\Feature;

use App\Models\BhudevDomain;
use App\Models\BhudevProfile;
use App\Models\BhudevSamagriCard;
use App\Models\Inquiry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExhaustiveUrlCrawlerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $bhudevUser;
    protected BhudevProfile $bhudevProfile;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::firstOrCreate(
            ['email' => 'admin@digitalbhudev.com'],
            ['name' => 'Super Admin', 'password' => bcrypt('password'), 'role' => 'admin', 'is_active' => true, 'email_verified_at' => now()]
        );

        $this->bhudevUser = User::firstOrCreate(
            ['email' => 'paras@test.com'],
            ['name' => 'Paras Rajyaguru', 'password' => bcrypt('password'), 'role' => 'bhudev', 'phone' => '9876543210', 'is_active' => true, 'email_verified_at' => now()]
        );

        $this->bhudevProfile = BhudevProfile::firstOrCreate(
            ['user_id' => $this->bhudevUser->id],
            [
                'display_name' => 'Paras Rajyaguru',
                'slug' => 'paras-rajyaguru',
                'city' => 'Ahmedabad',
                'experience_years' => 12,
                'status' => 'approved',
                'is_visible' => true,
            ]
        );
    }

    public function test_crawl_all_public_admin_and_bhudev_pages_and_links(): void
    {
        $urlsToVisit = [
            // Public Pages
            '/',
            '/about',
            '/contact',
            '/privacy',
            '/terms',
            '/services',
            '/panchang',
            '/rashifal',
            '/muhurat',
            '/samagri',
            '/samagri/satyanarayan-katha',
            '/samagri/grah-shanti-havan',
            '/bhudevs',
            '/bhudevs/' . $this->bhudevProfile->slug,
            '/login',
            '/register',
            '/forgot-password',
            '/language/en',
            '/language/gu',
            '/language/hi',
            '/lang/en',
            '/lang/gu',
            '/lang/hi',
        ];

        // Also add existing samagri cards
        $card = BhudevSamagriCard::first();
        if ($card) {
            $urlsToVisit[] = '/samagri/card/' . $card->id;
        }

        $visited = [];
        $broken = [];

        // 1. Check all public pages
        foreach ($urlsToVisit as $url) {
            $response = $this->get($url);
            $status = $response->getStatusCode();
            if ($status >= 400) {
                $broken[] = "Public URL '$url' returned HTTP $status";
            }
            $visited[$url] = $status;

            // Extract links from 200 responses
            if ($status === 200) {
                $this->extractAndVerifyInternalLinks($response->getContent(), $url, $broken);
            }
        }

        // 2. Check all Admin pages as Admin
        $adminPages = [
            '/admin',
            '/admin/bhudevs',
            '/admin/bhudevs/create',
            '/admin/bhudevs/' . $this->bhudevProfile->id,
            '/admin/bhudevs/' . $this->bhudevProfile->id . '/edit',
            '/admin/domains',
            '/admin/inquiries',
            '/admin/panchang',
            '/admin/rashifal',
            '/admin/audit-logs',
        ];

        foreach ($adminPages as $url) {
            $response = $this->actingAs($this->admin)->get($url);
            $status = $response->getStatusCode();
            if ($status >= 400) {
                $broken[] = "Admin URL '$url' returned HTTP $status";
            }
            if ($status === 200) {
                $this->extractAndVerifyInternalLinks($response->getContent(), $url, $broken);
            }
        }

        // 3. Check all Bhudev portal pages as Bhudev
        $bhudevPages = [
            '/bhudev',
            '/bhudev/website',
            '/bhudev/profile/edit',
            '/bhudev/gallery',
            '/bhudev/password',
            '/bhudev/history',
            '/bhudev/samagri-studio',
        ];

        foreach ($bhudevPages as $url) {
            $response = $this->actingAs($this->bhudevUser)->get($url);
            $status = $response->getStatusCode();
            if ($status >= 400) {
                $broken[] = "Bhudev Portal URL '$url' returned HTTP $status";
            }
            if ($status === 200) {
                $this->extractAndVerifyInternalLinks($response->getContent(), $url, $broken);
            }
        }

        if (!empty($broken)) {
            $this->fail("Found broken URLs/links:\n" . implode("\n", array_unique($broken)));
        }

        $this->assertTrue(true);
    }

    protected function extractAndVerifyInternalLinks(string $html, string $sourceUrl, array &$broken): void
    {
        // Extract all href attributes
        if (preg_match_all('/<a\s+[^>]*href=["\']([^"\']+)["\']/i', $html, $matches)) {
            foreach ($matches[1] as $href) {
                $href = trim($href);
                if (empty($href) || str_starts_with($href, '#') || str_starts_with($href, 'javascript:') || 
                    str_starts_with($href, 'mailto:') || str_starts_with($href, 'tel:') || 
                    str_starts_with($href, 'https://wa.me') || str_starts_with($href, 'https://api.whatsapp') ||
                    str_starts_with($href, 'https://www.google.com') || str_starts_with($href, 'https://fonts.')) {
                    continue;
                }

                // If link is a relative or absolute path, ensure it doesn't 404
                $path = parse_url($href, PHP_URL_PATH);
                $query = parse_url($href, PHP_URL_QUERY);
                $target = $path . ($query ? '?' . $query : '');

                // Ignore external domains
                $host = parse_url($href, PHP_URL_HOST);
                if ($host && $host !== 'localhost' && $host !== '127.0.0.1' && $host !== '192.168.0.237') {
                    continue;
                }

                if ($target) {
                    // Test target GET
                    try {
                        $res = $this->get($target);
                        $code = $res->getStatusCode();
                        if ($code >= 400) {
                            $broken[] = "Link '$href' on page '$sourceUrl' returned HTTP $code";
                        }
                    } catch (\Throwable $e) {
                        $broken[] = "Link '$href' on page '$sourceUrl' caused exception: " . $e->getMessage();
                    }
                }
            }
        }
    }
}
