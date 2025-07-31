<?php

// src/Traits/BelongsToTenant.php
namespace TenantScope\Traits;

use TenantScope\Scopes\TenantScope;
use TenantScope\TenantContext;

trait BelongsToTenant
{
    public static function bootBelongsToTenant(): void
    {
        static::addGlobalScope(new TenantScope);

        static::creating(function ($model) {
            $model->tenant_id = $model->tenant_id ?? TenantContext::getTenantId();
        });
    }

    public function scopeWithoutTenant($query)
    {
        return $query->withoutGlobalScope(TenantScope::class);
    }
}
