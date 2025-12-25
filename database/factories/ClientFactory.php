<?php

declare(strict_types=1);

namespace VmEngine\Pm\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use VmEngine\Pm\Models\Client;
use VmEngine\Pm\Models\ClientCategory;

/**
 * @extends Factory<Client>
 */
class ClientFactory extends Factory
{
    protected $model = Client::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $UserModel = config('auth.providers.users.model');

        return [
            'user_id' => $UserModel::factory(),
            'client_category_id' => ClientCategory::factory(),
            'company' => fake()->company(),
            'address' => fake()->address(),
            'city' => fake()->city(),
            'director' => fake()->name(),
            'pic' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'services' => [fake()->numberBetween(1, 6)],
            'social_media' => [
                'website' => fake()->url(),
                'linkedin' => null,
                'instagram' => null,
                'facebook' => null,
                'twitter' => null,
                'youtube' => null,
                'pinterest' => null,
            ],
            'status' => fake()->randomElement([Client::STATUS_PROSPECT, Client::STATUS_ACTIVE]),
            'active' => fake()->boolean(90),
            'is_proposal' => fake()->boolean(20),
            'created_by' => $UserModel::factory(),
            'updated_by' => null,
        ];
    }

    /**
     * Indicate that the client is a prospect.
     */
    public function prospect(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Client::STATUS_PROSPECT,
        ]);
    }

    /**
     * Indicate that the client is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Client::STATUS_ACTIVE,
        ]);
    }

    /**
     * Indicate that the client is inactive (disabled).
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'active' => false,
        ]);
    }

    /**
     * Indicate that the client is a proposal.
     */
    public function proposal(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_proposal' => true,
        ]);
    }
}
