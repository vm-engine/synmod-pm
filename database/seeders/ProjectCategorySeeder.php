<?php

declare(strict_types=1);

namespace VmEngine\Pm\Seeders;

use Illuminate\Database\Seeder;
use VmEngine\Pm\Models\ProjectCategory;

class ProjectCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['id' => 4, 'name' => 'Web Compro', 'code' => 'WEB', 'description' => 'Company profile website development'],
            ['id' => 5, 'name' => 'Web-based Application', 'code' => 'AW', 'description' => 'Web-based application development'],
            ['id' => 6, 'name' => 'Web-based Application + Mobile', 'code' => 'AWM', 'description' => 'Web-based application with mobile support'],
            ['id' => 7, 'name' => 'Mobile Application', 'code' => 'AM', 'description' => 'Mobile app development (iOS/Android)'],
            ['id' => 9, 'name' => 'General', 'code' => 'UM', 'description' => 'General Project'],
            ['id' => 10, 'name' => 'Maintenance & Support', 'code' => 'MS', 'description' => 'Ongoing maintenance and support services'],
            ['id' => 11, 'name' => 'Digital Marketing', 'code' => 'DM', 'description' => 'Digital marketing services including SEO, SEM, social media marketing'],
            ['id' => 12, 'name' => 'Website Redesign, Hosting, and Maintenance', 'code' => 'RHMS', 'description' => 'Website redesign, hosting, and maintenance services'],
        ];

        foreach ($categories as $category) {
            ProjectCategory::firstOrCreate(
                ['code' => $category['code']],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                ]
            );
        }
    }
}
