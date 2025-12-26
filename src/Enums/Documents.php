<?php

namespace VmEngine\Pm\Enums;

enum Documents: int
{
    case PROPOSAL = 0;
    case SPK = 1;
    case SOW = 2;
    case TOR = 3;
    case USE_CASE = 4;
    case BLUEPRINT = 5;
    case INVOICE = 6;
    case RECEIPT = 7;
    case BAST = 8;
    case PANDUAN = 9;
    case CHANGE_REQUEST = 10;
    case BACKUP = 11;
    case UAT = 12;
    case BA = 13;
    case SPH = 14;

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

    public function code(): string
    {
        return match ($this) {
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
    }
}
