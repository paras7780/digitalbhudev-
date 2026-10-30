<?php

namespace Tests\Feature;

use App\Mail\NewInquiryAlertMail;
use App\Models\BhudevImage;
use App\Models\BhudevProfile;
use App\Models\Inquiry;
use App\Models\PanchangEntry;
use App\Models\Rashifal;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ComprehensiveManualAuditTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $bhudevUser;
    private BhudevProfile $approvedProfile;
    private BhudevProfile $pendingProfile;
    private Service $service;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed 1 active service
        $this->service = Service::create([
            'name' => 'Satyanarayan Katha',
            'slug' => 'satyanarayan-katha',
            'is_active' => true,
        ]);

        // Seed Admin user
        $this->admin = User::create([
            'name' => 'DigitalBhudev Admin',
            'email' => 'admin@digitalbhudev.com',
            'phone' => '9999999999',
            'password' => Hash::make('AdminSecret123!'),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Seed Bhudev user & approved profile
        $this->bhudevUser = User::create([
            'name' => 'Pandit Rameshwar Joshi',
            'email' => 'rajyaguruparas669@gmail.com',
            'phone' => '9825012345',
            'password' => Hash::make('123456789012'),
            'role' => 'bhudev',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $this->approvedProfile = BhudevProfile::create([
            'user_id' => $this->bhudevUser->id,
            'display_name' => 'Pandit Rameshwar Joshi',
            'slug' => 'pandit-rameshwar-joshi',
            'city' => 'Ahmedabad',
            'state' => 'Gujarat',
            'status' => 'approved',
            'is_visible' => true,
            'show_mobile' => true,
            'show_email' => true,
            'show_address' => true,
            'experience_years' => 15,
            'availability' => 'Available on Booking',
            'bio' => 'Vedic scholar with 15 years experience.',
        ]);
        $this->approvedProfile->services()->attach($this->service->id);

        // Seed pending profile
        $pendingUser = User::create([
            'name' => 'Shastri Jayesh Dave',
            'email' => 'jayesh@digitalbhudev.com',
            'phone' => '9825099999',
            'password' => Hash::make('Password123!'),
            'role' => 'bhudev',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $this->pendingProfile = BhudevProfile::create([
            'user_id' => $pendingUser->id,
            'display_name' => 'Shastri Jayesh Dave',
            'slug' => 'shastri-jayesh-dave',
            'city' => 'Surat',
            'state' => 'Gujarat',
            'status' => 'pending',
            'is_visible' => false,
        ]);
    }

    public function test_all_public_guest_pages_render_cleanly()
    {
        $pages = [
            '/',
            '/bhudevs',
            '/bhudevs?city=Ahmedabad',
            '/bhudevs?q=Rameshwar',
            '/bhudevs/' . $this->approvedProfile->slug,
            '/services',
            '/samagri',
            '/samagri/satyanarayan-katha',
            '/samagri/griha-pravesh',
            '/muhurat',
            '/panchang',
            '/panchang?location=Surat',
            '/rashifal',
            '/about',
            '/contact',
            '/privacy',
            '/terms',
            '/login',
            '/register',
            '/forgot-password',
        ];

        foreach ($pages as $url) {
            $response = $this->get($url);
            $response->assertStatus(200, "Page {$url} failed to render cleanly.");
            $response->assertDontSee('Fatal error');
            $response->assertDontSee('Parse error');
            $response->assertDontSee('Notice:');
            $response->assertDontSee('Warning:');
            $response->assertDontSee('ErrorException');
        }
    }

    public function test_non_approved_bhudev_profiles_return_404_publicly()
    {
        // Pending profile must 404
        $this->get('/bhudevs/' . $this->pendingProfile->slug)->assertStatus(404);

        // Hidden profile must 404
        $this->approvedProfile->update(['is_visible' => false]);
        $this->get('/bhudevs/' . $this->approvedProfile->slug)->assertStatus(404);

        // Suspended profile must 404
        $this->approvedProfile->update(['status' => 'suspended', 'is_visible' => true]);
        $this->get('/bhudevs/' . $this->approvedProfile->slug)->assertStatus(404);

        // Rejected profile must 404
        $this->approvedProfile->update(['status' => 'rejected', 'is_visible' => true]);
        $this->get('/bhudevs/' . $this->approvedProfile->slug)->assertStatus(404);
    }

    public function test_contact_and_booking_forms_submission_flow()
    {
        Mail::fake();

        // 1. Submit contact inquiry
        $contactRes = $this->post(route('contact.store'), [
            'name' => 'Auditor Devotee',
            'email' => 'auditor@example.com',
            'phone' => '9825000000',
            'subject' => 'Vedic Puja Inquiry',
            'message' => 'Please provide details about home puja.',
        ]);
        $contactRes->assertSessionHas('status');
        $this->assertDatabaseHas('inquiries', [
            'name' => 'Auditor Devotee',
            'email' => 'auditor@example.com',
            'type' => 'contact',
        ]);

        // 2. Submit direct booking for approved Bhudev
        $bookingRes = $this->post(route('booking.store'), [
            'name' => 'Devotee Jayesh',
            'phone' => '9825111111',
            'email' => 'jayesh@example.com',
            'city' => 'Ahmedabad',
            'pooja_service' => 'Satyanarayan Katha',
            'preferred_date' => '2026-11-20',
            'bhudev_profile_id' => $this->approvedProfile->id,
            'message' => 'Need katha on Friday evening.',
        ]);
        $bookingRes->assertSessionHas('status');
        $this->assertDatabaseHas('inquiries', [
            'name' => 'Devotee Jayesh',
            'type' => 'booking',
            'bhudev_profile_id' => $this->approvedProfile->id,
        ]);

        Mail::assertSent(NewInquiryAlertMail::class);
    }

    public function test_bhudev_portal_complete_workflow()
    {
        Storage::fake('public');

        // 1. Dashboard
        $dashRes = $this->actingAs($this->bhudevUser)->get(route('bhudev.dashboard'));
        $dashRes->assertStatus(200);
        $dashRes->assertSee('Namaste, Pandit Rameshwar Joshi');
        $dashRes->assertSee('Bhudev Portal');

        // 2. Profile Edit
        $editRes = $this->actingAs($this->bhudevUser)->get(route('bhudev.profile.edit'));
        $editRes->assertStatus(200);
        $editRes->assertSee('Edit Profile & Services');

        // 3. Profile Update on approved profile (Creates pending revision)
        $updateRes = $this->actingAs($this->bhudevUser)->put(route('bhudev.profile.update'), [
            'display_name' => 'Pandit Rameshwar Joshi (Updated)',
            'city' => 'Gandhinagar',
            'state' => 'Gujarat',
            'experience_years' => 16,
        ]);
        $updateRes->assertRedirect(route('bhudev.profile.edit'));
        $this->assertDatabaseHas('profile_revisions', [
            'bhudev_profile_id' => $this->approvedProfile->id,
            'review_status' => 'pending',
        ]);

        // 4. Gallery upload
        $file = UploadedFile::fake()->create('puja.jpg', 120, 'image/jpeg');
        $galRes = $this->actingAs($this->bhudevUser)->post(route('bhudev.gallery.upload'), [
            'gallery' => [$file],
        ]);
        $galRes->assertSessionHas('status');
        $this->assertDatabaseHas('bhudev_images', [
            'bhudev_profile_id' => $this->approvedProfile->id,
        ]);

        // 5. Gallery view
        $this->actingAs($this->bhudevUser)->get(route('bhudev.gallery'))->assertStatus(200);

        // 6. Change Password
        $passRes = $this->actingAs($this->bhudevUser)->put(route('bhudev.password.update'), [
            'current_password' => '123456789012',
            'password' => 'NewSecurePassword123!',
            'password_confirmation' => 'NewSecurePassword123!',
        ]);
        $passRes->assertSessionHas('status');
        $this->assertTrue(Hash::check('NewSecurePassword123!', $this->bhudevUser->fresh()->password));

        // 7. History view
        $histRes = $this->actingAs($this->bhudevUser)->get(route('bhudev.history'));
        $histRes->assertStatus(200);

        // 8. Bhudev visiting /admin safely redirects to /bhudev
        $adminAttempt = $this->actingAs($this->bhudevUser)->get('/admin');
        $adminAttempt->assertRedirect(route('bhudev.dashboard'));
    }

    public function test_admin_panel_complete_workflow()
    {
        // 1. Dashboard
        $dash = $this->actingAs($this->admin)->get(route('admin.dashboard'));
        $dash->assertStatus(200);
        $dash->assertSee('Admin Dashboard');
        $dash->assertSee('Total Bhudevs');

        // 2. Bhudev listing with filters
        $this->actingAs($this->admin)->get(route('admin.bhudevs'))->assertStatus(200);
        $this->actingAs($this->admin)->get(route('admin.bhudevs', ['status' => 'pending']))->assertStatus(200);
        $this->actingAs($this->admin)->get(route('admin.bhudevs', ['status' => 'approved']))->assertStatus(200);

        // 3. View & Edit Bhudev
        $this->actingAs($this->admin)->get(route('admin.bhudevs.show', $this->approvedProfile))->assertStatus(200);
        $this->actingAs($this->admin)->get(route('admin.bhudevs.edit', $this->approvedProfile))->assertStatus(200);

        // 4. Status transitions: Suspend, Reactivate, Reject, Approve, Toggle Visibility
        $this->actingAs($this->admin)->patch(route('admin.bhudevs.suspend', $this->approvedProfile), [
            'reason' => 'Verification check',
        ])->assertSessionHas('status');
        $this->assertSame('suspended', $this->approvedProfile->fresh()->status);

        $this->actingAs($this->admin)->patch(route('admin.bhudevs.reactivate', $this->approvedProfile))->assertSessionHas('status');
        $this->assertSame('approved', $this->approvedProfile->fresh()->status);

        $this->actingAs($this->admin)->patch(route('admin.bhudevs.reject', $this->pendingProfile), [
            'rejection_reason' => 'Incomplete phone verification',
        ])->assertSessionHas('status');
        $this->assertSame('rejected', $this->pendingProfile->fresh()->status);

        $this->actingAs($this->admin)->patch(route('admin.bhudevs.approve', $this->pendingProfile))->assertSessionHas('status');
        $this->assertSame('approved', $this->pendingProfile->fresh()->status);

        $this->actingAs($this->admin)->patch(route('admin.bhudevs.toggle-visibility', $this->approvedProfile))->assertSessionHas('status');

        // 5. Inquiries & Bookings management
        $inquiry = Inquiry::create([
            'name' => 'Test Inquirer',
            'email' => 'test@example.com',
            'phone' => '9876543210',
            'type' => 'booking',
            'pooja_service' => 'Satyanarayan',
            'status' => 'new',
            'message' => 'Please call me',
        ]);

        $this->actingAs($this->admin)->get(route('admin.inquiries'))->assertStatus(200);
        $this->actingAs($this->admin)->patch(route('admin.inquiries.update', $inquiry), [
            'status' => 'closed',
            'admin_note' => 'Resolved on call',
        ])->assertSessionHas('status');
        $this->assertSame('closed', $inquiry->fresh()->status);

        // 6. Panchang & Rashifal admin
        $this->actingAs($this->admin)->get(route('admin.panchang'))->assertStatus(200);
        $this->actingAs($this->admin)->get(route('admin.rashifals'))->assertStatus(200);

        // 7. Audit logs
        $this->actingAs($this->admin)->get(route('admin.audit-logs'))->assertStatus(200);

        // 8. Admin accessing /bhudev safely redirects to /admin
        $this->actingAs($this->admin)->get(route('bhudev.dashboard'))->assertRedirect(route('admin.dashboard'));
    }
}
