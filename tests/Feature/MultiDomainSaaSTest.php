<?php

namespace Tests\Feature;

use App\Models\BhudevDomain;
use App\Models\BhudevProfile;
use App\Models\Inquiry;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MultiDomainSaaSTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $bhudevUser;
    private BhudevProfile $bhudevProfile;

    protected function setUp(): void
    {
        parent::setUp();

        // Create Admin
        $this->admin = User::factory()->create([
            'email'             => 'admin@digitalbhudev.test',
            'role'              => 'admin',
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);

        // Create Bhudev User & Profile
        $this->bhudevUser = User::factory()->create([
            'email'             => 'paras@digitalbhudev.test',
            'phone'             => '9876543210',
            'role'              => 'bhudev',
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);

        $this->bhudevProfile = BhudevProfile::create([
            'user_id'          => $this->bhudevUser->id,
            'display_name'     => 'Shastri Paras Rajyaguru',
            'slug'             => 'shastri-paras-rajyaguru',
            'city'             => 'Ahmedabad',
            'state'            => 'Gujarat',
            'status'           => 'approved',
            'is_visible'       => true,
            'experience_years' => 18,
            'qualification'    => 'Shukla Yajurveda Madhyandina Shakha',
            'bio'              => 'Experienced Vedic priest specializing in Griha Pravesh and Satyanarayan rituals.',
        ]);

        $service = Service::create([
            'name'        => 'Satyanarayan Katha',
            'slug'        => 'satyanarayan-katha',
            'description' => 'Sacred Katha ceremony with Shaligram abhishek and prasad.',
            'is_active'   => true,
        ]);

        $this->bhudevProfile->services()->attach($service->id);
    }

    public function test_bhudev_can_submit_domain_inquiry_from_dashboard(): void
    {
        $response = $this->actingAs($this->bhudevUser)->post(route('bhudev.website.request'), [
            'requested_domain' => 'shastriparas.in',
            'hero_title'       => 'Shastri Paras Rajyaguru - Official',
            'hero_tagline'     => '18+ Years of Sacred Vedic Experience in Ahmedabad',
            'whatsapp_number'  => '9876543210',
            'notes'            => 'Please include Satyanarayan katha prominently.',
        ]);

        $response->assertRedirect(route('bhudev.website'));
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('bhudev_domains', [
            'bhudev_profile_id' => $this->bhudevProfile->id,
            'domain_name'       => 'shastriparas.in',
            'status'            => 'inquiry_submitted',
            'whatsapp_number'   => '9876543210',
        ]);
    }

    public function test_admin_can_view_domains_quote_price_and_activate(): void
    {
        $domain = BhudevDomain::create([
            'bhudev_profile_id' => $this->bhudevProfile->id,
            'domain_name'       => 'shastriparas.in',
            'status'            => 'inquiry_submitted',
            'hero_title'        => 'Shastri Paras Rajyaguru',
            'whatsapp_number'   => '9876543210',
        ]);

        // Admin views listing
        $adminView = $this->actingAs($this->admin)->get(route('admin.domains.index'));
        $adminView->assertOk();
        $adminView->assertSee('shastriparas.in');

        // Admin updates details & sets quotation
        $quoteResponse = $this->actingAs($this->admin)->put(route('admin.domains.update', $domain->id), [
            'domain_name'     => 'shastriparas.in',
            'tier'            => 'standard_saas',
            'status'          => 'quoted',
            'quoted_price'    => 4999.00,
            'hero_title'      => 'Shastri Paras Rajyaguru',
            'hero_tagline'    => '18+ Years Vedic Experience',
            'whatsapp_number' => '9876543210',
            'admin_notes'     => 'Setup fee: 4999 INR includes 1 year domain and hosting.',
        ]);

        $quoteResponse->assertRedirect();
        $this->assertDatabaseHas('bhudev_domains', [
            'id'           => $domain->id,
            'status'       => 'quoted',
            'quoted_price' => 4999.00,
        ]);

        // Admin activates domain
        $activateResponse = $this->actingAs($this->admin)->patch(route('admin.domains.update-status', $domain->id), [
            'status' => 'active',
        ]);

        $activateResponse->assertRedirect();
        $this->assertDatabaseHas('bhudev_domains', [
            'id'     => $domain->id,
            'status' => 'active',
        ]);

        $domain->refresh();
        $this->assertNotNull($domain->activated_at);
    }

    public function test_visitor_on_pandit_custom_domain_sees_white_labeled_personal_site(): void
    {
        BhudevDomain::create([
            'bhudev_profile_id' => $this->bhudevProfile->id,
            'domain_name'       => 'shastriparas.in',
            'status'            => 'active',
            'hero_title'        => 'Shastri Paras Rajyaguru Official',
            'hero_tagline'      => 'Sacred Vedic Ceremonies in Ahmedabad',
            'whatsapp_number'   => '9876543210',
            'tier'              => 'standard_saas',
        ]);

        // Simulate request hitting the Pandit's custom domain
        $response = $this->get('http://shastriparas.in/');

        $response->assertOk();
        $response->assertSee('Shastri Paras Rajyaguru Official');
        $response->assertSee('Ahmedabad');
        $response->assertSee('Satyanarayan Katha');

        // White-label assertion: Pandit's personal domain must NOT contain "Log in" or "Join DigitalBhudev" buttons
        $response->assertDontSee('>Log in</a>', false);
        $response->assertDontSee('>Register</a>', false);
        $response->assertDontSee('Join DigitalBhudev', false);
    }

    public function test_devotee_inquiry_on_pandit_domain_saves_lead_and_routes_to_whatsapp(): void
    {
        $domain = BhudevDomain::create([
            'bhudev_profile_id' => $this->bhudevProfile->id,
            'domain_name'       => 'shastriparas.in',
            'status'            => 'active',
            'hero_title'        => 'Shastri Paras Rajyaguru',
            'whatsapp_number'   => '9876543210',
            'tier'              => 'standard_saas',
        ]);

        // Simulate devotee submitting inquiry on Pandit's domain
        $response = $this->post('http://shastriparas.in/pandit-inquiry', [
            'name'           => 'Rajeshbhai Patel',
            'phone'          => '9825012345',
            'city'           => 'Ahmedabad',
            'pooja_service'  => 'Satyanarayan Katha',
            'preferred_date' => '2026-11-15',
            'message'        => 'We need morning Muhurat for family katha.',
        ]);

        // Must redirect to WhatsApp click-to-chat URL with Pandit's mobile number
        $response->assertRedirect();
        $redirectUrl = $response->headers->get('Location');
        $this->assertStringContainsString('https://wa.me/919876543210', $redirectUrl);
        $this->assertStringContainsString('Rajeshbhai', $redirectUrl);
        $this->assertStringContainsString('Satyanarayan', $redirectUrl);

        // Must be saved in inquiries table linked to this Bhudev
        $this->assertDatabaseHas('inquiries', [
            'name'              => 'Rajeshbhai Patel',
            'phone'             => '9825012345',
            'bhudev_profile_id' => $this->bhudevProfile->id,
            'pooja_service'     => 'Satyanarayan Katha',
        ]);
    }

    public function test_main_domain_visitor_sees_main_digitalbhudev_directory(): void
    {
        // Normal request to main domain (localhost)
        $response = $this->get('http://localhost/');

        $response->assertOk();
        $response->assertSee('DigitalBhudev');
        $response->assertSee('Log in');
        $response->assertSee('Join DigitalBhudev');
    }

    public function test_pandit_can_update_personal_introduction_and_see_it_live_on_frontend(): void
    {
        $domain = BhudevDomain::create([
            'bhudev_profile_id' => $this->bhudevProfile->id,
            'domain_name'       => 'shastriparas.in',
            'status'            => 'active',
            'hero_title'        => 'Shastri Paras Rajyaguru',
            'whatsapp_number'   => '9876543210',
            'tier'              => 'standard_saas',
        ]);

        $customIntro = 'Namaste. I am Shastri Paras, a 5th-generation Shukla Yajurveda scholar. I conduct all home pujas with complete Shastra Vidhi.';

        // Pandit updates his introduction from his dashboard
        $updateResponse = $this->actingAs($this->bhudevUser)->post(route('bhudev.website.content'), [
            'hero_title'      => 'Acharya Paras Shastri',
            'hero_tagline'    => '20+ Years Vedic Legacy',
            'about_intro'     => $customIntro,
            'whatsapp_number' => '9876543210',
        ]);

        $updateResponse->assertRedirect();
        $updateResponse->assertSessionHas('status');

        $this->assertDatabaseHas('bhudev_domains', [
            'id'          => $domain->id,
            'hero_title'  => 'Acharya Paras Shastri',
            'about_intro' => $customIntro,
        ]);

        // Visitor opens Pandit's personal domain
        $frontendResponse = $this->get('http://shastriparas.in/');
        $frontendResponse->assertOk();
        $frontendResponse->assertSee('Acharya Paras Shastri');
        $frontendResponse->assertSee($customIntro);
    }

    public function test_admin_can_directly_assign_domain_to_bhudev(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.domains.store'), [
            'bhudev_profile_id' => $this->bhudevProfile->id,
            'domain_name'       => 'panditparas.in',
            'tier'              => 'standard_saas',
            'status'            => 'active',
            'quoted_price'      => 4500.00,
            'hero_title'        => 'Pandit Paras Live',
            'whatsapp_number'   => '9876543210',
            'admin_notes'       => 'Assigned directly by Admin via phone call request',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('bhudev_domains', [
            'bhudev_profile_id' => $this->bhudevProfile->id,
            'domain_name'       => 'panditparas.in',
            'status'            => 'active',
            'quoted_price'      => 4500.00,
        ]);
    }

    public function test_pandit_can_upload_dakshina_qr_and_it_shows_strictly_on_personal_domain(): void
    {
        Storage::fake('public');

        $domain = BhudevDomain::create([
            'bhudev_profile_id' => $this->bhudevProfile->id,
            'domain_name'       => 'shastriparas.in',
            'status'            => 'active',
            'hero_title'        => 'Shastri Paras Rajyaguru',
            'whatsapp_number'   => '9876543210',
            'tier'              => 'standard_saas',
        ]);

        $qrFile = UploadedFile::fake()->create('my-dakshina-qr.jpg', 150, 'image/jpeg');

        // Bhudev uploads Dakshina QR code & UPI ID from his dashboard
        $response = $this->actingAs($this->bhudevUser)->post(route('bhudev.website.content'), [
            'hero_title'      => 'Shastri Paras Rajyaguru',
            'about_intro'     => 'Welcome to my Vedic site.',
            'whatsapp_number' => '9876543210',
            'upi_id'          => 'paras@oksbi',
            'dakshina_qr'     => $qrFile,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $domain->refresh();
        $this->assertNotNull($domain->dakshina_qr_path);
        $this->assertEquals('paras@oksbi', $domain->upi_id);

        // 1. Visitor on Pandit's personal domain SEES the Dakshina QR section & UPI ID
        $personalSiteResponse = $this->get('http://shastriparas.in/');
        $personalSiteResponse->assertOk();
        $personalSiteResponse->assertSee('Offer Dakshina Directly to Shastri Ji');
        $personalSiteResponse->assertSee('paras@oksbi');
        $personalSiteResponse->assertSee('100% Direct to Shastri Ji');

        // 2. Visitor on Main Directory Profile does NOT see the personal Dakshina QR code
        $mainDirectoryResponse = $this->get(route('bhudevs.show', $this->bhudevProfile->slug));
        $mainDirectoryResponse->assertOk();
        $mainDirectoryResponse->assertDontSee('Offer Dakshina Directly to Shastri Ji');
        $mainDirectoryResponse->assertDontSee('paras@oksbi');
    }

    public function test_main_homepage_displays_pandit_saas_website_callout_banner(): void
    {
        $response = $this->get('http://localhost/');
        $response->assertOk();
        $response->assertSee('FOR VEDIC PRIESTS');
        $response->assertSee('Get Your Own Personal Website');
        $response->assertSee('0% commission');
        $response->assertSee('Personal Dakshina UPI QR Code');
    }

    public function test_admin_can_suspend_domain_with_reason_and_generate_whatsapp_notice(): void
    {
        $domain = BhudevDomain::create([
            'bhudev_profile_id' => $this->bhudevProfile->id,
            'domain_name'       => 'shastriparas.in',
            'status'            => 'active',
            'hero_title'        => 'Shastri Paras Rajyaguru',
            'whatsapp_number'   => '9876543210',
            'tier'              => 'standard_saas',
        ]);

        $suspensionReason = 'Annual domain & hosting renewal fee pending for 2026-2027';

        // Admin suspends the domain with specific reason
        $response = $this->actingAs($this->admin)->patch(route('admin.domains.update-status', $domain->id), [
            'status'            => 'suspended',
            'suspension_reason' => $suspensionReason,
        ]);

        $response->assertRedirect();
        $domain->refresh();

        $this->assertSame('suspended', $domain->status);
        $this->assertSame($suspensionReason, $domain->suspension_reason);
        $this->assertNotNull($domain->suspended_at);

        // Verify pre-filled WhatsApp notice URL contains domain and reason
        $waUrl = $domain->generateSuspensionWhatsAppUrl();
        $this->assertStringContainsString('shastriparas.in', $waUrl);
        $this->assertStringContainsString('Suspended', $waUrl);
        $this->assertStringContainsString('https://wa.me/919876543210', $waUrl);

        // Bhudev visits dashboard and sees suspension alert & reason
        $bhudevDashboard = $this->actingAs($this->bhudevUser)->get(route('bhudev.website'));
        $bhudevDashboard->assertOk();
        $bhudevDashboard->assertSee('WEBSITE TEMPORARILY SUSPENDED');
        $bhudevDashboard->assertSee($suspensionReason);
    }

    public function test_preview_domain_parameter_renders_pandit_site_with_clean_header(): void
    {
        $domain = BhudevDomain::create([
            'bhudev_profile_id' => $this->bhudevProfile->id,
            'domain_name'       => 'parasrajyaguru.in',
            'status'            => 'active',
            'hero_title'        => 'Paras Rajyaguru',
            'theme_color'       => 'saffron',
        ]);

        $response = $this->get('/?preview_domain=parasrajyaguru.in');
        $response->assertOk();
        $response->assertSee('Paras Rajyaguru');
        $response->assertSee('Vedic Pandit &amp; Astrologer', false);
        $response->assertSee('--pandit-primary: #ea580c', false);
        $response->assertSee('pandit-nav-container', false);
    }
}
