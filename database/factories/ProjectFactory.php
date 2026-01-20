<?php

declare(strict_types=1);

namespace VmEngine\Pm\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use VmEngine\Pm\Enums\MaintenanceType;
use VmEngine\Pm\Enums\ProjectPriority;
use VmEngine\Pm\Enums\ProjectStatus;
use VmEngine\Pm\Enums\RiskLevel;
use VmEngine\Pm\Models\Client;
use VmEngine\Pm\Models\Division;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\ProjectCategory;

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
        $division = Division::factory()->create();
        $client = Client::factory()->create();
        $category = ProjectCategory::factory()->create();

        return [
            'user_id' => $client->user_id,
            'division_id' => $division->id,
            'client_id' => $client->id,
            'project_category_id' => $category->id,
            'name' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'year' => fake()->year(),
            'sub_division' => [fake()->numberBetween(1, 26)],
            'services' => [1, 2], // COMPRO, WEBAPP
            'source_of_sales' => fake()->numberBetween(1, 6),
            'nominal' => fake()->randomFloat(2, 5000000, 200000000),
            'budget' => fake()->randomFloat(2, 5000000, 200000000),
            'down_payment' => fake()->randomFloat(2, 1000000, 50000000),
            'payment_terms' => fake()->sentence(),
            'url' => fake()->url(),
            'staging_url' => fake()->url(),
            'production_url' => fake()->url(),
            'repository_url' => fake()->url(),
            'status' => ProjectStatus::IN_PROGRESS->value,
            'progress' => fake()->numberBetween(0, 100),
            'priority' => ProjectPriority::MEDIUM->value,
            'risk_level' => RiskLevel::LOW->value,
            'client_pic' => fake()->name(),
            'client_pic_phone' => fake()->phoneNumber(),
            'project_start' => fake()->date(),
            'project_end' => fake()->date(),
            'planned_delivery_date' => fake()->date(),
            'manager' => fake()->name(),
            'project_manager_id' => $UserModel::factory(),
            'assigned_team' => [$UserModel::factory()->create()->id],
            'tags' => ['web', 'laravel'],
            'maintenance_type' => MaintenanceType::BASIC->value,
            'quality_score' => fake()->numberBetween(1, 5),
            'client_satisfaction' => fake()->numberBetween(1, 5),
            'created_by' => $UserModel::factory(),
            'updated_by' => null,
        ];
    }

    /**
     * Indicate that the project is in planning stage.
     */
    public function planning(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ProjectStatus::PLANNING->value,
            'progress' => 0,
        ]);
    }

    /**
     * Indicate that the project is in progress.
     */
    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ProjectStatus::IN_PROGRESS->value,
            'progress' => fake()->numberBetween(1, 99),
        ]);
    }

    /**
     * Indicate that the project is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ProjectStatus::COMPLETED->value,
            'progress' => 100,
            'actual_delivery_date' => fake()->date(),
        ]);
    }

    /**
     * Indicate that the project is on hold.
     */
    public function onHold(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ProjectStatus::ON_HOLD->value,
        ]);
    }

    /**
     * Indicate that the project is cancelled.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ProjectStatus::CANCELLED->value,
        ]);
    }

    /**
     * Indicate that the project has high priority.
     */
    public function highPriority(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => ProjectPriority::URGENT->value,
        ]);
    }

    /**
     * Indicate that the project is overdue.
     */
    public function overdue(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ProjectStatus::IN_PROGRESS->value,
            'planned_delivery_date' => now()->subDays(10),
            'actual_delivery_date' => null,
        ]);
    }
}
