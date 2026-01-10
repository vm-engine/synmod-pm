<?php

declare(strict_types=1);

namespace VmEngine\Pm\Seeders;

use Illuminate\Database\Seeder;
use VmEngine\Pm\Models\BankAccount;

class BankAccountSeeder extends Seeder
{
    public function run(): void
    {
        BankAccount::factory()
            ->count(10)
            ->create();
    }
}
