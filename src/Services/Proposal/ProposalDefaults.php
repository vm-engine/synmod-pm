<?php

declare(strict_types=1);

namespace VmEngine\Pm\Services\Proposal;

use VmEngine\Pm\Enums\ProposalType;

/**
 * Factory defaults copied from the reference decks (Think = website, PT ABS = application).
 * Used to seed `proposal_type_defaults` and as a fallback when that row is missing.
 */
final class ProposalDefaults
{
    /**
     * @return array{costing: list<array<string, mixed>>, hosting: list<array<string, mixed>>, timeline_tasks: list<array<string, mixed>>, deliverables: list<array<string, mixed>>, after_sales: list<array<string, mixed>>}
     */
    public static function for(ProposalType $type): array
    {
        return $type === ProposalType::Website ? self::website() : self::application();
    }

    /**
     * @return list<array{name: string, description: string, default_checked: bool, sort: int}>
     */
    public static function featureItems(): array
    {
        $items = [
            ['Modul Static Page', 'Modul untuk manajemen konten statis, seperti halaman: About Us, Vision & Mission.'],
            ['Modul Dynamic Page', 'Modul untuk manajemen konten dinamis, seperti halaman: Policy, News.'],
            ['Modul Gallery', 'Modul untuk manajemen konten foto maupun video.'],
            ['Modul Inquiry', 'Modul untuk manajemen konten contact form, seperti halaman: Contact Us yang berisi informasi alamat perusahaan, nomor telepon, alamat email (Map Location) dan form kirim pesan.'],
            ['Modul Weblink', 'Modul untuk manajemen konten link ke website mitra atau customer yang memiliki kerjasama Eksklusif (Logo/Text).'],
            ['Manajemen User', 'Konfigurasi untuk manajemen user pengelola web (halaman admin panel), seperti administrator maupun operator.'],
            ['Social Media Configuration', 'Konfigurasi untuk manajemen link ke social media official perusahaan.'],
            ['Fitur Multi-Bahasa', 'Fitur untuk pengelolaan bahasa di website (EN/ID).'],
        ];

        return array_map(
            fn (array $item, int $index): array => [
                'name' => $item[0],
                'description' => $item[1],
                'default_checked' => true,
                'sort' => ($index + 1) * 10,
            ],
            $items,
            array_keys($items),
        );
    }

    /**
     * @return array{costing: list<array<string, mixed>>, hosting: list<array<string, mixed>>, timeline_tasks: list<array<string, mixed>>, deliverables: list<array<string, mixed>>, after_sales: list<array<string, mixed>>}
     */
    private static function website(): array
    {
        return [
            'costing' => [
                ['id' => 'c1', 'section' => 'Build System', 'features' => [
                    'PHP - Laravel framework',
                    'Custom Design (HTML/CSS/VueJS)',
                    'Content Management System, Administrator Page, Operator Page, Database Setup',
                    'Home, Page, Detail Page',
                ], 'price' => 15000000],
            ],
            'hosting' => [
                ['id' => 'h1', 'name' => 'Shared Hosting', 'specs' => ['Storage: 8 GB', 'Bandwidth: Unlimited'], 'price' => 6000000, 'period' => 'tahun', 'optional' => true, 'start_from' => false],
                ['id' => 'h2', 'name' => 'VPS Hosting', 'specs' => ['RAM: 1 GB', 'vCPU: 1 Core', 'Storage: 20 GB SSD', 'Public IP: 1', 'Lisensi cPanel: 1'], 'price' => 8400000, 'period' => 'tahun', 'optional' => true, 'start_from' => false],
                ['id' => 'h3', 'name' => 'Domain Name', 'specs' => ['Top Level Domain .org'], 'price' => 450000, 'period' => 'tahun', 'optional' => true, 'start_from' => false],
                ['id' => 'h4', 'name' => 'Domain Name', 'specs' => ['Top Level Domain .id'], 'price' => 500000, 'period' => 'tahun', 'optional' => true, 'start_from' => false],
            ],
            'timeline_tasks' => [
                ['id' => 't1', 'name' => 'Kick of Meeting', 'parent_id' => null, 'duration' => 1, 'predecessor_id' => null],
                ['id' => 't2', 'name' => 'Requirement Gathering / Planning', 'parent_id' => null, 'duration' => 1, 'predecessor_id' => 't1'],
                ['id' => 't3', 'name' => 'Analysis', 'parent_id' => null, 'duration' => 1, 'predecessor_id' => 't2'],
                ['id' => 't4', 'name' => 'Design (UI/UX)', 'parent_id' => null, 'duration' => 1, 'predecessor_id' => 't3'],
                ['id' => 't5', 'name' => 'Home page', 'parent_id' => 't4', 'duration' => 5, 'predecessor_id' => null],
                ['id' => 't6', 'name' => 'Other Page', 'parent_id' => 't4', 'duration' => 6, 'predecessor_id' => 't5'],
                ['id' => 't7', 'name' => 'Development (Programming) / Implementation', 'parent_id' => null, 'duration' => 6, 'predecessor_id' => 't4'],
                ['id' => 't8', 'name' => 'Testing & Bug Fixing (local dev.)', 'parent_id' => null, 'duration' => 1, 'predecessor_id' => 't7'],
                ['id' => 't9', 'name' => 'Staging Deployment', 'parent_id' => null, 'duration' => 1, 'predecessor_id' => 't8'],
                ['id' => 't10', 'name' => 'Dev Site', 'parent_id' => 't9', 'duration' => 1, 'predecessor_id' => null],
                ['id' => 't11', 'name' => 'Production Site', 'parent_id' => 't9', 'duration' => 1, 'predecessor_id' => 't10'],
            ],
            'deliverables' => [
                ['id' => 'd1', 'item' => 'Website versi web browser', 'description' => ''],
                ['id' => 'd2', 'item' => 'Website versi mobile browser (mobile responsive)', 'description' => ''],
                ['id' => 'd3', 'item' => 'CMS (Content Management System)', 'description' => 'Halaman Admin multi level user'],
                ['id' => 'd4', 'item' => 'Multi-language System', 'description' => 'Mendukung 2 versi Bahasa (EN/ID)'],
                ['id' => 'd5', 'item' => 'Maintenance Support', 'description' => 'Layanan perawatan purna jual {months} bulan (free maintenance)'],
                ['id' => 'd6', 'item' => 'Content (text/image) Optimization + Keyword suggestion', 'description' => 'Konten awal dari pihak pemilik website, selanjutnya akan dilakukan penyesuaian agar optimal di search engine'],
                ['id' => 'd7', 'item' => 'Modul Penggunaan/User manual guide', 'description' => ''],
                ['id' => 'd8', 'item' => 'Konsultasi teknis & optimalisasi search engine', 'description' => ''],
                ['id' => 'd9', 'item' => 'Setting Google Webmaster Tools & Google Analytics', 'description' => ''],
            ],
            'after_sales' => [
                ['id' => 'a1', 'label' => 'Free Maintenance Service {months} Bulan'],
                ['id' => 'a2', 'label' => 'Training Administrator (Online)'],
                ['id' => 'a3', 'label' => 'Manual Guide Administrator (PDF/Printed)'],
                ['id' => 'a4', 'label' => 'SEO on Page Activity (Consultancy)'],
                ['id' => 'a5', 'label' => 'Bug Fixing Error'],
            ],
        ];
    }

    /**
     * @return array{costing: list<array<string, mixed>>, hosting: list<array<string, mixed>>, timeline_tasks: list<array<string, mixed>>, deliverables: list<array<string, mixed>>, after_sales: list<array<string, mixed>>}
     */
    private static function application(): array
    {
        return [
            'costing' => [
                ['id' => 'c1', 'section' => 'Build System', 'features' => [
                    'PHP - Laravel framework',
                    'Content Management System, Administrator Page, Operator Page, Database Setup',
                ], 'price' => 60000000],
                ['id' => 'c2', 'section' => 'Customized Module & Feature', 'features' => [
                    'Master Data',
                    'Modul sesuai Feature & Module',
                    'Report',
                ], 'price' => 160000000],
            ],
            'hosting' => [
                ['id' => 'h1', 'name' => 'VPS Hosting', 'specs' => ['RAM: 4 GB', 'vCPU: 4 Core', 'Storage: 70 GB (Full SSD)', 'Public IP: 1', 'Lisensi cPanel: 1'], 'price' => 18000000, 'period' => 'tahun', 'optional' => true, 'start_from' => false],
                ['id' => 'h2', 'name' => 'Domain Name', 'specs' => ['Top level domain: .com'], 'price' => 400000, 'period' => 'tahun', 'optional' => true, 'start_from' => true],
            ],
            'timeline_tasks' => [
                ['id' => 't1', 'name' => 'Kick of Meeting', 'parent_id' => null, 'duration' => 1, 'predecessor_id' => null],
                ['id' => 't2', 'name' => 'Requirement Gathering / Planning', 'parent_id' => null, 'duration' => 4, 'predecessor_id' => 't1'],
                ['id' => 't3', 'name' => 'Analysis', 'parent_id' => null, 'duration' => 10, 'predecessor_id' => 't2'],
                ['id' => 't4', 'name' => 'Design (UI/UX)', 'parent_id' => null, 'duration' => 5, 'predecessor_id' => 't3'],
                ['id' => 't5', 'name' => 'Development (Programming) / Implementation', 'parent_id' => null, 'duration' => 60, 'predecessor_id' => 't4'],
                ['id' => 't6', 'name' => 'Test & Bug Fix (local)', 'parent_id' => null, 'duration' => 5, 'predecessor_id' => 't5'],
                ['id' => 't7', 'name' => 'Staging Deployment', 'parent_id' => null, 'duration' => 1, 'predecessor_id' => 't6'],
                ['id' => 't8', 'name' => 'Dev Site', 'parent_id' => 't7', 'duration' => 2, 'predecessor_id' => null],
                ['id' => 't9', 'name' => 'Production', 'parent_id' => 't7', 'duration' => 1, 'predecessor_id' => 't8'],
            ],
            'deliverables' => [
                ['id' => 'd1', 'item' => 'Aplikasi versi web browser', 'description' => ''],
                ['id' => 'd2', 'item' => 'Aplikasi versi mobile browser (mobile responsive)', 'description' => ''],
                ['id' => 'd3', 'item' => 'User access control', 'description' => 'Halaman Admin Panel untuk mengelola data pengguna maupun produk'],
                ['id' => 'd4', 'item' => 'Maintenance Support', 'description' => 'Layanan dukungan {months} bulan (free maintenance)'],
                ['id' => 'd5', 'item' => 'Dokumen Panduan Penggunaan/User manual guide', 'description' => 'Pdf/printed doc.'],
            ],
            'after_sales' => [
                ['id' => 'a1', 'label' => 'Free Maintenance Service {months} Bulan'],
                ['id' => 'a2', 'label' => 'Training Administrator (Online)'],
                ['id' => 'a3', 'label' => 'Manual Guide Administrator (PDF/Printed)'],
                ['id' => 'a4', 'label' => 'Error/Bug Fix'],
            ],
        ];
    }
}
