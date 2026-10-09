<?php

declare(strict_types=1);

namespace VmEngine\Pm\Services\Spk;

use VmEngine\Pm\Models\ProjectSpk;
use VmEngine\Pm\Services\Proposal\ProposalDocumentData;

/**
 * Publish/preview checks per form tab.
 */
class SpkValidator
{
    /**
     * @return array<string, list<string>> tab => messages, in tab order
     */
    public function errors(ProjectSpk $spk): array
    {
        $errors = ['general' => [], 'scope' => [], 'duration' => [], 'payment' => [], 'terms' => [], 'extra' => []];
        $blank = fn (mixed $value): bool => trim((string) $value) === '';

        foreach (['title', 'party1_company', 'party1_address', 'party1_representative', 'party1_position', 'party2_address'] as $field) {
            if ($blank($spk->{$field})) {
                $errors['general'][] = __('pm::spk.error_'.$field);
            }
        }
        if ($spk->issuer_id === null) {
            $errors['general'][] = __('pm::spk.error_issuer');
        }
        if ($spk->signature_id === null) {
            $errors['general'][] = __('pm::spk.error_signature');
        }

        if ($blank($spk->scope)) {
            $errors['scope'][] = __('pm::spk.error_scope');
        }
        if ($spk->work_duration < 1) {
            $errors['duration'][] = __('pm::spk.error_duration');
        }

        $value = (float) $spk->contract_value;
        $rows = $spk->installments ?? [];
        if ($value <= 0) {
            $errors['payment'][] = __('pm::spk.error_contract_value');
        }
        if ($spk->bank_account_id === null) {
            $errors['payment'][] = __('pm::spk.error_bank');
        }
        if (collect($rows)->contains(fn (array $row): bool => (float) ($row['value'] ?? 0) <= 0 || $blank($row['label'] ?? ''))) {
            $errors['payment'][] = __('pm::spk.error_installment_row');
        }
        $total = ProjectSpk::installmentsTotal($rows, $value);
        if ($rows !== [] && $value > 0 && abs($total - $value) >= 1) {
            $errors['payment'][] = __('pm::spk.error_installments_sum', [
                'total' => ProposalDocumentData::money($total),
                'value' => ProposalDocumentData::money($value),
            ]);
        }
        if ($spk->server_enabled && $blank($spk->server_text)) {
            $errors['payment'][] = __('pm::spk.error_server');
        }

        if ($spk->correspondence_enabled && ($blank($spk->contacts['first']['name'] ?? '') || $blank($spk->contacts['second']['name'] ?? ''))) {
            $errors['terms'][] = __('pm::spk.error_contacts');
        }

        if (collect($spk->extra_pasal ?? [])->contains(fn (array $row): bool => $blank($row['title'] ?? '') || $blank($row['body'] ?? ''))) {
            $errors['extra'][] = __('pm::spk.error_extra');
        }

        return array_filter($errors, fn (array $messages): bool => $messages !== []);
    }
}
