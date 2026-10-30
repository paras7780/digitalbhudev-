<?php

namespace Tests\Feature;

use App\Models\BhudevDomain;
use App\Models\BhudevProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResponsiveLayoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_header_contains_mobile_drawer_and_backdrop(): void
    {
        $response = $this->get(route('home'));
        $response->assertStatus(200);

        // Hamburger button & backdrop
        $response->assertSee('class="menu-toggle"', false);
        $response->assertSee('class="nav-backdrop"', false);

        // Mobile drawer footer with auth & language
        $response->assertSee('class="mobile-nav-footer"', false);
        $response->assertSee('class="mobile-auth-buttons"', false);
        $response->assertSee('class="mobile-lang-row"', false);
    }

    public function test_admin_layout_contains_mobile_responsive_navigation(): void
    {
        $admin = User::factory()->create([
            'role'              => 'admin',
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);

        // Sidebar toggle button & overlay
        $response->assertSee('class="sidebar-toggle-btn"', false);
        $response->assertSee('class="sidebar-overlay"', false);
        $response->assertSee('class="admin-topbar"', false);
    }

    public function test_bhudev_portal_layout_contains_mobile_responsive_navigation(): void
    {
        $bhudev = User::factory()->create([
            'role'              => 'bhudev',
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);

        $profile = BhudevProfile::create([
            'user_id'      => $bhudev->id,
            'display_name' => 'Shastri Ramesh',
            'slug'         => 'shastri-ramesh-1',
            'status'       => 'approved',
            'is_visible'   => true,
        ]);

        $response = $this->actingAs($bhudev)->get(route('bhudev.dashboard'));
        $response->assertStatus(200);

        // Mobile sidebar toggle and overlay
        $response->assertSee('class="sidebar-toggle-btn"', false);
        $response->assertSee('class="sidebar-overlay"', false);
    }

    public function test_pandit_whitelabel_site_contains_mobile_navigation_toggle(): void
    {
        $bhudev = User::factory()->create([
            'role'              => 'bhudev',
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);

        $profile = BhudevProfile::create([
            'user_id'      => $bhudev->id,
            'display_name' => 'Shastri Paras',
            'slug'         => 'shastri-paras',
            'status'       => 'approved',
            'is_visible'   => true,
        ]);

        $domain = BhudevDomain::create([
            'bhudev_profile_id' => $profile->id,
            'domain_name'       => 'shastriparas.in',
            'status'            => 'active',
            'hero_title'        => 'Shastri Paras Maharaj',
        ]);

        $response = $this->get('http://shastriparas.in/');

        $response->assertStatus(200);
        $response->assertSee('id="panditMobileToggle"', false);
        $response->assertSee('id="panditBackdrop"', false);
        $response->assertSee('class="pandit-hero-grid"', false);
        $response->assertSee('class="pandit-form-grid"', false);
    }

    public function test_bhudev_directory_uses_responsive_search_grid(): void
    {
        $response = $this->get(route('bhudevs.index'));
        $response->assertStatus(200);
        $response->assertSee('class="bhudevs-search-grid"', false);
    }
}
