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
    case TAX_INVOICE = 15;
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
            Documents::RECEIPT => 'Kuitansi',
            Documents::TAX_INVOICE => 'Tax Invoice/Faktur Pajak',
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
            Documents::TAX_INVOICE => 'TAX',
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
            Documents::TAX_INVOICE,
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

    public function isReadonly(): bool
    {
        return match ($this) {
            Documents::INVOICE,
            Documents::RECEIPT => true,
            default => false,
        };
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
            Documents::TAX_INVOICE => app(\VmEngine\Pm\Services\Documents\TaxInvoice::class),
            Documents::BAST => app(\VmEngine\Pm\Services\Documents\Bast::class),
            Documents::PANDUAN => app(\VmEngine\Pm\Services\Documents\Panduan::class),
            Documents::CHANGE_REQUEST => app(\VmEngine\Pm\Services\Documents\ChangeRequest::class),
            Documents::BACKUP => app(\VmEngine\Pm\Services\Documents\BackupRequest::class),
            Documents::UAT => app(\VmEngine\Pm\Services\Documents\Uat::class),
            Documents::BA => app(\VmEngine\Pm\Services\Documents\BeritaAcara::class),
        };
    }

    public function color(): string
    {
        return match ($this) {
            Documents::PROPOSAL => 'purple',
            Documents::SPH => 'indigo',
            Documents::SPK => 'blue',
            Documents::SOW => 'cyan',
            Documents::TOR => 'teal',
            Documents::USE_CASE => 'green',
            Documents::BLUEPRINT => 'lime',
            Documents::INVOICE => 'yellow',
            Documents::TAX_INVOICE => 'orange',
            Documents::RECEIPT => 'amber',
            Documents::BAST => 'orange',
            Documents::PANDUAN => 'red',
            Documents::CHANGE_REQUEST => 'pink',
            Documents::BACKUP => 'fuchsia',
            Documents::UAT => 'violet',
            Documents::BA => 'rose',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            Documents::PROPOSAL => 'fa-file-contract',
            Documents::SPH => 'fa-file-invoice-dollar',
            Documents::SPK => 'fa-handshake',
            Documents::SOW => 'fa-file-lines',
            Documents::TOR => 'fa-list-check',
            Documents::USE_CASE => 'fa-diagram-project',
            Documents::BLUEPRINT => 'fa-sitemap',
            Documents::INVOICE => 'fa-file-invoice',
            Documents::RECEIPT => 'fa-receipt',
            Documents::TAX_INVOICE => 'fa-file-invoice-dollar',
            Documents::BAST => 'fa-file-signature',
            Documents::PANDUAN => 'fa-book',
            Documents::CHANGE_REQUEST => 'fa-code-pull-request',
            Documents::BACKUP => 'fa-database',
            Documents::UAT => 'fa-clipboard-check',
            Documents::BA => 'fa-file-pen',
        };
    }
}
