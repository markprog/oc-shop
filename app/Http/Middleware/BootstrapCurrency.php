<?php

namespace App\Http\Middleware;

use App\Services\CurrencyService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BootstrapCurrency
{
    public function __construct(private CurrencyService $currency)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $this->currency->boot();

        return $next($request);
    }
}
