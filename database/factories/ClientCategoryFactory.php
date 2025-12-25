<?php

declare(strict_types=1);

namespace VmEngine\Pm\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use VmEngine\Pm\Models\ClientCategory;

/**
 * @extends Factory<ClientCategory>
 */
class ClientCategoryFactory extends Factory
{
    protected $model = ClientCategory::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $UserModel = config('auth.providers.users.model');

        return [
            'name' => fake()->words(2, true),
            'description' => fake()->sentence(),
            'created_by' => $UserModel::factory(),
            'updated_by' => null,
        ];
    }
}
