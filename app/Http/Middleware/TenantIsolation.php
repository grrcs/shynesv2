<?php

namespace App\Http\Middleware;

use App\Services\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TenantIsolation
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            Log::warning('Tenant isolation blocked unauthenticated access', [
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
            ]);

            return redirect()->route('login');
        }

        // Admin can access all tenants - bypass isolation
        if ($user->role === 'admin') {
            TenantContext::set('admin');
            return $next($request);
        }

        $tenantId = $user->tenant_id;

        if (!$tenantId) {
            Log::warning('Tenant isolation blocked user without tenant context', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip(),
            ]);

            abort(403, 'No tenant context assigned to your account');
        }

        TenantContext::set($tenantId);

        Log::info('Tenant access granted', [
            'user_id' => $user->id,
            'tenant_id' => $tenantId,
            'url' => $request->fullUrl(),
        ]);

        return $next($request);
    }
}
