<?php

namespace VmEngine\Pm\Enums;

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
    case BAST = 9;
    case PANDUAN = 10;
    case CHANGE_REQUEST = 11;
    case BACKUP = 12;
    case UAT = 13;
    case BA = 14;

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
}
