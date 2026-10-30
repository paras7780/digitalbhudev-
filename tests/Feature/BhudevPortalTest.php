<?php

namespace Tests\Feature;

use App\Models\BhudevImage;
use App\Models\BhudevProfile;
use App\Models\ProfileRevision;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class BhudevPortalTest extends TestCase
{
    use RefreshDatabase;

    private function createBhudev(array $attributes = [], array $profileAttributes = []): array
    {
        $user = User::factory()->create(array_merge([
            'role'              => 'bhudev',
            'is_active'         => true,
            'email_verified_at' => now(),
        ], $attributes));

        $profile = BhudevProfile::create(array_merge([
            'user_id'      => $user->id,
            'display_name' => $user->name,
            'slug'         => 'bhudev-' . $user->id,
            'city'         => 'Ahmedabad',
            'state'        => 'Gujarat',
            'status'       => 'pending',
            'is_visible'   => false,
        ], $profileAttributes));

        return [$user, $profile];
    }

    private function createAdmin(): User
    {
        return User::factory()->create([
            'role'              => 'admin',
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);
    }

    public function test_email_verification_signed_url_verifies_user_without_csrf_419()
    {
        $user = User::factory()->create([
            'role'              => 'bhudev',
            'is_active'         => true,
            'email_verified_at' => null,
        ]);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->getEmailForVerification())]
        );

        // Click signed link from external mail client (no session, no CSRF)
        $response = $this->get($verificationUrl);

        $response->assertRedirect(route('bhudev.dashboard'));
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        $this->assertAuthenticatedAs($user);
    }

    public function test_tampered_email_verification_link_is_rejected()
    {
        $user = User::factory()->create([
            'role'              => 'bhudev',
            'email_verified_at' => null,
        ]);

        $invalidUrl = route('verification.verify', [
            'id'   => $user->id,
            'hash' => 'invalid-hash',
        ]);

        $response = $this->get($invalidUrl);
        $response->assertStatus(403);
    }

    public function test_bhudev_cannot_access_admin_panel()
    {
        [$bhudev] = $this->createBhudev();

        // Navigating to /admin safely routes them to their portal
        $response = $this->actingAs($bhudev)->get(route('admin.dashboard'));
        $response->assertRedirect(route('bhudev.dashboard'));

        // Direct sub-routes are strictly forbidden
        $response = $this->actingAs($bhudev)->get(route('admin.bhudevs'));
        $response->assertStatus(403);
    }

    public function test_admin_cannot_access_bhudev_portal()
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(route('bhudev.dashboard'));
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_bhudev_dashboard_renders_with_personal_overview()
    {
        [$bhudev, $profile] = $this->createBhudev([], [
            'status'     => 'pending',
            'is_visible' => false,
        ]);

        $response = $this->actingAs($bhudev)->get(route('bhudev.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Namaste, ' . $bhudev->name);
        $response->assertSee('Bhudev Portal');
        $response->assertSee('Profile Pending Admin Verification');
        $response->assertDontSee('Control Center');
        $response->assertDontSee('Audit Trail');
    }

    public function test_bhudev_can_edit_and_update_pending_profile()
    {
        [$bhudev, $profile] = $this->createBhudev();
        $service = Service::create(['name' => 'Rudrabhishek', 'slug' => 'rudrabhishek', 'is_active' => true]);

        $response = $this->actingAs($bhudev)->get(route('bhudev.profile.edit'));
        $response->assertStatus(200);
        $response->assertSee('Edit Profile & Services');

        $updateResponse = $this->actingAs($bhudev)->put(route('bhudev.profile.update'), [
            'display_name'     => 'Pandit Rameshwar Joshi',
            'city'             => 'Vadodara',
            'state'            => 'Gujarat',
            'address'          => '102 Shivam Complex, Alkapuri',
            'service_area'     => 'Vadodara and Anand district',
            'languages'        => 'Gujarati, Hindi, Sanskrit',
            'expertise'        => 'Specialized in Vivah Sanskar and Rudrabhishek',
            'experience_years' => 18,
            'qualification'    => 'Acharya degree in Shukla Yajurveda',
            'availability'     => 'Available with 2 days prior notice',
            'bio'              => 'Practicing Vedic priest for over 18 years.',
            'show_mobile'      => 1,
            'show_email'       => 0,
            'show_address'     => 1,
            'services'         => [$service->id],
        ]);

        $updateResponse->assertRedirect(route('bhudev.profile.edit'));

        $profile->refresh();
        $this->assertSame('Pandit Rameshwar Joshi', $profile->display_name);
        $this->assertSame('Vadodara', $profile->city);
        $this->assertSame(18, $profile->experience_years);
        $this->assertTrue($profile->show_mobile);
        $this->assertFalse($profile->show_email);
        $this->assertTrue($profile->services->contains($service->id));
    }

    public function test_approved_bhudev_profile_edit_creates_pending_revision_without_breaking_live_profile()
    {
        [$bhudev, $profile] = $this->createBhudev([], [
            'status'       => 'approved',
            'is_visible'   => true,
            'display_name' => 'Original Live Name',
            'city'         => 'Surat',
        ]);

        $updateResponse = $this->actingAs($bhudev)->put(route('bhudev.profile.update'), [
            'display_name'     => 'Updated Name Request',
            'city'             => 'Navsari',
            'state'            => 'Gujarat',
            'experience_years' => 20,
        ]);

        $updateResponse->assertRedirect(route('bhudev.profile.edit'));

        // Live profile must remain approved and visible with updated details
        $profile->refresh();
        $this->assertSame('approved', $profile->status);
        $this->assertTrue($profile->is_visible);
        $this->assertSame('Updated Name Request', $profile->display_name);
        $this->assertSame('Navsari', $profile->city);
        $this->assertSame(20, $profile->experience_years);

        // A revision must have been created
        $revision = ProfileRevision::where('bhudev_profile_id', $profile->id)->latest()->first();
        $this->assertNotNull($revision);
        $this->assertSame('pending', $revision->review_status);
        $this->assertSame('Updated Name Request', $revision->payload['display_name']);
    }

    public function test_bhudev_can_manage_gallery_photos()
    {
        Storage::fake('public');
        [$bhudev, $profile] = $this->createBhudev();

        $file = UploadedFile::fake()->create('ceremony.jpg', 150, 'image/jpeg');

        $response = $this->actingAs($bhudev)->post(route('bhudev.gallery.upload'), [
            'gallery' => [$file],
        ]);

        $response->assertSessionHas('status');
        $this->assertDatabaseHas('bhudev_images', [
            'bhudev_profile_id' => $profile->id,
        ]);

        $image = BhudevImage::where('bhudev_profile_id', $profile->id)->first();

        // Delete own image
        $deleteResponse = $this->actingAs($bhudev)->delete(route('bhudev.gallery.delete', $image));
        $deleteResponse->assertSessionHas('status');
        $this->assertDatabaseMissing('bhudev_images', ['id' => $image->id]);
    }

    public function test_bhudev_cannot_delete_another_bhudevs_gallery_photo()
    {
        [$bhudev1] = $this->createBhudev();
        [$bhudev2, $profile2] = $this->createBhudev();

        $image = BhudevImage::create([
            'bhudev_profile_id' => $profile2->id,
            'path'              => 'bhudev-gallery/sample.jpg',
            'is_approved'       => true,
            'sort_order'        => 0,
        ]);

        $response = $this->actingAs($bhudev1)->delete(route('bhudev.gallery.delete', $image));
        $response->assertStatus(404);
        $this->assertDatabaseHas('bhudev_images', ['id' => $image->id]);
    }

    public function test_bhudev_can_change_password()
    {
        [$bhudev] = $this->createBhudev(['password' => Hash::make('CurrentSecret123!')]);

        $response = $this->actingAs($bhudev)->put(route('bhudev.password.update'), [
            'current_password'      => 'CurrentSecret123!',
            'password'              => 'NewStrongSecret456!',
            'password_confirmation' => 'NewStrongSecret456!',
        ]);

        $response->assertSessionHas('status');
        $this->assertTrue(Hash::check('NewStrongSecret456!', $bhudev->fresh()->password));
    }
}
