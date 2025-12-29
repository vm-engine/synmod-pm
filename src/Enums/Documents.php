<?php

namespace VmEngine\Pm\Enums;

use VmEngine\Pm\Services\Documents\Base;

enum Documents: int
{
    case PROPOSAL = 0;
    case SPH = 1;
    case SPK = 2;
    case SOW = 3;
    case TOR = 4;
    case USE_CASE = 5;
    case BLUEPRINT = 6;
    case INVOICE = 7;
    case RECEIPT = 8;
    case PANDUAN = 10;
    case CHANGE_REQUEST = 11;
    case BA = 14;
    case UAT = 13;
    case BAST = 9;
    case BACKUP = 12;

    public function label(): string
    {
        return match ($this) {
            Documents::PROPOSAL => 'Proposal',
            Documents::SPK => 'Surat Perjanjian Kerjasama',
            Documents::SOW => 'Scope of Work (SOW)/Kerangka Acuan Kerja (KAK)',
            Documents::TOR => 'Term of Requirment (TOR)',
            Documents::USE_CASE => 'Use Case Document',
            Documents::BLUEPRINT => 'Blueprint',
            Documents::INVOICE => 'Invoice',
            Documents::RECEIPT => 'Kwitansi',
            Documents::BAST => 'Berita Acara Serah Terima (BAST)',
            Documents::PANDUAN => 'Panduan Penggunaan',
            Documents::CHANGE_REQUEST => 'Change Request Document',
            Documents::BACKUP => 'Backup Source Code',
            Documents::UAT => 'User Acceptance Test (UAT)',
            Documents::BA => 'Berita Acara (BA)',
            Documents::SPH => 'Surat Penawaran Harga (SPH)',
        };
    }

    public function code($lowercase = false): string
    {
        $code = match ($this) {
            Documents::PROPOSAL => '',
            Documents::SPK => 'SPK',
            Documents::SOW => 'SOW',
            Documents::TOR => 'TOR',
            Documents::USE_CASE => 'UC',
            Documents::BLUEPRINT => 'BP',
            Documents::INVOICE => 'INV',
            Documents::RECEIPT => 'RCP',
            Documents::BAST => 'BAST',
            Documents::PANDUAN => 'PANDUAN',
            Documents::CHANGE_REQUEST => 'CR',
            Documents::BACKUP => 'BACKUP',
            Documents::UAT => 'UAT',
            Documents::BA => 'BA',
            Documents::SPH => 'SPH',
        };

        return $lowercase ? strtolower($code) : $code;
    }

    public function hasNCIndex(): bool
    {
        return match ($this) {
            Documents::PROPOSAL, Documents::SPH => true,
            default => false,
        };
    }

    public function isMultiple(): bool
    {
        return match ($this) {
            Documents::INVOICE,
            Documents::RECEIPT,
            Documents::BA,
            Documents::CHANGE_REQUEST,
            Documents::BACKUP,
            Documents::UAT => true,
            default => false,
        };
    }

    public function isVersioned(): bool
    {
        return ! $this->isMultiple();
    }

    public function handler(): Base
    {
        return match ($this) {
            Documents::PROPOSAL => app(\VmEngine\Pm\Services\Documents\Proposal::class),
            Documents::SPH => app(\VmEngine\Pm\Services\Documents\Sph::class),
            Documents::SPK => app(\VmEngine\Pm\Services\Documents\Spk::class),
            Documents::SOW => app(\VmEngine\Pm\Services\Documents\Sow::class),
            Documents::TOR => app(\VmEngine\Pm\Services\Documents\Tor::class),
            Documents::USE_CASE => app(\VmEngine\Pm\Services\Documents\UseCase::class),
            Documents::BLUEPRINT => app(\VmEngine\Pm\Services\Documents\Blueprint::class),
            Documents::INVOICE => app(\VmEngine\Pm\Services\Documents\Invoice::class),
            Documents::RECEIPT => app(\VmEngine\Pm\Services\Documents\Receipt::class),
            Documents::BAST => app(\VmEngine\Pm\Services\Documents\Bast::class),
            Documents::PANDUAN => app(\VmEngine\Pm\Services\Documents\Panduan::class),
            Documents::CHANGE_REQUEST => app(\VmEngine\Pm\Services\Documents\ChangeRequest::class),
            Documents::BACKUP => app(\VmEngine\Pm\Services\Documents\BackupRequest::class),
            Documents::UAT => app(\VmEngine\Pm\Services\Documents\Uat::class),
            Documents::BA => app(\VmEngine\Pm\Services\Documents\BeritaAcara::class),
        };
    }
}
