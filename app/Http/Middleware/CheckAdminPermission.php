<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminPermission
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth('admin')->user();

        if (!$user) {
            return redirect()->route('admin.login');
        }

        $routeName = $request->route()?->getName();

        if ($routeName && $user->group) {
            $permission = $user->group->permission ?? ['access' => [], 'modify' => []];

            // Skip permission check for dashboard
            if ($routeName === 'admin.dashboard') {
                return $next($request);
            }

            // Check access permission for GET requests
            if ($request->isMethod('GET')) {
                $hasAccess = in_array($routeName, $permission['access'] ?? []);
                if (!$hasAccess) {
                    abort(403, 'Access denied: ' . $routeName);
                }
            }

            // Check modify permission for mutating requests
            if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
                $hasModify = in_array($routeName, $permission['modify'] ?? []);
                if (!$hasModify) {
                    abort(403, 'Modify access denied: ' . $routeName);
                }
            }
        }

        return $next($request);
    }
}
