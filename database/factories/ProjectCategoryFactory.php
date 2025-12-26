<?php

declare(strict_types=1);

namespace VmEngine\Pm\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use VmEngine\Pm\Models\ProjectCategory;

class ProjectCategoryFactory extends Factory
{
    protected $model = ProjectCategory::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'name' => ucwords($name),
            'code' => strtoupper(fake()->unique()->lexify('???-###')),
            'description' => fake()->sentence(),
        ];
    }
}
