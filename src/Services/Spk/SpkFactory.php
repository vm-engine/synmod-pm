<?php

declare(strict_types=1);

namespace VmEngine\Pm\Services\Spk;

use Carbon\CarbonImmutable;
use VmEngine\Pm\Enums\ProposalStatus;
use VmEngine\Pm\Models\BankAccount;
use VmEngine\Pm\Models\DocumentIssuer;
use VmEngine\Pm\Models\Holiday;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\ProjectProposal;
use VmEngine\Pm\Models\ProjectSph;
use VmEngine\Pm\Models\ProjectSpk;
use VmEngine\Pm\Models\Signature;
use VmEngine\Pm\Services\Proposal\Gantt\GanttLayout;
use VmEngine\Pm\Services\Proposal\Gantt\GanttScheduler;
use VmEngine\Pm\Services\Proposal\Gantt\WorkCalendar;
use VmEngine\Pm\Services\Proposal\ProposalFactory;
use VmEngine\Pm\Services\Sph\SphFactory;

final class SpkFactory
{
    public function create(Project $project): ProjectSpk
    {
        $project->loadMissing('client.user', 'proposal', 'sph');
        $client = $project->client;
        $previous = ProjectSpk::query()->whereNotNull('published_at')->latest('published_at')->first();
        $proposal = $project->proposal;
        $source = $proposal === null ? [] : ($proposal->published_snapshot ?? $proposal->only(ProjectProposal::CONTENT_FIELDS));
        $address = trim(((string) ($client->address ?? ''))."\n".((string) ($client->city ?? '')));

        $spk = $project->spk()->create([
            'status' => ProposalStatus::Draft,
            'spk_date' => now()->toDateString(),
            'title' => $project->name,
            'party1_company' => (string) ($client->company ?? ''),
            'party1_address' => $address,
            'party1_representative' => (string) ($client->director ?? ''),
            'party1_position' => 'Direktur',
            'party1_description' => '',
            'issuer_id' => $previous->issuer_id ?? DocumentIssuer::query()->orderBy('id')->value('id'),
            'signature_id' => $previous->signature_id ?? Signature::query()->orderBy('id')->value('id'),
            'has_signature' => true,
            'party2_address' => $previous->party2_address ?? '',
            'party2_description' => $previous->party2_description ?? '',
            'asas' => SpkDefaults::ASAS,
            'scope' => self::bullets(array_column((array) ($source['costing'] ?? []), 'section')),
            'reference_document' => $proposal?->number !== null ? 'Proposal '.$project->name.' No. '.$proposal->number : '',
            'work_duration' => self::workingDays((array) ($source['timeline'] ?? [])),
            'work_duration_unit' => 'hari kerja',
            'work_start_text' => SpkDefaults::WORK_START,
            'maintenance_months' => 3,
            'duration_notes' => '',
            'obligations_first' => SpkDefaults::OBLIGATIONS_FIRST,
            'obligations_second' => SpkDefaults::OBLIGATIONS_SECOND,
            'contract_value' => (float) $project->nominal,
            'tax_note' => true,
            'installments' => self::dpInstallments(SphFactory::DEFAULT_DP_PERCENTAGE),
            'payment_notes' => '',
            'bank_account_id' => $previous->bank_account_id ?? BankAccount::query()->orderBy('id')->value('id'),
            'server_enabled' => false,
            'server_text' => SpkDefaults::SERVER,
            'confidentiality' => SpkDefaults::CONFIDENTIALITY,
            'dispute' => SpkDefaults::DISPUTE,
            'correspondence_enabled' => true,
            'contacts' => [
                'first' => [
                    'name' => trim((string) ($project->client_pic ?: ($client->pic ?? ''))),
                    'address' => str_replace("\n", ', ', $address),
                    'phone' => trim((string) ($project->client_pic_phone ?: ($client->phone ?? ''))),
                    'email' => (string) ($client->user->email ?? ''),
                ],
                'second' => $previous->contacts['second'] ?? ['name' => '', 'address' => '', 'phone' => '', 'email' => ''],
            ],
            'correspondence_text' => SpkDefaults::CORRESPONDENCE,
            'extra_pasal' => [],
            'misc' => SpkDefaults::MISC,
            'attachments' => [],
        ]);

        return $project->sph !== null ? $this->importFromSph($spk) : $spk;
    }

    /**
     * Replace scope, contract value, tax note, DP installments and duration with the SPH's (its published version when there is one).
     */
    public function importFromSph(ProjectSpk $spk): ProjectSpk
    {
        $sph = $spk->project->sph;
        if ($sph === null) {
            return $spk;
        }
        $source = $sph->published_snapshot ?? $sph->only(ProjectSph::CONTENT_FIELDS);
        $items = array_values((array) ($source['items'] ?? []));

        $spk->update([
            'scope' => self::bullets(array_column($items, 'name')),
            'contract_value' => ProjectSph::computeGrandTotal($items),
            'tax_note' => (bool) ($source['tax_note'] ?? true),
            'installments' => self::dpInstallments(! empty($source['payment_enabled']) ? (float) $source['dp_percentage'] : SphFactory::DEFAULT_DP_PERCENTAGE),
            'work_duration' => self::workingDays((array) ($source['timeline'] ?? [])) ?: $spk->work_duration,
        ]);

        return $spk;
    }

    /**
     * DP row plus the remaining share on completion.
     *
     * @return list<array{id: string, label: string, type: string, value: float, count: int, due: string}>
     */
    public static function dpInstallments(float $dp): array
    {
        return [
            ['id' => ProposalFactory::newRowId(), 'label' => SpkDefaults::DP_LABEL, 'type' => 'percent', 'value' => $dp, 'count' => 1, 'due' => SpkDefaults::DP_DUE],
            ['id' => ProposalFactory::newRowId(), 'label' => SpkDefaults::FINAL_LABEL, 'type' => 'percent', 'value' => round(100 - $dp, 2), 'count' => 1, 'due' => SpkDefaults::FINAL_DUE],
        ];
    }

    /**
     * Working days of a proposal/SPH timeline; 0 without tasks.
     *
     * @param  array<string, mixed>  $timeline
     */
    public static function workingDays(array $timeline): int
    {
        if (empty($timeline['tasks'])) {
            return 0;
        }
        $calendar = WorkCalendar::fromSets(Holiday::dates(), $timeline['extra_holidays'] ?? [], $timeline['worked_holidays'] ?? []);
        $tasks = (new GanttScheduler)->schedule($timeline['tasks'], $timeline['start_date'] ?? CarbonImmutable::now()->toDateString(), $calendar);

        return GanttLayout::build($tasks, $calendar)->workingDays;
    }

    /**
     * @param  array<int, mixed>  $lines
     */
    private static function bullets(array $lines): string
    {
        $lines = array_filter(array_map(fn (mixed $line): string => trim((string) $line), $lines), fn (string $line): bool => $line !== '');

        return implode("\n", array_map(fn (string $line): string => '- '.$line, $lines));
    }
}
