<?php

namespace Tests\Feature;

use App\Models\BhudevDomain;
use App\Models\BhudevInstagramPost;
use App\Models\BhudevInstagramSetting;
use App\Models\BhudevProfile;
use App\Models\Inquiry;
use App\Models\User;
use App\Services\InstagramIntegrationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class InstagramIntegrationTest extends TestCase
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
            'email'             => 'shastri@digitalbhudev.test',
            'phone'             => '9876543210',
            'role'              => 'bhudev',
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);

        $this->bhudevProfile = BhudevProfile::create([
            'user_id'          => $this->bhudevUser->id,
            'display_name'     => 'Shastri Rameshwar Dave',
            'slug'             => 'shastri-rameshwar-dave',
            'city'             => 'Ahmedabad',
            'state'            => 'Gujarat',
            'status'           => 'approved',
            'is_visible'       => true,
            'experience_years' => 18,
            'qualification'    => 'Shukla Yajurveda Acharya',
        ]);
    }

    /**
     * Test 1: Access Control - Instagram management is admin-only.
     */
    public function test_instagram_routes_are_strictly_restricted_to_admins(): void
    {
        // Guest is redirected to login
        $this->get(route('admin.instagram.index'))
            ->assertRedirect(route('login'));

        $this->get(route('admin.instagram.show', $this->bhudevProfile))
            ->assertRedirect(route('login'));

        // Bhudev role receives 403 Forbidden
        $this->actingAs($this->bhudevUser)
            ->get(route('admin.instagram.index'))
            ->assertForbidden();

        $this->actingAs($this->bhudevUser)
            ->get(route('admin.instagram.show', $this->bhudevProfile))
            ->assertForbidden();

        // Admin can access both successfully
        $this->actingAs($this->admin)
            ->get(route('admin.instagram.index'))
            ->assertOk()
            ->assertSee('Instagram Integration Directory');

        $this->actingAs($this->admin)
            ->get(route('admin.instagram.show', $this->bhudevProfile))
            ->assertOk()
            ->assertSee($this->bhudevProfile->display_name);
    }

    /**
     * Test 2: Exact notice text is rendered in the admin panel.
     */
    public function test_exact_notice_text_and_options_are_displayed(): void
    {
        $exactNotice = "Connected Instagram posts and Reels are displayed only on Bhudevs' own active website or domain. If you don't have a website yet, contact us on WhatsApp or submit a website request form.";

        $response = $this->actingAs($this->admin)
            ->get(route('admin.instagram.show', $this->bhudevProfile));

        $response->assertOk();
        $response->assertSee($exactNotice);
        $response->assertSee('Option A: Contact Us on WhatsApp');
        $response->assertSee('Option B: Submit Website Request Form');
        $response->assertSee('https://wa.me/91', false);
    }

    /**
     * Test 3: Instagram Post and Reel URL validation, normalization, and duplicate prevention.
     */
    public function test_instagram_urls_are_strictly_validated_and_duplicates_prevented(): void
    {
        // Invalid host / bad scheme
        $this->actingAs($this->admin)
            ->post(route('admin.instagram.urls.store', $this->bhudevProfile), [
                'url' => 'https://facebook.com/reel/12345',
            ])
            ->assertSessionHasErrors('url');

        // Invalid path
        $this->actingAs($this->admin)
            ->post(route('admin.instagram.urls.store', $this->bhudevProfile), [
                'url' => 'https://www.instagram.com/explore/',
            ])
            ->assertSessionHasErrors('url');

        // Valid Reel URL
        $this->actingAs($this->admin)
            ->post(route('admin.instagram.urls.store', $this->bhudevProfile), [
                'url'     => 'https://www.instagram.com/reel/C3zY4X1v8Wk/?igsh=MTc4MmM1YmI2Ng==',
                'caption' => 'Rudrabhishek Mahavidhi',
            ])
            ->assertRedirect()
            ->assertSessionHas('status');

        $this->assertDatabaseHas('bhudev_instagram_posts', [
            'bhudev_profile_id' => $this->bhudevProfile->id,
            'shortcode'         => 'C3zY4X1v8Wk',
            'media_type'        => 'reel',
            'url'               => 'https://www.instagram.com/reel/C3zY4X1v8Wk/',
            'caption'           => 'Rudrabhishek Mahavidhi',
        ]);

        // Duplicate URL submission is rejected
        $this->actingAs($this->admin)
            ->post(route('admin.instagram.urls.store', $this->bhudevProfile), [
                'url'     => 'https://instagram.com/reel/C3zY4X1v8Wk/',
                'caption' => 'Duplicate attempt',
            ])
            ->assertSessionHasErrors('url');

        $this->assertEquals(1, BhudevInstagramPost::where('bhudev_profile_id', $this->bhudevProfile->id)->count());
    }

    /**
     * Test 4: Cannot delete another Bhudev's post URL.
     */
    public function test_post_deletion_is_scoped_strictly_to_owner(): void
    {
        $otherProfile = BhudevProfile::create([
            'user_id'      => User::factory()->create(['role' => 'bhudev'])->id,
            'display_name' => 'Other Pandit',
            'slug'         => 'other-pandit',
            'status'       => 'approved',
        ]);

        $post = BhudevInstagramPost::create([
            'bhudev_profile_id' => $otherProfile->id,
            'url'               => 'https://www.instagram.com/p/Cother12345/',
            'shortcode'         => 'Cother12345',
            'media_type'        => 'post',
        ]);

        // Trying to delete otherProfile's post via this->bhudevProfile route returns 403
        $this->actingAs($this->admin)
            ->delete(route('admin.instagram.urls.destroy', [$this->bhudevProfile, $post]))
            ->assertForbidden();

        $this->assertDatabaseHas('bhudev_instagram_posts', ['id' => $post->id]);

        // Deleting via otherProfile succeeds
        $this->actingAs($this->admin)
            ->delete(route('admin.instagram.urls.destroy', [$otherProfile, $post]))
            ->assertRedirect();

        $this->assertDatabaseMissing('bhudev_instagram_posts', ['id' => $post->id]);
    }

    /**
     * Test 5: Meta Graph API Access Token Validation and Encryption.
     */
    public function test_meta_api_token_validation_and_secure_storage(): void
    {
        // Mock successful Meta Graph API response
        Http::fake([
            'https://graph.instagram.com/me*' => Http::response([
                'id'           => '17841400000000001',
                'username'     => 'shastri_rameshwar_vedic',
                'account_type' => 'BUSINESS',
            ], 200),
        ]);

        $this->actingAs($this->admin)
            ->post(route('admin.instagram.connect-api', $this->bhudevProfile), [
                'access_token' => 'EAA_valid_meta_token_123456789',
                'app_id'       => '987654321',
                'app_secret'   => 'secret_xyz',
            ])
            ->assertRedirect()
            ->assertSessionHas('status');

        $setting = BhudevInstagramSetting::where('bhudev_profile_id', $this->bhudevProfile->id)->first();
        $this->assertNotNull($setting);
        $this->assertEquals('connected', $setting->connection_status);
        $this->assertEquals('shastri_rameshwar_vedic', $setting->account_username);
        $this->assertEquals('17841400000000001', $setting->account_id);

        // Verify token is encrypted in database (raw original is NOT plaintext)
        $rawToken = $setting->getRawOriginal('access_token');
        $this->assertNotEquals('EAA_valid_meta_token_123456789', $rawToken);
        $this->assertEquals('EAA_valid_meta_token_123456789', $setting->access_token);

        // Disconnect API
        $this->actingAs($this->admin)
            ->post(route('admin.instagram.disconnect', $this->bhudevProfile))
            ->assertRedirect();

        $setting->refresh();
        $this->assertEquals('disconnected', $setting->connection_status);
        $this->assertNull($setting->access_token);
    }

    /**
     * Test 6: Meta Graph API Failure is gracefully handled without leaking secrets.
     */
    public function test_meta_api_validation_failure_is_handled_gracefully(): void
    {
        Http::fake([
            'https://graph.instagram.com/me*' => Http::response([
                'error' => [
                    'message' => 'Invalid OAuth access token - Cannot parse access token',
                    'type'    => 'OAuthException',
                    'code'    => 190,
                ],
            ], 400),
        ]);

        $this->actingAs($this->admin)
            ->post(route('admin.instagram.connect-api', $this->bhudevProfile), [
                'access_token' => 'invalid_token_xyz',
            ])
            ->assertSessionHasErrors('access_token');

        $setting = BhudevInstagramSetting::where('bhudev_profile_id', $this->bhudevProfile->id)->first();
        $this->assertNotNull($setting);
        $this->assertEquals('error', $setting->connection_status);
        $this->assertStringContainsString('Invalid OAuth access token', $setting->last_error);
    }

    /**
     * Test 7: Public Website Eligibility Gate - Content ONLY appears on active website.
     */
    public function test_instagram_content_strictly_only_appears_on_active_domain_website(): void
    {
        $service = app(InstagramIntegrationService::class);

        // Case A: No domain configured -> Ineligible
        $this->assertFalse($service->isEligibleForPublicDisplay($this->bhudevProfile));

        // Attempting to toggle on without active website is rejected
        $this->actingAs($this->admin)
            ->post(route('admin.instagram.toggle', $this->bhudevProfile), [
                'is_enabled' => 1,
            ])
            ->assertSessionHasErrors('toggle');

        // Case B: Create Domain with status = 'suspended' -> Ineligible
        $domain = BhudevDomain::create([
            'bhudev_profile_id' => $this->bhudevProfile->id,
            'domain_name'       => 'shastrirameshwar.in',
            'status'            => 'suspended',
            'hero_title'        => 'Shastri Rameshwar Dave',
        ]);

        $this->assertFalse($service->isEligibleForPublicDisplay($this->bhudevProfile, $domain));

        // Case C: Activate Domain, but integration not enabled yet -> Ineligible
        $domain->update(['status' => 'active']);
        $this->assertFalse($service->isEligibleForPublicDisplay($this->bhudevProfile, $domain));

        // Enable integration
        $this->actingAs($this->admin)
            ->post(route('admin.instagram.toggle', $this->bhudevProfile), [
                'is_enabled' => 1,
            ])
            ->assertRedirect()
            ->assertSessionHas('status');

        // Case D: Enabled, but 0 active posts -> Ineligible
        $this->assertFalse($service->isEligibleForPublicDisplay($this->bhudevProfile, $domain));

        // Add a valid post
        BhudevInstagramPost::create([
            'bhudev_profile_id' => $this->bhudevProfile->id,
            'url'               => 'https://www.instagram.com/reel/C3zY4X1v8Wk/',
            'shortcode'         => 'C3zY4X1v8Wk',
            'media_type'        => 'reel',
            'caption'           => 'Sacred Havan Ceremony',
            'is_active'         => true,
            'sort_order'        => 1,
        ]);

        // Case E: All 7 criteria met -> Eligible!
        $this->assertTrue($service->isEligibleForPublicDisplay($this->bhudevProfile, $domain));
        $this->assertCount(1, $service->getEligiblePosts($this->bhudevProfile, $domain));

        // Render Pandit website directly
        $response = $this->get('http://shastrirameshwar.in/');

        $response->assertOk();
        $response->assertSee('Sacred Havan Ceremony');
        $response->assertSee('https://www.instagram.com/reel/C3zY4X1v8Wk/');
        $response->assertSee('Reels & Videos');
    }

    /**
     * Test 8: Cross-Tenant Isolation - Bhudev A's posts NEVER appear on Bhudev B's website.
     */
    public function test_cross_tenant_isolation_never_leaks_instagram_content(): void
    {
        $service = app(InstagramIntegrationService::class);

        // Bhudev A setup
        $domainA = BhudevDomain::create([
            'bhudev_profile_id' => $this->bhudevProfile->id,
            'domain_name'       => 'pandit-a.in',
            'status'            => 'active',
        ]);
        BhudevInstagramSetting::create([
            'bhudev_profile_id' => $this->bhudevProfile->id,
            'is_enabled'        => true,
        ]);
        BhudevInstagramPost::create([
            'bhudev_profile_id' => $this->bhudevProfile->id,
            'url'               => 'https://www.instagram.com/reel/C_BHUDEV_A_REEL/',
            'shortcode'         => 'C_BHUDEV_A_REEL',
            'media_type'        => 'reel',
            'caption'           => 'Special Ceremony of Pandit A',
            'is_active'         => true,
        ]);

        // Bhudev B setup
        $userB = User::factory()->create(['role' => 'bhudev']);
        $profileB = BhudevProfile::create([
            'user_id'      => $userB->id,
            'display_name' => 'Pandit B Harishbhai',
            'slug'         => 'pandit-b-harishbhai',
            'status'       => 'approved',
            'is_visible'   => true,
        ]);
        $domainB = BhudevDomain::create([
            'bhudev_profile_id' => $profileB->id,
            'domain_name'       => 'pandit-b.in',
            'status'            => 'active',
        ]);
        BhudevInstagramSetting::create([
            'bhudev_profile_id' => $profileB->id,
            'is_enabled'        => true,
        ]);
        BhudevInstagramPost::create([
            'bhudev_profile_id' => $profileB->id,
            'url'               => 'https://www.instagram.com/reel/C_BHUDEV_B_REEL/',
            'shortcode'         => 'C_BHUDEV_B_REEL',
            'media_type'        => 'reel',
            'caption'           => 'Distinct Ceremony of Pandit B',
            'is_active'         => true,
        ]);

        // Render Pandit A's website
        $responseA = $this->get('http://pandit-a.in/');
        $responseA->assertOk();
        $responseA->assertSee('Special Ceremony of Pandit A');
        $responseA->assertDontSee('Distinct Ceremony of Pandit B');

        // Render Pandit B's website
        $responseB = $this->get('http://pandit-b.in/');
        $responseB->assertOk();
        $responseB->assertSee('Distinct Ceremony of Pandit B');
        $responseB->assertDontSee('Special Ceremony of Pandit A');

        // Service check: passing domain B to profile A returns ineligible
        $this->assertFalse($service->isEligibleForPublicDisplay($this->bhudevProfile, $domainB));
    }

    /**
     * Test 9: Option B Website Request Form submission.
     */
    public function test_submitting_website_request_form_creates_inquiry_and_domain_record(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.instagram.request-website', $this->bhudevProfile), [
                'requested_domain' => 'shastri-rameshwar.digitalbhudev.in',
                'whatsapp_number'  => '9876543210',
                'notes'            => 'Need domain ASAP to connect Instagram Reels',
            ])
            ->assertRedirect()
            ->assertSessionHas('status');

        $this->assertDatabaseHas('bhudev_domains', [
            'bhudev_profile_id' => $this->bhudevProfile->id,
            'domain_name'       => 'shastri-rameshwar.digitalbhudev.in',
            'status'            => 'inquiry_submitted',
        ]);

        $this->assertDatabaseHas('inquiries', [
            'bhudev_profile_id' => $this->bhudevProfile->id,
            'type'              => 'domain_request',
            'phone'             => '9876543210',
        ]);
    }

    /**
     * Test 10: Bhudev self-service Instagram portal.
     */
    public function test_bhudev_can_access_own_instagram_management_panel(): void
    {
        $response = $this->actingAs($this->bhudevUser)
            ->get(route('bhudev.instagram'));

        $response->assertOk();
        $response->assertSee('Instagram &amp; Reels Integration', false);
        $response->assertSee(InstagramIntegrationService::NOTICE_TEXT);
    }

    /**
     * Test 11: Bhudev can add and delete own Instagram URL.
     */
    public function test_bhudev_can_add_and_delete_own_instagram_url(): void
    {
        // Add URL
        $response = $this->actingAs($this->bhudevUser)
            ->post(route('bhudev.instagram.urls.store'), [
                'url'     => 'https://www.instagram.com/reel/C7xYzA_vX12/',
                'caption' => 'Rudra Abhishek Live',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $post = BhudevInstagramPost::where('bhudev_profile_id', $this->bhudevProfile->id)->first();
        $this->assertNotNull($post);
        $this->assertSame('C7xYzA_vX12', $post->shortcode);
        $this->assertSame('reel', $post->media_type);

        // Delete URL
        $deleteResponse = $this->actingAs($this->bhudevUser)
            ->delete(route('bhudev.instagram.urls.destroy', $post));

        $deleteResponse->assertRedirect();
        $this->assertDatabaseMissing('bhudev_instagram_posts', ['id' => $post->id]);
    }

    /**
     * Test 12: Bhudev cannot delete another Bhudev's Instagram post.
     */
    public function test_bhudev_cannot_delete_another_bhudevs_instagram_post(): void
    {
        $otherUser = User::factory()->create([
            'role'              => 'bhudev',
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);
        $otherProfile = BhudevProfile::create([
            'user_id'      => $otherUser->id,
            'display_name' => 'Other Pandit',
            'slug'         => 'other-pandit',
            'status'       => 'approved',
        ]);
        $foreignPost = BhudevInstagramPost::create([
            'bhudev_profile_id' => $otherProfile->id,
            'url'               => 'https://www.instagram.com/reel/Cz998877/',
            'shortcode'         => 'Cz998877',
            'media_type'        => 'reel',
        ]);

        $this->actingAs($this->bhudevUser)
            ->delete(route('bhudev.instagram.urls.destroy', $foreignPost))
            ->assertForbidden();

        $this->assertDatabaseHas('bhudev_instagram_posts', ['id' => $foreignPost->id]);
    }
}
