<?php

declare(strict_types=1);

namespace VmEngine\Pm\Seeders;

use Illuminate\Database\Seeder;
use VmEngine\Pm\Models\Division;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisions = [
            ['id' => 1, 'name' => 'Project'],
            ['id' => 2, 'name' => 'Product'],
            ['id' => 3, 'name' => 'Digital Marketing'],
            ['id' => 4, 'name' => '4VM Studio'],
            ['id' => 5, 'name' => '4VM Cloud Services'],
            ['id' => 6, 'name' => 'Training & Consultancy'],
        ];

        foreach ($divisions as $division) {
            Division::firstOrCreate(
                ['id' => $division['id']],
                [
                    'name' => $division['name'],
                    'created_by' => 1,
                    'updated_by' => null,
                ]
            );
        }
    }
}
