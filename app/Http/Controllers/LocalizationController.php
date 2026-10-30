<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class LocalizationController extends Controller
{
    /**
     * Allowed language codes.
     */
    public const SUPPORTED_LOCALES = ['en', 'gu', 'hi'];

    /**
     * Switch the user's active locale.
     * Stores in cookie for all users (1 year persistence),
     * and persists to database profile if authenticated as Bhudev.
     */
    public function switchLocale(Request $request, string $locale): RedirectResponse
    {
        if (!in_array($locale, self::SUPPORTED_LOCALES, true)) {
            $locale = config('app.fallback_locale', 'en');
        }

        // 1. If authenticated Bhudev, save preference into profile table
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->bhudevProfile) {
                $user->bhudevProfile->update([
                    'preferred_locale' => $locale,
                ]);
            }
        }

        // 2. Set current application runtime locale
        App::setLocale($locale);

        // 3. Store in session
        $request->session()->put('locale', $locale);

        // 4. Create permanent 1-year cookie (525600 minutes)
        $cookie = cookie()->forever('locale', $locale);

        return redirect()->back(fallback: route('home'))->withCookie($cookie);
    }
}
