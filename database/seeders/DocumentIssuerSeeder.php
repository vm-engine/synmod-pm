<?php

declare(strict_types=1);

namespace VmEngine\Pm\Seeders;

use Illuminate\Database\Seeder;
use VmEngine\Pm\Models\DocumentIssuer;

class DocumentIssuerSeeder extends Seeder
{
    public function run(): void
    {
        DocumentIssuer::factory()
            ->count(5)
            ->create();
    }
}
