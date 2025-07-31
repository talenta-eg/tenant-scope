<?php

namespace TenantScope\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use TenantScope\TenantContext;

class SetTenantContext
{
    public function handle(Request $request, Closure $next)
    {
        if (!Config::get('tenant-scope.enabled', false)) {
            return $next($request);
        }

        $tenantId = null;
        $source = Config::get('tenant-scope.source', 'header');

        if ($source === 'header') {
            $headerKey = Config::get('tenant-scope.header_key', 'X-Tenant-ID');

            if(!$request->hasHeader($headerKey))
                return response()->json([
                'success' => false,
                'message' => 'Header '.$headerKey.' not found.'
                ],400);

            $tenantId = $request->header($headerKey);
        }

        if ($source === 'jwt') {
            $jwtClaimKey = Config::get('tenant-scope.jwt_claim_key', 'tenant_id');
            $token = $request->bearerToken();

            if ($token) {
                try {
                    $payload = $this->decodeJwt($token);
                    $tenantId = $payload[$jwtClaimKey] ?? null;
                } catch (\Exception $e) {
                    Log::warning('Failed to decode JWT while resolving tenant ID', ['error' => $e->getMessage()]);
                }
            }
        }

        if (! $tenantId) {
         return  response()->json(['success' => false, 'message' => 'Tenant ID not found.'],400);
        }

        // Set tenant in context
        TenantContext::setTenantId($tenantId);

        return $next($request);
    }

    protected function decodeJwt(string $token): array
    {
        // Note: You can replace this with a proper JWT library like lcobucci/jwt or firebase/php-jwt
        [$header, $payload, $signature] = explode('.', $token);
        return json_decode(base64_decode(strtr($payload, '-_', '+/')), true);
    }
}
