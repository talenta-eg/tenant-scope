<?php

// src/TenantContext.php
namespace TenantScope;

class TenantContext
{
    protected static ?string $tenantId = null;

    public static function setTenantId(string $tenantId): void
    {
        static::$tenantId = $tenantId;
    }
    public static function getTenantId(): ?string
    {
        return static::$tenantId;
    }

    public static function forget(): void
    {
        static::$tenantId = null;
    }
}
