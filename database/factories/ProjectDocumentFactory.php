<?php

declare(strict_types=1);

namespace VmEngine\Pm\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use VmEngine\Pm\Enums\Documents;
use VmEngine\Pm\Models\Client;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\ProjectDocument;

/**
 * @extends Factory<ProjectDocument>
 */
class ProjectDocumentFactory extends Factory
{
    protected $model = ProjectDocument::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $UserModel = config('auth.providers.users.model');
        $documentType = fake()->randomElement(Documents::cases());
        $fileExtension = fake()->randomElement(['pdf', 'doc', 'docx']);

        return [
            'user_id' => $UserModel::factory(),
            'client_id' => Client::factory(),
            'project_id' => Project::factory(),
            'type' => $documentType->value,
            'number' => $this->generateDocumentNumber($documentType),
            'notes' => fake()->optional()->sentence(),
            'file_path' => "documents/{$documentType->code(true)}/".fake()->uuid().".{$fileExtension}",
            'file_info' => [
                'original_name' => fake()->words(3, true).".{$fileExtension}",
                'size' => fake()->numberBetween(10000, 5000000),
                'mime_type' => $this->getMimeType($fileExtension),
            ],
            'version' => 1,
            'created_by' => $UserModel::factory(),
        ];
    }

    /**
     * Generate document number based on type.
     */
    protected function generateDocumentNumber(Documents $type): string
    {
        $code = $type->code();

        if (empty($code)) {
            return (string) fake()->numberBetween(1000, 9999);
        }

        $year = date('Y');
        $month = date('m');
        $sequence = fake()->numberBetween(1, 999);

        return sprintf('%s/%s/%s/%03d', $code, $year, $month, $sequence);
    }

    /**
     * Get MIME type based on file extension.
     */
    protected function getMimeType(string $extension): string
    {
        return match ($extension) {
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            default => 'application/octet-stream',
        };
    }

    /**
     * State for specific document type.
     */
    public function ofType(Documents $type): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => $type->value,
            'number' => $this->generateDocumentNumber($type),
        ]);
    }

    /**
     * State for proposal document.
     */
    public function proposal(): static
    {
        return $this->ofType(Documents::PROPOSAL);
    }

    /**
     * State for SPK document.
     */
    public function spk(): static
    {
        return $this->ofType(Documents::SPK);
    }

    /**
     * State for invoice document.
     */
    public function invoice(): static
    {
        return $this->ofType(Documents::INVOICE);
    }

    /**
     * State for specific version.
     */
    public function version(int $version): static
    {
        return $this->state(fn (array $attributes) => [
            'version' => $version,
        ]);
    }

    /**
     * State for specific project.
     */
    public function forProject(Project $project): static
    {
        return $this->state(fn (array $attributes) => [
            'project_id' => $project->id,
            'client_id' => $project->client_id,
            'user_id' => $project->user_id,
        ]);
    }
}
