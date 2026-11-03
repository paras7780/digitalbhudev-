<?php

namespace Tests\Feature;

use App\Models\BhudevDomain;
use App\Models\BhudevProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    private function createBhudev(string $slug, string $status = 'approved', bool $visible = true): BhudevProfile
    {
        $user = User::factory()->create([
            'role'              => 'bhudev',
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);

        return BhudevProfile::create([
            'user_id'      => $user->id,
            'display_name' => 'Shastri ' . ucfirst(str_replace('-', ' ', $slug)),
            'slug'         => $slug,
            'city'         => 'Ahmedabad',
            'state'        => 'Gujarat',
            'bio'          => 'Vedic priest for Satyanarayan Katha and Griha Pravesh.',
            'status'       => $status,
            'is_visible'   => $visible,
        ]);
    }

    public function test_public_pages_carry_canonical_open_graph_and_site_schema(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('<link rel="canonical" href="' . route('home') . '">', false)
            ->assertSee('<meta name="robots" content="index, follow">', false)
            ->assertSee('property="og:title"', false)
            ->assertSee('property="og:image" content="' . \App\Support\Seo::absolute(asset('assets/img/og-default.jpg')) . '"', false)
            ->assertSee('name="twitter:card"', false)
            ->assertSee('"@type":"WebSite"', false)
            ->assertSee('"@type":"SearchAction"', false);
    }

    public function test_auth_pages_are_not_indexed(): void
    {
        $this->get(route('login'))->assertOk()->assertSee('<meta name="robots" content="noindex, nofollow">', false);
        $this->get(route('register'))->assertOk()->assertSee('<meta name="robots" content="noindex, nofollow">', false);
    }

    public function test_bhudev_profile_has_person_schema_and_profile_open_graph(): void
    {
        $profile = $this->createBhudev('shastri-seo-test');

        $this->get(route('bhudevs.show', $profile->slug))
            ->assertOk()
            ->assertSee('<meta property="og:type" content="profile">', false)
            ->assertSee('"@type":"Person"', false)
            ->assertSee('"name":"' . $profile->display_name . '"', false)
            ->assertSee('"addressLocality":"Ahmedabad"', false)
            ->assertSee('"@type":"BreadcrumbList"', false);
    }

    public function test_samagri_checklist_has_breadcrumb_schema(): void
    {
        $this->get(route('samagri.show', 'griha-pravesh'))
            ->assertOk()
            ->assertSee('"@type":"BreadcrumbList"', false)
            ->assertSee('"@type":"Organization"', false);
    }

    public function test_sitemap_lists_public_pages_and_only_visible_bhudevs(): void
    {
        $visible = $this->createBhudev('visible-bhudev');
        $pending = $this->createBhudev('pending-bhudev', 'pending');
        $hidden  = $this->createBhudev('hidden-bhudev', 'approved', false);

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee('<loc>' . route('bhudevs.index') . '</loc>', false)
            ->assertSee('<loc>' . route('samagri.show', 'griha-pravesh') . '</loc>', false)
            ->assertSee('<loc>' . route('bhudevs.show', $visible->slug) . '</loc>', false)
            ->assertDontSee($pending->slug)
            ->assertDontSee($hidden->slug);
    }

    public function test_robots_blocks_private_areas_and_points_to_sitemap(): void
    {
        $this->get('/robots.txt')
            ->assertOk()
            ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
            ->assertSee('Disallow: /admin')
            ->assertSee('Disallow: /bhudev')
            ->assertSee('Sitemap: ' . url('/sitemap.xml'));
    }

    public function test_pandit_website_has_local_business_schema_and_its_own_sitemap(): void
    {
        $profile = $this->createBhudev('shastri-tenant-seo');
        BhudevDomain::create([
            'bhudev_profile_id' => $profile->id,
            'domain_name'       => 'shastriseo.in',
            'status'            => 'active',
            'tier'              => 'standard_saas',
            'hero_title'        => 'Shastri Tenant Seo Jyotish Karyalay',
            'whatsapp_number'   => '9876543210',
            'activated_at'      => now(),
        ]);

        $this->get('http://shastriseo.in/')
            ->assertOk()
            ->assertSee('<link rel="canonical" href="http://shastriseo.in">', false)
            ->assertSee('<meta property="og:site_name" content="' . $profile->display_name . '">', false)
            ->assertSee('"@type":"LocalBusiness"', false)
            ->assertSee('"name":"Shastri Tenant Seo Jyotish Karyalay"', false)
            ->assertSee('"telephone":"+919876543210"', false)
            ->assertDontSee('"@type":"WebSite"', false);

        // A Pandit's own domain never exposes the directory through its sitemap or robots
        $this->get('http://shastriseo.in/sitemap.xml')
            ->assertOk()
            ->assertSee('<loc>http://shastriseo.in/</loc>', false)
            ->assertDontSee('/bhudevs');

        $this->get('http://shastriseo.in/robots.txt')
            ->assertOk()
            ->assertSee('Sitemap: http://shastriseo.in/sitemap.xml')
            ->assertDontSee('Disallow: /admin');
    }
}
