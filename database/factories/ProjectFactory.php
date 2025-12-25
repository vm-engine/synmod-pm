<?php

declare(strict_types=1);

namespace VmEngine\Pm\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use VmEngine\Pm\Models\Division;
use VmEngine\Pm\Models\Project;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $UserModel = config('auth.providers.users.model');

        return [
            'user_id' => $UserModel::factory(),
            'division_id' => Division::factory(),
            'name' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'year' => fake()->year(),
            'sub_division' => [fake()->numberBetween(1, 26)],
            'type_of_client' => fake()->numberBetween(1, 2),
            'pricing' => fake()->numberBetween(1000000, 200000000),
            'source_of_sales' => fake()->numberBetween(1, 6),
            'url' => fake()->url(),
            'status' => fake()->numberBetween(0, 100),
            'created_by' => $UserModel::factory(),
            'updated_by' => null,
        ];
    }

    /**
     * Indicate that the project is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 100,
        ]);
    }

    /**
     * Indicate that the project is in progress.
     */
    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => fake()->numberBetween(1, 99),
        ]);
    }

    /**
     * Indicate that the project is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 0,
        ]);
    }
}
