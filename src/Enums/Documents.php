<?php

declare(strict_types=1);

namespace VmEngine\Pm\Enums;

use VmEngine\Pm\Services\Documents\AccountInformation;
use VmEngine\Pm\Services\Documents\BackupRequest;
use VmEngine\Pm\Services\Documents\Base;
use VmEngine\Pm\Services\Documents\Bast;
use VmEngine\Pm\Services\Documents\BeritaAcara;
use VmEngine\Pm\Services\Documents\Blueprint;
use VmEngine\Pm\Services\Documents\ChangeRequest;
use VmEngine\Pm\Services\Documents\Invoice;
use VmEngine\Pm\Services\Documents\Panduan;
use VmEngine\Pm\Services\Documents\Proposal;
use VmEngine\Pm\Services\Documents\Receipt;
use VmEngine\Pm\Services\Documents\Sow;
use VmEngine\Pm\Services\Documents\Sph;
use VmEngine\Pm\Services\Documents\Spk;
use VmEngine\Pm\Services\Documents\TaxInvoice;
use VmEngine\Pm\Services\Documents\Tor;
use VmEngine\Pm\Services\Documents\Uat;
use VmEngine\Pm\Services\Documents\UseCase;

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
    case IA = 16;
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
            Documents::IA => 'Informasi Akun',
            Documents::BAST => 'Berita Acara Serah Terima (BAST)',
            Documents::PANDUAN => 'Panduan Penggunaan',
            Documents::CHANGE_REQUEST => 'Change Request Document',
            Documents::BACKUP => 'Backup Source Code',
            Documents::UAT => 'User Acceptance Test (UAT)',
            Documents::BA => 'Berita Acara (BA)',
            Documents::SPH => 'Surat Penawaran Harga (SPH)',
        };
    }

    public function code(bool $lowercase = false): string
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
            Documents::IA => 'IA',
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
            Documents::IA,
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
            Documents::PROPOSAL => app(Proposal::class),
            Documents::SPH => app(Sph::class),
            Documents::SPK => app(Spk::class),
            Documents::SOW => app(Sow::class),
            Documents::TOR => app(Tor::class),
            Documents::USE_CASE => app(UseCase::class),
            Documents::BLUEPRINT => app(Blueprint::class),
            Documents::INVOICE => app(Invoice::class),
            Documents::RECEIPT => app(Receipt::class),
            Documents::TAX_INVOICE => app(TaxInvoice::class),
            Documents::IA => app(AccountInformation::class),
            Documents::BAST => app(Bast::class),
            Documents::PANDUAN => app(Panduan::class),
            Documents::CHANGE_REQUEST => app(ChangeRequest::class),
            Documents::BACKUP => app(BackupRequest::class),
            Documents::UAT => app(Uat::class),
            Documents::BA => app(BeritaAcara::class),
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
            Documents::IA => 'red',
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
            Documents::IA => 'fa-user',
            Documents::BAST => 'fa-file-signature',
            Documents::PANDUAN => 'fa-book',
            Documents::CHANGE_REQUEST => 'fa-code-pull-request',
            Documents::BACKUP => 'fa-database',
            Documents::UAT => 'fa-clipboard-check',
            Documents::BA => 'fa-file-pen',
        };
    }
}
