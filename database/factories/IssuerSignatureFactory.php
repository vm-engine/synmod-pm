<?php

declare(strict_types=1);

namespace VmEngine\Pm\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use VmEngine\Pm\Models\DocumentIssuer;
use VmEngine\Pm\Models\IssuerSignature;
use VmEngine\Pm\Models\Signature;

/**
 * @extends Factory<IssuerSignature>
 */
class IssuerSignatureFactory extends Factory
{
    protected $model = IssuerSignature::class;

    public function definition(): array
    {
        return [
            'issuer_id' => DocumentIssuer::factory(),
            'signature_id' => Signature::factory(),
            'created_by' => \VmEngine\SynAuth\Models\User::factory(),
            'updated_by' => null,
        ];
    }

    public function withUpdater(): static
    {
        return $this->state(fn (array $attributes) => [
            'updated_by' => \VmEngine\SynAuth\Models\User::factory(),
        ]);
    }
}
