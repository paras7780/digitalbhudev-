<?php

namespace App\Http\Middleware;

use App\Models\BhudevDomain;
use Closure;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveTenantDomain
{
    /**
     * Detect whether the request host (or an explicit ?preview_domain=) belongs to a Pandit's
     * custom domain and, if so, share that tenant with the container and the views.
     *
     * This middleware only resolves and shares. It never renders a response itself, so it must run
     * after the session, CSRF and locale middleware (it is appended to the web group) and the
     * actual pages are rendered by normal routes (see PanditWebsiteController::landing).
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Start every request clean. The container instance survives between requests in tests and
        // long-running workers, so a previous tenant must never leak into this request.
        $this->clearTenant();

        $rawHost = $this->stripWww(strtolower(trim($request->getHost())));

        $previewDomain = $request->query('preview_domain');
        $lookupDomain = $previewDomain
            ? $this->stripWww(strtolower(trim((string) $previewDomain)))
            : $rawHost;

        // The platform's own host never maps to a tenant, so skip the database entirely.
        $appHost = $this->stripWww(strtolower((string) parse_url((string) config('app.url'), PHP_URL_HOST)));
        if (!$previewDomain && $lookupDomain === $appHost) {
            return $next($request);
        }

        try {
            $domain = BhudevDomain::with(['profile.services', 'profile.publicImages', 'profile.user'])
                ->where('domain_name', $lookupDomain)
                ->first();
        } catch (QueryException $e) {
            // Database not migrated yet: behave as the main site.
            return $next($request);
        }

        if ($domain && ($domain->isActive() || $previewDomain)) {
            app()->instance('current_tenant_domain', $domain);
            app()->instance('current_pandit', $domain->profile);
            view()->share('tenantDomain', $domain);
            view()->share('tenantPandit', $domain->profile);
        }

        return $next($request);
    }

    private function clearTenant(): void
    {
        app()->forgetInstance('current_tenant_domain');
        app()->forgetInstance('current_pandit');
        view()->share('tenantDomain', null);
        view()->share('tenantPandit', null);
    }

    private function stripWww(string $host): string
    {
        return str_starts_with($host, 'www.') ? substr($host, 4) : $host;
    }
}
