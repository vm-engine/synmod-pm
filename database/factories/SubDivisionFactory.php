<?php

declare(strict_types=1);

namespace VmEngine\Pm\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use VmEngine\Pm\Models\Division;
use VmEngine\Pm\Models\SubDivision;

/**
 * @extends Factory<SubDivision>
 */
class SubDivisionFactory extends Factory
{
    protected $model = SubDivision::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'division_id' => Division::factory(),
            'name' => fake()->words(3, true),
            'created_by' => 1,
            'updated_by' => null,
        ];
    }
}
