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
use VmEngine\SynAuth\Models\User;

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
        $vatPercentage = (float) dbconf('pm.invoice.vat_percentage', 11.00);
        $vatAmount = $includeVat ? round($amount * $vatPercentage / 100, 2) : 0;

        $includeDiscount = fake()->boolean(30);
        $discountPercentage = $includeDiscount ? fake()->randomFloat(2, 5, 20) : null;
        $discountAmount = $includeDiscount ? round($amount * $discountPercentage / 100, 2) : null;

        $includeDpp = fake()->boolean(20);
        $dppAmount = $includeDpp ? fake()->randomFloat(2, 100000, 1000000) : null;

        $totalAmount = $amount + $vatAmount - ($discountAmount ?? 0) - ($dppAmount ?? 0);
        $isPaid = fake()->boolean(60);

        $hasSignature = fake()->boolean(70);

        $issueDate = fake()->dateTimeBetween('-6 months', 'now');
        $dueDate = fake()->boolean(80) ? fake()->dateTimeBetween($issueDate, '+30 days') : null;
        $paidAt = $isPaid ? fake()->dateTimeBetween($issueDate, 'now') : null;

        $numberWords = [
            'Satu', 'Dua', 'Tiga', 'Lima', 'Sepuluh', 'Lima Belas',
            'Dua Puluh', 'Tiga Puluh', 'Lima Puluh', 'Seratus',
        ];

        return [
            'project_id' => Project::factory(),
            'project_category_id' => ProjectCategory::factory(),
            'client_id' => Client::factory(),
            'user_id' => User::factory(),
            'invoice_number' => 'INV-'.fake()->unique()->numerify('######'),
            'currency' => 'IDR',
            'spk_document_id' => ProjectDocument::factory(),
            'is_external_spk' => false,
            'spk_number' => null,
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
            // IDR is the only currency this factory defaults to, so the
            // converted fields are always a 1:1 passthrough of the raw ones.
            'exchange_rate' => 1,
            'amount_idr' => $amount,
            'vat_amount_idr' => $vatAmount,
            'dpp_amount_idr' => $dppAmount ?? 0,
            'discount_amount_idr' => $discountAmount ?? 0,
            'total_amount_idr' => $totalAmount,
            'is_paid' => $isPaid,
            'paid_at' => $paidAt,
            'payment_notes' => $isPaid && fake()->boolean(60) ? fake()->sentence() : null,
            'payment_proof' => $isPaid ? fake()->imageUrl(640, 480, 'business', true) : null,
            'issuer_id' => DocumentIssuer::factory(),
            'bank_account_id' => fake()->boolean(80) ? BankAccount::factory() : null,
            'has_signature' => $hasSignature,
            'signature_id' => $hasSignature ? Signature::factory() : null,
            'invoice_document_id' => fake()->boolean(40) ? ProjectDocument::factory() : null,
            'receipt_number' => $isPaid ? 'RCP-'.fake()->unique()->numerify('######') : null,
            'receipt_amount_text' => $isPaid ? fake()->randomElement($numberWords).' Juta Rupiah' : null,
            'receipt_document_id' => $isPaid && fake()->boolean(40) ? ProjectDocument::factory() : null,
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

    public function paid(): static
    {
        return $this->state(function (array $attributes) {
            $numberWords = [
                'Satu', 'Dua', 'Tiga', 'Lima', 'Sepuluh', 'Lima Belas',
                'Dua Puluh', 'Tiga Puluh', 'Lima Puluh', 'Seratus',
            ];
            $paidAt = fake()->dateTimeBetween($attributes['issue_date'] ?? '-6 months', 'now');

            return [
                'is_paid' => true,
                'paid_at' => $paidAt,
                'payment_notes' => fake()->boolean(70) ? fake()->sentence() : null,
                'payment_proof' => fake()->imageUrl(640, 480, 'business', true),
                'receipt_number' => 'RCP-'.fake()->unique()->numerify('######'),
                'receipt_amount_text' => fake()->randomElement($numberWords).' Juta Rupiah',
            ];
        });
    }

    public function unpaid(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_paid' => false,
            'paid_at' => null,
            'payment_notes' => null,
            'payment_proof' => null,
            'receipt_number' => null,
            'receipt_amount_text' => null,
            'receipt_document_id' => null,
        ]);
    }

    /**
     * USD invoice with a given exchange rate, its _idr fields pre-computed
     * to match (amount/vat/dpp/discount/total * rate).
     */
    public function usdConverted(float $rate = 16000): static
    {
        return $this->state(function (array $attributes) use ($rate) {
            $amount = (float) ($attributes['amount'] ?? 0);
            $vatAmount = (float) ($attributes['vat_amount'] ?? 0);
            $dppAmount = (float) ($attributes['dpp_amount'] ?? 0);
            $discountAmount = (float) ($attributes['discount_amount'] ?? 0);
            $totalAmount = (float) ($attributes['total_amount'] ?? 0);

            return [
                'currency' => 'USD',
                'exchange_rate' => $rate,
                'amount_idr' => round($amount * $rate, 2),
                'vat_amount_idr' => round($vatAmount * $rate, 2),
                'dpp_amount_idr' => round($dppAmount * $rate, 2),
                'discount_amount_idr' => round($discountAmount * $rate, 2),
                'total_amount_idr' => round($totalAmount * $rate, 2),
            ];
        });
    }
}
