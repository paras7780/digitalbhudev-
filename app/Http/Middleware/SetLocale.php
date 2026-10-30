<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Supported application locales.
     */
    public const SUPPORTED_LOCALES = ['en', 'gu', 'hi'];

    /**
     * Handle an incoming request and set the active locale.
     *
     * Priority:
     * 1. Authenticated Bhudev profile database column (`preferred_locale`)
     * 2. User cookie (`locale`)
     * 3. Session (`locale`)
     * 4. Application default fallback
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = null;

        // 1. Check authenticated Bhudev profile preference
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->bhudevProfile && !empty($user->bhudevProfile->preferred_locale)) {
                $profileLocale = $user->bhudevProfile->preferred_locale;
                if (in_array($profileLocale, self::SUPPORTED_LOCALES, true)) {
                    $locale = $profileLocale;
                }
            }
        }

        // 2. Check Cookie preference if not determined by logged in Bhudev
        if (!$locale && $request->hasCookie('locale')) {
            $cookieLocale = $request->cookie('locale');
            if (in_array($cookieLocale, self::SUPPORTED_LOCALES, true)) {
                $locale = $cookieLocale;
            }
        }

        // 3. Check Session
        if (!$locale && $request->hasSession() && $request->session()->has('locale')) {
            $sessionLocale = $request->session()->get('locale');
            if (in_array($sessionLocale, self::SUPPORTED_LOCALES, true)) {
                $locale = $sessionLocale;
            }
        }

        // 4. Fallback to default
        if (!$locale || !in_array($locale, self::SUPPORTED_LOCALES, true)) {
            $locale = config('app.fallback_locale', 'en');
        }

        App::setLocale($locale);

        // Keep session in sync
        if ($request->hasSession() && $request->session()->get('locale') !== $locale) {
            $request->session()->put('locale', $locale);
        }

        // Share current locale to all Blade views
        view()->share('current_locale', $locale);

        /** @var Response $response */
        $response = $next($request);

        $activeLocale = App::getLocale();

        // If response already has a 'locale' cookie set (e.g. from LocalizationController), do not overwrite
        $hasLocaleCookie = false;
        if (method_exists($response, 'headers') && $response->headers) {
            foreach ($response->headers->getCookies() as $c) {
                if ($c->getName() === 'locale') {
                    $hasLocaleCookie = true;
                    break;
                }
            }
        }

        // Ensure cookie is in sync if different and not already explicitly set
        if (!$hasLocaleCookie && $request->cookie('locale') !== $activeLocale && method_exists($response, 'withCookie')) {
            $response->withCookie(cookie()->forever('locale', $activeLocale));
        }

        return $response;
    }
}
