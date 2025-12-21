<?php

declare(strict_types=1);

namespace VmEngine\Pm\Seeders;

use Illuminate\Database\Seeder;
use VmEngine\Pm\Models\SubDivision;

class SubDivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subDivisions = [
            // Division 1: Project
            ['id' => 1, 'division_id' => 1, 'name' => 'Corporate Website'],
            ['id' => 2, 'division_id' => 1, 'name' => 'Customize Website'],
            ['id' => 3, 'division_id' => 1, 'name' => 'Web Base Application'],
            ['id' => 4, 'division_id' => 1, 'name' => 'Mobile Application'],

            // Division 2: Product
            ['id' => 5, 'division_id' => 2, 'name' => 'Smartcoop'],
            ['id' => 6, 'division_id' => 2, 'name' => 'ProBumdes'],
            ['id' => 7, 'division_id' => 2, 'name' => 'E-GOVline'],

            // Division 3: Digital Marketing
            ['id' => 8, 'division_id' => 3, 'name' => 'Maintenance Website'],
            ['id' => 9, 'division_id' => 3, 'name' => 'Admin / Updating Website'],
            ['id' => 10, 'division_id' => 3, 'name' => 'SEO'],
            ['id' => 11, 'division_id' => 3, 'name' => 'SMO'],
            ['id' => 12, 'division_id' => 3, 'name' => 'MPO'],
            ['id' => 13, 'division_id' => 3, 'name' => 'Digital Advetising'],
            ['id' => 14, 'division_id' => 3, 'name' => 'Customer Service'],

            // Division 4: 4VM Studio
            ['id' => 15, 'division_id' => 4, 'name' => 'Design Logo, Stationary, Marketing Tools'],
            ['id' => 16, 'division_id' => 4, 'name' => 'Design Company Profile Cetak'],
            ['id' => 17, 'division_id' => 4, 'name' => 'Foto Produk'],
            ['id' => 18, 'division_id' => 4, 'name' => 'Foto Corporate Profile'],
            ['id' => 19, 'division_id' => 4, 'name' => 'Video Corporate Profile'],

            // Division 5: 4VM Cloud Services
            ['id' => 20, 'division_id' => 5, 'name' => 'Kebutuhan Sewa Server'],
            ['id' => 21, 'division_id' => 5, 'name' => 'Security Server'],
            ['id' => 22, 'division_id' => 5, 'name' => 'Pembelian domain'],
        ];

        foreach ($subDivisions as $subDivision) {
            SubDivision::firstOrCreate(
                ['id' => $subDivision['id']],
                [
                    'division_id' => $subDivision['division_id'],
                    'name' => $subDivision['name'],
                    'created_by' => 1,
                    'updated_by' => null,
                ]
            );
        }
    }
}
