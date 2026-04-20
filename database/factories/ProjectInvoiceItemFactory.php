<?php

declare(strict_types=1);

namespace VmEngine\Pm\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use VmEngine\Pm\Models\Client;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\ProjectInvoice;
use VmEngine\Pm\Models\ProjectInvoiceItem;
use VmEngine\SynAuth\Models\User;

/**
 * @extends Factory<ProjectInvoiceItem>
 */
class ProjectInvoiceItemFactory extends Factory
{
    protected $model = ProjectInvoiceItem::class;

    public function definition(): array
    {
        $itemNames = [
            'Website Development',
            'Mobile App Development',
            'UI/UX Design',
            'Digital Marketing Services',
            'SEO Optimization',
            'Content Writing',
            'Logo Design',
            'Branding Package',
            'Social Media Management',
            'Video Production',
            'Photography Services',
            'Consultation Services',
        ];

        $amount = fake()->numberBetween(1000000, 10000000);
        $includeDiscount = fake()->boolean(30);
        $discountPercentage = $includeDiscount ? fake()->randomFloat(2, 5, 20) : 0;
        $discountAmount = $includeDiscount ? round($amount * $discountPercentage / 100, 2) : 0;

        return [
            'project_id' => Project::factory(),
            'project_invoice_id' => ProjectInvoice::factory(),
            'client_id' => Client::factory(),
            'name' => fake()->randomElement($itemNames),
            'amount' => $amount,
            'include_discount' => $includeDiscount,
            'discount_percentage' => $discountPercentage,
            'discount_amount' => $discountAmount,
            'is_paid' => fake()->boolean(60),
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
        return $this->state(fn (array $attributes) => [
            'is_paid' => true,
        ]);
    }

    public function unpaid(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_paid' => false,
        ]);
    }
}
