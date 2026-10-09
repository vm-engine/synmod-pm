<?php

declare(strict_types=1);

namespace VmEngine\Pm\Services\Spk;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Storage;
use VmEngine\Pm\Enums\Documents;
use VmEngine\Pm\Helpers\Terbilang;
use VmEngine\Pm\Models\ProjectSpk;
use VmEngine\Pm\Services\Proposal\ProposalDocumentData;
use VmEngine\Pm\Services\Sph\MarkdownBlocks;
use VmEngine\Pm\Services\Sph\SphDocumentData;

/**
 * Everything the PDF and DOCX renderers need, computed once from an SPK. Every Pasal is a list of parts:
 * Markdown-style blocks or a label/value table (bank account, contacts), so renderers stay generic.
 *
 * @phpstan-import-type Block from MarkdownBlocks
 *
 * @phpstan-type Part array{type: 'blocks', blocks: list<Block>}|array{type: 'table', indent: int, rows: list<array{0: string, 1: string}>}
 * @phpstan-type Pasal array{number: int, title: string, parts: list<Part>}
 * @phpstan-type Signer array{label: string, company: string, name: string, position: string}
 */
final readonly class SpkDocumentData
{
    public const CLOSING = 'Demikian Perjanjian ini dibuat dalam rangkap dua, dan ditandatangani oleh PIHAK PERTAMA dan PIHAK KEDUA di atas meterai cukup, di mana masing-masing mempunyai kekuatan hukum yang sama.';

    /**
     * @param  list<Block>  $parties  numbered party paragraphs
     * @param  list<Block>  $recitals  "Bahwa ..." lines
     * @param  list<Pasal>  $pasal
     * @param  Signer  $firstSigner
     * @param  Signer  $secondSigner
     * @param  list<array{lampiran: int, title: string, path: string}>  $attachments  absolute image paths
     */
    public function __construct(
        public string $number,
        public string $dateLabel,
        public string $title,
        public string $opening,
        public array $parties,
        public array $recitals,
        public array $pasal,
        public array $firstSigner,
        public array $secondSigner,
        public ?string $signaturePath,
        public ?string $headerImagePath,
        public ?string $footerImagePath,
        public array $attachments,
    ) {}

    public static function build(ProjectSpk $spk): self
    {
        $spk->loadMissing('project.client', 'project.projectCategory', 'project.division', 'issuer', 'signature', 'bankAccount');

        $title = trim($spk->title);
        $date = CarbonImmutable::parse($spk->spk_date);
        $company2 = (string) ($spk->issuer->name ?? '');
        $signer2 = (string) ($spk->signature->name ?? '');
        $position2 = (string) ($spk->signature->position ?? '');
        $numbers = self::pasalNumbers($spk->server_enabled, $spk->correspondence_enabled, count($spk->extra_pasal ?? []));
        // A party without a description gets no "Bahwa ..." line
        $recitals = array_values(array_filter([
            trim($spk->party1_description) !== '' ? 'Bahwa PIHAK PERTAMA adalah '.rtrim(trim($spk->party1_description), '.').'.' : '',
            trim($spk->party2_description) !== '' ? 'Bahwa PIHAK KEDUA adalah '.rtrim(trim($spk->party2_description), '.').'.' : '',
            'Bahwa PIHAK PERTAMA dan PIHAK KEDUA telah setuju untuk menuangkan Kerja Sama ini dalam suatu Perjanjian Kerja Sama '.$title.'.',
        ], fn (string $line): bool => $line !== ''));

        $sections = [
            'asas' => ['ASAS KERJA SAMA', self::markdown($spk->asas)],
            'pokok' => ['POKOK PERJANJIAN', self::pokok($spk, $title)],
            'duration' => ['JANGKA WAKTU', self::duration($spk, $title)],
            'obligations' => ['HAK DAN KEWAJIBAN', [['type' => 'blocks', 'blocks' => [
                self::line('PIHAK PERTAMA', 1, 'a.', true),
                ...self::indent(MarkdownBlocks::parse($spk->obligations_first), 1),
                self::line('PIHAK KEDUA', 1, 'b.', true),
                ...self::indent(MarkdownBlocks::parse($spk->obligations_second), 1),
            ]]]],
            'payment' => ['NILAI KONTRAK DAN CARA PEMBAYARAN', self::payment($spk, $title, $numbers['pokok'])],
            'server' => ['LAYANAN SEWA SERVER DAN DOMAIN', self::markdown($spk->server_text)],
            'confidentiality' => ['KERAHASIAAN', self::markdown($spk->confidentiality)],
            'dispute' => ['PENYELESAIAN PERSELISIHAN', self::markdown($spk->dispute)],
            'correspondence' => ['KORESPONDENSI', self::correspondence($spk)],
            'misc' => ['LAIN-LAIN', self::markdown($spk->misc)],
        ];
        foreach ($spk->extra_pasal ?? [] as $i => $row) {
            $sections['extra.'.$i] = [mb_strtoupper(trim((string) ($row['title'] ?? ''))), self::markdown((string) ($row['body'] ?? ''))];
        }

        $pasal = [];
        foreach ($numbers as $key => $number) {
            [$heading, $parts] = $sections[$key];
            $pasal[] = ['number' => $number, 'title' => $heading, 'parts' => $parts];
        }

        $local = Storage::disk('local');
        $attachments = [];
        foreach ($spk->attachments ?? [] as $attachment) {
            $path = (string) ($attachment['path'] ?? '');
            if ($path !== '' && $local->exists($path)) {
                $attachments[] = ['lampiran' => count($attachments) + 1, 'title' => trim((string) ($attachment['title'] ?? '')), 'path' => $local->path($path)];
            }
        }

        return new self(
            number: $spk->number ?? Documents::SPK->handler()->generateDocumentNumber($spk->project, Documents::SPK),
            dateLabel: $date->settings(['locale' => 'id'])->translatedFormat('d F Y'),
            title: mb_strtoupper($title),
            opening: 'Perjanjian Kerja Sama '.$title.' (selanjutnya dengan segala lampirannya disebut Perjanjian) ini dibuat dan ditandatangani pada hari ini, '
                .self::dateInWords($date).', oleh dan antara:',
            parties: [
                self::party(1, $spk->party1_company, (string) $spk->party1_address, $spk->party1_representative, $spk->party1_position, 'PIHAK PERTAMA'),
                self::party(2, $company2, (string) $spk->party2_address, $signer2, $position2, 'PIHAK KEDUA'),
            ],
            recitals: array_map(fn (string $text, int $i): array => self::line($text, 1, ($i + 1).'.'), $recitals, array_keys($recitals)),
            pasal: $pasal,
            firstSigner: ['label' => 'PIHAK PERTAMA', 'company' => $spk->party1_company, 'name' => $spk->party1_representative, 'position' => $spk->party1_position],
            secondSigner: ['label' => 'PIHAK KEDUA', 'company' => $company2, 'name' => $signer2, 'position' => $position2],
            signaturePath: $spk->has_signature ? self::publicPath($spk->signature?->file) : null,
            headerImagePath: self::publicPath($spk->issuer?->header_image),
            footerImagePath: self::publicPath($spk->issuer?->footer_image),
            attachments: $attachments,
        );
    }

    /**
     * Pasal number per key, in document order; extra Pasal are keyed extra.0, extra.1, ...
     *
     * @return array<string, int>
     */
    public static function pasalNumbers(bool $server, bool $correspondence, int $extraCount): array
    {
        $keys = [
            'asas', 'pokok', 'duration', 'obligations', 'payment',
            ...($server ? ['server'] : []),
            'confidentiality', 'dispute',
            ...($correspondence ? ['correspondence'] : []),
            ...($extraCount > 0 ? array_map(fn (int $i): string => 'extra.'.$i, range(0, $extraCount - 1)) : []),
            'misc',
        ];

        return array_combine($keys, range(1, count($keys)));
    }

    /** "Rabu tanggal Empat bulan Februari tahun Dua Ribu Dua Puluh Enam (04-02-2026)" */
    public static function dateInWords(CarbonImmutable $date): string
    {
        $local = $date->settings(['locale' => 'id']);

        return $local->translatedFormat('l').' tanggal '.Terbilang::convert($date->day).' bulan '.$local->translatedFormat('F')
            .' tahun '.Terbilang::convert($date->year).' ('.$date->format('d-m-Y').')';
    }

    /** "88 (Delapan Puluh Delapan)" */
    public static function quantity(int $number): string
    {
        return $number.' ('.Terbilang::convert($number).')';
    }

    /** "Rp56.000.000,- (Lima Puluh Enam Juta Rupiah)" */
    public static function rupiah(float $amount): string
    {
        return ProposalDocumentData::money($amount).' ('.Terbilang::rupiah($amount).')';
    }

    /**
     * @param  array<string, mixed>  $row
     */
    public static function installmentText(array $row, float $contractValue): string
    {
        $count = max(1, (int) ($row['count'] ?? 1));
        $share = ($row['type'] ?? 'percent') === 'percent' ? ' '.SphDocumentData::percent((float) ($row['value'] ?? 0)).'% atau' : '';
        $due = trim((string) ($row['due'] ?? ''));

        return rtrim(trim(trim((string) ($row['label'] ?? '')).$share.' sebesar '.self::rupiah(ProjectSpk::installmentAmount($row, $contractValue))
            .($count > 1 ? ' sebanyak '.self::quantity($count).' kali' : '')
            .($due !== '' ? ' '.$due : '')), '.').'.';
    }

    /**
     * Split free text into ayat: every top-level paragraph or top-level list item starts one, deeper blocks stay with it.
     *
     * @return list<list<Block>>
     */
    public static function splitAyat(?string $markdown): array
    {
        $items = [];
        foreach (MarkdownBlocks::parse((string) $markdown) as $block) {
            $starts = $block['level'] === 0 || ($block['level'] === 1 && $block['marker'] !== '');
            if ($starts || $items === []) {
                $items[] = [$block];
            } else {
                $items[array_key_last($items)][] = $block;
            }
        }

        return $items;
    }

    /**
     * Number ayat from $start: each item's first block becomes "n." at level 1, the rest keep their nesting.
     *
     * @param  list<list<Block>>  $items
     * @return list<Block>
     */
    private static function ayat(array $items, int $start = 1): array
    {
        $blocks = [];
        foreach ($items as $i => $item) {
            foreach ($item as $j => $block) {
                $blocks[] = $j === 0 ? ['level' => 1, 'marker' => ($start + $i).'.', 'runs' => $block['runs']] : $block;
            }
        }

        return $blocks;
    }

    /**
     * @return list<Part>
     */
    private static function markdown(?string $text): array
    {
        return [['type' => 'blocks', 'blocks' => MarkdownBlocks::parse((string) $text)]];
    }

    /**
     * @return list<Part>
     */
    private static function pokok(ProjectSpk $spk, string $title): array
    {
        $items = [
            [
                self::line('PIHAK PERTAMA dengan ini menunjuk PIHAK KEDUA. Dan PIHAK KEDUA dengan ini menerima penunjukan PIHAK PERTAMA untuk melakukan pekerjaan berupa '.$title.':'),
                ...self::indent(MarkdownBlocks::parse($spk->scope), 1),
            ],
            [self::line('Di luar pekerjaan tersebut (butir 1) akan dikenakan biaya yang besarnya berdasarkan kesepakatan kedua pihak.')],
        ];
        if (trim($spk->reference_document) !== '') {
            $items[] = [
                self::line('Spesifikasi lengkap mengenai cakupan pekerjaan termuat pada dokumen:'),
                self::line(trim($spk->reference_document).' yang merupakan satu kesatuan dan bagian yang tidak terpisahkan dari Perjanjian ini.', 2),
            ];
        }

        return [['type' => 'blocks', 'blocks' => self::ayat($items)]];
    }

    /**
     * @return list<Part>
     */
    private static function duration(ProjectSpk $spk, string $title): array
    {
        $start = trim($spk->work_start_text);
        $items = [[self::line('Jangka waktu pelaksanaan '.$title.' adalah '.self::quantity($spk->work_duration).' '.$spk->work_duration_unit.($start !== '' ? ', '.$start : '').'.')]];
        if ($spk->maintenance_months > 0) {
            $items[] = [self::line('Jangka waktu maintenance & supporting adalah '.self::quantity($spk->maintenance_months).' bulan kalender sejak pekerjaan '.$title
                .' telah selesai dan dilakukan penandatanganan Berita Acara Serah Terima (BAST) pekerjaan oleh Para Pihak (final and officially launched).')];
        }

        return [['type' => 'blocks', 'blocks' => self::ayat([...$items, ...self::splitAyat($spk->duration_notes)])]];
    }

    /**
     * @return list<Part>
     */
    private static function payment(ProjectSpk $spk, string $title, int $pokokNumber): array
    {
        $value = (float) $spk->contract_value;
        $items = [
            [self::line('Fee dan Biaya yang harus dibayarkan oleh PIHAK PERTAMA kepada PIHAK KEDUA adalah '.self::rupiah($value).'.'.($spk->tax_note ? ' Harga belum termasuk Pajak.' : ''))],
            [self::line('Segala hal yang dianggap sebagai pekerjaan tambahan di luar jasa '.$title.' sebagaimana dimaksud dalam Pasal '.$pokokNumber
                .' Pokok Perjanjian akan dikenakan biaya tersendiri yang besarnya akan ditentukan kemudian secara bersama-sama oleh Para Pihak.')],
        ];
        $rows = $spk->installments ?? [];
        if ($rows !== []) {
            $letters = range('a', 'z');
            $items[] = [
                self::line('Pembayaran nilai kontrak untuk '.$title.' dilakukan dengan skema pembayaran sebagai berikut:'),
                ...array_map(fn (array $row, int $i): array => self::line(self::installmentText($row, $value), 2, $letters[$i % 26].'.'), $rows, array_keys($rows)),
            ];
        }
        $items = [...$items, ...self::splitAyat($spk->payment_notes)];
        $items[] = [self::line('Pelaksanaan pembayaran biaya oleh PIHAK PERTAMA akan dilaksanakan dengan cara mengkredit/transfer rekening PIHAK KEDUA paling lambat 14 (empat belas) hari kalender sejak invoice diterima oleh PIHAK PERTAMA.')];

        $parts = [['type' => 'blocks', 'blocks' => self::ayat($items)]];
        $bank = $spk->bankAccount;
        if ($bank !== null) {
            $parts[] = ['type' => 'table', 'indent' => 1, 'rows' => [['Atas nama', $bank->name], ['Nomor rekening', $bank->number], ['Pada Bank', $bank->bank_name]]];
        }

        return $parts;
    }

    /**
     * @return list<Part>
     */
    private static function correspondence(ProjectSpk $spk): array
    {
        $table = fn (array $contact): array => ['type' => 'table', 'indent' => 2, 'rows' => [
            ['Nama', (string) ($contact['name'] ?? '')],
            ['Alamat', (string) ($contact['address'] ?? '')],
            ['Nomor Telp.', (string) ($contact['phone'] ?? '')],
            ['E-Mail', (string) ($contact['email'] ?? '')],
        ]];

        return [
            ['type' => 'blocks', 'blocks' => self::ayat([[self::line('Berikut ini adalah korespondensi Para Pihak yang terlibat di dalam Perjanjian ini:')]])],
            ['type' => 'blocks', 'blocks' => [self::line('PIHAK PERTAMA', 2, 'a.', true)]],
            $table($spk->contacts['first'] ?? []),
            ['type' => 'blocks', 'blocks' => [self::line('PIHAK KEDUA', 2, 'b.', true)]],
            $table($spk->contacts['second'] ?? []),
            ['type' => 'blocks', 'blocks' => self::ayat(self::splitAyat($spk->correspondence_text), 2)],
        ];
    }

    /**
     * @return Block
     */
    private static function party(int $number, string $company, string $address, string $representative, string $position, string $alias): array
    {
        $address = implode(', ', array_filter(array_map('trim', preg_split('/\R/', $address) ?: []), fn (string $line): bool => $line !== ''));

        return ['level' => 1, 'marker' => $number.'.', 'runs' => [
            ['text' => mb_strtoupper(trim($company)), 'bold' => true, 'italic' => false],
            ['text' => ', berkedudukan di '.$address.', dalam hal ini diwakili oleh '.trim($representative).' selaku '.trim($position)
                .' dan karenanya sah bertindak untuk dan atas nama '.trim($company).', selanjutnya disebut ', 'bold' => false, 'italic' => false],
            ['text' => $alias, 'bold' => true, 'italic' => false],
            ['text' => '.', 'bold' => false, 'italic' => false],
        ]];
    }

    /**
     * @return Block
     */
    private static function line(string $text, int $level = 0, string $marker = '', bool $bold = false): array
    {
        return ['level' => $level, 'marker' => $marker, 'runs' => [['text' => $text, 'bold' => $bold, 'italic' => false]]];
    }

    /**
     * @param  list<Block>  $blocks
     * @return list<Block>
     */
    private static function indent(array $blocks, int $by): array
    {
        return array_map(fn (array $block): array => ['level' => $block['level'] + $by] + $block, $blocks);
    }

    private static function publicPath(?string $path): ?string
    {
        $disk = Storage::disk('public');

        return $path !== null && $path !== '' && $disk->exists($path) ? $disk->path($path) : null;
    }
}
