<?php

declare(strict_types=1);

namespace VmEngine\Pm\Services\Sph;

use VmEngine\Pm\Models\ProjectSph;

/**
 * Rules an SPH must pass before Preview or Publish. Drafts may be saved incomplete.
 */
final class SphValidator
{
    /**
     * @return array<string, list<string>> tab => messages, in tab order
     */
    public function errors(ProjectSph $sph): array
    {
        $errors = ['general' => [], 'items' => [], 'terms' => [], 'timeline' => []];

        if (trim($sph->recipient_name) === '') {
            $errors['general'][] = __('pm::sph.error_recipient');
        }
        if (trim($sph->need) === '') {
            $errors['general'][] = __('pm::sph.error_need');
        }
        if ($sph->issuer_id === null) {
            $errors['general'][] = __('pm::sph.error_issuer');
        }
        if ($sph->signature_id === null) {
            $errors['general'][] = __('pm::sph.error_signature');
        }

        $items = $sph->items ?? [];
        if ($items === []) {
            $errors['items'][] = __('pm::sph.error_items_empty');
        }
        if (collect($items)->contains(fn (array $row): bool => trim((string) ($row['name'] ?? '')) === '')) {
            $errors['items'][] = __('pm::sph.error_item_name');
        }
        if (collect($items)->contains(fn (array $row): bool => (float) ($row['price'] ?? 0) < 0)) {
            $errors['items'][] = __('pm::sph.error_price_negative');
        }
        if ($items !== [] && ProjectSph::computeGrandTotal($items) <= 0) {
            $errors['items'][] = __('pm::sph.error_total_zero');
        }

        $dp = (float) $sph->dp_percentage;
        if ($sph->payment_enabled && ($dp < 1 || $dp > 99)) {
            $errors['terms'][] = __('pm::sph.error_dp');
        }

        $timeline = $sph->timeline ?? [];
        if (($sph->show_timeplan || $sph->show_timeline_summary) && (empty($timeline['start_date']) || empty($timeline['tasks']))) {
            $errors['timeline'][] = __('pm::sph.error_timeline');
        }

        return array_filter($errors, fn (array $messages): bool => $messages !== []);
    }
}
