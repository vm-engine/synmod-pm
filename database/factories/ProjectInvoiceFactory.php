<?php

declare(strict_types=1);

namespace VmEngine\Pm\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use VmEngine\Pm\Models\BankAccount;
use VmEngine\Pm\Models\Client;
use VmEngine\Pm\Models\DocumentIssuer;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\ProjectCategory;
use VmEngine\Pm\Models\ProjectDocument;
use VmEngine\Pm\Models\ProjectInvoice;
use VmEngine\Pm\Models\Signature;

/**
 * @extends Factory<ProjectInvoice>
 */
class ProjectInvoiceFactory extends Factory
{
    protected $model = ProjectInvoice::class;

    public function definition(): array
    {
        $amount = fake()->numberBetween(5000000, 50000000);
        $includeVat = fake()->boolean(80);
        $vatPercentage = 11.00;
        $vatAmount = $includeVat ? round($amount * $vatPercentage / 100, 2) : 0;

        $includeDiscount = fake()->boolean(30);
        $discountPercentage = $includeDiscount ? fake()->randomFloat(2, 5, 20) : null;
        $discountAmount = $includeDiscount ? round($amount * $discountPercentage / 100, 2) : null;

        $includeDpp = fake()->boolean(20);
        $dppAmount = $includeDpp ? fake()->randomFloat(2, 100000, 1000000) : null;

        $totalAmount = $amount + $vatAmount - ($discountAmount ?? 0) - ($dppAmount ?? 0);
        $paidAmount = fake()->boolean(60) ? fake()->randomFloat(2, 0, $totalAmount) : 0;

        $hasSignature = fake()->boolean(70);

        $issueDate = fake()->dateTimeBetween('-6 months', 'now');
        $dueDate = fake()->boolean(80) ? fake()->dateTimeBetween($issueDate, '+30 days') : null;

        return [
            'project_id' => Project::factory(),
            'project_category_id' => ProjectCategory::factory(),
            'client_id' => Client::factory(),
            'user_id' => \VmEngine\SynAuth\Models\User::factory(),
            'invoice_number' => 'INV-'.fake()->unique()->numerify('######'),
            'spk_document_id' => ProjectDocument::factory(),
            'issue_date' => $issueDate,
            'due_date' => $dueDate,
            'recipient' => fake()->company(),
            'address' => fake()->address(),
            'attention' => fake()->name(),
            'notes' => fake()->boolean(50) ? fake()->sentence() : null,
            'amount' => $amount,
            'include_vat' => $includeVat,
            'vat_percentage' => $vatPercentage,
            'vat_amount' => $vatAmount,
            'include_discount' => $includeDiscount,
            'discount_percentage' => $discountPercentage,
            'discount_amount' => $discountAmount,
            'include_dpp' => $includeDpp,
            'dpp_amount' => $dppAmount,
            'total_amount' => $totalAmount,
            'paid_amount' => $paidAmount,
            'issuer_id' => DocumentIssuer::factory(),
            'bank_account_id' => fake()->boolean(80) ? BankAccount::factory() : null,
            'has_signature' => $hasSignature,
            'signature_id' => $hasSignature ? Signature::factory() : null,
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

    public function fullyPaid(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'paid_amount' => $attributes['total_amount'],
            ];
        });
    }

    public function unpaid(): static
    {
        return $this->state(fn (array $attributes) => [
            'paid_amount' => 0,
        ]);
    }
}
