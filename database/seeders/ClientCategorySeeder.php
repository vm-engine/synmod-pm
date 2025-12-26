<?php

declare(strict_types=1);

namespace VmEngine\Pm\Seeders;

use Illuminate\Database\Seeder;
use VmEngine\Pm\Models\ClientCategory;

class ClientCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'id' => 1,
                'name' => 'Dinas',
                'description' => 'Government institutions and agencies',
            ],
            [
                'id' => 2,
                'name' => 'BUMN',
                'description' => 'State-owned enterprises',
            ],
            [
                'id' => 3,
                'name' => 'Perusahaan Swasta',
                'description' => 'Private companies and corporations',
            ],
            [
                'id' => 4,
                'name' => 'Kementerian',
                'description' => 'Ministries and governmental departments',
            ],
            [
                'id' => 5,
                'name' => 'Personal',
                'description' => 'Individual clients and freelancers',
            ],
            [
                'id' => 6,
                'name' => 'Yayasan/Lembaga',
                'description' => 'Foundations and non-profit organizations',
            ],
            [
                'id' => 7,
                'name' => 'Instansi Pendidikan',
                'description' => 'Educational institutions and schools',
            ],
            [
                'id' => 8,
                'name' => 'Koperasi',
                'description' => 'Cooperative organizations',
            ],
            [
                'id' => 9,
                'name' => 'Other',
                'description' => 'Other types of clients',
            ],
        ];

        foreach ($categories as $category) {
            ClientCategory::firstOrCreate(
                ['id' => $category['id']],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                    'created_by' => 1,
                    'updated_by' => null,
                ]
            );
        }
    }
}