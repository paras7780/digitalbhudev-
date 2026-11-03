<?php

namespace App\Services;

use App\Models\BhudevDomain;
use App\Models\BhudevInstagramPost;
use App\Models\BhudevInstagramSetting;
use App\Models\BhudevProfile;
use App\Support\WhatsApp;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InstagramIntegrationService
{
    /**
     * Exact notice text required by specification.
     */
    public const NOTICE_TEXT = "Connected Instagram posts and Reels are displayed only on Bhudevs' own active website or domain. If you don't have a website yet, contact us on WhatsApp or submit a website request form.";

    /**
     * Prefilled WhatsApp message for domain/website inquiries.
     */
    public const WHATSAPP_INQUIRY_TEXT = "Hello, I am interested in getting my own website/domain so that my Instagram posts and Reels can be displayed on my Bhudev website. Please share the details.";

    /**
     * Validate and normalize an Instagram URL.
     * Supports /p/{shortcode}/, /reel/{shortcode}/, /reels/{shortcode}/, /tv/{shortcode}/.
     */
    public function validateAndNormalizeUrl(string $rawUrl): array
    {
        $trimmed = trim($rawUrl);

        if (empty($trimmed)) {
            return [
                'valid' => false,
                'error' => 'Please provide an Instagram post or Reel URL.',
            ];
        }

        // Scheme check - must be http or https
        $parsed = parse_url($trimmed);
        if (!$parsed || empty($parsed['host']) || empty($parsed['path'])) {
            return [
                'valid' => false,
                'error' => 'The provided URL is malformed or invalid.',
            ];
        }

        $scheme = strtolower($parsed['scheme'] ?? '');
        if (!in_array($scheme, ['https', 'http'], true)) {
            return [
                'valid' => false,
                'error' => 'Unsafe URL scheme. Only https:// links are supported.',
            ];
        }

        // Host validation
        $host = strtolower($parsed['host']);
        $allowedHosts = [
            'instagram.com',
            'www.instagram.com',
            'm.instagram.com',
            'instagr.am',
            'www.instagr.am',
        ];

        if (!in_array($host, $allowedHosts, true)) {
            return [
                'valid' => false,
                'error' => 'Unsupported host. Only official Instagram URLs (instagram.com) are accepted.',
            ];
        }

        // Path validation and shortcode extraction
        $path = trim($parsed['path'], '/');
        // Matches: p/C1234567, reel/C1234567, reels/C1234567, tv/C1234567
        if (!preg_match('#^(p|reel|reels|tv)/([a-zA-Z0-9_\-]+)#i', $path, $matches)) {
            return [
                'valid' => false,
                'error' => 'Invalid Instagram path. Supported formats: https://www.instagram.com/p/POST_ID/ or https://www.instagram.com/reel/REEL_ID/',
            ];
        }

        $typeSegment = strtolower($matches[1]);
        $shortcode = $matches[2];

        // Normalize media type
        $mediaType = in_array($typeSegment, ['reel', 'reels'], true) ? 'reel' : 'post';

        // Canonical URL: Instagram uses /reel/{code}/ for reels and /p/{code}/ for posts
        $canonicalUrl = 'https://www.instagram.com/' . ($mediaType === 'reel' ? 'reel' : 'p') . "/{$shortcode}/";

        return [
            'valid'         => true,
            'canonical_url' => $canonicalUrl,
            'shortcode'     => $shortcode,
            'media_type'    => $mediaType,
        ];
    }

    /**
     * Add or update an Instagram post/reel for a Bhudev profile.
     */
    public function addPostUrl(BhudevProfile $profile, string $rawUrl, ?string $caption = null): array
    {
        $validated = $this->validateAndNormalizeUrl($rawUrl);
        if (!$validated['valid']) {
            return $validated;
        }

        // Prevent duplicate URLs for the same Bhudev
        $existing = BhudevInstagramPost::where('bhudev_profile_id', $profile->id)
            ->where(function ($q) use ($validated) {
                $q->where('url', $validated['canonical_url'])
                  ->orWhere('shortcode', $validated['shortcode']);
            })
            ->first();

        if ($existing) {
            return [
                'valid' => false,
                'error' => 'This Instagram Post/Reel URL has already been added to this Bhudev profile.',
            ];
        }

        $post = BhudevInstagramPost::create([
            'bhudev_profile_id' => $profile->id,
            'url'               => $validated['canonical_url'],
            'shortcode'         => $validated['shortcode'],
            'media_type'        => $validated['media_type'],
            'caption'           => $caption ? trim($caption) : null,
            'is_active'         => true,
            'sort_order'        => (int) BhudevInstagramPost::where('bhudev_profile_id', $profile->id)->max('sort_order') + 1,
        ]);

        return [
            'valid'   => true,
            'post'    => $post,
            'message' => 'Instagram ' . ucfirst($post->media_type) . ' saved and validated successfully.',
        ];
    }

    /**
     * Upload a direct video file for a Bhudev Reel (native HTML5 autoplay video).
     * Can also attach/update video on an existing post if $postId is provided.
     */
    public function uploadReelVideo(BhudevProfile $profile, UploadedFile $file, ?string $caption = null, bool $isAutoplay = true, ?int $postId = null): array
    {
        $allowedExtensions = ['mp4', 'mov', 'webm', 'm4v', 'quicktime'];
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $allowedExtensions, true) && !str_starts_with($file->getMimeType(), 'video/')) {
            return [
                'valid' => false,
                'error' => 'Please upload a valid video file (MP4, MOV, or WebM format).',
            ];
        }

        // Store video securely on public disk under reels/
        $filename = 'reel_' . $profile->id . '_' . time() . '_' . Str::lower(Str::random(8)) . '.' . ($extension ?: 'mp4');
        $path = $file->storeAs('reels', $filename, 'public');

        // If attaching to existing post
        if ($postId) {
            $existingPost = BhudevInstagramPost::where('bhudev_profile_id', $profile->id)
                ->where('id', $postId)
                ->first();

            if ($existingPost) {
                if ($existingPost->video_path) {
                    Storage::disk('public')->delete($existingPost->video_path);
                }
                $existingPost->video_path = $path;
                $existingPost->is_autoplay = $isAutoplay;
                if ($caption) {
                    $existingPost->caption = trim($caption);
                }
                $existingPost->save();

                return [
                    'valid'   => true,
                    'post'    => $existingPost,
                    'message' => 'Ceremony Reel video attached successfully to post with Auto-Play enabled.',
                ];
            }
        }

        $shortcode = 'video_' . Str::lower(Str::random(10));
        $canonicalUrl = asset('storage/' . $path);

        $post = BhudevInstagramPost::create([
            'bhudev_profile_id' => $profile->id,
            'url'               => $canonicalUrl,
            'video_path'        => $path,
            'shortcode'         => $shortcode,
            'media_type'        => 'reel',
            'caption'           => $caption ? trim($caption) : null,
            'is_active'         => true,
            'is_autoplay'       => $isAutoplay,
            'sort_order'        => (int) BhudevInstagramPost::where('bhudev_profile_id', $profile->id)->max('sort_order') + 1,
        ]);

        return [
            'valid'   => true,
            'post'    => $post,
            'message' => 'Ceremony Reel video uploaded successfully with Auto-Play enabled.',
        ];
    }

    /**
     * Toggle or update the site-wide Auto-Play setting for Reels.
     */
    public function updateAutoplaySetting(BhudevProfile $profile, bool $autoPlay): array
    {
        $settings = BhudevInstagramSetting::firstOrCreate([
            'bhudev_profile_id' => $profile->id,
        ], [
            'is_enabled'        => false,
            'auto_play'         => true,
            'connection_status' => 'disconnected',
        ]);

        $settings->auto_play = $autoPlay;
        $settings->save();

        return [
            'success'   => true,
            'auto_play' => $autoPlay,
            'message'   => 'Reels Auto-Play setting updated to ' . ($autoPlay ? 'ENABLED (Muted Auto-Play)' : 'DISABLED (Click to Play)') . '.',
        ];
    }

    /**
     * Remove a post URL for a Bhudev profile.
     */
    public function removePost(BhudevProfile $profile, int $postId): bool
    {
        $post = BhudevInstagramPost::where('bhudev_profile_id', $profile->id)
            ->where('id', $postId)
            ->first();

        if ($post) {
            if ($post->video_path) {
                Storage::disk('public')->delete($post->video_path);
            }
            $post->delete();
            return true;
        }

        return false;
    }

    /**
     * Test and validate official Meta Instagram Graph API access token.
     */
    public function validateMetaAccessToken(string $accessToken): array
    {
        $cleanToken = trim($accessToken);
        if (empty($cleanToken)) {
            return [
                'success' => false,
                'error'   => 'Access token cannot be empty.',
            ];
        }

        try {
            // Server-side validation request to official Instagram Graph API
            $response = Http::timeout(8)
                ->get('https://graph.instagram.com/me', [
                    'fields'       => 'id,username,account_type',
                    'access_token' => $cleanToken,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success'      => true,
                    'account_id'   => $data['id'] ?? null,
                    'username'     => $data['username'] ?? null,
                    'account_type' => $data['account_type'] ?? 'PERSONAL',
                ];
            }

            $errorData = $response->json('error');
            $errorMessage = $errorData['message'] ?? 'Meta API validation failed with HTTP ' . $response->status() . '.';

            return [
                'success' => false,
                'error'   => $errorMessage,
            ];
        } catch (\Throwable $e) {
            Log::warning('Instagram API validation exception: ' . $e->getMessage());
            return [
                'success' => false,
                'error'   => 'Network timeout or unable to reach Meta Instagram servers. Please try again.',
            ];
        }
    }

    /**
     * Save and validate Meta API connection credentials for a Bhudev.
     */
    public function saveApiConnection(
        BhudevProfile $profile,
        string $accessToken,
        ?string $appId = null,
        ?string $appSecret = null
    ): array {
        $validation = $this->validateMetaAccessToken($accessToken);

        $settings = BhudevInstagramSetting::firstOrNew([
            'bhudev_profile_id' => $profile->id,
        ]);

        $settings->app_id = $appId ? trim($appId) : $settings->app_id;
        if (!empty($appSecret)) {
            $settings->app_secret = trim($appSecret);
        }

        if ($validation['success']) {
            $settings->access_token      = trim($accessToken);
            $settings->token_expires_at  = now()->addDays(60);
            $settings->connection_status = 'connected';
            $settings->account_id        = $validation['account_id'];
            $settings->account_username  = $validation['username'];
            $settings->last_sync_at      = now();
            $settings->last_error        = null;
            $settings->save();

            // A short-lived token can be upgraded to a 60-day token when the app secret is known.
            if (!empty($settings->app_secret)) {
                $this->exchangeForLongLivedToken($settings);
            }

            // Pull the account's posts and Reels right away so nothing has to be uploaded twice.
            $sync = $this->syncLibrary($profile);
            $message = 'Instagram account @' . $validation['username'] . ' connected and verified successfully!';
            $message .= $sync['success']
                ? " {$sync['imported']} post(s)/Reel(s) imported from your Instagram library."
                : ' Library sync will retry automatically (' . $sync['error'] . ').';

            return [
                'success'  => true,
                'settings' => $settings,
                'message'  => $message,
            ];
        }

        $settings->connection_status = 'error';
        $settings->last_error        = $validation['error'];
        $settings->save();

        return [
            'success' => false,
            'error'   => $validation['error'],
        ];
    }

    /**
     * Disconnect Instagram API for a Bhudev. Library items that came from the account are
     * removed too; manually added URLs and uploaded videos stay.
     */
    public function disconnect(BhudevProfile $profile): void
    {
        $settings = BhudevInstagramSetting::where('bhudev_profile_id', $profile->id)->first();
        if ($settings) {
            $settings->connection_status = 'disconnected';
            $settings->access_token      = null;
            $settings->token_expires_at  = null;
            $settings->account_username  = null;
            $settings->account_id        = null;
            $settings->last_error        = null;
            $settings->save();
        }

        BhudevInstagramPost::where('bhudev_profile_id', $profile->id)
            ->where('source', 'api')
            ->whereNull('video_path')
            ->delete();
    }

    /**
     * Maximum pages (50 items each) pulled from the Instagram media endpoint per sync.
     */
    public const MAX_SYNC_PAGES = 5;

    /**
     * Pull the connected account's media library (posts + Reels) into the Bhudev's content list,
     * so nothing has to be uploaded twice. Safe to run repeatedly: items are matched by their
     * Instagram media id (or shortcode), items deleted on Instagram are pruned, manual entries
     * and uploaded videos are never touched.
     */
    public function syncLibrary(BhudevProfile $profile): array
    {
        $settings = BhudevInstagramSetting::where('bhudev_profile_id', $profile->id)->first();

        if (!$settings || !$settings->isConnected() || empty($settings->access_token)) {
            return ['success' => false, 'error' => 'Instagram account is not connected.', 'imported' => 0, 'removed' => 0];
        }

        $this->refreshTokenIfNeeded($settings);

        $items = [];
        $url   = 'https://graph.instagram.com/me/media';
        $query = [
            'fields'       => 'id,caption,media_type,media_url,permalink,thumbnail_url,timestamp',
            'limit'        => 50,
            'access_token' => $settings->access_token,
        ];

        try {
            for ($page = 0; $page < self::MAX_SYNC_PAGES && $url; $page++) {
                $response = Http::timeout(10)->get($url, $query);

                if (!$response->successful()) {
                    $message = $response->json('error.message') ?? ('Instagram API responded with HTTP ' . $response->status() . '.');
                    $settings->last_error = $message;
                    if (in_array((int) $response->json('error.code'), [190, 102], true)) {
                        // Token invalid or expired: the account must be reconnected.
                        $settings->connection_status = 'error';
                    }
                    $settings->save();

                    return ['success' => false, 'error' => $message, 'imported' => 0, 'removed' => 0];
                }

                $payload = $response->json() ?: [];
                foreach ((array) ($payload['data'] ?? []) as $item) {
                    if (!empty($item['id']) && !empty($item['permalink'])) {
                        $items[] = $item;
                    }
                }

                $url   = $payload['paging']['next'] ?? null; // already carries the cursor and token
                $query = [];
            }
        } catch (\Throwable $e) {
            Log::warning('Instagram library sync failed: ' . $e->getMessage(), ['profile_id' => $profile->id]);
            $settings->last_error = 'Could not reach Instagram. Please try again later.';
            $settings->save();

            return ['success' => false, 'error' => $settings->last_error, 'imported' => 0, 'removed' => 0];
        }

        $seenIds  = [];
        $imported = 0;

        foreach ($items as $item) {
            $parsed = $this->validateAndNormalizeUrl((string) $item['permalink']);
            if (!$parsed['valid']) {
                continue;
            }

            $mediaId  = (string) $item['id'];
            $rawType  = strtoupper((string) ($item['media_type'] ?? 'IMAGE'));
            $mediaUrl = $item['media_url'] ?? null;
            $thumb    = $item['thumbnail_url'] ?? ($rawType === 'VIDEO' ? null : $mediaUrl);
            $postedAt = !empty($item['timestamp']) ? Carbon::parse($item['timestamp']) : null;
            $seenIds[] = $mediaId;

            $post = BhudevInstagramPost::where('bhudev_profile_id', $profile->id)
                ->where(function ($q) use ($mediaId, $parsed) {
                    $q->where('ig_media_id', $mediaId)->orWhere('shortcode', $parsed['shortcode']);
                })
                ->first();

            if (!$post) {
                $post = new BhudevInstagramPost([
                    'bhudev_profile_id' => $profile->id,
                    'is_active'         => true,
                    'is_autoplay'       => true,
                ]);
            }

            $post->fill([
                'source'        => 'api',
                'ig_media_id'   => $mediaId,
                'url'           => $parsed['canonical_url'],
                'shortcode'     => $parsed['shortcode'],
                'media_type'    => $parsed['media_type'],
                'caption'       => Str::limit(trim((string) ($item['caption'] ?? '')), 1000, '') ?: $post->caption,
                'thumbnail_url' => $thumb,
                'media_url'     => $mediaUrl,
                'author_name'   => $settings->account_username,
                'posted_at'     => $postedAt,
                'synced_at'     => now(),
                // Newest first in every list (sort_order ascending); manual items keep their positive order
                'sort_order'    => $postedAt ? -1 * (int) $postedAt->timestamp : 0,
                'metadata'      => array_merge((array) $post->metadata, ['media_type' => $rawType]),
            ]);
            $post->save();
            $imported++;
        }

        // Items removed on Instagram disappear here too (uploaded videos attached to them stay).
        $pruneQuery = BhudevInstagramPost::where('bhudev_profile_id', $profile->id)
            ->where('source', 'api')
            ->whereNull('video_path');
        if ($seenIds) {
            $pruneQuery->whereNotIn('ig_media_id', $seenIds);
        }
        $removed = $pruneQuery->delete();

        $settings->last_sync_at = now();
        $settings->last_error   = null;
        $settings->save();

        return [
            'success'  => true,
            'imported' => $imported,
            'removed'  => $removed,
            'message'  => "Instagram library synced: {$imported} post(s)/Reel(s) imported" . ($removed ? ", {$removed} removed" : '') . '.',
        ];
    }

    /**
     * Sync every connected account. Used by the scheduled `instagram:sync` command.
     */
    public function syncAllConnected(): array
    {
        $results = ['synced' => 0, 'failed' => 0];

        BhudevInstagramSetting::with('profile')->where('connection_status', 'connected')->get()->each(function ($setting) use (&$results) {
            if (!$setting->profile) {
                return;
            }
            $this->syncLibrary($setting->profile)['success'] ? $results['synced']++ : $results['failed']++;
        });

        return $results;
    }

    /**
     * Upgrade a short-lived user token to a 60-day token (needs the Meta app secret).
     */
    protected function exchangeForLongLivedToken(BhudevInstagramSetting $settings): void
    {
        try {
            $response = Http::timeout(8)->get('https://graph.instagram.com/access_token', [
                'grant_type'    => 'ig_exchange_token',
                'client_secret' => $settings->app_secret,
                'access_token'  => $settings->access_token,
            ]);

            if ($response->successful() && $response->json('access_token')) {
                $settings->access_token     = $response->json('access_token');
                $settings->token_expires_at = now()->addSeconds((int) ($response->json('expires_in') ?: 5184000));
                $settings->save();
            }
        } catch (\Throwable $e) {
            Log::info('Instagram long-lived token exchange skipped: ' . $e->getMessage());
        }
    }

    /**
     * Long-lived tokens last 60 days; refresh when fewer than 10 days remain so syncing never stops.
     */
    protected function refreshTokenIfNeeded(BhudevInstagramSetting $settings): void
    {
        if ($settings->token_expires_at && $settings->token_expires_at->gt(now()->addDays(10))) {
            return;
        }

        try {
            $response = Http::timeout(8)->get('https://graph.instagram.com/refresh_access_token', [
                'grant_type'   => 'ig_refresh_token',
                'access_token' => $settings->access_token,
            ]);

            if ($response->successful() && $response->json('access_token')) {
                $settings->access_token     = $response->json('access_token');
                $settings->token_expires_at = now()->addSeconds((int) ($response->json('expires_in') ?: 5184000));
                $settings->save();
            }
        } catch (\Throwable $e) {
            Log::info('Instagram token refresh skipped: ' . $e->getMessage());
        }
    }

    /**
     * Toggle the enabled/disabled state of the Instagram integration for a Bhudev.
     */
    public function toggleIntegration(BhudevProfile $profile, bool $enable): array
    {
        $eligibility = $this->getDomainEligibilityDetails($profile);

        // If enabling, verify that the Bhudev has an eligible website
        if ($enable && !$eligibility['has_active_website']) {
            return [
                'success' => false,
                'error'   => "Cannot enable Instagram display: This Bhudev does not have an active website or domain yet. Please review the notice below.",
            ];
        }

        $settings = BhudevInstagramSetting::firstOrCreate(
            ['bhudev_profile_id' => $profile->id],
            ['connection_status' => 'disconnected']
        );

        $settings->is_enabled = $enable;
        $settings->save();

        return [
            'success' => true,
            'enabled' => $enable,
            'message' => $enable
                ? 'Instagram integration enabled for this Bhudev website.'
                : 'Instagram integration disabled. No Instagram content will be shown on the website.',
        ];
    }

    /**
     * Centralized Public Website Eligibility Check.
     * All 7 rules must pass for Instagram content to be rendered publicly:
     * 1. Bhudev profile exists.
     * 2. Bhudev has an individual website/domain configured.
     * 3. Website/domain is strictly 'active'.
     * 4. Integration is enabled for this Bhudev (is_enabled === true).
     * 5. Connection is validated (valid posts exist or account connected).
     * 6. At least one active, validated post/reel exists.
     * 7. The request strictly belongs to the exact Bhudev whose website is rendered.
     */
    public function isEligibleForPublicDisplay(BhudevProfile $profile, ?BhudevDomain $domain = null): bool
    {
        // Rule 1: Profile check
        if (!$profile || !$profile->exists || $profile->trashed()) {
            return false;
        }

        // Rule 2 & 3: Domain check (must be active and associated with this profile)
        $targetDomain = $domain ?? $profile->activeDomain;
        if (!$targetDomain || !$targetDomain->isActive()) {
            return false;
        }

        // Rule 7: Match domain to exact profile
        if ((int) $targetDomain->bhudev_profile_id !== (int) $profile->id) {
            return false;
        }

        // Rule 4: Integration enabled flag
        $settings = $profile->instagramSetting()->first();
        if (!$settings || !$settings->is_enabled) {
            return false;
        }

        // Rule 6: At least one active post exists
        return $profile->activeInstagramPosts()->exists();
    }

    /**
     * Retrieve eligible posts strictly scoped to this Bhudev.
     */
    public function getEligiblePosts(BhudevProfile $profile, ?BhudevDomain $domain = null): Collection
    {
        if (!$this->isEligibleForPublicDisplay($profile, $domain)) {
            return new Collection();
        }

        return $profile->activeInstagramPosts()->get();
    }

    /**
     * Get domain eligibility details, small notice text, and WhatsApp URL for admin view.
     */
    public function getDomainEligibilityDetails(BhudevProfile $profile): array
    {
        $domain = $profile->activeDomain;
        $latestDomain = $profile->domains()->first();

        $hasActiveWebsite = $domain && $domain->isActive();
        $domainName = $domain?->domain_name ?? $latestDomain?->domain_name;
        $status = $domain ? 'active' : ($latestDomain?->status ?? 'no_domain');

        // Null when SUPPORT_WHATSAPP is not configured; views hide the WhatsApp option in that case.
        $supportPhone = WhatsApp::supportNumber();
        $whatsAppUrl = WhatsApp::supportUrl(self::WHATSAPP_INQUIRY_TEXT);

        return [
            'has_active_website'  => $hasActiveWebsite,
            'domain_name'         => $domainName,
            'status'              => $status,
            'status_label'        => ucfirst(str_replace('_', ' ', $status)),
            'can_enable_public'   => $hasActiveWebsite,
            'notice_text'         => self::NOTICE_TEXT,
            'whatsapp_url'        => $whatsAppUrl,
            'whatsapp_phone'      => $supportPhone,
        ];
    }
}
