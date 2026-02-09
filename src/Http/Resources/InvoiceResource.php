<?php

declare(strict_types=1);

namespace VmEngine\Pm\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'invoice_number' => $this->invoice_number,
            'issue_date' => $this->issue_date?->format('Y-m-d'),
            'due_date' => $this->due_date?->format('Y-m-d'),
            'recipient' => $this->recipient,
            'amount' => (float) $this->amount,
            'vat_amount' => (float) $this->vat_amount,
            'discount_amount' => (float) ($this->discount_amount ?? 0),
            'total_amount' => (float) $this->total_amount,
            'is_paid' => (bool) $this->is_paid,
            'paid_at' => $this->paid_at?->toIso8601String(),
            'receipt_number' => $this->receipt_number,
            'project' => $this->whenLoaded('project', fn () => [
                'id' => $this->project->id,
                'name' => $this->project->name,
            ]),
            'project_category' => $this->whenLoaded('projectCategory', fn () => $this->projectCategory ? [
                'id' => $this->projectCategory->id,
                'name' => $this->projectCategory->name,
            ] : null),
            'creator' => $this->whenLoaded('creator', fn () => $this->creator ? [
                'id' => $this->creator->id,
                'name' => $this->creator->name,
            ] : null),
            'client' => $this->whenLoaded('client', fn () => $this->client ? [
                'id' => $this->client->id,
                'company' => $this->client->company,
            ] : null),
        ];
    }
}
