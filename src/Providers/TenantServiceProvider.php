<?php

namespace TenantScope\Providers;

use Illuminate\Support\ServiceProvider;
use TenantScope\Http\Middleware\SetTenantContext;

class TenantServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app['router']->aliasMiddleware('tenant.context', SetTenantContext::class);

        $this->publishes([
            __DIR__ . '/../config/tenant-scope.php' => config_path('tenant-scope.php'),
        ], 'config');

        require_once __DIR__.'/../Helpers.php';
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/tenant-scope.php', 'tenant-scope');
    }
}
