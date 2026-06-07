<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class TenantContext
{
    private static ?string $tenantId = null;

    public static function get(): ?string
    {
        return static::$tenantId;
    }

    public static function set(string $tenantId): void
    {
        static::$tenantId = $tenantId;
    }

    public static function has(): bool
    {
        return static::$tenantId !== null;
    }

    public static function clear(): void
    {
        static::$tenantId = null;
    }
}
