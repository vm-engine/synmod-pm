<?php

declare(strict_types=1);

namespace VmEngine\Pm\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use VmEngine\Pm\Models\Client;
use VmEngine\SynAuth\Models\User;

class ClientUserService
{
    /**
     * Create a new user account for a client.
     *
     * @param  array<string, mixed>  $userData
     */
    public function createUser(array $userData, int $status, bool $active): User
    {
        /** @var class-string<User> $UserModel */
        $UserModel = config('auth.providers.users.model');

        // Determine user active status based on client status and active flag
        $userStatus = ($status === Client::STATUS_ACTIVE && $active) ? 1 : 0;

        $user = $UserModel::create([
            'name' => $userData['name'],
            'username' => $userData['username'] ?? null,
            'email' => $userData['email'],
            'password' => Hash::make($userData['password']),
            'status' => $userStatus,
        ]);

        // Set email as verified (not in fillable array)
        $user->email_verified_at = now();
        $user->save();

        // Assign client role
        $this->assignClientRole($user);

        return $user;
    }

    /**
     * Update an existing user account for a client.
     *
     * @param  array<string, mixed>  $userData
     */
    public function updateUser(Model $user, array $userData, int $status, bool $active): void
    {
        // Determine user active status based on client status and active flag
        $userStatus = ($status === Client::STATUS_ACTIVE && $active) ? 1 : 0;

        $updateData = [
            'name' => $userData['name'],
            'username' => $userData['username'] ?? null,
            'email' => $userData['email'],
            'status' => $userStatus,
        ];

        // Only update password if provided
        if (! empty($userData['password'])) {
            $updateData['password'] = Hash::make($userData['password']);
        }

        $user->update($updateData);
    }

    /**
     * Assign client role to a user.
     */
    private function assignClientRole(User $user): void
    {
        $clientRoleId = dbconf('pm.client_role_id');
        if ($clientRoleId && ! $user->roles()->where('role_id', $clientRoleId)->exists()) {
            $user->roles()->attach($clientRoleId);
        }
    }
}
