<?php

namespace Spatie\Multitenancy\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response;

class EnsureValidTenantSession
{
    public function handle($request, Closure $next)
    {
        if (! $request->session()->has('tenant_id')) {
            $request->session()->put('tenant_id', app('current_tenant')->id);

            return $next($request);
        }

        if ($request->session()->get('tenant_id') !== app('current_tenant')->id) {
            return $this->handleInvalidTenantSession($request);
        }

        return $next($request);
    }

    protected function handleInvalidTenantSession($request)
    {
        abort(Response::HTTP_UNAUTHORIZED);
    }
}
