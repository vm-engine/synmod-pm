<?php

declare(strict_types=1);

use VmEngine\Pm\Seeder\ClientRoleSeeder;
use VmEngine\Pm\Seeders\ClientCategorySeeder;
use VmEngine\Pm\Seeders\DivisionSeeder;
use VmEngine\Pm\Seeders\SubDivisionSeeder;

// Register your seeders here
return [
    ClientRoleSeeder::class,
    ClientCategorySeeder::class,
    DivisionSeeder::class,
    SubDivisionSeeder::class,
];
