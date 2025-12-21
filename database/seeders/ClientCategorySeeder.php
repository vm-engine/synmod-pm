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
                'name' => 'Government',
                'description' => 'Government institutions and agencies',
            ],
            [
                'id' => 2,
                'name' => 'Private Sector',
                'description' => 'Private companies and corporations',
            ],
            [
                'id' => 3,
                'name' => 'Non-Profit',
                'description' => 'Non-profit organizations and foundations',
            ],
            [
                'id' => 4,
                'name' => 'Education',
                'description' => 'Educational institutions (schools, universities, etc.)',
            ],
            [
                'id' => 5,
                'name' => 'Healthcare',
                'description' => 'Hospitals, clinics, and healthcare providers',
            ],
            [
                'id' => 6,
                'name' => 'Financial Services',
                'description' => 'Banks, insurance companies, and financial institutions',
            ],
            [
                'id' => 7,
                'name' => 'Retail',
                'description' => 'Retail businesses and e-commerce companies',
            ],
            [
                'id' => 8,
                'name' => 'Technology',
                'description' => 'Technology companies and startups',
            ],
            [
                'id' => 9,
                'name' => 'SME',
                'description' => 'Small and Medium Enterprises',
            ],
            [
                'id' => 10,
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
