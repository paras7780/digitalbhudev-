<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\BhudevDomain;
use App\Models\BhudevImage;
use App\Models\BhudevProfile;
use App\Models\BhudevStatusHistory;
use App\Models\Inquiry;
use App\Models\PanchangEntry;
use App\Models\ProfileRevision;
use App\Models\Rashifal;
use App\Models\Service;
use App\Models\User;
use App\Support\Audit;
use App\Support\ImageOptimizer;
use App\Services\AstroApiService;
use App\Services\VedicAstroEngine;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    private function guard(Request $request): void
    {
        abort_unless($request->user()?->isAdmin(), 403, 'Unauthorized. Admin access required.');
    }

    /**
     * Admin Dashboard: statistics, pending profile submissions, pending revisions, and recent audit activity.
     */
    public function dashboard(Request $request)
    {
        $this->guard($request);

        $counts = [
            'total'     => BhudevProfile::count(),
            'pending'   => BhudevProfile::where('status', 'pending')->count(),
            'approved'  => BhudevProfile::where('status', 'approved')->count(),
            'rejected'  => BhudevProfile::where('status', 'rejected')->count(),
            'suspended' => BhudevProfile::where('status', 'suspended')->count(),
            'hidden'    => BhudevProfile::where('status', 'approved')->where('is_visible', false)->count(),
        ];

        $pendingProfiles = BhudevProfile::with(['user', 'services'])
            ->where('status', 'pending')
            ->latest()
            ->take(8)
            ->get();

        $pendingRevisions = ProfileRevision::with(['profile.user', 'profile.services'])
            ->where('review_status', 'pending')
            ->latest()
            ->take(8)
            ->get();

        $recentActivity = AuditLog::with('user')
            ->latest()
            ->take(8)
            ->get();

        return view('admin.dashboard', compact('counts', 'pendingProfiles', 'pendingRevisions', 'recentActivity'));
    }

    /**
     * Bhudev Listing: search, status filter pills, city filter, and pagination.
     */
    public function bhudevs(Request $request)
    {
        $this->guard($request);

        $query = BhudevProfile::with(['user', 'services'])->latest();

        $status = $request->query('status');
        if ($status === 'hidden') {
            $query->where('status', 'approved')->where('is_visible', false);
        } elseif ($request->filled('status')) {
            $query->where('status', $status);
        }

        if ($request->filled('q')) {
            $term = $request->string('q')->trim()->toString();
            $query->where(function ($q) use ($term) {
                $q->where('display_name', 'like', "%{$term}%")
                  ->orWhere('city', 'like', "%{$term}%")
                  ->orWhere('state', 'like', "%{$term}%")
                  ->orWhereHas('user', function ($uq) use ($term) {
                      $uq->where('email', 'like', "%{$term}%")
                         ->orWhere('phone', 'like', "%{$term}%");
                  });
            });
        }

        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->string('city')->trim()->toString() . '%');
        }

        $tabCounts = [
            'all'       => BhudevProfile::count(),
            'pending'   => BhudevProfile::where('status', 'pending')->count(),
            'approved'  => BhudevProfile::where('status', 'approved')->count(),
            'hidden'    => BhudevProfile::where('status', 'approved')->where('is_visible', false)->count(),
            'suspended' => BhudevProfile::where('status', 'suspended')->count(),
            'rejected'  => BhudevProfile::where('status', 'rejected')->count(),
        ];

        $profiles = $query->paginate(15)->withQueryString();

        return view('admin.bhudevs', compact('profiles', 'tabCounts'));
    }

    /**
     * Show form to add a new Bhudev directly from the Admin panel.
     */
    public function createBhudevForm(Request $request)
    {
        $this->guard($request);
        $services = Service::where('is_active', true)->orderBy('name')->get();
        return view('admin.bhudev-create', compact('services'));
    }

    /**
     * Save a new Bhudev account and full profile directly from the Admin panel.
     */
    public function createBhudev(Request $request)
    {
        $this->guard($request);

        $data = $request->validate([
            'name'             => 'required|string|max:120',
            'slug'             => 'nullable|string|max:191|unique:bhudev_profiles,slug',
            'email'            => 'required|email|max:255|unique:users,email',
            'phone'            => 'nullable|string|max:25|unique:users,phone',
            'password'         => 'nullable|string|min:8|max:64',
            'send_email'       => 'sometimes|boolean',
            'city'             => 'nullable|string|max:100',
            'state'            => 'nullable|string|max:100',
            'address'          => 'nullable|string|max:2000',
            'service_area'     => 'nullable|string|max:255',
            'languages'        => 'nullable|string|max:300',
            'expertise'        => 'nullable|string|max:2000',
            'experience_years' => 'nullable|integer|min:0|max:80',
            'qualification'    => 'nullable|string|max:2000',
            'availability'     => 'nullable|string|max:255',
            'bio'              => 'nullable|string|max:10000',
            'status'           => 'required|in:pending,approved',
            'is_visible'       => 'sometimes|boolean',
            'show_address'     => 'sometimes|boolean',
            'show_mobile'      => 'sometimes|boolean',
            'show_email'       => 'sometimes|boolean',
            'seo_title'        => 'nullable|string|max:180',
            'seo_description'  => 'nullable|string|max:255',
            'photo'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'gallery'          => 'nullable|array|max:8',
            'gallery.*'        => 'image|mimes:jpg,jpeg,png,webp|max:3072',
            'services'         => 'nullable|array',
            'services.*'       => 'integer|exists:services,id',
        ]);

        $plainPassword = $data['password'] ?: Str::random(16);

        $profile = DB::transaction(function () use ($request, $data, $plainPassword) {
            $user = User::create([
                'name'              => $data['name'],
                'email'             => $data['email'],
                'phone'             => $data['phone'] ?? null,
                'password'          => Hash::make($plainPassword),
                'role'              => 'bhudev',
                'is_active'         => true,
                'email_verified_at' => now(),
            ]);

            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = ImageOptimizer::store($request->file('photo'), 'bhudevs');
            }

            $languages = !empty($data['languages'])
                ? array_values(array_filter(array_map('trim', explode(',', $data['languages']))))
                : null;

            $status = $data['status'];
            $isVisible = ($status === 'approved') && $request->boolean('is_visible', true);

            $baseSlug = !empty($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['name']);
            $finalSlug = $baseSlug ?: 'bhudev';
            $counter = 1;
            while (BhudevProfile::where('slug', $finalSlug)->exists()) {
                $finalSlug = $baseSlug . '-' . (++$counter);
            }

            $profile = BhudevProfile::create([
                'user_id'          => $user->id,
                'display_name'     => $data['name'],
                'slug'             => $finalSlug,
                'city'             => $data['city'] ?? null,
                'state'            => $data['state'] ?? null,
                'address'          => $data['address'] ?? null,
                'service_area'     => $data['service_area'] ?? null,
                'languages'        => $languages,
                'expertise'        => $data['expertise'] ?? null,
                'experience_years' => $data['experience_years'] ?? null,
                'qualification'    => $data['qualification'] ?? null,
                'availability'     => $data['availability'] ?? null,
                'bio'              => $data['bio'] ?? null,
                'photo_path'       => $photoPath,
                'show_address'     => $request->boolean('show_address'),
                'show_mobile'      => $request->boolean('show_mobile', true),
                'show_email'       => $request->boolean('show_email'),
                'seo_title'        => $data['seo_title'] ?? null,
                'seo_description'  => $data['seo_description'] ?? null,
                'status'           => $status,
                'is_visible'       => $isVisible,
                'reviewed_by'      => $status === 'approved' ? $request->user()->id : null,
                'reviewed_at'      => $status === 'approved' ? now() : null,
            ]);

            if (!empty($data['services'])) {
                $profile->services()->sync($data['services']);
            }

            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery') as $idx => $galleryFile) {
                    $galleryPath = ImageOptimizer::store($galleryFile, 'bhudev-gallery');
                    $profile->images()->create([
                        'path'        => $galleryPath,
                        'alt_text'    => $profile->display_name . ' gallery image',
                        'is_approved' => ($status === 'approved'),
                        'sort_order'  => $idx,
                    ]);
                }
            }

            BhudevStatusHistory::create([
                'bhudev_profile_id' => $profile->id,
                'changed_by'        => $request->user()->id,
                'from_status'       => null,
                'to_status'         => $status,
                'reason'            => 'Account and profile created by Administrator',
                'is_visible'        => $isVisible,
            ]);

            Audit::record($request, 'admin.bhudev.created', $profile, [
                'user_id' => $user->id,
                'status'  => $status,
                'email'   => $user->email,
            ]);

            return $profile;
        });

        if ($request->boolean('send_email')) {
            try {
                Password::sendResetLink(['email' => $data['email']]);
            } catch (\Throwable $e) {
                // Email send failure shouldn't abort account creation
            }
        }

        $notice = "Bhudev account for '{$profile->display_name}' created successfully.";
        if (empty($data['password'])) {
            $notice .= " Temporary password generated: {$plainPassword}";
        }

        return redirect()->route('admin.bhudevs.show', $profile)->with('status', $notice);
    }

    /**
     * View full Bhudev details, gallery, audit trail, and status history.
     */
    public function showBhudev(Request $request, BhudevProfile $profile)
    {
        $this->guard($request);

        $profile->load(['user', 'services', 'images', 'statusHistory.changedBy', 'revisions.reviewer']);

        $auditLogs = AuditLog::where(function ($q) use ($profile) {
            $q->where(function ($sq) use ($profile) {
                $sq->where('subject_type', BhudevProfile::class)
                   ->where('subject_id', $profile->id);
            })->orWhere(function ($sq) use ($profile) {
                $sq->where('subject_type', User::class)
                   ->where('subject_id', $profile->user_id);
            });
        })->latest()->take(15)->get();

        return view('admin.bhudev-show', compact('profile', 'auditLogs'));
    }

    /**
     * Show form to edit an existing Bhudev profile and account.
     */
    public function editBhudev(Request $request, BhudevProfile $profile)
    {
        $this->guard($request);

        $profile->load(['user', 'services', 'images']);
        $services = Service::orderBy('name')->get();

        return view('admin.bhudev-edit', compact('profile', 'services'));
    }

    /**
     * Update an existing Bhudev profile and account.
     */
    public function updateBhudev(Request $request, BhudevProfile $profile)
    {
        $this->guard($request);

        $data = $request->validate([
            'name'             => 'required|string|max:120',
            'slug'             => 'nullable|string|max:191|unique:bhudev_profiles,slug,' . $profile->id,
            'email'            => 'required|email|max:255|unique:users,email,' . $profile->user_id,
            'phone'            => 'nullable|string|max:25|unique:users,phone,' . $profile->user_id,
            'password'         => 'nullable|string|min:8|max:64',
            'city'             => 'nullable|string|max:100',
            'state'            => 'nullable|string|max:100',
            'address'          => 'nullable|string|max:2000',
            'service_area'     => 'nullable|string|max:255',
            'languages'        => 'nullable|string|max:300',
            'expertise'        => 'nullable|string|max:2000',
            'experience_years' => 'nullable|integer|min:0|max:80',
            'qualification'    => 'nullable|string|max:2000',
            'availability'     => 'nullable|string|max:255',
            'bio'              => 'nullable|string|max:10000',
            'status'           => 'required|in:pending,approved,rejected,suspended',
            'is_visible'       => 'sometimes|boolean',
            'show_address'     => 'sometimes|boolean',
            'show_mobile'      => 'sometimes|boolean',
            'show_email'       => 'sometimes|boolean',
            'seo_title'        => 'nullable|string|max:180',
            'seo_description'  => 'nullable|string|max:255',
            'photo'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'gallery'          => 'nullable|array|max:8',
            'gallery.*'        => 'image|mimes:jpg,jpeg,png,webp|max:3072',
            'services'         => 'nullable|array',
            'services.*'       => 'integer|exists:services,id',
        ]);

        DB::transaction(function () use ($request, $profile, $data) {
            $user = $profile->user;
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->phone = $data['phone'] ?? null;
            if (!empty($data['password'])) {
                $user->password = Hash::make($data['password']);
            }
            $user->save();

            if ($request->hasFile('photo')) {
                if ($profile->photo_path) {
                    Storage::disk('public')->delete($profile->photo_path);
                }
                $profile->photo_path = ImageOptimizer::store($request->file('photo'), 'bhudevs');
            }

            $languages = !empty($data['languages'])
                ? array_values(array_filter(array_map('trim', explode(',', $data['languages']))))
                : [];

            $oldStatus = $profile->status;
            $newStatus = $data['status'];
            $isVisible = ($newStatus === 'approved') && $request->boolean('is_visible');

            $profile->display_name     = $data['name'];
            if (!empty($data['slug'])) {
                $profile->slug = Str::slug($data['slug']);
            }
            $profile->city             = $data['city'] ?? null;
            $profile->state            = $data['state'] ?? null;
            $profile->address          = $data['address'] ?? null;
            $profile->service_area     = $data['service_area'] ?? null;
            $profile->languages        = $languages;
            $profile->expertise        = $data['expertise'] ?? null;
            $profile->experience_years = $data['experience_years'] ?? null;
            $profile->qualification    = $data['qualification'] ?? null;
            $profile->availability     = $data['availability'] ?? null;
            $profile->bio              = $data['bio'] ?? null;
            $profile->seo_title        = $data['seo_title'] ?? null;
            $profile->seo_description  = $data['seo_description'] ?? null;
            $profile->show_address     = $request->boolean('show_address');
            $profile->show_mobile      = $request->boolean('show_mobile');
            $profile->show_email       = $request->boolean('show_email');
            $profile->status           = $newStatus;
            $profile->is_visible       = $isVisible;

            if ($oldStatus !== $newStatus) {
                $profile->reviewed_by = $request->user()->id;
                $profile->reviewed_at = now();
                if ($newStatus === 'approved') {
                    $profile->rejection_reason = null;
                    $profile->images()->update(['is_approved' => true]);
                }

                BhudevStatusHistory::create([
                    'bhudev_profile_id' => $profile->id,
                    'changed_by'        => $request->user()->id,
                    'from_status'       => $oldStatus,
                    'to_status'         => $newStatus,
                    'reason'            => 'Status updated in profile editor by Administrator',
                    'is_visible'        => $isVisible,
                ]);
            }

            $profile->save();
            $profile->services()->sync($data['services'] ?? []);

            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery') as $idx => $galleryFile) {
                    $galleryPath = ImageOptimizer::store($galleryFile, 'bhudev-gallery');
                    $profile->images()->create([
                        'path'        => $galleryPath,
                        'alt_text'    => $profile->display_name . ' gallery image',
                        'is_approved' => ($newStatus === 'approved'),
                        'sort_order'  => $profile->images()->count() + $idx,
                    ]);
                }
            }

            Audit::record($request, 'admin.bhudev.updated', $profile, [
                'status'     => $newStatus,
                'is_visible' => $isVisible,
            ]);
        });

        return redirect()->route('admin.bhudevs.show', $profile)->with('status', 'Bhudev profile updated successfully.');
    }

    /**
     * Approve an eligible Bhudev profile.
     */
    public function approveBhudev(Request $request, BhudevProfile $profile)
    {
        $this->guard($request);

        $from = $profile->status;
        $profile->status = 'approved';
        $profile->is_visible = true;
        $profile->rejection_reason = null;
        $profile->reviewed_by = $request->user()->id;
        $profile->reviewed_at = now();
        $profile->save();

        $profile->images()->update(['is_approved' => true]);

        BhudevStatusHistory::create([
            'bhudev_profile_id' => $profile->id,
            'changed_by'        => $request->user()->id,
            'from_status'       => $from,
            'to_status'         => 'approved',
            'reason'            => $request->input('reason', 'Profile approved by Administrator'),
            'is_visible'        => true,
        ]);

        Audit::record($request, 'admin.bhudev.approved', $profile, [
            'from_status' => $from,
        ]);

        return back()->with('status', "Profile for '{$profile->display_name}' has been approved and published.");
    }

    /**
     * Reject a submission with a required reason.
     */
    public function rejectBhudev(Request $request, BhudevProfile $profile)
    {
        $this->guard($request);

        $data = $request->validate([
            'reason'           => 'required_without:rejection_reason|nullable|string|max:2000',
            'rejection_reason' => 'required_without:reason|nullable|string|max:2000',
        ]);

        $reason = $data['reason'] ?? $data['rejection_reason'];

        $from = $profile->status;
        $profile->status = 'rejected';
        $profile->is_visible = false;
        $profile->rejection_reason = $reason;
        $profile->reviewed_by = $request->user()->id;
        $profile->reviewed_at = now();
        $profile->save();

        BhudevStatusHistory::create([
            'bhudev_profile_id' => $profile->id,
            'changed_by'        => $request->user()->id,
            'from_status'       => $from,
            'to_status'         => 'rejected',
            'reason'            => $reason,
            'is_visible'        => false,
        ]);

        Audit::record($request, 'admin.bhudev.rejected', $profile, [
            'from_status' => $from,
            'reason'      => $reason,
        ]);

        return back()->with('status', "Profile for '{$profile->display_name}' has been rejected.");
    }

    /**
     * Suspend an active profile.
     */
    public function suspendBhudev(Request $request, BhudevProfile $profile)
    {
        $this->guard($request);

        $data = $request->validate([
            'reason' => 'nullable|string|max:2000',
        ]);

        $from = $profile->status;
        $profile->status = 'suspended';
        $profile->is_visible = false;
        $profile->save();

        BhudevStatusHistory::create([
            'bhudev_profile_id' => $profile->id,
            'changed_by'        => $request->user()->id,
            'from_status'       => $from,
            'to_status'         => 'suspended',
            'reason'            => $data['reason'] ?? 'Profile suspended by Administrator',
            'is_visible'        => false,
        ]);

        Audit::record($request, 'admin.bhudev.suspended', $profile, [
            'from_status' => $from,
            'reason'      => $data['reason'] ?? null,
        ]);

        return back()->with('status', "Profile for '{$profile->display_name}' has been suspended.");
    }

    /**
     * Reactivate a suspended profile back to approved.
     */
    public function reactivateBhudev(Request $request, BhudevProfile $profile)
    {
        $this->guard($request);

        $from = $profile->status;
        $profile->status = 'approved';
        $profile->is_visible = true;
        $profile->save();

        BhudevStatusHistory::create([
            'bhudev_profile_id' => $profile->id,
            'changed_by'        => $request->user()->id,
            'from_status'       => $from,
            'to_status'         => 'approved',
            'reason'            => 'Account reactivated by Administrator',
            'is_visible'        => true,
        ]);

        Audit::record($request, 'admin.bhudev.reactivated', $profile, [
            'from_status' => $from,
        ]);

        return back()->with('status', "Profile for '{$profile->display_name}' has been reactivated.");
    }

    /**
     * Toggle public visibility of an approved profile (Hide / Show).
     */
    public function toggleVisibility(Request $request, BhudevProfile $profile)
    {
        $this->guard($request);

        if ($profile->status !== 'approved') {
            return back()->with('error', 'Only approved profiles can have their public visibility toggled.');
        }

        $newVisibility = !$profile->is_visible;
        $profile->is_visible = $newVisibility;
        $profile->save();

        BhudevStatusHistory::create([
            'bhudev_profile_id' => $profile->id,
            'changed_by'        => $request->user()->id,
            'from_status'       => $profile->status,
            'to_status'         => $profile->status,
            'reason'            => $newVisibility ? 'Profile made visible by Administrator' : 'Profile hidden from public view by Administrator',
            'is_visible'        => $newVisibility,
        ]);

        Audit::record($request, 'admin.bhudev.visibility_toggled', $profile, [
            'is_visible' => $newVisibility,
        ]);

        $actionWord = $newVisibility ? 'visible publicly' : 'hidden from public view';
        return back()->with('status', "Profile for '{$profile->display_name}' is now {$actionWord}.");
    }

    /**
     * Generic status fallback.
     */
    public function setStatus(Request $request, BhudevProfile $profile)
    {
        $this->guard($request);

        $data = $request->validate([
            'status'     => 'required|in:approved,rejected,suspended,pending',
            'reason'     => 'nullable|string|max:2000',
            'is_visible' => 'sometimes|boolean',
        ]);

        $from = $profile->status;
        $profile->status = $data['status'];
        $profile->is_visible = ($data['status'] === 'approved') && $request->boolean('is_visible', true);
        $profile->rejection_reason = ($data['status'] === 'rejected') ? ($data['reason'] ?? '') : null;
        $profile->reviewed_by = $request->user()->id;
        $profile->reviewed_at = now();
        $profile->save();

        if ($profile->status === 'approved') {
            $profile->images()->update(['is_approved' => true]);
        }

        BhudevStatusHistory::create([
            'bhudev_profile_id' => $profile->id,
            'changed_by'        => $request->user()->id,
            'from_status'       => $from,
            'to_status'         => $profile->status,
            'reason'            => $data['reason'] ?? null,
            'is_visible'        => $profile->is_visible,
        ]);

        Audit::record($request, 'admin.bhudev.status_changed', $profile, [
            'from'   => $from,
            'to'     => $profile->status,
            'reason' => $data['reason'] ?? null,
        ]);

        return back()->with('status', 'Bhudev status updated.');
    }

    /**
     * Safe delete / archive a Bhudev account and profile.
     */
    public function deleteBhudev(Request $request, BhudevProfile $profile)
    {
        $this->guard($request);

        abort_if($profile->user_id === $request->user()->id, 403, 'You cannot delete your own admin account.');

        Audit::record($request, 'admin.bhudev.deleted', $profile, [
            'name'  => $profile->display_name,
            'email' => $profile->user?->email,
        ]);

        // Soft delete both profile and user account safely
        $profile->delete();
        $profile->user?->delete();

        return redirect()->route('admin.bhudevs')->with('status', "Bhudev account for '{$profile->display_name}' has been safely archived.");
    }

    /**
     * Review submitted profile changes from an approved Bhudev.
     */
    public function reviewRevision(Request $request, ProfileRevision $revision)
    {
        $this->guard($request);

        $data = $request->validate([
            'decision'    => 'required|in:approved,rejected',
            'review_note' => 'nullable|string|max:2000',
        ]);

        $profile = $revision->profile()->with('services')->firstOrFail();

        $revision->review_status = $data['decision'];
        $revision->review_note = $data['review_note'] ?? null;
        $revision->reviewed_by = $request->user()->id;
        $revision->reviewed_at = now();
        $revision->save();

        if ($data['decision'] === 'approved') {
            $payload = $revision->payload;
            $serviceIds = $payload['service_ids'] ?? [];
            unset($payload['service_ids']);

            $profile->fill($payload);
            $profile->status = 'approved';
            $profile->is_visible = true;
            $profile->rejection_reason = null;
            $profile->reviewed_by = $request->user()->id;
            $profile->reviewed_at = now();
            $profile->save();

            if (!empty($serviceIds)) {
                $profile->services()->sync($serviceIds);
            }
            $profile->images()->update(['is_approved' => true]);

            BhudevStatusHistory::create([
                'bhudev_profile_id' => $profile->id,
                'changed_by'        => $request->user()->id,
                'from_status'       => 'pending',
                'to_status'         => 'approved',
                'reason'            => 'Profile revision approved by Administrator',
                'is_visible'        => true,
            ]);
        }

        Audit::record($request, 'admin.profile_revision.reviewed', $revision, [
            'decision' => $data['decision'],
        ]);

        return back()->with('status', "Profile revision {$data['decision']}.");
    }

    public function rashifals(Request $request)
    {
        $this->guard($request);
        return view('admin.rashifals', [
            'items'   => Rashifal::orderByDesc('date')->paginate(20),
            'editing' => $request->filled('edit') ? Rashifal::findOrFail($request->integer('edit')) : null,
        ]);
    }

    public function saveRashifal(Request $request, ?Rashifal $rashifal = null)
    {
        $this->guard($request);
        $data = $request->validate([
            'sign'         => 'required|in:Aries,Taurus,Gemini,Cancer,Leo,Virgo,Libra,Scorpio,Sagittarius,Capricorn,Aquarius,Pisces',
            'date'         => 'required|date',
            'title'        => 'nullable|string|max:180',
            'content'      => 'required|string|max:20000',
            'status'       => 'required|in:draft,published',
            'published_at' => 'nullable|date',
            'id'           => 'nullable|integer',
        ]);

        $item = isset($data['id']) ? Rashifal::findOrFail($data['id']) : new Rashifal();
        $item->fill(collect($data)->except(['id', 'published_at'])->all());
        $item->published_at = ($data['status'] === 'published') ? ($data['published_at'] ?? $item->published_at ?? now()) : null;
        $item->created_by = $item->exists ? $item->created_by : $request->user()->id;
        $item->updated_by = $request->user()->id;
        $item->save();

        Audit::record($request, 'admin.rashifal.saved', $item);

        return redirect()->route('admin.rashifals')->with('status', 'Rashifal saved.');
    }

    public function deleteRashifal(Request $request, Rashifal $rashifal)
    {
        $this->guard($request);
        Audit::record($request, 'admin.rashifal.deleted', $rashifal);
        $rashifal->delete();
        return back()->with('status', 'Rashifal deleted.');
    }

    public function panchang(Request $request)
    {
        $this->guard($request);
        return view('admin.panchang', [
            'items'   => PanchangEntry::orderByDesc('date')->paginate(20),
            'editing' => $request->filled('edit') ? PanchangEntry::findOrFail($request->integer('edit')) : null,
        ]);
    }

    public function savePanchang(Request $request)
    {
        $this->guard($request);
        $data = $request->validate([
            'date'         => 'required|date',
            'location'     => 'nullable|string|max:160',
            'timezone'     => 'required|string|max:80',
            'vaar'         => 'nullable|string|max:40',
            'tithi'        => 'nullable|string|max:120',
            'nakshatra'    => 'nullable|string|max:120',
            'yoga'         => 'nullable|string|max:120',
            'karana'       => 'nullable|string|max:120',
            'sunrise'      => 'nullable|string|max:20',
            'sunset'       => 'nullable|string|max:20',
            'moonrise'     => 'nullable|string|max:20',
            'moonset'      => 'nullable|string|max:20',
            'notes'        => 'nullable|string|max:5000',
            'status'       => 'required|in:draft,published',
            'published_at' => 'nullable|date',
            'id'           => 'nullable|integer',
        ]);

        $item = isset($data['id']) ? PanchangEntry::findOrFail($data['id']) : new PanchangEntry();
        $item->fill(collect($data)->except(['id', 'published_at'])->all());
        $item->published_at = ($data['status'] === 'published') ? ($data['published_at'] ?? $item->published_at ?? now()) : null;
        $item->created_by = $item->exists ? $item->created_by : $request->user()->id;
        $item->updated_by = $request->user()->id;
        $item->save();

        Audit::record($request, 'admin.panchang.saved', $item);

        return redirect()->route('admin.panchang')->with('status', 'Panchang saved.');
    }

    public function deletePanchang(Request $request, PanchangEntry $entry)
    {
        $this->guard($request);
        Audit::record($request, 'admin.panchang.deleted', $entry);
        $entry->delete();
        return back()->with('status', 'Panchang deleted.');
    }

    /**
     * Trigger on-demand sync / pre-fetch of upcoming Panchang & Rashifal records.
     */
    public function syncAstro(Request $request, AstroApiService $service)
    {
        $this->guard($request);

        $days = (int) $request->input('days', 7);
        if ($days < 1) $days = 1;
        if ($days > 31) $days = 31;

        $startDate = $request->filled('date')
            ? Carbon::parse($request->input('date'))
            : now('Asia/Kolkata');

        $location = $request->input('location') ?: VedicAstroEngine::DEFAULT_LOCATION;
        $force = $request->boolean('force', false);

        $results = $service->syncDays($startDate, $days, $location, $force);

        Audit::record($request, 'admin.astro.synced', null, $results);

        return back()->with('status', "Panchang & Rashifal synced: {$results['days_requested']} day(s) from {$results['start_date']} to {$results['end_date']} (Panchang: {$results['panchang_synced']}, Rashifal: {$results['rashifal_synced']}).");
    }

    public function inquiries(Request $request)
    {
        $this->guard($request);
        return view('admin.inquiries', ['items' => Inquiry::latest()->paginate(20)]);
    }

    public function updateInquiry(Request $request, Inquiry $inquiry)
    {
        $this->guard($request);
        $data = $request->validate([
            'status'     => 'required|in:new,in_progress,closed',
            'admin_note' => 'nullable|string|max:5000',
        ]);
        $inquiry->update($data);
        Audit::record($request, 'admin.inquiry.updated', $inquiry);
        return back()->with('status', 'Inquiry updated.');
    }

    public function auditLogs(Request $request)
    {
        $this->guard($request);
        return view('admin.audit-logs', [
            'items' => AuditLog::with('user')->orderByDesc('created_at')->paginate(50),
        ]);
    }

    /**
     * SaaS & Custom Domains Manager: list all domain requests, manage statuses, and configure pricing.
     */
    public function domains(Request $request)
    {
        $this->guard($request);

        $counts = [
            'total'             => BhudevDomain::count(),
            'inquiry_submitted' => BhudevDomain::where('status', 'inquiry_submitted')->count(),
            'quoted'            => BhudevDomain::where('status', 'quoted')->count(),
            'paid'              => BhudevDomain::where('status', 'paid')->count(),
            'active'            => BhudevDomain::where('status', 'active')->count(),
            'suspended'         => BhudevDomain::where('status', 'suspended')->count(),
        ];

        $query = BhudevDomain::with(['profile.user'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('tier')) {
            $query->where('tier', $request->input('tier'));
        }

        if ($request->filled('search')) {
            $s = trim($request->input('search'));
            $query->where(function ($q) use ($s) {
                $q->where('domain_name', 'like', "%{$s}%")
                  ->orWhere('hero_title', 'like', "%{$s}%")
                  ->orWhereHas('profile', fn($p) => $p->where('display_name', 'like', "%{$s}%"));
            });
        }

        $domains = $query->paginate(20)->withQueryString();
        $allBhudevs = BhudevProfile::with('user')->orderBy('display_name')->get();

        return view('admin.domains', compact('domains', 'counts', 'allBhudevs'));
    }

    /**
     * Admin directly assigns or creates a new domain for a Bhudev.
     */
    public function assignDomain(Request $request)
    {
        $this->guard($request);

        $validated = $request->validate([
            'bhudev_profile_id' => 'required|exists:bhudev_profiles,id',
            'domain_name'       => 'required|string|max:191',
            'tier'              => 'required|in:standard_saas,dedicated_custom',
            'status'            => 'required|in:inquiry_submitted,quoted,paid,dns_pending,active,suspended',
            'quoted_price'      => 'nullable|numeric|min:0',
            'hero_title'        => 'nullable|string|max:120',
            'hero_tagline'      => 'nullable|string|max:255',
            'whatsapp_number'   => 'nullable|string|max:30',
            'admin_notes'       => 'nullable|string|max:5000',
        ]);

        // Clean domain format
        $cleanDomain = strtolower(trim($validated['domain_name']));
        $cleanDomain = preg_replace('#^https?://#i', '', $cleanDomain);
        $cleanDomain = preg_replace('#^www\.#i', '', $cleanDomain);
        $cleanDomain = rtrim($cleanDomain, '/');

        // Check uniqueness
        if (BhudevDomain::where('domain_name', $cleanDomain)->exists()) {
            return back()->withInput()->withErrors(['domain_name' => 'This domain name is already registered or assigned to another account.']);
        }

        $validated['domain_name'] = $cleanDomain;

        if ($validated['status'] === 'active') {
            $validated['activated_at'] = now();
        }

        $domain = BhudevDomain::create($validated);

        Audit::record($request, 'admin.domain.assigned', $domain);

        return back()->with('status', 'Domain "' . $cleanDomain . '" has been directly assigned to ' . ($domain->profile?->display_name ?? 'Bhudev') . '.');
    }

    /**
     * 1-Click Status Update for Bhudev Domain (e.g. Activate, Suspend, Mark Paid).
     */
    public function updateDomainStatus(Request $request, BhudevDomain $domain)
    {
        $this->guard($request);

        $validated = $request->validate([
            'status'            => 'required|in:inquiry_submitted,quoted,paid,dns_pending,active,suspended',
            'suspension_reason' => 'nullable|string|max:1000',
        ]);

        $domain->status = $validated['status'];

        if ($domain->status === 'active') {
            if (!$domain->activated_at) {
                $domain->activated_at = now();
            }
            $domain->suspended_at = null;
        } elseif ($domain->status === 'suspended') {
            $domain->suspended_at = now();
            if (!empty($validated['suspension_reason'])) {
                $domain->suspension_reason = $validated['suspension_reason'];
            }
        }

        $domain->save();

        Audit::record($request, 'admin.domain.status_updated', $domain);

        return back()->with('status', 'Domain status for "' . $domain->domain_name . '" changed to ' . strtoupper(str_replace('_', ' ', $domain->status)) . '.');
    }

    /**
     * Update full domain configuration (pricing, tier, custom headline, notes).
     */
    public function updateDomainDetails(Request $request, BhudevDomain $domain)
    {
        $this->guard($request);

        $validated = $request->validate([
            'domain_name'       => 'required|string|max:191|unique:bhudev_domains,domain_name,' . $domain->id,
            'tier'              => 'required|in:standard_saas,dedicated_custom',
            'status'            => 'required|in:inquiry_submitted,quoted,paid,dns_pending,active,suspended',
            'suspension_reason' => 'nullable|string|max:1000',
            'quoted_price'      => 'nullable|numeric|min:0',
            'hero_title'        => 'nullable|string|max:120',
            'hero_tagline'      => 'nullable|string|max:255',
            'about_intro'       => 'nullable|string|max:10000',
            'whatsapp_number'   => 'nullable|string|max:30',
            'admin_notes'       => 'nullable|string|max:5000',
            'separate_db_name'  => 'nullable|string|max:100',
            'separate_db_host'  => 'nullable|string|max:100',
        ]);

        // Clean domain format
        $cleanDomain = strtolower(trim($validated['domain_name']));
        $cleanDomain = preg_replace('#^https?://#i', '', $cleanDomain);
        $cleanDomain = preg_replace('#^www\.#i', '', $cleanDomain);
        $cleanDomain = rtrim($cleanDomain, '/');
        $validated['domain_name'] = $cleanDomain;

        if ($validated['status'] === 'active' && !$domain->activated_at) {
            $domain->activated_at = now();
        }

        $domain->update($validated);

        Audit::record($request, 'admin.domain.details_updated', $domain);

        return back()->with('status', 'Domain configuration for "' . $domain->domain_name . '" updated successfully.');
    }

    /**
     * Delete a domain mapping.
     */
    public function deleteDomain(Request $request, BhudevDomain $domain)
    {
        $this->guard($request);

        $domainName = $domain->domain_name;
        $domain->delete();

        Audit::record($request, 'admin.domain.deleted', ['domain_name' => $domainName]);

        return back()->with('status', 'Domain "' . $domainName . '" has been removed.');
    }
}
