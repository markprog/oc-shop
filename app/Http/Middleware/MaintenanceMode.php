<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MaintenanceMode
{
    public function handle(Request $request, Closure $next): Response
    {
        $maintenanceEnabled = (bool) Setting::get('config_maintenance', false);

        if ($maintenanceEnabled) {
            // Allow admin users through
            if (auth('admin')->check()) {
                return $next($request);
            }

            // Allow the maintenance page itself
            if ($request->routeIs('maintenance')) {
                return $next($request);
            }

            return response()->view('storefront.maintenance', [], 503);
        }

        return $next($request);
    }
}
