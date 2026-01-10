<?php

declare(strict_types=1);

namespace VmEngine\Pm\Seeders;

use Illuminate\Database\Seeder;
use VmEngine\Pm\Models\Signature;

class SignatureSeeder extends Seeder
{
    public function run(): void
    {
        Signature::factory()
            ->count(8)
            ->create();
    }
}
