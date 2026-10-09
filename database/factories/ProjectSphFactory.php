<?php

declare(strict_types=1);

namespace VmEngine\Pm\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use VmEngine\Pm\Enums\ProposalStatus;
use VmEngine\Pm\Enums\ProposalType;
use VmEngine\Pm\Models\DocumentIssuer;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\ProjectSph;
use VmEngine\Pm\Models\Signature;
use VmEngine\Pm\Services\Proposal\ProposalDefaults;

/**
 * @extends Factory<ProjectSph>
 */
class ProjectSphFactory extends Factory
{
    protected $model = ProjectSph::class;

    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'status' => ProposalStatus::Draft,
            'sph_date' => '2026-10-07',
            'subject' => 'Surat Penawaran Harga',
            'attachment_label' => '1 (Satu) Set',
            'recipient_name' => 'Bapak/Ibu Pimpinan',
            'recipient_title' => '',
            'recipient_company' => 'PT Cahaya Arif Abadi',
            'recipient_address' => "Jl. Tebet Barat X No. 33, Tebet\nJakarta Selatan 12810",
            'need' => 'Pengembangan Aplikasi ERP – CAA Telco',
            'issuer_id' => DocumentIssuer::factory(),
            'signature_id' => Signature::factory(),
            'has_signature' => false,
            'items' => [
                ['id' => 'i1', 'name' => 'Pembuatan Log Aplikasi', 'description' => "- auth_events\n  - login\n  - logout\n- **activity_logs**", 'duration' => '15 Hari Kerja', 'price' => 15000000.0],
                ['id' => 'i2', 'name' => 'Modul Account Management', 'description' => "Modul untuk admin.\n\n1. Block\n2. Unblock", 'duration' => '', 'price' => 5000000.0],
            ],
            'show_duration' => true,
            'tax_note' => true,
            'payment_enabled' => true,
            'dp_percentage' => 30,
            // Application defaults from 19/01/26: 88 working days (see tests/Unit/Proposal/Fixtures.php)
            'timeline' => ['start_date' => '2026-01-19', 'extra_holidays' => [], 'worked_holidays' => [], 'tasks' => ProposalDefaults::for(ProposalType::Application)['timeline_tasks']],
            'show_timeline_summary' => true,
            'show_timeplan' => true,
            'attachments' => [],
        ];
    }
}
