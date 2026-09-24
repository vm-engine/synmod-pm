<?php

declare(strict_types=1);

namespace VmEngine\Pm\Services\Concerns;

use Illuminate\Support\Facades\Auth;
use VmEngine\SynAuth\Facades\SynAuth;

trait RecordsActivity
{
    /**
     * Log a pm module activity for the current user (null when nobody is authenticated).
     */
    protected function recordActivity(string $action, string $description, string $feature): void
    {
        $userId = Auth::id();

        // The guard types ids as int|string; pm users have integer keys.
        SynAuth::logActivity($userId === null ? null : (int) $userId, $action, $description, $feature, 'pm');
    }
}
