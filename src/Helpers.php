<?php


use TenantScope\TenantContext;

if (!function_exists('tenant_id')) {
    function tenant_id(): ?string
    {
        return TenantContext::getTenantId();
    }
}
