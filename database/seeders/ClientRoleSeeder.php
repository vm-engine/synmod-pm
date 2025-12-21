<?php

declare(strict_types=1);

namespace VmEngine\Pm\Seeders;

use Illuminate\Database\Seeder;
use VmEngine\SynAuth\Models\Role;

class ClientRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clientRole = Role::updateOrCreate(
            ['slug' => 'client'],
            [
                'name' => 'Client',
                'level' => 10,
                'description' => 'Role for Mod Project Management Client',
            ]
        );

        dbconf(['pm.client_role_id' => $clientRole->id]);
    }
}
