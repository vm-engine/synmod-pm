<?php

declare(strict_types=1);

namespace VmEngine\Pm\Seeders;

use Illuminate\Database\Seeder;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\ProjectDocument;

class ProjectDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This seeder is primarily for testing/demo purposes.
     * Project documents are typically user-generated content.
     */
    public function run(): void
    {
        // Only seed if there are projects available
        if (Project::count() === 0) {
            $this->command->warn('No projects found. Skipping ProjectDocument seeding.');

            return;
        }

        // Get first project for demo documents
        $project = Project::with('client', 'user')->first();

        if (! $project || ! $project->client || ! $project->user) {
            $this->command->warn('Project missing required relationships. Skipping ProjectDocument seeding.');

            return;
        }

        // Create sample documents if they don't exist
        $documents = [
            [
                'project_id' => $project->id,
                'client_id' => $project->client_id,
                'user_id' => $project->user_id,
                'type' => 0, // PROPOSAL
                'number' => 'PROP/'.date('Y').'/001',
            ],
            [
                'project_id' => $project->id,
                'client_id' => $project->client_id,
                'user_id' => $project->user_id,
                'type' => 2, // SPK
                'number' => 'SPK/'.date('Y').'/001',
            ],
        ];

        foreach ($documents as $docData) {
            ProjectDocument::firstOrCreate(
                [
                    'project_id' => $docData['project_id'],
                    'type' => $docData['type'],
                ],
                array_merge($docData, [
                    'notes' => 'Sample document for testing purposes',
                    'file_path' => 'documents/sample/'.$docData['number'].'.pdf',
                    'file_info' => [
                        'original_name' => $docData['number'].'.pdf',
                        'size' => 1024000,
                        'mime_type' => 'application/pdf',
                    ],
                    'version' => 1,
                    'created_by' => 1,
                ])
            );
        }

        $this->command->info('Sample project documents created successfully.');
    }
}
