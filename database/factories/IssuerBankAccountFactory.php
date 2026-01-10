<?php

declare(strict_types=1);

namespace VmEngine\Pm\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use VmEngine\Pm\Models\BankAccount;
use VmEngine\Pm\Models\DocumentIssuer;
use VmEngine\Pm\Models\IssuerBankAccount;

/**
 * @extends Factory<IssuerBankAccount>
 */
class IssuerBankAccountFactory extends Factory
{
    protected $model = IssuerBankAccount::class;

    public function definition(): array
    {
        return [
            'issuer_id' => DocumentIssuer::factory(),
            'bank_account_id' => BankAccount::factory(),
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
