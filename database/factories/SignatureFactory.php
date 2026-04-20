<?php

declare(strict_types=1);

namespace VmEngine\Pm\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use VmEngine\Pm\Models\Signature;
use VmEngine\SynAuth\Models\User;

/**
 * @extends Factory<Signature>
 */
class SignatureFactory extends Factory
{
    protected $model = Signature::class;

    public function definition(): array
    {
        $positions = ['Director', 'CEO', 'CFO', 'Manager', 'Finance Manager', 'Project Manager'];

        return [
            'name' => fake()->name(),
            'position' => fake()->randomElement($positions),
            'file' => fake()->imageUrl(300, 150, 'business', true),
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
