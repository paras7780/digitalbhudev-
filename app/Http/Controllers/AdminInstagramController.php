<?php

namespace App\Http\Controllers;

use App\Models\BhudevDomain;
use App\Models\BhudevInstagramPost;
use App\Models\BhudevInstagramSetting;
use App\Models\BhudevProfile;
use App\Models\Inquiry;
use App\Services\InstagramIntegrationService;
use App\Support\Audit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminInstagramController extends Controller
{
    protected InstagramIntegrationService $instagramService;

    public function __construct(InstagramIntegrationService $instagramService)
    {
        $this->instagramService = $instagramService;
    }

    /**
     * Enforce strict admin-only authorization.
     */
    protected function guard(Request $request): void
    {
        abort_unless($request->user() && $request->user()->isAdmin(), 403, 'Unauthorized. Admin access required.');
    }

    /**
     * Instagram Management Directory: view all Bhudevs, domain status, and Instagram integration status.
     */
    public function index(Request $request)
    {
        $this->guard($request);

        $query = BhudevProfile::with(['user', 'activeDomain', 'domains', 'instagramSetting', 'instagramPosts'])
            ->orderBy('display_name');

        // Search query
        if ($request->filled('q')) {
            $q = trim($request->input('q'));
            $query->where(function ($sub) use ($q) {
                $sub->where('display_name', 'like', "%{$q}%")
                    ->orWhere('city', 'like', "%{$q}%")
                    ->orWhere('qualification', 'like', "%{$q}%")
                    ->orWhereHas('user', function ($uq) use ($q) {
                        $uq->where('email', 'like', "%{$q}%")
                           ->orWhere('phone', 'like', "%{$q}%");
                    })
                    ->orWhereHas('domains', function ($dq) use ($q) {
                        $dq->where('domain_name', 'like', "%{$q}%");
                    });
            });
        }

        // Domain filter
        if ($request->filled('filter')) {
            $filter = $request->input('filter');
            if ($filter === 'with_active_website') {
                $query->whereHas('domains', function ($dq) {
                    $dq->where('status', 'active');
                });
            } elseif ($filter === 'no_website') {
                $query->whereDoesntHave('domains', function ($dq) {
                    $dq->where('status', 'active');
                });
            } elseif ($filter === 'enabled') {
                $query->whereHas('instagramSetting', function ($sq) {
                    $sq->where('is_enabled', true);
                });
            }
        }

        $profiles = $query->paginate(20)->withQueryString();

        return view('admin.instagram.index', [
            'profiles'    => $profiles,
            'totalCount'  => BhudevProfile::count(),
            'activeSites' => BhudevDomain::where('status', 'active')->count(),
            'enabledCount'=> BhudevInstagramSetting::where('is_enabled', true)->count(),
        ]);
    }

    /**
     * Manage Instagram integration for a specific Bhudev.
     */
    public function show(Request $request, BhudevProfile $profile)
    {
        $this->guard($request);

        $profile->load(['user', 'domains', 'instagramSetting', 'instagramPosts']);

        $eligibility = $this->instagramService->getDomainEligibilityDetails($profile);

        $settings = $profile->instagramSetting ?? new BhudevInstagramSetting([
            'bhudev_profile_id' => $profile->id,
            'is_enabled'        => false,
            'connection_status' => 'disconnected',
        ]);

        $posts = $profile->instagramPosts()->orderBy('sort_order')->orderByDesc('id')->get();

        return view('admin.instagram.manage', compact('profile', 'eligibility', 'settings', 'posts'));
    }

    /**
     * Toggle Instagram integration enabled/disabled state.
     */
    public function toggle(Request $request, BhudevProfile $profile)
    {
        $this->guard($request);

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

        Audit::record($request, 'admin.instagram.toggled', $profile, [
            'is_enabled' => $enable,
        ]);

        return back()->with('status', $result['message']);
    }

    /**
     * Store and validate a new Instagram Post or Reel URL.
     */
    public function storeUrl(Request $request, BhudevProfile $profile)
    {
        $this->guard($request);

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

        Audit::record($request, 'admin.instagram.url_added', $profile, [
            'url'        => $result['post']->url,
            'shortcode'  => $result['post']->shortcode,
            'media_type' => $result['post']->media_type,
        ]);

        return back()->with('status', $result['message']);
    }

    /**
     * Delete an Instagram post/reel for a Bhudev.
     */
    public function destroyUrl(Request $request, BhudevProfile $profile, BhudevInstagramPost $post)
    {
        $this->guard($request);

        abort_unless((int) $post->bhudev_profile_id === (int) $profile->id, 403, 'Post does not belong to this Bhudev.');

        $deletedUrl = $post->url;
        $this->instagramService->removePost($profile, $post->id);

        Audit::record($request, 'admin.instagram.url_deleted', $profile, [
            'url' => $deletedUrl,
        ]);

        return back()->with('status', 'Instagram post removed successfully.');
    }

    /**
     * Connect and validate official Meta Instagram API access token.
     */
    public function connectApi(Request $request, BhudevProfile $profile)
    {
        $this->guard($request);

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

        Audit::record($request, 'admin.instagram.api_connected', $profile, [
            'account_username' => $result['settings']->account_username,
        ]);

        return back()->with('status', $result['message']);
    }

    /**
     * Disconnect official Meta Instagram API.
     */
    public function disconnect(Request $request, BhudevProfile $profile)
    {
        $this->guard($request);

        $this->instagramService->disconnect($profile);

        Audit::record($request, 'admin.instagram.api_disconnected', $profile);

        return back()->with('status', 'Instagram account disconnected successfully.');
    }

    /**
     * Submit website request inquiry for Bhudevs without an active website (Option B).
     */
    public function requestWebsite(Request $request, BhudevProfile $profile)
    {
        $this->guard($request);

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

        $phone = $validated['whatsapp_number'] ?? $profile->user?->phone ?? '919876543210';

        // Check if an inquiry domain already exists
        $domain = BhudevDomain::firstOrNew([
            'bhudev_profile_id' => $profile->id,
            'status'            => 'inquiry_submitted',
        ]);

        $domain->domain_name     = $cleanDomain;
        $domain->whatsapp_number = $phone;
        $domain->bhudev_notes    = 'Website request initiated via Instagram Integration panel. ' . ($validated['notes'] ?? '');
        $domain->save();

        // Also record an inquiry so it appears in Communications > Inquiries
        Inquiry::create([
            'name'              => $profile->display_name,
            'email'             => $profile->user?->email ?? ('bhudev_' . $profile->id . '@digitalbhudev.test'),
            'phone'             => $phone,
            'subject'           => 'Website/Domain Request for Instagram Integration: ' . $cleanDomain,
            'type'              => 'domain_request',
            'city'              => $profile->city,
            'bhudev_profile_id' => $profile->id,
            'message'           => 'Bhudev website requested to enable Instagram Reels and Posts display. Domain: ' . $cleanDomain . '. Notes: ' . ($validated['notes'] ?? 'None'),
            'status'            => 'new',
        ]);

        Audit::record($request, 'admin.instagram.website_requested', $profile, [
            'domain_name' => $cleanDomain,
        ]);

        return back()->with('status', "Website inquiry submitted successfully for {$profile->display_name} ({$cleanDomain}). Our onboarding team will follow up via WhatsApp.");
    }
}
