<?php

namespace Tests\Feature;

use App\Models\BhudevProfile;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebsitePagesTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private BhudevProfile $approvedProfile;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'name'              => 'Administrator',
            'email'             => 'admin@digitalbhudev.com',
            'role'              => 'admin',
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);

        $bhudevUser = User::factory()->create([
            'name'              => 'Pandit Shivshankar',
            'email'             => 'shivshankar@example.com',
            'role'              => 'bhudev',
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);

        $this->approvedProfile = BhudevProfile::create([
            'user_id'          => $bhudevUser->id,
            'display_name'     => 'Pandit Shivshankar',
            'slug'             => 'pandit-shivshankar',
            'city'             => 'Ahmedabad',
            'state'            => 'Gujarat',
            'status'           => 'approved',
            'is_visible'       => true,
            'experience_years' => 20,
            'bio'              => 'Experienced Vedic Purohit.',
        ]);
    }

    public function test_all_public_frontend_pages_render_cleanly(): void
    {
        $this->get('/')->assertOk()->assertSee('DigitalBhudev');
        $this->get('/bhudevs')->assertOk()->assertSee('Pandit Shivshankar');
        $this->get('/bhudevs/pandit-shivshankar')->assertOk()->assertSee('Pandit Shivshankar');
        $this->get('/services')->assertOk()->assertSee('Services');
        $this->get('/panchang')->assertOk()->assertSee('Panchang');
        $this->get('/rashifal')->assertOk()->assertSee('Rashifal');
        $this->get('/about')->assertOk()->assertSee('About');
        $this->get('/contact')->assertOk()->assertSee('Contact');
        $this->get('/privacy')->assertOk()->assertSee('Privacy');
        $this->get('/terms')->assertOk()->assertSee('Terms');
        $this->get('/login')->assertOk()->assertSee('Log in');
        $this->get('/register')->assertOk()->assertSee('Bhudev');
    }

    public function test_all_admin_pages_render_cleanly_for_authenticated_admin(): void
    {
        $this->actingAs($this->admin)->get('/admin')->assertOk()->assertSee('Admin Dashboard');
        $this->actingAs($this->admin)->get('/admin/bhudevs')->assertOk()->assertSee('Bhudev Management');
        $this->actingAs($this->admin)->get('/admin/bhudevs/create')->assertOk()->assertSee('Register New Bhudev');
        $this->actingAs($this->admin)->get(route('admin.bhudevs.show', $this->approvedProfile))->assertOk()->assertSee('Pandit Shivshankar');
        $this->actingAs($this->admin)->get(route('admin.bhudevs.edit', $this->approvedProfile))->assertOk()->assertSee('Edit Bhudev');
        $this->actingAs($this->admin)->get('/admin/rashifal')->assertOk()->assertSee('Daily Rashifal');
        $this->actingAs($this->admin)->get('/admin/panchang')->assertOk()->assertSee('Nitya Panchang');
        $this->actingAs($this->admin)->get('/admin/inquiries')->assertOk()->assertSee('Contact Inquiries');
        $this->actingAs($this->admin)->get('/admin/audit-logs')->assertOk()->assertSee('Audit Trail');
    }
}
