<?php

declare(strict_types=1);

namespace VmEngine\Pm\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use VmEngine\Pm\Models\Client;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\ProjectInvoice;
use VmEngine\Pm\Models\ProjectReceipt;

/**
 * @extends Factory<ProjectReceipt>
 */
class ProjectReceiptFactory extends Factory
{
    protected $model = ProjectReceipt::class;

    public function definition(): array
    {
        $amount = fake()->randomFloat(2, 1000000, 20000000);

        $includeVat = fake()->boolean(80);
        $vatAmount = $includeVat ? round($amount * 0.11, 2) : null;

        $includeDiscount = fake()->boolean(20);
        $discountAmount = $includeDiscount ? fake()->randomFloat(2, 100000, 500000) : null;

        $includeDpp = fake()->boolean(15);
        $dppAmount = $includeDpp ? fake()->randomFloat(2, 50000, 300000) : null;

        $issueDate = fake()->dateTimeBetween('-3 months', 'now');
        $isPaid = fake()->boolean(70);
        $paidAt = $isPaid ? fake()->dateTimeBetween($issueDate, 'now') : null;

        $numberWords = [
            'Satu', 'Dua', 'Tiga', 'Lima', 'Sepuluh', 'Lima Belas',
            'Dua Puluh', 'Tiga Puluh', 'Lima Puluh', 'Seratus',
        ];
        $amountText = fake()->randomElement($numberWords).' Juta Rupiah';

        return [
            'project_id' => Project::factory(),
            'project_invoice_id' => ProjectInvoice::factory(),
            'client_id' => Client::factory(),
            'from' => fake()->company(),
            'receipt_number' => 'RCP-'.fake()->unique()->numerify('######'),
            'amount' => $amount,
            'amount_text' => $amountText,
            'notes' => fake()->boolean(50) ? fake()->sentence() : null,
            'issue_date' => $issueDate,
            'include_vat' => $includeVat,
            'vat_amount' => $vatAmount,
            'include_discount' => $includeDiscount,
            'discount_amount' => $discountAmount,
            'include_dpp' => $includeDpp,
            'dpp_amount' => $dppAmount,
            'payment_proof' => $isPaid ? fake()->imageUrl(640, 480, 'business', true) : null,
            'paid_at' => $paidAt,
            'payment_notes' => $isPaid && fake()->boolean(60) ? fake()->sentence() : null,
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

    public function paid(): static
    {
        return $this->state(function (array $attributes) {
            $paidAt = fake()->dateTimeBetween($attributes['issue_date'], 'now');

            return [
                'payment_proof' => fake()->imageUrl(640, 480, 'business', true),
                'paid_at' => $paidAt,
                'payment_notes' => fake()->boolean(70) ? fake()->sentence() : null,
            ];
        });
    }

    public function unpaid(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_proof' => null,
            'paid_at' => null,
            'payment_notes' => null,
        ]);
    }
}
