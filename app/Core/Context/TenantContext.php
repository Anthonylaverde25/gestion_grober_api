<?php

namespace App\Core\Context;

class TenantContext
{
    private static ?string $companyId = null;
    private static bool $isGlobal = false;
    private static ?string $role = null;

    public static function setCompanyId(?string $companyId): void
    {
        self::$companyId = $companyId;
    }

    public static function getCompanyId(): ?string
    {
        return self::$companyId;
    }

    public static function setGlobal(bool $isGlobal): void
    {
        self::$isGlobal = $isGlobal;
    }

    public static function isGlobalContext(): bool
    {
        return self::$isGlobal;
    }

    public static function setRole(?string $role): void
    {
        self::$role = $role;
    }

    public static function getRole(): ?string
    {
        return self::$role;
    }
}
