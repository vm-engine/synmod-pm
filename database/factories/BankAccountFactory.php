<?php

declare(strict_types=1);

namespace VmEngine\Pm\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use VmEngine\Pm\Models\BankAccount;

/**
 * @extends Factory<BankAccount>
 */
class BankAccountFactory extends Factory
{
    protected $model = BankAccount::class;

    public function definition(): array
    {
        $banks = ['BCA', 'Mandiri', 'BNI', 'BRI', 'CIMB Niaga', 'Permata', 'Danamon'];

        return [
            'name' => fake()->name(),
            'number' => fake()->numerify('##########'),
            'bank_name' => fake()->randomElement($banks),
            'bank_logo' => fake()->imageUrl(200, 100, 'business', true),
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
