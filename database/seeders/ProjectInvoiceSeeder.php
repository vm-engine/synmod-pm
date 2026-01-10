<?php

declare(strict_types=1);

namespace VmEngine\Pm\Seeders;

use Illuminate\Database\Seeder;
use VmEngine\Pm\Models\ProjectInvoice;

class ProjectInvoiceSeeder extends Seeder
{
    public function run(): void
    {
        ProjectInvoice::factory()
            ->count(20)
            ->create();
    }
}
