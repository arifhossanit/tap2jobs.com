<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminPermission
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        abort_unless($user && $user->hasAnyRole(['Super Admin', 'Admin']), 403);

        if ($user->hasRole('Super Admin')) {
            return $next($request);
        }

        $adminPath = ltrim((string) $request->route()?->uri(), '/');
        $adminPath = str_starts_with($adminPath, 'admin/') ? substr($adminPath, 6) : $adminPath;

        foreach (config('admin_permissions.route_groups', []) as $permission => $patterns) {
            foreach ($patterns as $pattern) {
                if (str_is($pattern, $adminPath)) {
                    abort_unless($user->can($permission), 403);

                    return $next($request);
                }
            }
        }

        // New admin endpoints must be assigned explicitly before regular admins can use them.
        abort(403, 'This admin route has not been assigned to a permission.');
    }
}
