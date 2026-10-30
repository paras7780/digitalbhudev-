<?php

namespace App\Http\Middleware;

use App\Http\Controllers\PanditWebsiteController;
use App\Models\BhudevDomain;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class ResolveTenantDomain
{
    /**
     * Handle an incoming request.
     * Detects if the incoming host or preview parameter corresponds to a Pandit's custom domain.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Safe check if database has been migrated
        if (!Schema::hasTable('bhudev_domains')) {
            return $next($request);
        }

        $rawHost = strtolower(trim($request->getHost()));

        // Strip www. prefix
        if (str_starts_with($rawHost, 'www.')) {
            $rawHost = substr($rawHost, 4);
        }

        // Check if an explicit preview domain parameter was passed (useful for local testing)
        $previewDomain = $request->query('preview_domain');
        $lookupDomain = $previewDomain ? strtolower(trim($previewDomain)) : $rawHost;

        // Find active domain mapping
        $domain = BhudevDomain::with(['profile.services', 'profile.publicImages', 'profile.user'])
            ->where('domain_name', $lookupDomain)
            ->first();

        // If domain exists and is active (or in preview mode)
        if ($domain && ($domain->isActive() || $previewDomain)) {
            app()->instance('current_tenant_domain', $domain);
            app()->instance('current_pandit', $domain->profile);
            view()->share('tenantDomain', $domain);
            view()->share('tenantPandit', $domain->profile);

            // Intercept root homepage on Pandit's domain
            if ($request->path() === '/' || $request->path() === '') {
                $content = app(PanditWebsiteController::class)->show($request, $domain);
                return response($content);
            }

            // Intercept inquiry submission on Pandit's domain
            if ($request->is('pandit-inquiry') || ($request->is('inquiry') && $request->isMethod('POST'))) {
                $response = app(PanditWebsiteController::class)->handleInquiry($request);
                return $response instanceof Response ? $response : response($response);
            }
        }

        return $next($request);
    }
}
