<?php

declare(strict_types=1);

namespace VmEngine\Pm\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use VmEngine\Pm\Models\Division;

/**
 * @extends Factory<Division>
 */
class DivisionFactory extends Factory
{
    protected $model = Division::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'created_by' => 1,
            'updated_by' => null,
        ];
    }
}
