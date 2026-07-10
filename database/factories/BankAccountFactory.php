<?php

declare(strict_types=1);

namespace VmEngine\Pm\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use VmEngine\Pm\Models\BankAccount;
use VmEngine\SynAuth\Models\User;

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
            'swift_code' => fake()->optional()->regexify('[A-Z]{4}IDJA'),
            'bank_logo' => fake()->imageUrl(200, 100, 'business', true),
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
