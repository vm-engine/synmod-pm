<?php

declare(strict_types=1);

namespace VmEngine\Pm\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use VmEngine\Pm\Models\DocumentIssuer;
use VmEngine\SynAuth\Models\User;

/**
 * @extends Factory<DocumentIssuer>
 */
class DocumentIssuerFactory extends Factory
{
    protected $model = DocumentIssuer::class;

    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'header_image' => fake()->imageUrl(800, 200, 'business', true),
            'tax_id_name' => fake()->company(),
            'tax_id_address' => fake()->address(),
            'tax_id_number' => fake()->numerify('##.###.###.#-###.###'),
            'created_by' => User::factory(),
            'updated_by' => null,
        ];
    }

    public function withUpdater(): static
    {
        return $this->state(fn (array $attributes) => [
            'updated_by' => User::factory(),
        ]);
    }
}
