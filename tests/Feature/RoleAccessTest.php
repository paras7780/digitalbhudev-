<?php

namespace Tests\Feature;

use App\Models\BhudevProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_bhudev_cannot_open_admin_dashboard(): void
    {
        $user = User::factory()->create([
            'role'              => 'bhudev',
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);

        // Navigating to /admin safely redirects Bhudev to their portal
        $this->actingAs($user)->get('/admin')->assertRedirect(route('bhudev.dashboard'));

        // Navigating to admin-only management routes is strictly forbidden
        $this->actingAs($user)->get('/admin/bhudevs')->assertForbidden();
    }

    public function test_guest_is_redirected_from_admin_dashboard(): void
    {
        $this->get('/admin')->assertRedirect('/login');
    }

    public function test_login_from_admin_url_redirects_bhudev_to_bhudev_dashboard_without_403(): void
    {
        $user = User::create([
            'name'              => 'Paras Rajyaguru',
            'email'             => 'rajyaguruparas669@gmail.com',
            'phone'             => '9876543210',
            'password'          => Hash::make('123456789012'),
            'role'              => 'bhudev',
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);

        BhudevProfile::create([
            'user_id'      => $user->id,
            'display_name' => 'Paras Rajyaguru',
            'slug'         => 'paras-rajyaguru',
            'status'       => 'approved',
            'is_visible'   => true,
        ]);

        // Guest visits /admin first
        $this->get('/admin')->assertRedirect(route('login'));

        // Logs in with Bhudev credentials
        $response = $this->post(route('login.store'), [
            'email'    => 'rajyaguruparas669@gmail.com',
            'password' => '123456789012',
        ]);

        // Redirects to bhudev portal, not /admin
        $response->assertRedirect(route('bhudev.dashboard'));

        // Following redirect lands with 200 OK
        $final = $this->followRedirects($response);
        $final->assertStatus(200);
        $final->assertSee('Namaste, Paras Rajyaguru');
    }
}
