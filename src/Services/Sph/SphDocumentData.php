<?php

declare(strict_types=1);

namespace VmEngine\Pm\Services\Sph;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use VmEngine\Pm\Enums\Documents;
use VmEngine\Pm\Helpers\Terbilang;
use VmEngine\Pm\Models\Holiday;
use VmEngine\Pm\Models\ProjectSph;
use VmEngine\Pm\Services\Proposal\Gantt\GanttLayout;
use VmEngine\Pm\Services\Proposal\Gantt\GanttScheduler;
use VmEngine\Pm\Services\Proposal\Gantt\WorkCalendar;
use VmEngine\Pm\Services\Proposal\ProposalDocumentData;

/**
 * Everything the PDF and DOCX renderers need, computed once from an SPH.
 *
 * @phpstan-import-type Block from MarkdownBlocks
 */
final readonly class SphDocumentData
{
    public const TIMEPLAN_DISCLAIMER = 'Tanggal pada timeplan bersifat gambaran. Tanggal start pekerjaan menyesuaikan persetujuan pada dokumen Perjanjian Kerja Sama (Purchase Order, Invoice dan pembayaran invoice DP). Poin ini bisa dibahas lebih lanjut (terpisah).';

    /**
     * @param  list<string>  $recipientLines  title, company and address lines (non-empty)
     * @param  list<array{number: int, name: string, blocks: list<Block>, duration: string, price: string}>  $items
     * @param  list<string>  $paymentLines  empty when the payment section is off
     * @param  array{lampiran: int, gantt: GanttLayout, notes: list<string>}|null  $timeplan
     * @param  list<array{lampiran: int, title: string, path: string}>  $attachments  absolute image paths
     */
    public function __construct(
        public string $number,
        public string $dateLabel,
        public string $attachmentLabel,
        public string $subject,
        public string $recipientName,
        public array $recipientLines,
        public string $need,
        public string $companyName,
        public string $signerName,
        public string $signerPosition,
        public ?string $signaturePath,
        public ?string $headerImagePath,
        public ?string $footerImagePath,
        public array $items,
        public bool $showDuration,
        public string $grandTotalLabel,
        public string $grandTotalWords,
        public bool $taxNote,
        public array $paymentLines,
        public ?string $timelineSummary,
        public ?array $timeplan,
        public array $attachments,
    ) {}

    public static function build(ProjectSph $sph): self
    {
        $sph->loadMissing('project.client', 'project.projectCategory', 'project.division', 'issuer', 'signature');

        $timeline = $sph->timeline ?? [];
        $calendar = WorkCalendar::fromSets(Holiday::dates(), $timeline['extra_holidays'] ?? [], $timeline['worked_holidays'] ?? []);
        $tasks = (new GanttScheduler)->schedule(
            $timeline['tasks'] ?? [],
            $timeline['start_date'] ?? CarbonImmutable::now()->toDateString(),
            $calendar,
        );
        $gantt = GanttLayout::build($tasks, $calendar);
        $holidaysInSpan = $gantt->start !== null && $gantt->finish !== null
            && $calendar->holidaysBetween($gantt->start, $gantt->finish) > 0;

        $lampiran = 1;
        $timeplan = null;
        if ($sph->show_timeplan && $gantt->pages !== []) {
            $timeplan = ['lampiran' => ++$lampiran, 'gantt' => $gantt, 'notes' => [
                'Durasi pekerjaan: '.$gantt->workingDays.' hari kerja.',
                'Timeplan menggunakan hari kerja: Senin – Jumat'.($holidaysInSpan ? ', di luar hari libur' : '').'.',
                self::TIMEPLAN_DISCLAIMER,
            ]];
        }

        $local = Storage::disk('local');
        $attachments = [];
        foreach ($sph->attachments ?? [] as $attachment) {
            $path = (string) ($attachment['path'] ?? '');
            if ($path !== '' && $local->exists($path)) {
                $attachments[] = ['lampiran' => ++$lampiran, 'title' => trim((string) ($attachment['title'] ?? '')), 'path' => $local->path($path)];
            }
        }

        $total = (float) $sph->grand_total;
        $dp = (float) $sph->dp_percentage;
        $items = $sph->items ?? [];

        return new self(
            number: $sph->number ?? Documents::SPH->handler()->generateDocumentNumber($sph->project, Documents::SPH),
            dateLabel: CarbonImmutable::parse($sph->sph_date)->settings(['locale' => 'id'])->translatedFormat('j F Y'),
            attachmentLabel: trim($sph->attachment_label) !== '' ? trim($sph->attachment_label) : '-',
            subject: $sph->subject,
            recipientName: $sph->recipient_name,
            recipientLines: array_values(array_filter(
                array_map('trim', [$sph->recipient_title, $sph->recipient_company, ...(preg_split('/\R/', (string) $sph->recipient_address) ?: [])]),
                fn (string $line): bool => $line !== '',
            )),
            need: $sph->need,
            companyName: (string) ($sph->issuer->name ?? ''),
            signerName: (string) ($sph->signature->name ?? ''),
            signerPosition: (string) ($sph->signature->position ?? ''),
            signaturePath: $sph->has_signature ? self::publicPath($sph->signature?->file) : null,
            headerImagePath: self::publicPath($sph->issuer?->header_image),
            footerImagePath: self::publicPath($sph->issuer?->footer_image),
            items: array_map(fn (array $row, int $i): array => [
                'number' => $i + 1,
                'name' => (string) ($row['name'] ?? ''),
                'blocks' => MarkdownBlocks::parse((string) ($row['description'] ?? '')),
                'duration' => trim((string) ($row['duration'] ?? '')) !== '' ? trim((string) $row['duration']) : '-',
                'price' => ProposalDocumentData::money((float) ($row['price'] ?? 0)),
            ], $items, array_keys($items)),
            showDuration: $sph->show_duration,
            grandTotalLabel: ProposalDocumentData::money($total),
            grandTotalWords: Str::upper(Terbilang::rupiah($total)),
            taxNote: $sph->tax_note,
            paymentLines: $sph->payment_enabled ? [
                'Down Payment '.self::percent($dp).'% dari nilai yang disepakati atau senilai '.ProposalDocumentData::money(round($total * $dp / 100)),
                'Sisa pembayaran sebesar '.self::percent(100 - $dp).'% dilakukan pada saat selesai pekerjaan.',
            ] : [],
            timelineSummary: $sph->show_timeline_summary && $gantt->workingDays > 0
                ? 'Pelaksanaan Pekerjaan: '.$gantt->workingDays.' Hari Kerja.'
                : null,
            timeplan: $timeplan,
            attachments: $attachments,
        );
    }

    /** "30", "12,5", "100" */
    public static function percent(float $value): string
    {
        return rtrim(rtrim(number_format($value, 2, ',', ''), '0'), ',');
    }

    private static function publicPath(?string $path): ?string
    {
        $disk = Storage::disk('public');

        return $path !== null && $path !== '' && $disk->exists($path) ? $disk->path($path) : null;
    }
}
