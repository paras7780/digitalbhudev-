<?php

namespace Tests\Feature;

use App\Models\BhudevProfile;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminBhudevManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $bhudevUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'name'              => 'Super Admin',
            'email'             => 'admin@digitalbhudev.com',
            'role'              => 'admin',
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);

        $this->bhudevUser = User::factory()->create([
            'name'              => 'Test Bhudev User',
            'email'             => 'bhudev@example.com',
            'role'              => 'bhudev',
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);
    }

    public function test_guest_is_redirected_from_admin_bhudev_routes(): void
    {
        $this->get('/admin/bhudevs')->assertRedirect('/login');
        $this->get('/admin/bhudevs/create')->assertRedirect('/login');
    }

    public function test_bhudev_role_is_forbidden_from_admin_bhudev_routes(): void
    {
        $this->actingAs($this->bhudevUser)->get('/admin/bhudevs')->assertForbidden();
        $this->actingAs($this->bhudevUser)->get('/admin/bhudevs/create')->assertForbidden();
    }

    public function test_admin_can_view_dashboard_with_all_metrics(): void
    {
        $user1 = User::factory()->create(['role' => 'bhudev']);
        BhudevProfile::create([
            'user_id'      => $user1->id,
            'display_name' => 'Approved Visible',
            'slug'         => 'approved-visible',
            'status'       => 'approved',
            'is_visible'   => true,
        ]);

        $user2 = User::factory()->create(['role' => 'bhudev']);
        BhudevProfile::create([
            'user_id'      => $user2->id,
            'display_name' => 'Approved Hidden',
            'slug'         => 'approved-hidden',
            'status'       => 'approved',
            'is_visible'   => false,
        ]);

        $user3 = User::factory()->create(['role' => 'bhudev']);
        BhudevProfile::create([
            'user_id'      => $user3->id,
            'display_name' => 'Pending Profile',
            'slug'         => 'pending-profile',
            'status'       => 'pending',
            'is_visible'   => false,
        ]);

        $response = $this->actingAs($this->admin)->get('/admin');

        $response->assertOk();
        $response->assertViewHas('counts', function ($counts) {
            return $counts['total'] === 3
                && $counts['approved'] === 2
                && $counts['hidden'] === 1
                && $counts['pending'] === 1;
        });
    }

    public function test_admin_can_filter_bhudev_listing_by_status(): void
    {
        $u1 = User::factory()->create(['role' => 'bhudev']);
        BhudevProfile::create([
            'user_id'      => $u1->id,
            'display_name' => 'Pandit Ramesh',
            'slug'         => 'pandit-ramesh',
            'status'       => 'pending',
            'is_visible'   => false,
        ]);

        $u2 = User::factory()->create(['role' => 'bhudev']);
        BhudevProfile::create([
            'user_id'      => $u2->id,
            'display_name' => 'Pandit Suresh',
            'slug'         => 'pandit-suresh',
            'status'       => 'approved',
            'is_visible'   => true,
        ]);

        $resPending = $this->actingAs($this->admin)->get('/admin/bhudevs?status=pending');
        $resPending->assertOk();
        $resPending->assertSee('Pandit Ramesh');
        $resPending->assertDontSee('Pandit Suresh');

        $resApproved = $this->actingAs($this->admin)->get('/admin/bhudevs?status=approved');
        $resApproved->assertOk();
        $resApproved->assertSee('Pandit Suresh');
        $resApproved->assertDontSee('Pandit Ramesh');
    }

    public function test_admin_can_create_bhudev_with_services_and_photo(): void
    {
        Storage::fake('public');

        $service1 = Service::create(['name' => 'Satyanarayan Katha', 'slug' => 'satyanarayan-katha', 'is_active' => true]);
        $service2 = Service::create(['name' => 'Griha Pravesh', 'slug' => 'griha-pravesh', 'is_active' => true]);

        $photo = UploadedFile::fake()->create('profile.jpg', 100, 'image/jpeg');

        $payload = [
            'name'             => 'Pandit Harshad Shastri',
            'email'            => 'harshad.shastri@example.com',
            'phone'            => '+91 98980 12345',
            'password'         => 'StrongPassword123!',
            'city'             => 'Ahmedabad',
            'state'            => 'Gujarat',
            'address'          => '102 Shivalik Heights, CG Road',
            'service_area'     => 'Ahmedabad & Gandhinagar',
            'languages'        => 'Gujarati, Hindi, Sanskrit',
            'expertise'        => 'Vedic Rituals, Vivah Sanskar',
            'experience_years' => 18,
            'qualification'    => 'Shukla Yajurveda Acharya',
            'availability'     => 'Available all days, 6:00 AM - 8:00 PM',
            'bio'              => 'Practicing Vedic purohit with over 18 years of experience.',
            'status'           => 'approved',
            'is_visible'       => true,
            'show_mobile'      => true,
            'show_email'       => false,
            'show_address'     => false,
            'seo_title'        => 'Pandit Harshad Shastri in Ahmedabad',
            'seo_description'  => 'Book Pandit Harshad Shastri for sacred ceremonies.',
            'services'         => [$service1->id, $service2->id],
            'photo'            => $photo,
        ];

        $response = $this->actingAs($this->admin)->post('/admin/bhudevs', $payload);

        $this->assertDatabaseHas('users', [
            'email' => 'harshad.shastri@example.com',
            'role'  => 'bhudev',
        ]);

        $createdProfile = BhudevProfile::where('display_name', 'Pandit Harshad Shastri')->first();
        $this->assertNotNull($createdProfile);
        $this->assertEquals('approved', $createdProfile->status);
        $this->assertTrue($createdProfile->is_visible);
        $this->assertEquals('Available all days, 6:00 AM - 8:00 PM', $createdProfile->availability);
        $this->assertCount(2, $createdProfile->services);

        $response->assertRedirect(route('admin.bhudevs.show', $createdProfile));
    }

    public function test_admin_can_view_bhudev_details(): void
    {
        $user = User::factory()->create(['role' => 'bhudev']);
        $profile = BhudevProfile::create([
            'user_id'      => $user->id,
            'display_name' => 'Pandit Devendra Joshi',
            'slug'         => 'pandit-devendra-joshi',
            'city'         => 'Surat',
            'state'        => 'Gujarat',
            'status'       => 'approved',
            'is_visible'   => true,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.bhudevs.show', $profile));
        $response->assertOk();
        $response->assertSee('Pandit Devendra Joshi');
        $response->assertSee('Surat');
    }

    public function test_admin_can_edit_bhudev(): void
    {
        $user = User::factory()->create(['name' => 'Old Name', 'email' => 'old@example.com', 'role' => 'bhudev']);
        $profile = BhudevProfile::create([
            'user_id'      => $user->id,
            'display_name' => 'Old Name',
            'slug'         => 'old-name',
            'city'         => 'Rajkot',
            'status'       => 'pending',
            'is_visible'   => false,
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.bhudevs.update', $profile), [
            'name'             => 'Updated New Name',
            'email'            => 'updated@example.com',
            'phone'            => '+91 91234 56789',
            'city'             => 'Vadodara',
            'state'            => 'Gujarat',
            'status'           => 'approved',
            'is_visible'       => true,
            'availability'     => 'Morning pooja only',
            'experience_years' => 12,
        ]);

        $profile->refresh();
        $user->refresh();

        $this->assertEquals('Updated New Name', $profile->display_name);
        $this->assertEquals('Vadodara', $profile->city);
        $this->assertEquals('approved', $profile->status);
        $this->assertTrue($profile->is_visible);
        $this->assertEquals('Morning pooja only', $profile->availability);
        $this->assertEquals('updated@example.com', $user->email);

        $response->assertRedirect(route('admin.bhudevs.show', $profile));
    }

    public function test_admin_can_approve_profile(): void
    {
        $user = User::factory()->create(['role' => 'bhudev']);
        $profile = BhudevProfile::create([
            'user_id'      => $user->id,
            'display_name' => 'Pending Pandit',
            'slug'         => 'pending-pandit',
            'status'       => 'pending',
            'is_visible'   => false,
        ]);

        $response = $this->actingAs($this->admin)->patch(route('admin.bhudevs.approve', $profile));
        $response->assertSessionHas('status');

        $profile->refresh();
        $this->assertEquals('approved', $profile->status);
        $this->assertTrue($profile->is_visible);
    }

    public function test_admin_can_reject_profile_with_reason(): void
    {
        $user = User::factory()->create(['role' => 'bhudev']);
        $profile = BhudevProfile::create([
            'user_id'      => $user->id,
            'display_name' => 'Unverified Pandit',
            'slug'         => 'unverified-pandit',
            'status'       => 'pending',
            'is_visible'   => false,
        ]);

        $response = $this->actingAs($this->admin)->patch(route('admin.bhudevs.reject', $profile), [
            'reason' => 'Photo is unclear and contact number cannot be verified.',
        ]);

        $response->assertSessionHas('status');

        $profile->refresh();
        $this->assertEquals('rejected', $profile->status);
        $this->assertFalse($profile->is_visible);
        $this->assertEquals('Photo is unclear and contact number cannot be verified.', $profile->rejection_reason);
    }

    public function test_admin_can_suspend_and_reactivate_profile(): void
    {
        $user = User::factory()->create(['role' => 'bhudev']);
        $profile = BhudevProfile::create([
            'user_id'      => $user->id,
            'display_name' => 'Active Pandit',
            'slug'         => 'active-pandit',
            'status'       => 'approved',
            'is_visible'   => true,
        ]);

        // Suspend
        $resSuspend = $this->actingAs($this->admin)->patch(route('admin.bhudevs.suspend', $profile), [
            'reason' => 'Temporary suspension for annual verification.',
        ]);
        $resSuspend->assertSessionHas('status');

        $profile->refresh();
        $this->assertEquals('suspended', $profile->status);
        $this->assertFalse($profile->is_visible);

        // Reactivate
        $resReactivate = $this->actingAs($this->admin)->patch(route('admin.bhudevs.reactivate', $profile));
        $resReactivate->assertSessionHas('status');

        $profile->refresh();
        $this->assertEquals('approved', $profile->status);
        $this->assertTrue($profile->is_visible);
    }

    public function test_admin_can_toggle_visibility_of_approved_profile(): void
    {
        $user = User::factory()->create(['role' => 'bhudev']);
        $profile = BhudevProfile::create([
            'user_id'      => $user->id,
            'display_name' => 'Visible Pandit',
            'slug'         => 'visible-pandit',
            'status'       => 'approved',
            'is_visible'   => true,
        ]);

        // Toggle to hidden
        $this->actingAs($this->admin)->patch(route('admin.bhudevs.toggle-visibility', $profile));
        $profile->refresh();
        $this->assertFalse($profile->is_visible);

        // Toggle back to visible
        $this->actingAs($this->admin)->patch(route('admin.bhudevs.toggle-visibility', $profile));
        $profile->refresh();
        $this->assertTrue($profile->is_visible);
    }

    public function test_hidden_or_rejected_or_suspended_profile_is_inaccessible_publicly(): void
    {
        $u1 = User::factory()->create(['role' => 'bhudev', 'is_active' => true]);
        BhudevProfile::create([
            'user_id'      => $u1->id,
            'display_name' => 'Hidden Guy',
            'slug'         => 'hidden-guy',
            'status'       => 'approved',
            'is_visible'   => false,
        ]);

        $u2 = User::factory()->create(['role' => 'bhudev', 'is_active' => true]);
        BhudevProfile::create([
            'user_id'      => $u2->id,
            'display_name' => 'Rejected Guy',
            'slug'         => 'rejected-guy',
            'status'       => 'rejected',
            'is_visible'   => false,
        ]);

        $u3 = User::factory()->create(['role' => 'bhudev', 'is_active' => true]);
        BhudevProfile::create([
            'user_id'      => $u3->id,
            'display_name' => 'Suspended Guy',
            'slug'         => 'suspended-guy',
            'status'       => 'suspended',
            'is_visible'   => false,
        ]);

        $this->get('/bhudevs')->assertDontSee('Hidden Guy');
        $this->get('/bhudevs')->assertDontSee('Rejected Guy');
        $this->get('/bhudevs')->assertDontSee('Suspended Guy');

        $this->get('/bhudevs/hidden-guy')->assertNotFound();
        $this->get('/bhudevs/rejected-guy')->assertNotFound();
        $this->get('/bhudevs/suspended-guy')->assertNotFound();
    }

    public function test_admin_can_safely_delete_bhudev(): void
    {
        $user = User::factory()->create(['role' => 'bhudev']);
        $profile = BhudevProfile::create([
            'user_id'      => $user->id,
            'display_name' => 'To Be Deleted',
            'slug'         => 'to-be-deleted',
            'status'       => 'approved',
            'is_visible'   => true,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.bhudevs.delete', $profile));
        $response->assertRedirect(route('admin.bhudevs'));

        // Verify soft-deleted
        $this->assertSoftDeleted('bhudev_profiles', ['id' => $profile->id]);
        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }
}
