<?php

declare(strict_types=1);

namespace VmEngine\Pm\Services\Sph;

use VmEngine\Pm\Enums\ProposalStatus;
use VmEngine\Pm\Enums\ProposalType;
use VmEngine\Pm\Models\DocumentIssuer;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\ProjectProposal;
use VmEngine\Pm\Models\ProjectSph;
use VmEngine\Pm\Models\ProposalTypeDefault;
use VmEngine\Pm\Models\Signature;
use VmEngine\Pm\Services\Proposal\ProposalDefaults;
use VmEngine\Pm\Services\Proposal\ProposalFactory;

final class SphFactory
{
    public const GENERIC_RECIPIENT = 'Bapak/Ibu Pimpinan';

    public const DEFAULT_DP_PERCENTAGE = 30.0;

    public function create(Project $project): ProjectSph
    {
        $project->loadMissing('client', 'proposal');
        $client = $project->client;
        $previous = ProjectSph::query()->whereNotNull('published_at')->latest('published_at')->first();

        $sph = $project->sph()->create([
            'status' => ProposalStatus::Draft,
            'sph_date' => now()->toDateString(),
            'subject' => 'Surat Penawaran Harga',
            'attachment_label' => '1 (Satu) Set',
            'recipient_name' => self::firstFilled([$project->client_pic, $client?->pic]) ?? self::GENERIC_RECIPIENT,
            'recipient_title' => '',
            'recipient_company' => (string) ($client->company ?? ''),
            'recipient_address' => trim(((string) ($client->address ?? ''))."\n".((string) ($client->city ?? ''))),
            'need' => $project->name,
            'issuer_id' => $previous->issuer_id ?? DocumentIssuer::query()->orderBy('id')->value('id'),
            'signature_id' => $previous->signature_id ?? Signature::query()->orderBy('id')->value('id'),
            'has_signature' => true,
            'items' => [['id' => ProposalFactory::newRowId(), 'name' => '', 'description' => '', 'duration' => '', 'price' => 0.0]],
            'show_duration' => false,
            'tax_note' => true,
            'payment_enabled' => false,
            'dp_percentage' => self::defaultDpPercentage($project),
            'timeline' => [
                'start_date' => now()->toDateString(),
                'extra_holidays' => [],
                'worked_holidays' => [],
                'tasks' => ProposalTypeDefault::query()->where('type', ProposalType::Application->value)->first()?->sections()['timeline_tasks']
                    ?? ProposalDefaults::for(ProposalType::Application)['timeline_tasks'],
            ],
            'show_timeline_summary' => false,
            'show_timeplan' => false,
            'attachments' => [],
        ]);

        return $project->proposal !== null ? $this->importFromProposal($sph) : $sph;
    }

    /**
     * Replace items, tax note and timeline with the proposal's (its published version when there is one).
     */
    public function importFromProposal(ProjectSph $sph): ProjectSph
    {
        $proposal = $sph->project->proposal;
        if ($proposal === null) {
            return $sph;
        }
        $source = $proposal->published_snapshot ?? $proposal->only(ProjectProposal::CONTENT_FIELDS);

        $sph->update([
            'items' => array_map(fn (array $row): array => [
                'id' => (string) ($row['id'] ?? ProposalFactory::newRowId()),
                'name' => (string) ($row['section'] ?? ''),
                'description' => implode("\n", array_map(fn (string $feature): string => '- '.$feature, (array) ($row['features'] ?? []))),
                'duration' => '',
                'price' => (float) ($row['price'] ?? 0),
            ], array_values((array) ($source['costing'] ?? []))),
            'tax_note' => (bool) ($source['tax_note'] ?? true),
            'timeline' => (array) ($source['timeline'] ?? $sph->timeline),
            'show_timeplan' => true,
        ]);

        return $sph;
    }

    /** Project down payment as a share of the contract value, else 30%. */
    private static function defaultDpPercentage(Project $project): float
    {
        $nominal = (float) $project->nominal;
        $downPayment = (float) $project->down_payment;
        if ($nominal <= 0 || $downPayment <= 0) {
            return self::DEFAULT_DP_PERCENTAGE;
        }

        return min(99.0, max(1.0, round($downPayment / $nominal * 100, 2)));
    }

    /**
     * @param  list<string|null>  $values
     */
    private static function firstFilled(array $values): ?string
    {
        foreach ($values as $value) {
            if (trim((string) $value) !== '') {
                return trim((string) $value);
            }
        }

        return null;
    }
}
