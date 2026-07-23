<?php

declare(strict_types=1);

namespace VmEngine\Pm\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use VmEngine\Pm\Enums\Services;

class ClientResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'company' => $this->company,
            'address' => $this->address,
            'city' => $this->city,
            'director' => $this->director,
            'pic' => $this->pic,
            'phone' => $this->phone,
            'status' => $this->status,
            'active' => (bool) $this->active,
            'is_proposal' => (bool) $this->is_proposal,
            'social_media' => $this->social_media,
            'services' => collect($this->services ?? [])
                ->map(function ($value) {
                    $case = Services::tryFrom((int) $value);

                    return $case ? ['id' => $case->value, 'label' => $case->label()] : null;
                })
                ->filter()
                ->values()
                ->all(),
            'category' => $this->whenLoaded('category', fn () => $this->category ? [
                'id' => $this->category->id,
                'name' => $this->category->name,
            ] : null),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
