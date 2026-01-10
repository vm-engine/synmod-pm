<?php

declare(strict_types=1);

namespace VmEngine\Pm\Seeders;

use Illuminate\Database\Seeder;
use VmEngine\Pm\Models\ProjectReceipt;

class ProjectReceiptSeeder extends Seeder
{
    public function run(): void
    {
        ProjectReceipt::factory()
            ->count(30)
            ->create();
    }
}
