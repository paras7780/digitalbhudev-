<?php

namespace App\Http\Controllers;

use App\Models\BhudevProfile;
use App\Models\User;
use App\Support\Audit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Lets the administrator open a Bhudev's portal as that Bhudev ("Login as") to see
 * exactly what they see, including their inquiries, and then return to the admin panel.
 */
class ImpersonationController extends Controller
{
    public const SESSION_ADMIN_KEY   = 'impersonator_id';
    public const SESSION_PROFILE_KEY = 'impersonated_profile_id';

    public static function isActive(): bool
    {
        return session()->has(self::SESSION_ADMIN_KEY);
    }

    /**
     * Admin only: switch the session to the Bhudev's user account.
     */
    public function start(Request $request, BhudevProfile $profile)
    {
        abort_unless($request->user()?->isAdmin(), 403, 'Unauthorized. Admin access required.');

        $target = $profile->user;
        abort_unless($target && $target->isBhudev(), 404, 'This profile has no Bhudev account to open.');

        Audit::record($request, 'admin.bhudev.impersonated', $profile, [
            'target_user_id' => $target->id,
        ]);

        $adminId = $request->user()->id;

        Auth::login($target);
        $request->session()->regenerate();
        $request->session()->put(self::SESSION_ADMIN_KEY, $adminId);
        $request->session()->put(self::SESSION_PROFILE_KEY, $profile->id);

        return redirect()->route('bhudev.dashboard')
            ->with('status', "You are now viewing the portal as {$profile->display_name}. Use \"Return to Admin\" when finished.");
    }

    /**
     * Return to the admin account that started the impersonation.
     */
    public function stop(Request $request)
    {
        $adminId   = $request->session()->pull(self::SESSION_ADMIN_KEY);
        $profileId = $request->session()->pull(self::SESSION_PROFILE_KEY);

        $admin = $adminId ? User::find($adminId) : null;

        if (!$admin || !$admin->isAdmin()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('status', 'Your admin session has ended. Please log in again.');
        }

        Audit::record($request, 'admin.bhudev.impersonation_ended', null, [
            'profile_id' => $profileId,
        ]);

        Auth::login($admin);
        $request->session()->regenerate();

        return $profileId
            ? redirect()->route('admin.bhudevs.show', $profileId)->with('status', 'Returned to your admin account.')
            : redirect()->route('admin.dashboard')->with('status', 'Returned to your admin account.');
    }
}
