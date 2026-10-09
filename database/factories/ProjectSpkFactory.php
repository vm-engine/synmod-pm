<?php

declare(strict_types=1);

namespace VmEngine\Pm\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use VmEngine\Pm\Enums\ProposalStatus;
use VmEngine\Pm\Models\BankAccount;
use VmEngine\Pm\Models\DocumentIssuer;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\ProjectSpk;
use VmEngine\Pm\Models\Signature;
use VmEngine\Pm\Services\Spk\SpkDefaults;

/**
 * @extends Factory<ProjectSpk>
 */
class ProjectSpkFactory extends Factory
{
    protected $model = ProjectSpk::class;

    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'status' => ProposalStatus::Draft,
            'spk_date' => '2026-02-04',
            'title' => 'Pengembangan Sistem Informasi Perusahaan',
            'party1_company' => 'PT Anugerah Berkat Sarana',
            'party1_address' => "Jl. H. Adam Malik Ruko Graha 99 No.6, Kreo Selatan\nKota Tangerang, Banten 15156",
            'party1_representative' => 'Ronny Lesmana',
            'party1_position' => 'Direktur',
            'party1_description' => 'perusahaan distributor alat kesehatan yang berfokus pada produk intervensi kardiologi',
            'issuer_id' => DocumentIssuer::factory(),
            'signature_id' => Signature::factory(),
            'has_signature' => false,
            'party2_address' => 'Bandung, Jl. Lingkar Bojongsereh No. 80, Desa Lebakwangi, Kecamatan Arjasari, Kabupaten Bandung',
            'party2_description' => 'perusahaan swasta yang bergerak di bidang jasa layanan IT, software development dan Digital Marketing',
            'asas' => SpkDefaults::ASAS,
            'scope' => "- Master Data : Warehouse, Product, Customer\n- Inventory\n- **Customer Order**",
            'reference_document' => 'Proposal Pengembangan Sistem Informasi Perusahaan No. 001/PRJ/VMC/534-NC/I/2026',
            'work_duration' => 88,
            'work_duration_unit' => 'hari kerja',
            'work_start_text' => SpkDefaults::WORK_START,
            'maintenance_months' => 8,
            'duration_notes' => '',
            'obligations_first' => SpkDefaults::OBLIGATIONS_FIRST,
            'obligations_second' => SpkDefaults::OBLIGATIONS_SECOND,
            'contract_value' => 178000000,
            'tax_note' => true,
            'installments' => [
                ['id' => 'n1', 'label' => 'Pembayaran awal (down payment)', 'type' => 'amount', 'value' => 35000000.0, 'count' => 1, 'due' => 'dibayar pada saat Perjanjian ini ditandatangani'],
                ['id' => 'n2', 'label' => 'Sisa pembayaran dibayarkan berkala setiap bulannya', 'type' => 'amount', 'value' => 13000000.0, 'count' => 11, 'due' => 'terhitung sejak bulan Maret 2026'],
            ],
            'payment_notes' => '',
            'bank_account_id' => BankAccount::factory(),
            'server_enabled' => true,
            'server_text' => SpkDefaults::SERVER,
            'confidentiality' => SpkDefaults::CONFIDENTIALITY,
            'dispute' => SpkDefaults::DISPUTE,
            'correspondence_enabled' => true,
            'contacts' => [
                'first' => ['name' => 'Monica Natayo', 'address' => 'Kota Tangerang', 'phone' => '+62 857 7333 1214', 'email' => 'monica@example.com'],
                'second' => ['name' => 'Muhammad Sholeh Effendy', 'address' => 'Kab. Bandung', 'phone' => '+62 852 2008 1718', 'email' => 'sholeh@example.com'],
            ],
            'correspondence_text' => SpkDefaults::CORRESPONDENCE,
            'extra_pasal' => [],
            'misc' => SpkDefaults::MISC,
            'attachments' => [],
        ];
    }
}
