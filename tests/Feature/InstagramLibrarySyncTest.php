<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\BhudevDomain;
use App\Models\BhudevInstagramPost;
use App\Models\BhudevInstagramSetting;
use App\Models\BhudevProfile;
use App\Models\Inquiry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Instagram library sync (connect once, every post/Reel appears automatically),
 * the "only on the Bhudev's own active website" display rule, and admin "Login as Bhudev".
 */
class InstagramLibrarySyncTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $bhudevUser;
    protected BhudevProfile $profile;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role'              => 'admin',
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);

        $this->bhudevUser = User::factory()->create([
            'role'              => 'bhudev',
            'is_active'         => true,
            'email_verified_at' => now(),
            'password'          => Hash::make('OriginalPass123!'),
        ]);

        $this->profile = BhudevProfile::create([
            'user_id'      => $this->bhudevUser->id,
            'display_name' => 'Shastri Library Test',
            'slug'         => 'shastri-library-test',
            'status'       => 'approved',
            'is_visible'   => true,
        ]);
    }

    private function connectedSettings(array $overrides = []): BhudevInstagramSetting
    {
        return BhudevInstagramSetting::create(array_merge([
            'bhudev_profile_id' => $this->profile->id,
            'is_enabled'        => false,
            'connection_status' => 'connected',
            'account_username'  => 'shastri_ig',
            'account_id'        => '17841400000000009',
            'access_token'      => 'IGQV_long_lived_token',
            'token_expires_at'  => now()->addDays(50),
        ], $overrides));
    }

    /** @var array<int, array<string, mixed>> */
    private array $libraryItems = [];
    private bool $libraryFaked = false;

    /**
     * Http::fake() appends stubs, so register once and read the items from a mutable list.
     */
    private function fakeMediaLibrary(array $items): void
    {
        $this->libraryItems = $items;

        if ($this->libraryFaked) {
            return;
        }
        $this->libraryFaked = true;

        Http::fake([
            'https://graph.instagram.com/me/media*' => fn () => Http::response(['data' => $this->libraryItems, 'paging' => []], 200),
            'https://graph.instagram.com/*'         => Http::response(['error' => ['message' => 'unexpected call']], 400),
        ]);
    }

    private function reelItem(string $code = 'CreelAAA111', string $id = '1001'): array
    {
        return [
            'id'            => $id,
            'caption'       => 'Satyanarayan Katha highlights',
            'media_type'    => 'VIDEO',
            'media_url'     => 'https://scontent.cdninstagram.com/v/reel-' . $code . '.mp4',
            'thumbnail_url' => 'https://scontent.cdninstagram.com/v/reel-' . $code . '.jpg',
            'permalink'     => 'https://www.instagram.com/reel/' . $code . '/',
            'timestamp'     => '2026-10-20T10:00:00+0000',
        ];
    }

    private function imageItem(string $code = 'CimgBBB222', string $id = '1002'): array
    {
        return [
            'id'         => $id,
            'caption'    => 'Griha Pravesh at Surat',
            'media_type' => 'IMAGE',
            'media_url'  => 'https://scontent.cdninstagram.com/v/img-' . $code . '.jpg',
            'permalink'  => 'https://www.instagram.com/p/' . $code . '/',
            'timestamp'  => '2026-10-18T10:00:00+0000',
        ];
    }

    public function test_post_urls_are_canonicalised_to_instagrams_p_segment(): void
    {
        $this->actingAs($this->bhudevUser)
            ->post(route('bhudev.instagram.urls.store'), ['url' => 'https://instagram.com/p/CpostXYZ789/?igsh=abc'])
            ->assertRedirect()
            ->assertSessionHas('status');

        $post = BhudevInstagramPost::where('bhudev_profile_id', $this->profile->id)->firstOrFail();

        $this->assertSame('https://www.instagram.com/p/CpostXYZ789/', $post->url);
        $this->assertSame('post', $post->media_type);
        $this->assertSame('https://www.instagram.com/p/CpostXYZ789/embed/', $post->getEmbedUrl());
        $this->assertSame('manual', $post->source);
    }

    public function test_connected_library_is_imported_and_kept_in_sync(): void
    {
        $this->connectedSettings();
        $this->fakeMediaLibrary([$this->reelItem(), $this->imageItem(), [
            'id'         => '1003',
            'caption'    => 'Album',
            'media_type' => 'CAROUSEL_ALBUM',
            'media_url'  => 'https://scontent.cdninstagram.com/v/album.jpg',
            'permalink'  => 'https://www.instagram.com/p/CalbumCCC333/',
            'timestamp'  => '2026-10-10T10:00:00+0000',
        ]]);

        // A URL the Bhudev pasted by hand must survive every sync
        $manual = BhudevInstagramPost::create([
            'bhudev_profile_id' => $this->profile->id,
            'url'               => 'https://www.instagram.com/reel/CmanualDDD/',
            'shortcode'         => 'CmanualDDD',
            'media_type'        => 'reel',
            'is_active'         => true,
            'sort_order'        => 1,
        ]);

        $this->actingAs($this->bhudevUser)
            ->post(route('bhudev.instagram.sync'))
            ->assertRedirect()
            ->assertSessionHas('status');

        Http::assertSent(fn ($request) => str_contains($request->url(), 'graph.instagram.com/me/media')
            && str_contains($request->url(), 'access_token=IGQV_long_lived_token'));

        $apiPosts = BhudevInstagramPost::where('bhudev_profile_id', $this->profile->id)->where('source', 'api')->get();
        $this->assertCount(3, $apiPosts);
        $this->assertSame(4, BhudevInstagramPost::where('bhudev_profile_id', $this->profile->id)->count());

        $reel = $apiPosts->firstWhere('shortcode', 'CreelAAA111');
        $this->assertSame('reel', $reel->media_type);
        $this->assertSame('1001', $reel->ig_media_id);
        $this->assertSame('https://www.instagram.com/reel/CreelAAA111/', $reel->url);
        $this->assertSame('VIDEO', $reel->metadata['media_type']);
        $this->assertTrue($reel->hasNativeVideo());
        $this->assertStringContainsString('reel-CreelAAA111.mp4', $reel->getVideoUrl());
        $this->assertSame('Satyanarayan Katha highlights', $reel->caption);
        $this->assertSame('shastri_ig', $reel->author_name);

        $image = $apiPosts->firstWhere('shortcode', 'CimgBBB222');
        $this->assertSame('post', $image->media_type);
        $this->assertFalse($image->hasNativeVideo());
        $this->assertStringContainsString('img-CimgBBB222.jpg', $image->getImageUrl());

        // Newest Instagram item is listed first
        $ordered = $this->profile->activeInstagramPosts()->get();
        $this->assertSame('CreelAAA111', $ordered->first()->shortcode);

        // Second sync: the reel was deleted on Instagram, the image got a new caption
        $updatedImage = $this->imageItem();
        $updatedImage['caption'] = 'Griha Pravesh at Surat (updated)';
        $this->fakeMediaLibrary([$updatedImage]);

        $this->actingAs($this->bhudevUser)->post(route('bhudev.instagram.sync'))->assertRedirect();

        $this->assertDatabaseMissing('bhudev_instagram_posts', ['shortcode' => 'CreelAAA111']);
        $this->assertDatabaseMissing('bhudev_instagram_posts', ['shortcode' => 'CalbumCCC333']);
        $this->assertDatabaseHas('bhudev_instagram_posts', ['id' => $manual->id, 'source' => 'manual']);
        $this->assertDatabaseHas('bhudev_instagram_posts', ['shortcode' => 'CimgBBB222', 'caption' => 'Griha Pravesh at Surat (updated)']);
        $this->assertSame(1, BhudevInstagramPost::where('bhudev_profile_id', $this->profile->id)->where('source', 'api')->count());
    }

    public function test_connecting_an_account_imports_the_library_immediately(): void
    {
        Http::fake([
            'https://graph.instagram.com/me/media*' => Http::response(['data' => [$this->reelItem()], 'paging' => []], 200),
            'https://graph.instagram.com/me*'       => Http::response(['id' => '17841400000000009', 'username' => 'shastri_ig', 'account_type' => 'BUSINESS'], 200),
            'https://graph.instagram.com/*'         => Http::response(['error' => ['message' => 'unexpected call']], 400),
        ]);

        $this->actingAs($this->bhudevUser)
            ->post(route('bhudev.instagram.connect-api'), ['access_token' => 'IGQV_fresh_token'])
            ->assertRedirect()
            ->assertSessionHas('status', fn ($status) => str_contains($status, '1 post(s)/Reel(s) imported'));

        $this->assertDatabaseHas('bhudev_instagram_posts', [
            'bhudev_profile_id' => $this->profile->id,
            'source'            => 'api',
            'shortcode'         => 'CreelAAA111',
        ]);

        $setting = BhudevInstagramSetting::where('bhudev_profile_id', $this->profile->id)->firstOrFail();
        $this->assertNotNull($setting->token_expires_at);
        $this->assertNotNull($setting->last_sync_at);
    }

    public function test_expired_token_marks_connection_for_reconnection(): void
    {
        $this->connectedSettings();
        Http::fake([
            'https://graph.instagram.com/me/media*' => Http::response(['error' => ['message' => 'Error validating access token: Session has expired', 'code' => 190]], 400),
            'https://graph.instagram.com/*'         => Http::response(['error' => ['message' => 'unexpected call']], 400),
        ]);

        $this->actingAs($this->admin)
            ->post(route('admin.instagram.sync', $this->profile))
            ->assertRedirect()
            ->assertSessionHas('error');

        $setting = BhudevInstagramSetting::where('bhudev_profile_id', $this->profile->id)->firstOrFail();
        $this->assertSame('error', $setting->connection_status);
        $this->assertStringContainsString('expired', $setting->last_error);
    }

    public function test_library_is_hidden_everywhere_until_the_bhudev_has_an_active_website(): void
    {
        $this->connectedSettings(['is_enabled' => true]);
        $this->fakeMediaLibrary([$this->reelItem(), $this->imageItem()]);
        $this->actingAs($this->bhudevUser)->post(route('bhudev.instagram.sync'))->assertRedirect();

        // Public directory profile and homepage never show the library
        $this->get('/bhudevs/shastri-library-test')->assertOk()->assertDontSee('CreelAAA111')->assertDontSee('CimgBBB222');
        $this->get('/')->assertOk()->assertDontSee('CreelAAA111');

        // The super admin can always review it in the Instagram manage screen
        $this->actingAs($this->admin)
            ->get(route('admin.instagram.show', $this->profile))
            ->assertOk()
            ->assertSee('CreelAAA111')
            ->assertSee('Synced from Instagram');

        // A pending (not active) domain still shows nothing
        $domain = BhudevDomain::create([
            'bhudev_profile_id' => $this->profile->id,
            'domain_name'       => 'shastrilibrary.in',
            'status'            => 'inquiry_submitted',
            'tier'              => 'standard_saas',
        ]);
        $this->get('http://shastrilibrary.in/')->assertOk()->assertDontSee('CreelAAA111');

        // Once the website is active, the whole library appears there (and only there)
        $domain->update(['status' => 'active', 'activated_at' => now()]);

        $this->get('http://shastrilibrary.in/')
            ->assertOk()
            ->assertSee('reel-CreelAAA111.mp4', false)   // native video from the library
            ->assertSee('img-CimgBBB222.jpg', false)     // library photo
            ->assertSee('bhudevEmbedFallback', false);

        $this->get('/bhudevs/shastri-library-test')->assertOk()->assertDontSee('CreelAAA111');
    }

    public function test_scheduled_command_syncs_every_connected_account(): void
    {
        $this->connectedSettings();
        $this->fakeMediaLibrary([$this->imageItem()]);

        $this->artisan('instagram:sync')->assertExitCode(0);

        $this->assertDatabaseHas('bhudev_instagram_posts', [
            'bhudev_profile_id' => $this->profile->id,
            'source'            => 'api',
            'shortcode'         => 'CimgBBB222',
        ]);
    }

    public function test_admin_can_open_the_bhudev_portal_as_that_bhudev_and_return(): void
    {
        Inquiry::create([
            'name'              => 'Devotee One',
            'email'             => 'devotee@example.test',
            'phone'             => '9876501234',
            'type'              => 'booking',
            'subject'           => 'Satyanarayan Katha',
            'message'           => 'Booking request',
            'status'            => 'new',
            'bhudev_profile_id' => $this->profile->id,
        ]);

        $this->actingAs($this->admin)
            ->get(route('admin.bhudevs.show', $this->profile))
            ->assertOk()
            ->assertSee('Login as Bhudev')
            ->assertSee('1 inquiry');

        $this->actingAs($this->admin)
            ->post(route('admin.bhudevs.login-as', $this->profile))
            ->assertRedirect(route('bhudev.dashboard'));

        $this->assertSame($this->bhudevUser->id, auth()->id());
        $this->assertSame($this->admin->id, session('impersonator_id'));

        $this->get(route('bhudev.dashboard'))
            ->assertOk()
            ->assertSee('Return to Admin')
            ->assertSee('Devotee One');

        $this->assertDatabaseHas('audit_logs', ['action' => 'admin.bhudev.impersonated', 'user_id' => $this->admin->id]);

        $this->post(route('impersonation.leave'))
            ->assertRedirect(route('admin.bhudevs.show', $this->profile));

        $this->assertSame($this->admin->id, auth()->id());
        $this->assertNull(session('impersonator_id'));
    }

    public function test_only_admins_can_use_login_as(): void
    {
        $this->actingAs($this->bhudevUser)
            ->post(route('admin.bhudevs.login-as', $this->profile))
            ->assertForbidden();
    }

    public function test_password_change_is_blocked_while_admin_views_the_portal(): void
    {
        $this->actingAs($this->bhudevUser)
            ->withSession(['impersonator_id' => $this->admin->id])
            ->put(route('bhudev.password.update'), [
                'current_password'      => 'OriginalPass123!',
                'password'              => 'NewPassword456!',
                'password_confirmation' => 'NewPassword456!',
            ])
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertTrue(Hash::check('OriginalPass123!', $this->bhudevUser->fresh()->password));
    }
}
