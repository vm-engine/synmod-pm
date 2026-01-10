<?php

declare(strict_types=1);

namespace VmEngine\Pm\Seeders;

use Illuminate\Database\Seeder;
use VmEngine\Pm\Models\ProjectInvoiceItem;

class ProjectInvoiceItemSeeder extends Seeder
{
    public function run(): void
    {
        ProjectInvoiceItem::factory()
            ->count(50)
            ->create();
    }
}
