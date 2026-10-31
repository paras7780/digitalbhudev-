<?php

namespace App\Http\Controllers;

use App\Models\BhudevDomain;
use App\Models\BhudevImage;
use App\Models\BhudevInstagramPost;
use App\Models\BhudevInstagramSetting;
use App\Models\BhudevProfile;
use App\Models\BhudevSamagriCard;
use App\Models\BhudevStatusHistory;
use App\Models\Inquiry;
use App\Models\ProfileRevision;
use App\Models\Service;
use App\Services\InstagramIntegrationService;
use App\Support\Audit;
use App\Support\ImageOptimizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BhudevDashboardController extends Controller
{
    protected InstagramIntegrationService $instagramService;

    public function __construct(InstagramIntegrationService $instagramService)
    {
        $this->instagramService = $instagramService;
    }

    private function guard(Request $request): BhudevProfile
    {
        abort_unless($request->user()?->isBhudev(), 403, 'Unauthorized. Bhudev account required.');

        $profile = $request->user()->bhudevProfile;

        // Auto-initialize profile if it doesn't exist yet
        if (!$profile) {
            $profile = BhudevProfile::create([
                'user_id'      => $request->user()->id,
                'display_name' => $request->user()->name,
                'slug'         => Str::slug($request->user()->name) . '-' . Str::lower(Str::random(6)),
                'status'       => 'pending',
                'is_visible'   => false,
            ]);
        }

        return $profile;
    }

    /**
     * Bhudev Dashboard: Overview, status badge, rejection notice, stats, profile snapshot.
     */
    public function dashboard(Request $request)
    {
        $profile = $this->guard($request)->load(['services', 'images', 'statusHistory.changedBy', 'revisions']);
        $pendingRevision = $profile->revisions()->where('review_status', 'pending')->latest()->first();
        $incomingBookings = $profile->inquiries()->take(10)->get();

        return view('bhudev.dashboard', compact('profile', 'pendingRevision', 'incomingBookings'));
    }

    /**
     * Form to edit profile and pooja services.
     */
    public function edit(Request $request)
    {
        $profile = $this->guard($request)->load('services');
        $services = Service::where('is_active', true)->orderBy('name')->get();

        return view('bhudev.profile-edit', compact('profile', 'services'));
    }

    /**
     * Update profile details.
     */
    public function update(Request $request)
    {
        $profile = $this->guard($request);

        $data = $request->validate([
            'display_name'     => 'required|string|max:120',
            'city'             => 'nullable|string|max:100',
            'state'            => 'nullable|string|max:100',
            'address'          => 'nullable|string|max:2000',
            'preferred_locale' => 'nullable|string|in:en,gu,hi',
            'service_area'     => 'nullable|string|max:255',
            'languages'        => 'nullable|string|max:300',
            'expertise'        => 'nullable|string|max:2000',
            'experience_years' => 'nullable|integer|min:0|max:80',
            'qualification'    => 'nullable|string|max:2000',
            'availability'     => 'nullable|string|max:255',
            'bio'              => 'nullable|string|max:10000',
            'show_address'     => 'sometimes|boolean',
            'show_mobile'      => 'sometimes|boolean',
            'show_email'       => 'sometimes|boolean',
            'photo'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'services'         => 'nullable|array',
            'services.*'       => 'integer|exists:services,id',
        ]);

        $payload = collect($data)->except(['photo', 'services'])->all();
        $payload['languages'] = !empty($data['languages'])
            ? array_values(array_filter(array_map('trim', explode(',', $data['languages']))))
            : [];
        $payload['show_address'] = $request->boolean('show_address');
        $payload['show_mobile'] = $request->boolean('show_mobile');
        $payload['show_email'] = $request->boolean('show_email');
        $payload['availability'] = $data['availability'] ?? null;

        if ($request->hasFile('photo')) {
            $payload['photo_path'] = ImageOptimizer::store($request->file('photo'), 'bhudevs');
        }

        if (!empty($data['preferred_locale'])) {
            $profile->preferred_locale = $data['preferred_locale'];
            $profile->save();
            session(['locale' => $data['preferred_locale']]);
            cookie()->queue(cookie()->forever('locale', $data['preferred_locale']));
        }

        // Apply updates directly to profile
        $isCurrentlyApproved = ($profile->status === 'approved');

        $profile->fill($payload);

        if ($isCurrentlyApproved) {
            $profile->status = 'approved';
            $profile->is_visible = true;
        } else {
            $profile->status = 'pending';
            $profile->is_visible = false;
            $profile->rejection_reason = null;
        }

        $profile->save();

        // Sync pooja services
        $profile->services()->sync($data['services'] ?? []);

        // Keep User account name in sync with Display Name
        if ($request->user() && !empty($payload['display_name'])) {
            $request->user()->name = $payload['display_name'];
            $request->user()->save();
        }

        // Record revision for history/audit
        ProfileRevision::create([
            'bhudev_profile_id' => $profile->id,
            'submitted_by'      => $request->user()->id,
            'payload'           => array_merge($payload, ['service_ids' => $data['services'] ?? []]),
            'review_status'     => 'pending',
        ]);

        if (!$isCurrentlyApproved) {
            BhudevStatusHistory::create([
                'bhudev_profile_id' => $profile->id,
                'changed_by'        => $request->user()->id,
                'from_status'       => $profile->getOriginal('status') ?: 'pending',
                'to_status'         => 'pending',
                'reason'            => 'Profile updated by Bhudev and re-submitted for review',
                'is_visible'        => false,
            ]);
        }

        Audit::record($request, 'bhudev.profile.updated', $profile);

        return redirect()->route('bhudev.profile.edit')->with('status', 'Your profile details have been updated and saved successfully.');
    }

    /**
     * Gallery page: view and upload ceremony images.
     */
    public function gallery(Request $request)
    {
        $profile = $this->guard($request)->load('images');
        return view('bhudev.gallery', compact('profile'));
    }

    /**
     * Upload new gallery images.
     */
    public function uploadGallery(Request $request)
    {
        $profile = $this->guard($request);

        $request->validate([
            'gallery'   => 'required|array|min:1|max:8',
            'gallery.*' => 'image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        foreach ($request->file('gallery') as $file) {
            $path = ImageOptimizer::store($file, 'bhudev-gallery');
            $profile->images()->create([
                'path'        => $path,
                'alt_text'    => $profile->display_name . ' ceremony photo',
                'is_approved' => ($profile->status === 'approved'),
                'sort_order'  => $profile->images()->count(),
            ]);
        }

        Audit::record($request, 'bhudev.gallery.uploaded', $profile);

        return back()->with('status', 'New gallery photo(s) uploaded successfully.');
    }

    /**
     * Delete an existing gallery image.
     */
    public function deleteImage(Request $request, BhudevImage $image)
    {
        $profile = $this->guard($request);
        abort_unless($image->bhudev_profile_id === $profile->id, 404);

        if ($image->path) {
            Storage::disk('public')->delete($image->path);
        }
        $image->delete();

        Audit::record($request, 'bhudev.gallery.deleted', $profile);

        return back()->with('status', 'Gallery image removed.');
    }

    /**
     * Change password form.
     */
    public function passwordForm(Request $request)
    {
        $profile = $this->guard($request);
        return view('bhudev.password', compact('profile'));
    }

    /**
     * Change password save.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password'         => 'required|string|min:8|max:64|confirmed',
        ]);

        $request->user()->update([
            'password' => Hash::make($request->input('password')),
        ]);

        Audit::record($request, 'bhudev.password.changed', $request->user());

        return back()->with('status', 'Your account password has been updated successfully.');
    }

    /**
     * Review history and status transitions timeline.
     */
    public function history(Request $request)
    {
        $profile = $this->guard($request)->load(['statusHistory.changedBy', 'revisions.reviewer']);
        return view('bhudev.history', compact('profile'));
    }

    /**
     * Bhudev Personal Website & Custom Domain management tab.
     */
    public function websiteSettings(Request $request)
    {
        $profile = $this->guard($request)->load('domains');
        $activeDomain = $profile->activeDomain;
        $latestDomainRequest = $profile->domains()->latest()->first();

        return view('bhudev.website', compact('profile', 'activeDomain', 'latestDomainRequest'));
    }

    /**
     * Submit an inquiry to launch a custom personal domain website.
     */
    public function submitDomainRequest(Request $request)
    {
        $profile = $this->guard($request);

        $validated = $request->validate([
            'requested_domain' => 'required|string|max:191',
            'hero_title'       => 'nullable|string|max:120',
            'hero_tagline'     => 'nullable|string|max:255',
            'about_intro'      => 'nullable|string|max:10000',
            'whatsapp_number'  => 'required|string|max:25',
            'notes'            => 'nullable|string|max:2000',
        ]);

        // Clean domain format: remove http, https, www, trailing slashes, spaces
        $cleanDomain = strtolower(trim($validated['requested_domain']));
        $cleanDomain = preg_replace('#^https?://#i', '', $cleanDomain);
        $cleanDomain = preg_replace('#^www\.#i', '', $cleanDomain);
        $cleanDomain = rtrim($cleanDomain, '/');

        // Check if domain is taken
        $existing = BhudevDomain::where('domain_name', $cleanDomain)
            ->where('bhudev_profile_id', '!=', $profile->id)
            ->first();

        if ($existing) {
            return back()->withInput()->withErrors(['requested_domain' => 'This domain is already registered or requested by another account. Please try another domain name.']);
        }

        $domainRecord = BhudevDomain::updateOrCreate(
            [
                'bhudev_profile_id' => $profile->id,
                'domain_name'       => $cleanDomain,
            ],
            [
                'status'          => 'inquiry_submitted',
                'hero_title'      => !empty($validated['hero_title']) ? $validated['hero_title'] : $profile->display_name,
                'hero_tagline'    => $validated['hero_tagline'] ?? null,
                'about_intro'     => !empty($validated['about_intro']) ? $validated['about_intro'] : $profile->bio,
                'whatsapp_number' => preg_replace('/[^0-9]/', '', $validated['whatsapp_number']),
                'bhudev_notes'    => $validated['notes'] ?? null,
            ]
        );

        Audit::record($request, 'bhudev.domain.requested', $domainRecord);

        return redirect()->route('bhudev.website')->with('status', 'Your custom website inquiry for "' . $cleanDomain . '" has been submitted to the administration. We will review it and provide your quotation shortly.');
    }

    /**
     * Update live personal website content and introduction.
     */
    public function updateWebsiteContent(Request $request)
    {
        $profile = $this->guard($request);
        $domain = $profile->activeDomain ?? $profile->domains()->latest()->first();
        abort_unless($domain, 404, 'No domain record found to update.');

        $validated = $request->validate([
            'hero_title'      => 'required|string|max:120',
            'hero_tagline'    => 'nullable|string|max:255',
            'about_intro'     => 'required|string|max:10000',
            'whatsapp_number' => 'required|string|max:25',
            'upi_id'          => 'nullable|string|max:100',
            'dakshina_qr'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        $updateData = [
            'hero_title'      => $validated['hero_title'],
            'hero_tagline'    => $validated['hero_tagline'] ?? null,
            'about_intro'     => $validated['about_intro'],
            'whatsapp_number' => preg_replace('/[^0-9]/', '', $validated['whatsapp_number']),
            'upi_id'          => !empty($validated['upi_id']) ? trim($validated['upi_id']) : null,
        ];

        if ($request->hasFile('dakshina_qr')) {
            $updateData['dakshina_qr_path'] = ImageOptimizer::store($request->file('dakshina_qr'), 'dakshina-qrs');
        }

        $domain->update($updateData);

        Audit::record($request, 'bhudev.domain.content_updated', $domain);

        return back()->with('status', 'Your personal website introduction and hero content have been updated and are live on your website!');
    }

    /**
     * Pooja Samagri Patrika Studio: Interactive Figma-identical card editor.
     */
    public function samagriStudio(Request $request)
    {
        $profile = $this->guard($request)->load('user');

        // Load 4 authentic Figma presets
        $presetsPath = public_path('assets/images/samagri/pooja_templates.json');
        $presets = file_exists($presetsPath) ? json_decode(file_get_contents($presetsPath), true) : [];

        // Load all saved cards for this priest
        $savedCards = $profile->samagriCards()->get();

        // Check if an existing card is requested to load
        $activeCard = null;
        if ($cardId = $request->query('card_id')) {
            $activeCard = $profile->samagriCards()->find($cardId);
        }

        // Determine default values
        $defaultMantra = '|| શ્રી ગણેશાય નમઃ ||';
        $defaultFirm = 'નારાયણ જ્યોતિષ';
        $defaultLeftName = 'મેહુલ દાદા';
        $defaultLeftPhone = '૮૯૮૦૫ ૫૫૭૫૬';
        $defaultRightName = $profile->display_name ?: $profile->user?->name ?: 'પારસ દાદા';
        $defaultRightPhone = $profile->user?->phone ?? $profile->user?->mobile ?? '૯૫૮૬૦ ૭૨૮૨૨';

        // Initial pooja template
        $initialPoojaSlug = $activeCard?->pooja_slug ?? $request->query('pooja', 'ganpati');
        $initialPreset = $presets[$initialPoojaSlug] ?? ($presets['ganpati'] ?? null);

        return view('bhudev.samagri-studio', compact(
            'profile',
            'presets',
            'savedCards',
            'activeCard',
            'defaultMantra',
            'defaultFirm',
            'defaultLeftName',
            'defaultLeftPhone',
            'defaultRightName',
            'defaultRightPhone',
            'initialPoojaSlug',
            'initialPreset'
        ));
    }

    /**
     * Save or update custom Samagri Card.
     */
    public function saveSamagriCard(Request $request)
    {
        $profile = $this->guard($request);

        $validated = $request->validate([
            'card_id'            => 'nullable|integer',
            'pooja_slug'         => 'required|string|max:60',
            'pooja_title'        => 'required|string|max:180',
            'mantra_line'        => 'nullable|string|max:120',
            'firm_title'         => 'nullable|string|max:160',
            'left_priest_name'   => 'nullable|string|max:120',
            'left_priest_phone'  => 'nullable|string|max:40',
            'right_priest_name'  => 'nullable|string|max:120',
            'right_priest_phone' => 'nullable|string|max:40',
            'column1_items'      => 'nullable|string',
            'column2_items'      => 'nullable|string',
            'yajman_name'        => 'nullable|string|max:180',
            'locale'             => 'nullable|string|max:10',
        ]);

        if (!empty($validated['card_id'])) {
            $card = $profile->samagriCards()->findOrFail($validated['card_id']);
            $card->update([
                'pooja_slug'         => $validated['pooja_slug'],
                'pooja_title'        => $validated['pooja_title'],
                'mantra_line'        => $validated['mantra_line'] ?? '|| શ્રી ગણેશાય નમઃ ||',
                'firm_title'         => $validated['firm_title'] ?? '',
                'left_priest_name'   => $validated['left_priest_name'] ?? '',
                'left_priest_phone'  => $validated['left_priest_phone'] ?? '',
                'right_priest_name'  => $validated['right_priest_name'] ?? '',
                'right_priest_phone' => $validated['right_priest_phone'] ?? '',
                'column1_items'      => $validated['column1_items'] ?? '',
                'column2_items'      => $validated['column2_items'] ?? '',
                'yajman_name'        => $validated['yajman_name'] ?? '',
                'locale'             => $validated['locale'] ?? app()->getLocale(),
            ]);
        } else {
            $card = $profile->samagriCards()->create([
                'pooja_slug'         => $validated['pooja_slug'],
                'pooja_title'        => $validated['pooja_title'],
                'mantra_line'        => $validated['mantra_line'] ?? '|| શ્રી ગણેશાય નમઃ ||',
                'firm_title'         => $validated['firm_title'] ?? '',
                'left_priest_name'   => $validated['left_priest_name'] ?? '',
                'left_priest_phone'  => $validated['left_priest_phone'] ?? '',
                'right_priest_name'  => $validated['right_priest_name'] ?? '',
                'right_priest_phone' => $validated['right_priest_phone'] ?? '',
                'column1_items'      => $validated['column1_items'] ?? '',
                'column2_items'      => $validated['column2_items'] ?? '',
                'yajman_name'        => $validated['yajman_name'] ?? '',
                'locale'             => $validated['locale'] ?? app()->getLocale(),
            ]);
        }

        Audit::record($request, 'bhudev.samagri_card.saved', $card);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('messages.samagri_studio.card_saved_success'),
                'card'    => $card,
                'share_url' => route('samagri.card.show', $card->id),
            ]);
        }

        return redirect()->route('bhudev.samagri-studio', ['card_id' => $card->id])
            ->with('status', __('messages.samagri_studio.card_saved_success'));
    }

    /**
     * Delete custom Samagri Card.
     */
    public function deleteSamagriCard(Request $request, BhudevSamagriCard $card)
    {
        $profile = $this->guard($request);
        abort_unless($card->bhudev_profile_id === $profile->id, 403, 'Unauthorized access to card.');

        $card->delete();
        Audit::record($request, 'bhudev.samagri_card.deleted', $card);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('messages.samagri_studio.card_deleted_success'),
            ]);
        }

        return redirect()->route('bhudev.samagri-studio')
            ->with('status', __('messages.samagri_studio.card_deleted_success'));
    }

    /**
     * Bhudev Portal: Instagram & Reels Management View
     */
    public function instagram(Request $request)
    {
        $profile = $this->guard($request);
        $profile->load(['domains', 'activeDomain', 'instagramSetting', 'instagramPosts']);

        $eligibility = $this->instagramService->getDomainEligibilityDetails($profile);

        $settings = $profile->instagramSetting ?? new BhudevInstagramSetting([
            'bhudev_profile_id' => $profile->id,
            'is_enabled'        => false,
            'connection_status' => 'disconnected',
        ]);

        $posts = $profile->instagramPosts()->orderBy('sort_order')->orderByDesc('id')->get();

        return view('bhudev.instagram', compact('profile', 'eligibility', 'settings', 'posts'));
    }

    /**
     * Toggle Instagram display on active website
     */
    public function toggleInstagram(Request $request)
    {
        $profile = $this->guard($request);

        $request->validate([
            'is_enabled' => 'required|boolean',
        ]);

        $enable = $request->boolean('is_enabled');
        $result = $this->instagramService->toggleIntegration($profile, $enable);

        if (!$result['success']) {
            return back()
                ->withErrors(['toggle' => $result['error']])
                ->with('error', $result['error']);
        }

        Audit::record($request, 'bhudev.instagram.toggled', $profile, [
            'is_enabled' => $enable,
        ]);

        return back()->with('status', $result['message']);
    }

    /**
     * Toggle or update the site-wide Auto-Play setting for Reels
     */
    public function toggleAutoplay(Request $request)
    {
        $profile = $this->guard($request);

        $request->validate([
            'auto_play' => 'required|boolean',
        ]);

        $autoPlay = $request->boolean('auto_play');
        $result = $this->instagramService->updateAutoplaySetting($profile, $autoPlay);

        Audit::record($request, 'bhudev.instagram.autoplay_toggled', $profile, [
            'auto_play' => $autoPlay,
        ]);

        return back()->with('status', $result['message']);
    }

    /**
     * Upload a direct video file for a Bhudev Reel (MP4, MOV, WebM) with Auto-Play.
     */
    public function uploadInstagramVideo(Request $request)
    {
        $profile = $this->guard($request);

        $request->validate([
            'video'       => 'required|file|mimes:mp4,mov,webm,m4v|max:102400',
            'caption'     => 'nullable|string|max:1000',
            'is_autoplay' => 'sometimes|boolean',
            'post_id'     => 'nullable|integer',
        ]);

        $result = $this->instagramService->uploadReelVideo(
            $profile,
            $request->file('video'),
            $request->input('caption'),
            $request->boolean('is_autoplay', true),
            $request->filled('post_id') ? (int) $request->input('post_id') : null
        );

        if (!$result['valid']) {
            return back()->withErrors(['video' => $result['error']])->withInput();
        }

        Audit::record($request, 'bhudev.instagram.video_uploaded', $profile, [
            'video_path' => $result['post']->video_path,
        ]);

        return back()->with('status', $result['message']);
    }

    /**
     * Store and validate an Instagram Post or Reel URL
     */
    public function storeInstagramUrl(Request $request)
    {
        $profile = $this->guard($request);

        $validated = $request->validate([
            'url'     => 'required|string|max:1000',
            'caption' => 'nullable|string|max:1000',
        ]);

        $result = $this->instagramService->addPostUrl($profile, $validated['url'], $validated['caption'] ?? null);

        if (!$result['valid']) {
            return back()
                ->withErrors(['url' => $result['error']])
                ->withInput();
        }

        Audit::record($request, 'bhudev.instagram.url_added', $profile, [
            'url'        => $result['post']->url,
            'shortcode'  => $result['post']->shortcode,
            'media_type' => $result['post']->media_type,
        ]);

        return back()->with('status', $result['message']);
    }

    /**
     * Delete an Instagram post/reel
     */
    public function destroyInstagramUrl(Request $request, BhudevInstagramPost $post)
    {
        $profile = $this->guard($request);

        abort_unless((int) $post->bhudev_profile_id === (int) $profile->id, 403, 'Post does not belong to your account.');

        $deletedUrl = $post->url;
        $this->instagramService->removePost($profile, $post->id);

        Audit::record($request, 'bhudev.instagram.url_deleted', $profile, [
            'url' => $deletedUrl,
        ]);

        return back()->with('status', 'Instagram post removed successfully.');
    }

    /**
     * Connect official Meta Instagram API access token
     */
    public function connectInstagramApi(Request $request)
    {
        $profile = $this->guard($request);

        $validated = $request->validate([
            'access_token' => 'required|string|max:2000',
            'app_id'       => 'nullable|string|max:100',
            'app_secret'   => 'nullable|string|max:100',
        ]);

        $result = $this->instagramService->saveApiConnection(
            $profile,
            $validated['access_token'],
            $validated['app_id'] ?? null,
            $validated['app_secret'] ?? null
        );

        if (!$result['success']) {
            return back()
                ->withErrors(['access_token' => $result['error']])
                ->with('error', 'Meta API validation failed: ' . $result['error']);
        }

        Audit::record($request, 'bhudev.instagram.api_connected', $profile, [
            'account_username' => $result['settings']->account_username,
        ]);

        return back()->with('status', $result['message']);
    }

    /**
     * Disconnect official Meta Instagram API
     */
    public function disconnectInstagram(Request $request)
    {
        $profile = $this->guard($request);

        $this->instagramService->disconnect($profile);

        Audit::record($request, 'bhudev.instagram.api_disconnected', $profile);

        return back()->with('status', 'Instagram account disconnected successfully.');
    }

    /**
     * Submit website request from Bhudev Instagram page if without website
     */
    public function requestInstagramWebsite(Request $request)
    {
        $profile = $this->guard($request);

        $validated = $request->validate([
            'requested_domain' => 'nullable|string|max:191',
            'whatsapp_number'  => 'nullable|string|max:25',
            'notes'            => 'nullable|string|max:2000',
        ]);

        $rawDomain = trim($validated['requested_domain'] ?? '');
        if (empty($rawDomain)) {
            $rawDomain = Str::slug($profile->display_name) . '.digitalbhudev.in';
        }

        $cleanDomain = strtolower($rawDomain);
        $cleanDomain = preg_replace('#^https?://#i', '', $cleanDomain);
        $cleanDomain = preg_replace('#^www\.#i', '', $cleanDomain);
        $cleanDomain = rtrim($cleanDomain, '/');

        $phone = $validated['whatsapp_number'] ?? $request->user()->phone ?? '919876543210';

        $domain = BhudevDomain::firstOrNew([
            'bhudev_profile_id' => $profile->id,
            'status'            => 'inquiry_submitted',
        ]);

        $domain->domain_name     = $cleanDomain;
        $domain->whatsapp_number = $phone;
        $domain->bhudev_notes    = 'Website request initiated via Bhudev Portal Instagram page. ' . ($validated['notes'] ?? '');
        $domain->save();

        Inquiry::create([
            'name'              => $profile->display_name,
            'email'             => $request->user()->email,
            'phone'             => $phone,
            'subject'           => 'Website/Domain Request for Instagram Integration: ' . $cleanDomain,
            'type'              => 'domain_request',
            'city'              => $profile->city,
            'bhudev_profile_id' => $profile->id,
            'message'           => 'Bhudev website requested to enable Instagram Reels and Posts display. Domain: ' . $cleanDomain . '. Notes: ' . ($validated['notes'] ?? 'None'),
            'status'            => 'new',
        ]);

        Audit::record($request, 'bhudev.instagram.website_requested', $profile, [
            'domain_name' => $cleanDomain,
        ]);

        return back()->with('status', "Website inquiry submitted successfully ({$cleanDomain}). Our team will activate your website shortly so your Instagram content can be displayed.");
    }
}
