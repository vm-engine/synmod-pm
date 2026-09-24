<?php

declare(strict_types=1);

namespace VmEngine\Pm\Models\Concerns;

use VmEngine\SynAuth\Models\User;

/**
 * Resolves the app's user model from config (SynAuth compatibility) while
 * giving static analysis a concrete class for relation generics.
 */
trait ResolvesUserModel
{
    /**
     * @return class-string<User>
     */
    protected static function userModel(): string
    {
        return config('auth.providers.users.model');
    }
}
