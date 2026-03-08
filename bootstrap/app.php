<?php

use App\Http\Middleware\BootstrapCurrency;
use App\Http\Middleware\BootstrapCustomer;
use App\Http\Middleware\CheckAdminPermission;
use App\Http\Middleware\MaintenanceMode;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            \Illuminate\Support\Facades\Route::middleware('web')
                ->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Global storefront middleware
        $middleware->web(append: [
            BootstrapCurrency::class,
            BootstrapCustomer::class,
            MaintenanceMode::class,
        ]);

        // Named middleware aliases
        $middleware->alias([
            'admin.permission' => CheckAdminPermission::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->withProviders([
        App\Providers\EventServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
    ])
    ->create();
