<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        abort_unless($user && $user->is_active, 403, 'Account is disabled or unauthenticated.');

        if ($user->role !== $role) {
            // If an authenticated Bhudev navigates directly to /admin,
            // gracefully redirect them to their dedicated Bhudev portal:
            if ($role === 'admin' && $user->isBhudev() && $request->routeIs('admin.dashboard')) {
                return redirect()->route('bhudev.dashboard')->with('status', 'Welcome to your Bhudev portal.');
            }

            // If an authenticated Admin navigates to /bhudev,
            // gracefully redirect them to the Admin panel:
            if ($role === 'bhudev' && $user->isAdmin() && $request->routeIs('bhudev.dashboard')) {
                return redirect()->route('admin.dashboard');
            }

            abort(403, 'Unauthorized. Access restricted.');
        }

        return $next($request);
    }
}
